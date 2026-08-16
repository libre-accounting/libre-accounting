<?php

namespace Tests\Feature\Api;

use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Common\ItemComponent;
use App\Models\Common\ItemStockMovement;
use App\Models\Setting\Category;
use Tests\Feature\FeatureTestCase;

class EndpointsTest extends FeatureTestCase
{
    public function testItShouldMarkBillReceived()
    {
        $bill = $this->createDocument('bill');

        $this->api()
            ->getJson('api/documents/' . $bill . '/received?search=type:bill')
            ->assertOk()
            ->assertJsonPath('data.status', 'received');
    }

    public function testItShouldRefuseToMarkInvoiceReceived()
    {
        $invoice = $this->createDocument('invoice');

        $this->withExceptionHandling()
            ->api()
            ->getJson('api/documents/' . $invoice . '/received?search=type:invoice')
            ->assertStatus(400);
    }

    public function testItShouldNotDowngradeAPaidBill()
    {
        $bill = $this->createDocument('bill');

        $this->api()->postJson('api/documents/' . $bill . '/transactions', [
            'type' => 'expense',
            'number' => 'API-PAY-1',
            'account_id' => setting('default.account'),
            'paid_at' => now()->format('Y-m-d H:i:s'),
            'amount' => 100,
            'currency_code' => default_currency(),
            'currency_rate' => 1,
            'category_id' => setting('default.expense_category'),
            'payment_method' => 'offline-payments.cash.1',
        ])->assertCreated();

        $this->withExceptionHandling()
            ->api()
            ->getJson('api/documents/' . $bill . '/received?search=type:bill')
            ->assertStatus(400);

        $this->api()
            ->getJson('api/documents/' . $bill . '?search=type:bill')
            ->assertJsonPath('data.status', 'paid');
    }

    public function testPutShouldUpdateLikePatch()
    {
        $transaction = Transaction::factory()->expense()->create();

        $this->api()
            ->putJson('api/transactions/' . $transaction->id, [
                'type' => 'expense',
                'number' => $transaction->number,
                'account_id' => $transaction->account_id,
                'paid_at' => $transaction->paid_at->format('Y-m-d H:i:s'),
                'amount' => 99.5,
                'currency_code' => $transaction->currency_code,
                'currency_rate' => 1,
                'category_id' => $transaction->category_id,
                'payment_method' => $transaction->payment_method,
            ])
            ->assertOk()
            ->assertJsonPath('data.number', $transaction->number)
            ->assertJsonPath('data.amount', 99.5);
    }

    public function testMissingRecordsShouldReturn404()
    {
        $this->withExceptionHandling();

        // Looked up in the controller
        $this->api()->getJson('api/items/999999')->assertNotFound();
        $this->api()->getJson('api/dashboards/999999')->assertNotFound();

        // Resolved by route model binding
        $this->api()->getJson('api/transactions/999999')->assertNotFound()
            ->assertJsonPath('status_code', 404);
    }

    public function testPatchWithoutEnabledShouldKeepCategoryInUseEnabled()
    {
        $transaction = Transaction::factory()->expense()->create();
        $category = Category::find($transaction->category_id);

        $this->api()
            ->patchJson('api/categories/' . $category->id, [
                'name' => $category->name . ' (renamed)',
                'type' => $category->type,
                'color' => $category->color,
            ])
            ->assertOk()
            ->assertJsonPath('data.enabled', true);
    }

    public function testPatchWithoutEnabledShouldKeepContactWithDocumentsEnabled()
    {
        $this->createDocument('invoice');

        $contact = Contact::customer()->whereHas('invoices')->first();

        $this->api()
            ->patchJson('api/contacts/' . $contact->id . '?search=type:customer', [
                'type' => 'customer',
                'name' => $contact->name,
                'currency_code' => $contact->currency_code,
                'phone' => '+48 600 000 000',
            ])
            ->assertOk()
            ->assertJsonPath('data.phone', '+48 600 000 000')
            ->assertJsonPath('data.enabled', true);
    }

    public function testEnableShouldWorkForAccountWithTransactions()
    {
        Transaction::factory()->income()->create(['account_id' => setting('default.account')]);

        $this->api()
            ->getJson('api/accounts/' . setting('default.account') . '/enable')
            ->assertOk()
            ->assertJsonPath('data.enabled', true);
    }

    public function testItShouldListStockMovements()
    {
        $item = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

        ItemStockMovement::factory()->forItem($item)->quantity(3)->create(['description' => 'Shelf count']);

        $this->api()
            ->getJson('api/items/' . $item->id . '/stock-movements')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.type', 'adjustment')
            ->assertJsonPath('data.0.quantity', 3)
            ->assertJsonPath('data.0.description', 'Shelf count');
    }

