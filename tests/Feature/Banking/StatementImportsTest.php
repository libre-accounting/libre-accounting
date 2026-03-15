<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Account;
use App\Models\Banking\BankStatementImport;
use App\Models\Banking\BankStatementLine;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\Feature\FeatureTestCase;

class StatementImportsTest extends FeatureTestCase
{
    protected function camtFixture(): string
    {
        return file_get_contents(base_path('tests/Unit/Fixtures/camt053_v02.xml'));
    }

    protected function uploadFile(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('statement.xml', $this->camtFixture());
    }

    public function testItShouldSeeStatementImportListPage()
    {
        $this->loginAs()
            ->get(route('statement-imports.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.statement_imports', 2));
    }

    public function testItShouldSeeStatementImportCreatePage()
    {
        $this->loginAs()
            ->get(route('statement-imports.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('statement_imports.account'));
    }

    public function testItShouldStageStatementLines()
    {
        $account = Account::factory()->create();

        $this->loginAs()
            ->post(route('statement-imports.import'), [
                'account_id' => $account->id,
                'import'     => $this->uploadFile(),
            ])
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        // The run row is created with both parsed lines.
        $this->assertDatabaseHas('bank_statement_imports', [
            'account_id'  => $account->id,
            'total_lines' => 2,
            'status'      => 'reviewing',
        ]);

        // One income (CRDT) and one expense (DBIT) line are staged.
        $this->assertDatabaseHas('bank_statement_lines', [
            'account_id'        => $account->id,
            'type'              => 'income',
            'amount'            => 500.00,
            'counterparty_name' => 'Acme Customer Sp. z o.o.',
            'status'            => BankStatementLine::STATUS_PENDING,
        ]);

        $this->assertDatabaseHas('bank_statement_lines', [
            'account_id'        => $account->id,
            'type'              => 'expense',
            'amount'            => 265.50,
            'counterparty_name' => 'Office Supplies Ltd',
            'status'            => BankStatementLine::STATUS_PENDING,
        ]);
    }

    public function testItShouldRejectAlreadyImportedFile()
    {
        $account = Account::factory()->create();

        // The throttle:import middleware allows only 1 request/minute; bypass it
        // here so both uploads reach the controller and exercise file-hash dedup.
        $this->withoutMiddleware(ThrottleRequests::class);

        // First import succeeds.
        $this->loginAs()
            ->post(route('statement-imports.import'), [
                'account_id' => $account->id,
                'import'     => $this->uploadFile(),
            ])
            ->assertStatus(200);

        $this->assertEquals(1, BankStatementImport::count());

        // Re-uploading the identical file is rejected (same file_hash), so no
        // second run is created.
        $this->loginAs()
            ->post(route('statement-imports.import'), [
                'account_id' => $account->id,
                'import'     => $this->uploadFile(),
            ])
            ->assertStatus(200);

        $this->assertEquals(1, BankStatementImport::count());
    }

    public function testItShouldRejectNonXmlFile()
    {
        $account = Account::factory()->create();

        $this->withExceptionHandling()
            ->loginAs()
            ->post(route('statement-imports.import'), [
                'account_id' => $account->id,
                'import'     => UploadedFile::fake()->create('statement.pdf', 10, 'application/pdf'),
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('import');

        $this->assertEquals(0, BankStatementImport::count());
    }

    public function testItShouldDeleteStatementImport()
    {
        $account = Account::factory()->create();

        $this->loginAs()
            ->post(route('statement-imports.import'), [
                'account_id' => $account->id,
                'import'     => $this->uploadFile(),
            ]);

        $import = BankStatementImport::first();

        // destroy() is an ajax action: it returns a JSON response (HTTP 200)
        // with a redirect target, matching the other Banking destroy endpoints
        // (see AccountsTest/TransfersTest), not a 302 redirect.
        $this->loginAs()
            ->delete(route('statement-imports.destroy', $import->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('bank_statement_imports', ['id' => $import->id]);
        $this->assertSoftDeleted('bank_statement_lines', ['bank_statement_import_id' => $import->id]);
    }
}
