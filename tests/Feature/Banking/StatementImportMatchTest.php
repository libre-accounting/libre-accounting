<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CommitStatementLines;
use App\Jobs\Banking\ImportCamtStatement;
use App\Models\Banking\Account;
use App\Models\Banking\BankStatementLine;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

class StatementImportMatchTest extends FeatureTestCase
{
    protected function uploadFixture(int $account_id)
    {
        $xml = file_get_contents(base_path('tests/Unit/Fixtures/camt053_v02.xml'));
        $file = UploadedFile::fake()->createWithContent('statement.xml', $xml);

        return $this->dispatch(new ImportCamtStatement($file, $account_id));
    }

    public function testItShouldAutoCreateContactsFromCounterpartyNames()
    {
        $this->loginAs();

        $account = Account::factory()->create();

        $this->uploadFixture($account->id);

        // Income line counterparty -> a customer is auto-created.
        $this->assertDatabaseHas('contacts', [
            'name' => 'Acme Customer Sp. z o.o.',
            'type' => Contact::CUSTOMER_TYPE,
        ]);

        // Expense line counterparty -> a vendor is auto-created.
        $this->assertDatabaseHas('contacts', [
            'name' => 'Office Supplies Ltd',
            'type' => Contact::VENDOR_TYPE,
        ]);

        // The staged lines reference the matched contacts.
        $income = BankStatementLine::where('type', 'income')->first();
        $this->assertNotNull($income->contact_id);
    }

    public function testItShouldPrefillMatchedDocumentOnStaging()
    {
        $this->loginAs();

        $account = Account::factory()->create();

        // The income fixture line has remittance "Payment for invoice INV-1001"
        // and end-to-end id "E2E-INV-1001"; create a matching invoice number.
        $invoice = Document::factory()->invoice()->create([
            'document_number' => 'E2E-INV-1001',
            'amount'          => 500,
            'status'          => 'sent',
        ]);

        $this->uploadFixture($account->id);

        $income = BankStatementLine::where('type', 'income')->where('amount', 500)->first();

        $this->assertEquals($invoice->id, $income->document_id);
    }

    public function testItShouldSettleMatchedInvoiceOnCommit()
    {
        $this->loginAs();

        $account = Account::factory()->create();

        // The document factory computes its own amount from default totals, so
        // settle the line against the invoice's actual outstanding amount.
        $invoice = Document::factory()->invoice()->create([
            'document_number' => 'E2E-INV-1001',
            'currency_code'   => default_currency(),
            'currency_rate'   => 1,
            'status'          => 'sent',
        ]);
        $invoice->refresh();

        $import = $this->uploadFixture($account->id);

        $line = $import->lines()->where('type', 'income')->where('amount', 500)->first();
        $line->update([
            'category_id' => Category::factory()->income()->create()->id,
            'amount'      => $invoice->amount, // exact payment -> fully paid
        ]);

        $this->dispatch(new CommitStatementLines($import, [$line->id]));

        // The transaction is linked to the invoice, which is now settled.
        $line->refresh();
        $this->assertNotNull($line->transaction_id);

        $this->assertDatabaseHas('transactions', [
            'id'          => $line->transaction_id,
            'document_id' => $invoice->id,
        ]);

        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
    }

    public function testItShouldStillImportWhenPaymentOverMatchesDocument()
    {
        $this->loginAs();

        $account = Account::factory()->create();

        $invoice = Document::factory()->invoice()->create([
            'document_number' => 'E2E-INV-1001',
            'currency_code'   => default_currency(),
            'currency_rate'   => 1,
            'status'          => 'sent',
        ]);
        $invoice->refresh();

        $import = $this->uploadFixture($account->id);

        $line = $import->lines()->where('type', 'income')->where('amount', 500)->first();
        $line->update([
            'category_id' => Category::factory()->income()->create()->id,
            'amount'      => $invoice->amount + 1000, // payment exceeds the invoice
        ]);

        $created = $this->dispatch(new CommitStatementLines($import, [$line->id]));

        // The transaction is still created (commit not aborted) ...
        $this->assertEquals(1, $created);
        $line->refresh();
        $this->assertEquals(BankStatementLine::STATUS_IMPORTED, $line->status);
        $this->assertNotNull($line->transaction_id);

        // ... but the over-matched invoice was not settled (left unpaid/unlinked).
        $invoice->refresh();
        $this->assertNotEquals('paid', $invoice->status);
    }
}