    public function testItShouldSortItemsByStock()
    {
        $quantities = [7, 2, 5];

        foreach ($quantities as $quantity) {
            $item = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

            ItemStockMovement::factory()->forItem($item)->quantity($quantity)->create();
        }

        $response = $this->api()
            ->getJson('api/items?search=track_stock:1&sort=stock&direction=asc')
            ->assertOk()
            ->json('data');

        $this->assertSame([2, 5, 7], array_column($response, 'stock_quantity'));
    }

    public function testItShouldAdjustStock()
    {
        $item = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

        $this->api()
            ->postJson('api/items/' . $item->id . '/stock-adjustments', ['quantity' => 10])
            ->assertCreated()
            ->assertJsonPath('data.type', 'adjustment')
            ->assertJsonPath('data.quantity', 10);

        $this->api()
            ->postJson('api/items/' . $item->id . '/stock-adjustments', ['change' => -3, 'moved_at' => '2026-02-01', 'description' => 'Breakage'])
            ->assertCreated()
            ->assertJsonPath('data.quantity', -3);

        $this->api()
            ->getJson('api/items/' . $item->id)
            ->assertJsonPath('data.stock_quantity', 7);
    }

    public function testItShouldRefuseAdjustingAnUntrackedItem()
    {
        $item = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => false]);

        $this->withExceptionHandling()
            ->api()
            ->postJson('api/items/' . $item->id . '/stock-adjustments', ['quantity' => 10])
            ->assertStatus(400);

        $this->withExceptionHandling()
            ->api()
            ->postJson('api/items/' . $item->id . '/stock-adjustments', [])
            ->assertStatus(422);
    }

    public function testItShouldBuildStock()
    {
        $composite = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);
        $part = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

        ItemComponent::factory()->forItem($composite)->component($part)->quantity(2)->create();

        $this->api()
            ->postJson('api/items/' . $composite->id . '/stock-builds', ['quantity' => 3])
            ->assertCreated()
            ->assertJsonPath('data.type', 'assembly')
            ->assertJsonPath('data.quantity', 3);

        $this->assertSame(3.0, $composite->fresh()->stock_quantity);
        $this->assertSame(-6.0, $part->fresh()->stock_quantity);

        $this->withExceptionHandling()
            ->api()
            ->postJson('api/items/' . $part->id . '/stock-builds', ['quantity' => 1])
            ->assertStatus(400);
    }

    public function testItShouldAddAndRemoveComponents()
    {
        $kit = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => false]);
        $part = Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true]);

        $component = $this->api()
            ->postJson('api/items/' . $kit->id . '/components', ['component_id' => $part->id, 'quantity' => 2])
            ->assertCreated()
            ->assertJsonPath('data.component_id', $part->id)
            ->assertJsonPath('data.quantity', 2)
            ->json('data.id');

        $this->api()
            ->getJson('api/items/' . $kit->id)
            ->assertJsonCount(1, 'data.components.data');

        // A kit cannot become a part of another item
        $other = Item::factory()->enabled()->create(['type' => 'product']);

        $this->withExceptionHandling()
            ->api()
            ->postJson('api/items/' . $other->id . '/components', ['component_id' => $kit->id, 'quantity' => 1])
            ->assertStatus(400);

        $this->api()
            ->deleteJson('api/items/' . $kit->id . '/components/' . $component)
            ->assertNoContent();

        $this->api()
            ->getJson('api/items/' . $kit->id)
            ->assertJsonCount(0, 'data.components.data');
    }

    protected function createDocument(string $type): int
    {
        $contact = Contact::factory()->{$type == 'bill' ? 'vendor' : 'customer'}()->enabled()->create();

        return $this->api()->postJson('api/documents?search=type:' . $type, [
            'type' => $type,
            'document_number' => strtoupper($type) . '-API-' . $contact->id,
            'status' => 'draft',
            'issued_at' => now()->format('Y-m-d H:i:s'),
            'due_at' => now()->addDays(14)->format('Y-m-d H:i:s'),
            'amount' => 0,
            'currency_code' => default_currency(),
            'currency_rate' => 1,
            'contact_id' => $contact->id,
            'contact_name' => $contact->name,
            'category_id' => setting($type == 'bill' ? 'default.expense_category' : 'default.income_category'),
            'items' => [
                ['name' => 'Line', 'description' => '', 'quantity' => 1, 'price' => 100],
            ],
        ])->assertCreated()->json('data.id');
    }

    protected function api()
    {
        return $this->withBasicAuth('test@company.com', '123456');
    }
}
