<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\DeleteBankStatementImport;
use App\Jobs\Banking\ImportCamtStatement;
use App\Models\Banking\Account;
use App\Models\Banking\BankStatementImport;
use App\Models\Banking\BankStatementLine;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

class StatementImportHardeningTest extends FeatureTestCase
{
    protected function uploadJob(int $account_id): ImportCamtStatement
    {
        $xml = file_get_contents(base_path('tests/Unit/Fixtures/camt053_v02.xml'));
        $file = UploadedFile::fake()->createWithContent('statement.xml', $xml);

        $job = new ImportCamtStatement($file, $account_id);
        $this->dispatch($job);

        return $job;
    }

    public function testItShouldCapStagedLinesAndFlagTruncation()
    {
        $this->loginAs();

        // The fixture has 2 lines; cap at 1 to force truncation.
        config(['statement_imports.line_limit' => 1]);

        $account = Account::factory()->create();

        $job = $this->uploadJob($account->id);

        $import = BankStatementImport::first();

        $this->assertEquals(1, $import->lines()->count());
        $this->assertEquals(1, $import->total_lines);
        $this->assertEquals(1, $job->truncated);
    }

    public function testItShouldNotTruncateUnderTheLimit()
    {
        $this->loginAs();

        config(['statement_imports.line_limit' => 500]);

        $account = Account::factory()->create();

        $job = $this->uploadJob($account->id);

        $this->assertEquals(0, $job->truncated);
        $this->assertEquals(2, BankStatementImport::first()->lines()->count());
    }

    public function testItShouldFlagIbanMismatch()
    {
        $this->loginAs();

        // Fixture statement IBAN is PL61109010140000071219812874.
        $account = Account::factory()->create(['number' => 'DE00000000000000000000']);

        $job = $this->uploadJob($account->id);

        $this->assertTrue($job->iban_mismatch);
    }

    public function testItShouldNotFlagWhenIbanMatches()
    {
        $this->loginAs();

        $account = Account::factory()->create(['number' => 'PL61 1090 1014 0000 0712 1981 2874']);

        $job = $this->uploadJob($account->id);

        // Normalised comparison ignores spacing/case.
        $this->assertFalse($job->iban_mismatch);
    }

    public function testItShouldNotFlagWhenAccountHasNoNumber()
    {
        $this->loginAs();

        $account = Account::factory()->create(['number' => '']);

        $job = $this->uploadJob($account->id);

        $this->assertFalse($job->iban_mismatch);
    }

    public function testDeleteJobSoftDeletesRunAndLines()
    {
        $this->loginAs();

        $account = Account::factory()->create();
        $this->uploadJob($account->id);

        $import = BankStatementImport::first();
        $line_id = $import->lines()->first()->id;

        $this->dispatch(new DeleteBankStatementImport($import));

        $this->assertSoftDeleted('bank_statement_imports', ['id' => $import->id]);
        $this->assertSoftDeleted('bank_statement_lines', ['id' => $line_id]);
    }
}
