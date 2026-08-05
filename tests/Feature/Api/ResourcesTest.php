<?php

namespace Tests\Feature\Api;

use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Common\Report;
use Tests\Feature\FeatureTestCase;

/**
 * API clients must be able to rebuild a full update request from a GET,
 * because updates re-validate every required field.
 */
class ResourcesTest extends FeatureTestCase
{
    public function testTransactionIncludesNumber()
    {
        $transaction = Transaction::factory()->income()->create();

        $this->api()
            ->getJson('api/transactions/' . $transaction->id)
            ->assertOk()
            ->assertJsonPath('data.number', $transaction->number);
    }

    public function testContactIncludesFullAddress()
    {
        $contact = Contact::factory()->customer()->enabled()->create([
            'city' => 'Warsaw',
            'zip_code' => '00-001',
            'state' => 'Mazowieckie',
            'country' => 'PL',
        ]);

        $this->api()
            ->getJson('api/contacts/' . $contact->id . '?search=type:customer')
            ->assertOk()
            ->assertJsonPath('data.city', 'Warsaw')
            ->assertJsonPath('data.zip_code', '00-001')
            ->assertJsonPath('data.state', 'Mazowieckie')
            ->assertJsonPath('data.country', 'PL');
    }

    public function testInvoiceItemsIncludeQuantityAndDiscount()
    {
        $contact = Contact::factory()->customer()->enabled()->create();

        $response = $this->api()->postJson('api/documents?search=type:invoice', [
            'type' => 'invoice',
            'document_number' => 'API-00001',
            'status' => 'draft',
            'issued_at' => '2026-10-19 10:00:00',
            'due_at' => '2026-11-02 10:00:00',
            'amount' => 0,
            'currency_code' => default_currency(),
            'currency_rate' => 1,
            'contact_id' => $contact->id,
            'contact_name' => $contact->name,
            'category_id' => setting('default.income_category'),
            'title' => 'Invoice',
            'footer' => 'Thank you',
            'items' => [
                // No "description" key on purpose: it used to cause a 500
                ['name' => 'API test service', 'quantity' => 3, 'price' => 100, 'discount' => 10, 'discount_type' => 'percentage'],
            ],
        ]);

        $response->assertCreated();

        $this->api()
            ->getJson('api/documents/' . $response->json('data.id') . '?search=type:invoice')
            ->assertOk()
            ->assertJsonPath('data.footer', 'Thank you')
            ->assertJsonPath('data.items.data.0.quantity', 3)
            ->assertJsonPath('data.items.data.0.discount_rate', 10)
            ->assertJsonPath('data.items.data.0.discount_type', 'percentage')
            ->assertJsonPath('data.amount', 270);
    }

    public function testReportReturnsDataWithFilters()
    {
        $report = Report::where('class', 'App\Reports\IncomeSummary')->first();

        $this->assertNotNull($report);

        $this->api()
            ->getJson('api/reports/' . $report->id . '?search=year:' . now()->year)
            ->assertOk()
            ->assertJsonPath('data.year', (string) now()->year)
            ->assertJsonPath('data.tables.income', trans_choice('general.incomes', 1));
    }

    protected function api()
    {
        return $this->withBasicAuth('test@company.com', '123456');
    }
}
