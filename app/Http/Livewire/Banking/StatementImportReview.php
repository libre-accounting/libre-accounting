<?php

namespace App\Http\Livewire\Banking;

use App\Jobs\Banking\CommitStatementLines;
use App\Models\Banking\BankStatementImport;
use App\Models\Banking\BankStatementLine;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Traits\Jobs;
use Livewire\Component;
use Livewire\WithPagination;

class StatementImportReview extends Component
{
    use Jobs, WithPagination;

    public BankStatementImport $statementImport;

    /** Per-line category selection, keyed by line id. */
    public array $categories = [];

    /** Per-line contact selection, keyed by line id. */
    public array $contacts = [];

    /** Selected line ids for committing, keyed by line id => bool. */
    public array $selected = [];

    /** Bulk "set category for all selected" value. */
    public $bulk_category = null;

    public function mount(BankStatementImport $statementImport): void
    {
        $this->statementImport = $statementImport;

        foreach ($this->pendingLines() as $line) {
            $this->categories[$line->id] = $line->category_id;
            $this->contacts[$line->id] = $line->contact_id;
            $this->selected[$line->id] = false;
        }
    }

    public function render()
    {
        $per_page = (int) config('statement_imports.per_page', 50);

        return view('livewire.banking.statement-import-review', [
            'lines'              => $this->statementImport->lines()->orderBy('id')->paginate($per_page),
            'pending_count'      => $this->statementImport->lines()->pending()->count(),
            'income_categories'  => Category::enabled()->income()->orderBy('name')->pluck('name', 'id'),
            'expense_categories' => Category::enabled()->expense()->orderBy('name')->pluck('name', 'id'),
            'customers'          => Contact::enabled()->customer()->orderBy('name')->pluck('name', 'id'),
            'vendors'            => Contact::enabled()->vendor()->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /** Toggle every pending line's selection on/off. */
    public function toggleAll($checked): void
    {
        foreach (array_keys($this->selected) as $id) {
            $this->selected[$id] = (bool) $checked;
        }
    }

    /** Apply the bulk category to every currently selected line. */
    public function applyBulkCategory(): void
    {
        if (empty($this->bulk_category)) {
            return;
        }

        foreach ($this->selected as $id => $isSelected) {
            if ($isSelected) {
                $this->categories[$id] = $this->bulk_category;
            }
        }
    }

    public function commit()
    {
        $selected_ids = array_keys(array_filter($this->selected));

        if (empty($selected_ids)) {
            flash(trans('statement_imports.errors.none_selected'))->warning()->important();

            return $this->backToReview();
        }

        // Enforce that every selected line has a category before committing.
        foreach ($selected_ids as $id) {
            if (empty($this->categories[$id] ?? null)) {
                flash(trans('statement_imports.errors.category_required'))->error()->important();

                return $this->backToReview();
            }
        }

        // Persist per-line category/contact edits chosen during review.
        foreach ($selected_ids as $id) {
            BankStatementLine::where('id', $id)
                ->where('bank_statement_import_id', $this->statementImport->id)
                ->update([
                    'category_id' => $this->categories[$id],
                    'contact_id'  => $this->contacts[$id] ?? null,
                ]);
        }

        $created = $this->dispatch(new CommitStatementLines($this->statementImport, $selected_ids));

        flash(trans('statement_imports.messages.committed', ['count' => $created]))->success();

        return $this->backToReview();
    }

    protected function backToReview()
    {
        return redirect()->route('statement-imports.edit', $this->statementImport->id);
    }

    protected function pendingLines()
    {
        return $this->statementImport->lines()->pending()->get();
    }
}
