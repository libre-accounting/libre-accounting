<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\LinkTransactionsAsTransfer;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Setting\Currency;
use Tests\Feature\FeatureTestCase;

class LinkTransferTest extends FeatureTestCase
{
    /**
     * Create a plain transaction on the given account with explicit
     * attributes, bypassing the factory's random type/contact churn.
     */
    protected function makeTransaction(string $type, Account $account, float $amount, string $paid_at = '2019-04-04'): Transaction
    {
        return Transaction::factory()->{$type}()->create([
            'type'          => $type,
            'account_id'    => $account->id,
            'amount'        => $amount,
            'currency_code' => $account->currency_code,
            'currency_rate' => '1.0',
            'paid_at'       => $paid_at . ' 00:00:00',
            'document_id'   => null,
            'reconciled'    => 0,
        ]);
    }

    protected function enableEur(): void
    {
        Currency::create([
            'company_id'          => company_id(),
            'name'                => 'Euro',
            'code'                => 'EUR',
            'rate'                => 1.1,
            'enabled'             => 1,
            'precision'           => 2,
            'symbol'              => '€',
            'symbol_first'        => 0,
            'decimal_mark'        => ',',
            'thousands_separator' => '.',
            'created_from'        => 'core::test',
        ]);
    }

    public function testItLinksSameCurrencyOppositePairIntoTransfer()
    {
        $this->loginAs();

        $a = Account::factory()->enabled()->default_currency()->create();
        $b = Account::factory()->enabled()->default_currency()->create();

        $expense = $this->makeTransaction('expense', $a, 100.00);
        $income = $this->makeTransaction('income', $b, 100.00);

        $transfer = $this->dispatch(new LinkTransactionsAsTransfer($expense, $income->id));

        $this->assertDatabaseHas('transfers', [
            'id'                     => $transfer->id,
            'expense_transaction_id' => $expense->id,
            'income_transaction_id'  => $income->id,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id'   => $expense->id,
            'type' => Transaction::EXPENSE_TRANSFER_TYPE,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id'   => $income->id,
            'type' => Transaction::INCOME_TRANSFER_TYPE,
        ]);
    }

    public function testItAssignsLegsByTypeRegardlessOfClickedSide()
    {
        $this->loginAs();

        $a = Account::factory()->enabled()->default_currency()->create();
        $b = Account::factory()->enabled()->default_currency()->create();

        $expense = $this->makeTransaction('expense', $a, 250.00);
        $income = $this->makeTransaction('income', $b, 250.00);

        // Act on the INCOME transaction; the expense must still become the
        // expense leg.
        $transfer = $this->dispatch(new LinkTransactionsAsTransfer($income, $expense->id));

        $this->assertEquals($expense->id, $transfer->expense_transaction_id);
        $this->assertEquals($income->id, $transfer->income_transaction_id);
    }

    public function testItLinksCrossCurrencyPairWithDifferentAmounts()
    {
        $this->loginAs();
        $this->enableEur();

        $usd = Account::factory()->enabled()->default_currency()->create();
        $eur = Account::factory()->enabled()->create(['currency_code' => 'EUR']);

        $expense = $this->makeTransaction('expense', $usd, 110.00);
        $income = $this->makeTransaction('income', $eur, 100.00);

        $transfer = $this->dispatch(new LinkTransactionsAsTransfer($expense, $income->id));

        $this->assertDatabaseHas('transfers', ['id' => $transfer->id]);
        $this->assertEquals(Transaction::EXPENSE_TRANSFER_TYPE, $expense->fresh()->type);
        $this->assertEquals(Transaction::INCOME_TRANSFER_TYPE, $income->fresh()->type);
    }

    public function testItRejectsSameAccountPair()
    {
        $this->loginAs();

        $a = Account::factory()->enabled()->default_currency()->create();

        $expense = $this->makeTransaction('expense', $a, 100.00);
        $income = $this->makeTransaction('income', $a, 100.00);

        $this->expectException(\Exception::class);

        $this->dispatch(new LinkTransactionsAsTransfer($expense, $income->id));
    }

    public function testItRejectsSameDirectionPair()
    {
        $this->loginAs();

        $a = Account::factory()->enabled()->default_currency()->create();
        $b = Account::factory()->enabled()->default_currency()->create();

        $one = $this->makeTransaction('income', $a, 100.00);
        $two = $this->makeTransaction('income', $b, 100.00);

        $this->expectException(\Exception::class);

        $this->dispatch(new LinkTransactionsAsTransfer($one, $two->id));
    }

    public function testItRejectsReconciledTransaction()
    {
        $this->loginAs();

        $a = Account::factory()->enabled()->default_currency()->create();
        $b = Account::factory()->enabled()->default_currency()->create();

        $expense = $this->makeTransaction('expense', $a, 100.00);
        $expense->reconciled = 1;
        $expense->save();
        $income = $this->makeTransaction('income', $b, 100.00);

        $this->expectException(\Exception::class);

        $this->dispatch(new LinkTransactionsAsTransfer($expense, $income->id));
    }

    public function testDialFiltersByExactAmountForSameCurrency()
    {
        $this->loginAs();

        $a = Account::factory()->enabled()->default_currency()->create();
        $b = Account::factory()->enabled()->default_currency()->create();

        $source = $this->makeTransaction('expense', $a, 100.00);
        $match = $this->makeTransaction('income', $b, 100.00);
        $noMatch = $this->makeTransaction('income', $b, 999.00);

        $response = $this->get(route('transactions.transfer-dial', $source->id) . '?account_id=' . $b->id);

        $response->assertStatus(200);

        $candidates = json_decode($response->json('candidates'), true);
        $ids = array_column($candidates, 'id');

        $this->assertContains($match->id, $ids);
        $this->assertNotContains($noMatch->id, $ids);
    }

    public function testDialDoesNotFilterByAmountForDifferentCurrency()
    {
        $this->loginAs();
        $this->enableEur();

        $usd = Account::factory()->enabled()->default_currency()->create();
        $eur = Account::factory()->enabled()->create(['currency_code' => 'EUR']);

        $source = $this->makeTransaction('expense', $usd, 110.00);
        $differentAmount = $this->makeTransaction('income', $eur, 100.00);

        $response = $this->get(route('transactions.transfer-dial', $source->id) . '?account_id=' . $eur->id);

        $candidates = json_decode($response->json('candidates'), true);
        $ids = array_column($candidates, 'id');

        $this->assertContains($differentAmount->id, $ids);
    }

    public function testDialRestrictsToSameDayUnlessAllDates()
    {
        $this->loginAs();

        $a = Account::factory()->enabled()->default_currency()->create();
        $b = Account::factory()->enabled()->default_currency()->create();

        $source = $this->makeTransaction('expense', $a, 100.00, '2019-04-04');
        $sameDay = $this->makeTransaction('income', $b, 100.00, '2019-04-04');
        $otherDay = $this->makeTransaction('income', $b, 100.00, '2019-04-10');

        // Default: same day only.
        $response = $this->get(route('transactions.transfer-dial', $source->id) . '?account_id=' . $b->id);
        $ids = array_column(json_decode($response->json('candidates'), true), 'id');

        $this->assertContains($sameDay->id, $ids);
        $this->assertNotContains($otherDay->id, $ids);

        // all_dates=1 widens the window.
        $response = $this->get(route('transactions.transfer-dial', $source->id) . '?account_id=' . $b->id . '&all_dates=1');
        $ids = array_column(json_decode($response->json('candidates'), true), 'id');

        $this->assertContains($sameDay->id, $ids);
        $this->assertContains($otherDay->id, $ids);
    }
}
