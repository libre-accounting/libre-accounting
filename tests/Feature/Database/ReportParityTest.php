<?php

namespace Tests\Feature\Database;

use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;
use Tests\Feature\FeatureTestCase;

/**
 * Cross-engine parity for money aggregation.
 *
 * Money is stored as double (binary floating point). Summing doubles in the
 * database engine (DOUBLE / double precision / REAL) can differ from a PHP-side
 * sum, and between engines, in the last decimal place. Financial figures must
 * therefore agree within a rounding tolerance on every engine — never assert
 * exact float equality here.
 */
class ReportParityTest extends FeatureTestCase
{
    /** One cent: totals must agree at least this closely across engines. */
    private const TOLERANCE = 0.01;

    public function testSqlSumMatchesPhpSumWithinTolerance()
    {
        $this->loginAs();

        // Deterministic amounts, including values chosen to expose float drift.
        $amounts = [0.1, 0.2, 0.3, 1234.56, 9999.99, 0.07, 42.42];

        $created = collect($amounts)->map(function ($amount) {
            return Transaction::factory()->income()->create(['amount' => $amount]);
        });

        $ids = $created->pluck('id');

        // DB-side aggregate (SUM runs in the engine).
        $sql_sum = (float) Transaction::whereIn('id', $ids)->sum('amount');

        // PHP-side sum over the same rows.
        $php_sum = (float) Transaction::whereIn('id', $ids)->get()->sum('amount');

        $expected = array_sum($amounts);

        $this->assertEqualsWithDelta($expected, $sql_sum, self::TOLERANCE, 'DB-side SUM diverged from the expected total.');
        $this->assertEqualsWithDelta($expected, $php_sum, self::TOLERANCE, 'PHP-side sum diverged from the expected total.');
        $this->assertEqualsWithDelta($sql_sum, $php_sum, self::TOLERANCE, 'DB-side and PHP-side sums disagree beyond tolerance.');
    }

    public function testIncomeMinusExpenseProfitIsConsistentAcrossEngines()
    {
        $this->loginAs();

        $income_amounts = [500.00, 250.25, 0.10, 0.20];
        $expense_amounts = [100.00, 33.33, 0.05];

        $income = collect($income_amounts)->map(fn ($a) => Transaction::factory()->income()->create(['amount' => $a]));
        $expense = collect($expense_amounts)->map(fn ($a) => Transaction::factory()->expense()->create(['amount' => $a]));

        $income_ids = $income->pluck('id');
        $expense_ids = $expense->pluck('id');

        $income_total = (float) Transaction::whereIn('id', $income_ids)->sum('amount');
        $expense_total = (float) Transaction::whereIn('id', $expense_ids)->sum('amount');

        $profit = $income_total - $expense_total;
        $expected_profit = array_sum($income_amounts) - array_sum($expense_amounts);

        $this->assertEqualsWithDelta($expected_profit, $profit, self::TOLERANCE, 'Income - expense profit diverged across engines.');
    }

    public function testReconciliationClosingBalanceTotalWithinTolerance()
    {
        $this->loginAs();

        $balances = [1000.10, 2500.25, 0.30];

        // Build reconciliations directly from real columns. (The Reconciliation
        // factory references columns that don't exist on the table, so it isn't
        // used here.)
        $reconciliations = collect($balances)->map(function ($balance) {
            return Reconciliation::create([
                'company_id'      => company_id(),
                'account_id'      => 1,
                'started_at'      => now()->startOfMonth()->toDateTimeString(),
                'ended_at'        => now()->endOfMonth()->toDateTimeString(),
                'closing_balance' => $balance,
                'reconciled'      => true,
            ]);
        });

        $ids = $reconciliations->pluck('id');

        $sql_total = (float) Reconciliation::whereIn('id', $ids)->sum('closing_balance');

        $this->assertEqualsWithDelta(array_sum($balances), $sql_total, self::TOLERANCE, 'Reconciliation closing-balance total diverged across engines.');
    }
}
