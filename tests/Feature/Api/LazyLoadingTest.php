<?php

namespace Tests\Feature\Api;

use App\Models\Banking\Reconciliation;
use App\Models\Common\Contact;
use App\Models\Common\Dashboard;
use App\Models\Common\Item;
use App\Models\Common\Report;
use App\Models\Setting\Tax;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

/**
 * Lazy-loading violations are reported to the log channel, and with
 * LOG_CHANNEL=sentry every one of them becomes a Sentry event, so the API
 * resources must only touch relations their controllers eager-load.
 */
class LazyLoadingTest extends FeatureTestCase
{
    public function testEndpointsDoNotLazyLoad()
    {
        $violations = [];
        $current = 'creating records';

        Model::preventLazyLoading();
        Model::handleLazyLoadingViolationUsing(function ($model, $relation) use (&$violations, &$current) {
            $violations[$current][] = class_basename($model) . '->' . $relation;
        });

        // Laravel only flags lazy loading on models that were loaded as part of
        // a collection with more than one model, so create two of everything.
        foreach ([1, 2] as $i) {
            [$invoice_id, $payment_id] = $this->createInvoiceWithPayments($i);
            $expense_id = $this->createExpenseWithReceipt($i);
            $account_id = $this->createAccount($i);

            $transfer = $this->api()->postJson('api/transfers', [
                'from_account_id' => setting('default.account'),
                'to_account_id' => $account_id,
                'amount' => 10,
                'transferred_at' => now()->toDateString(),
                'payment_method' => 'offline-payments.cash.1',
            ])->assertCreated()->json('data.id');

            Reconciliation::factory()->create(['account_id' => $account_id]);
        }

        $endpoints = [
            'api/users',
            'api/users/' . $this->user->id,
            'api/companies',
            'api/companies/' . $this->company->id,
            'api/dashboards',
            'api/dashboards/' . Dashboard::first()->id,
            'api/items',
            'api/items/' . Item::first()->id,
            'api/contacts?search=type:customer',
            'api/contacts/' . Contact::customer()->first()->id . '?search=type:customer',
            'api/documents?search=type:invoice',
            'api/documents/' . $invoice_id . '?search=type:invoice',
            'api/documents/' . $invoice_id . '/transactions',
            'api/documents/' . $invoice_id . '/transactions/' . $payment_id,
            'api/accounts',
            'api/accounts/' . setting('default.account'),
            'api/reconciliations',
            'api/transactions',
            'api/transactions/' . $expense_id,
            'api/transfers',
            'api/transfers/' . $transfer,
            'api/reports',
            'api/reports/' . Report::first()->id,
            'api/categories',
            'api/categories/' . setting('default.expense_category'),
            'api/currencies',
            'api/currencies/' . default_currency(),
            'api/taxes',
            'api/taxes/' . Tax::first()->id,
            'api/settings',
        ];

        foreach ($endpoints as $endpoint) {
            $current = $endpoint;

            $this->api()->getJson($endpoint)->assertOk();
        }

        $violations = array_map(fn ($list) => array_values(array_unique($list)), $violations);

        $this->assertSame([], $violations);
    }

    protected function createInvoiceWithPayments(int $i): array
    {
        $contact = Contact::factory()->customer()->enabled()->create();
        $tax = Tax::factory()->normal()->enabled()->create();

        $invoice = $this->api()->postJson('api/documents?search=type:invoice', [
            'type' => 'invoice',
            'document_number' => 'LAZY-0000' . $i,
            'status' => 'sent',
            'issued_at' => now()->format('Y-m-d H:i:s'),
            'due_at' => now()->addDays(14)->format('Y-m-d H:i:s'),
            'amount' => 0,
            'currency_code' => default_currency(),
            'currency_rate' => 1,
            'contact_id' => $contact->id,
            'contact_name' => $contact->name,
            'category_id' => setting('default.income_category'),
            'items' => [
                ['name' => 'Service ' . $i, 'description' => '', 'quantity' => 2, 'price' => 100, 'tax_ids' => [$tax->id]],
                ['name' => 'Setup ' . $i, 'description' => '', 'quantity' => 1, 'price' => 50, 'tax_ids' => [$tax->id]],
            ],
        ])->assertCreated()->json('data.id');

        foreach (['a', 'b'] as $suffix) {
            $payment = $this->api()->postJson('api/documents/' . $invoice . '/transactions', [
                'type' => 'income',
                'number' => 'LAZY-PAY-' . $i . $suffix,
                'account_id' => setting('default.account'),
                'paid_at' => now()->format('Y-m-d H:i:s'),
                'amount' => 20,
                'currency_code' => default_currency(),
                'currency_rate' => 1,
                'category_id' => setting('default.income_category'),
                'payment_method' => 'offline-payments.cash.1',
            ])->assertCreated()->json('data.id');
        }

        return [$invoice, $payment];
    }

    protected function createExpenseWithReceipt(int $i): int
    {
        return $this->api()->post('api/transactions', [
            'type' => 'expense',
            'number' => 'LAZY-EXP-' . $i,
            'account_id' => setting('default.account'),
            'paid_at' => now()->format('Y-m-d H:i:s'),
            'amount' => 12.5,
            'currency_code' => default_currency(),
            'currency_rate' => 1,
            'category_id' => setting('default.expense_category'),
            'payment_method' => 'offline-payments.cash.1',
            'attachment' => [UploadedFile::fake()->image('receipt.png')],
        ], ['Accept' => 'application/json'])->assertCreated()->json('data.id');
    }

    protected function createAccount(int $i): int
    {
        return $this->api()->postJson('api/accounts', [
            'type' => 'bank',
            'name' => 'Account ' . $i,
            'number' => 'LAZY-ACC-' . $i,
            'currency_code' => default_currency(),
            'opening_balance' => 0,
        ])->assertCreated()->json('data.id');
    }

    protected function api()
    {
        return $this->withBasicAuth('test@company.com', '123456');
    }
}
