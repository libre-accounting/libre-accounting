<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Account;
use App\Models\Banking\BankStatementImport;
use App\Models\Banking\BankStatementLine;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\Feature\FeatureTestCase;

class StatementImportReimportTest extends FeatureTestCase
{
    protected function camtFixture(): string
    {
        return file_get_contents(base_path('tests/Unit/Fixtures/camt053_reimport.xml'));
    }

    protected function uploadFile(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('statement.xml', $this->camtFixture());
    }

    protected function import(int $account_id)
    {
        return $this->loginAs()->post(route('statement-imports.import'), [
            'account_id' => $account_id,
            'import'     => $this->uploadFile(),
        ]);
    }

    /**
     * Reproduces the reported bug: import a file, delete the import, then
     * re-upload the same file. The second import must stage its lines again
     * (fresh review run) instead of 500-ing or silently refusing.
     */
    public function testItShouldReimportAfterDeletingThePriorImport()
    {
        $account = Account::factory()->create();

        // Only 1 request/minute is allowed by throttle:import; bypass it so both
        // uploads reach the controller.
        $this->withoutMiddleware(ThrottleRequests::class);

        // 1. First import stages all 17 lines.
        $this->import($account->id)->assertStatus(200);

        $first = BankStatementImport::first();
        $this->assertNotNull($first);
        $this->assertEquals(17, $first->total_lines);
        $this->assertEquals(17, $first->lines()->count());

        // 2. Delete the import (soft-deletes run + lines, as a user would).
        $this->loginAs()
            ->delete(route('statement-imports.destroy', $first->id))
            ->assertSuccessful();

        $this->assertSoftDeleted('bank_statement_imports', ['id' => $first->id]);

        // 3. Re-upload the SAME file. Must succeed and create a NEW review run.
        $this->import($account->id)->assertStatus(200);
        $this->assertFlashLevel('success');

        $second = BankStatementImport::where('id', '!=', $first->id)->first();
        $this->assertNotNull($second, 'A second import run should be created after re-uploading.');
        $this->assertEquals('reviewing', $second->status);
        $this->assertEquals(17, $second->lines()->count());

        // The re-staged lines should be reviewable (pending), not all marked
        // duplicate against the soft-deleted originals.
        $this->assertGreaterThan(
            0,
            $second->lines()->where('status', BankStatementLine::STATUS_PENDING)->count(),
            'Re-imported lines should be pending, not all duplicates of soft-deleted rows.'
        );
    }

    /**
     * Same scenario with laravel-model-caching ENABLED (the deployed default;
     * the test env disables it via .env.testing). This exercises the cached
     * `first()`/`exists()` dedup guards after a soft-delete flush.
     */
    public function testItShouldReimportAfterDeleteWithModelCachingEnabled()
    {
        config(['laravel-model-caching.enabled' => true]);

        $account = Account::factory()->create();

        $this->withoutMiddleware(ThrottleRequests::class);

        $this->import($account->id)->assertStatus(200);
        $first = BankStatementImport::first();
        $this->assertEquals(17, $first->lines()->count());

        $this->loginAs()->delete(route('statement-imports.destroy', $first->id));

        // Re-import must succeed and stage a fresh run, not 500 or refuse due to
        // a stale cached guard hit.
        $this->import($account->id)->assertStatus(200);
        $this->assertFlashLevel('success');

        $second = BankStatementImport::where('id', '!=', $first->id)->first();
        $this->assertNotNull($second, 'Second run should be created with model caching enabled.');
        $this->assertEquals(17, $second->lines()->count());
    }
}
