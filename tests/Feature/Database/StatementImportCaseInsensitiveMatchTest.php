<?php

namespace Tests\Feature\Database;

use App\Jobs\Banking\ImportCamtStatement;
use App\Models\Banking\Account;
use App\Models\Banking\BankStatementLine;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

/**
 * Cross-engine parity: the CAMT importer must match an EXISTING contact/document
 * regardless of letter case, on every database engine.
 *
 * MySQL's default utf8mb4_unicode_ci collation makes `where('name', $x)`
 * case-insensitive, so this passes there almost by accident. PostgreSQL compares
 * strings case-sensitively, so without the LOWER()-based lookups in
 * App\Traits\Import and Document::scopeNumber this test fails on pgsql: a
 * duplicate contact is created and the invoice is not matched.
 */
class StatementImportCaseInsensitiveMatchTest extends FeatureTestCase
{
    protected function uploadFixture(int $account_id)
    {
        $xml = file_get_contents(base_path('tests/Unit/Fixtures/camt053_v02.xml'));
        $file = UploadedFile::fake()->createWithContent('statement.xml', $xml);

        return $this->dispatch(new ImportCamtStatement($file, $account_id));
    }

    public function testItShouldMatchExistingContactRegardlessOfCase()
    {
        $this->loginAs();

        $account = Account::factory()->create();

        // The fixture's income counterparty is "Acme Customer Sp. z o.o.".
        // Pre-create the same contact in a DIFFERENT case; the importer must
        // reuse it rather than create a second one.
        $existing = Contact::factory()->customer()->create([
            'name' => 'ACME CUSTOMER SP. Z O.O.',
        ]);

        $this->uploadFixture($account->id);

        // Exactly one customer with this name (case-insensitively) exists: the
        // pre-created one. No duplicate was auto-created.
        $matches = Contact::where('type', Contact::CUSTOMER_TYPE)
            ->whereRaw('LOWER(name) = ?', ['acme customer sp. z o.o.'])
            ->get();

        $this->assertCount(1, $matches, 'Importer created a duplicate contact instead of matching the existing one.');
        $this->assertEquals($existing->id, $matches->first()->id);

        // The staged income line references the pre-existing contact.
        $income = BankStatementLine::where('type', 'income')->first();
        $this->assertEquals($existing->id, $income->contact_id);
    }

    public function testItShouldMatchExistingDocumentRegardlessOfCase()
    {
        $this->loginAs();

        $account = Account::factory()->create();

        // The fixture remittance references invoice "E2E-INV-1001"; create the
        // invoice with a lower-cased number. The importer must still match it.
        $invoice = Document::factory()->invoice()->create([
            'document_number' => 'e2e-inv-1001',
            'amount'          => 500,
            'status'          => 'sent',
        ]);

        $this->uploadFixture($account->id);

        $income = BankStatementLine::where('type', 'income')->where('amount', 500)->first();

        $this->assertEquals($invoice->id, $income->document_id);
    }
}
