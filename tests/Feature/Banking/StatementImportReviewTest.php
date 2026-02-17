<?php

namespace Tests\Feature\Banking;

use App\Http\Livewire\Banking\StatementImportReview;
use App\Jobs\Banking\CommitStatementLines;
use App\Models\Banking\Account;
use App\Models\Banking\BankStatementImport;
use App\Models\Banking\BankStatementLine;
use App\Models\Banking\Transaction;
use App\Models\Setting\Category;
use Tests\Feature\FeatureTestCase;

class StatementImportReviewTest extends FeatureTestCase
{
    protected function makeImport(): BankStatementImport
    {
        $account = Account::factory()->create();

        $import = BankStatementImport::factory()->create([
            'account_id' => $account->id,
            'status'     => 'reviewing',
        ]);

        BankStatementLine::factory()->income()->create([
            'bank_statement_import_id' => $import->id,
            'account_id'               => $account->id,
            'amount'                   => 500,
            'status'                   => BankStatementLine::STATUS_PENDING,
        ]);

        BankStatementLine::factory()->expense()->create([
            'bank_statement_import_id' => $import->id,
            'account_id'               => $account->id,
            'amount'                   => 265.50,
            'status'                   => BankStatementLine::STATUS_PENDING,
        ]);

        return $import;
    }

    public function testItShouldSeeReviewPage()
    {
        $import = $this->makeImport();

        $this->loginAs()
            ->get(route('statement-imports.edit', $import->id))
            ->assertStatus(200)
            ->assertSeeText(trans('statement_imports.import_selected'));
    }

    public function testItShouldCommitSelectedLinesAsTransactions()
    {
        $this->loginAs();

        $import = $this->makeImport();
        $income = $import->lines()->where('type', 'income')->first();
        $expense = $import->lines()->where('type', 'expense')->first();

        $income->update(['category_id' => Category::factory()->income()->create()->id]);
        $expense->update(['category_id' => Category::factory()->expense()->create()->id]);

        $created = $this->dispatch(new CommitStatementLines($import, [$income->id, $expense->id]));

        $this->assertEquals(2, $created);

        // Both lines now reference a real transaction and are marked imported.
        $income->refresh();
        $expense->refresh();

        $this->assertEquals(BankStatementLine::STATUS_IMPORTED, $income->status);
        $this->assertNotNull($income->transaction_id);

        $this->assertDatabaseHas('transactions', [
            'id'     => $income->transaction_id,
            'type'   => 'income',
            'amount' => 500,
        ]);
        $this->assertDatabaseHas('transactions', [
            'id'     => $expense->transaction_id,
            'type'   => 'expense',
            'amount' => 265.50,
        ]);

        // The run is marked completed once no pending lines remain.
        $import->refresh();
        $this->assertEquals(2, $import->imported_lines);
        $this->assertEquals('completed', $import->status);
    }

    public function testItShouldNotCommitLineTwice()
    {
        $this->loginAs();

        $import = $this->makeImport();
        $line = $import->lines()->where('type', 'income')->first();
        $line->update(['category_id' => Category::factory()->income()->create()->id]);

        $this->dispatch(new CommitStatementLines($import, [$line->id]));

        $count_after_first = Transaction::count();

        // Re-committing the same already-imported line is a no-op.
        $created = $this->dispatch(new CommitStatementLines($import, [$line->id]));

        $this->assertEquals(0, $created);
        $this->assertEquals($count_after_first, Transaction::count());
    }

    public function testItShouldSkipLineMatchingExistingTransaction()
    {
        $this->loginAs();

        $import = $this->makeImport();
        $line = $import->lines()->where('type', 'income')->first();
        $line->update([
            'category_id'    => Category::factory()->income()->create()->id,
            'bank_reference' => 'DUP-REF-1',
        ]);

        // Pre-existing transaction matching the line's account/type/amount/reference.
        Transaction::factory()->income()->create([
            'account_id' => $line->account_id,
            'amount'     => $line->amount,
            'paid_at'    => $line->booked_at,
            'reference'  => 'DUP-REF-1',
        ]);

        $created = $this->dispatch(new CommitStatementLines($import, [$line->id]));

        $this->assertEquals(0, $created);

        $line->refresh();
        $this->assertEquals(BankStatementLine::STATUS_SKIPPED, $line->status);
        $this->assertNull($line->transaction_id);
    }

    public function testItShouldBlockCommitWhenCategoryMissing()
    {
        $this->loginAs();

        $import = $this->makeImport();
        $line = $import->lines()->where('type', 'income')->first();

        $component = $this->makeReviewComponent($import);
        $component->selected[$line->id] = true;
        // no category chosen for the selected line
        $component->commit();

        $this->assertFlashLevel('danger');

        // No transaction created, line still pending.
        $this->assertEquals(0, Transaction::count());
        $line->refresh();
        $this->assertEquals(BankStatementLine::STATUS_PENDING, $line->status);
    }

    public function testItShouldApplyBulkCategoryToSelectedLines()
    {
        $this->loginAs();

        $import = $this->makeImport();
        $income = $import->lines()->where('type', 'income')->first();
        $category = Category::factory()->income()->create();

        $component = $this->makeReviewComponent($import);
        $component->selected[$income->id] = true;
        $component->bulk_category = $category->id;
        $component->applyBulkCategory();

        $this->assertEquals($category->id, $component->categories[$income->id]);
    }

    /**
     * Build the Livewire component directly (the app does not expose the
     * /livewire/message endpoint that Livewire::test() relies on).
     */
    protected function makeReviewComponent(BankStatementImport $import): StatementImportReview
    {
        $component = new StatementImportReview();
        $component->mount($import);

        return $component;
    }
}
