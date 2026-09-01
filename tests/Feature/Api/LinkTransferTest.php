<?php

namespace Tests\Feature\Api;

use App\Jobs\Auth\CreateUser;
use App\Jobs\Common\CreateCompany;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Models\Common\Company;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;
use Tests\Feature\FeatureTestCase;

class LinkTransferTest extends FeatureTestCase
{
    public function testItShouldLinkTwoTransactionsAsATransfer()
    {
        [$expense, $income] = $this->pair();

        $this->api()
            ->postJson($this->url($expense), ['target_transaction_id' => $income->id])
            ->assertCreated()
            ->assertJsonPath('data.expense_transaction_id', $expense->id)
            ->assertJsonPath('data.income_transaction_id', $income->id)
            ->assertJsonPath('data.from_account_id', $expense->account_id)
            ->assertJsonPath('data.to_account_id', $income->account_id)
            ->assertJsonPath('data.created_from', 'core::link_transfer');

        $this->assertSame(Transaction::EXPENSE_TRANSFER_TYPE, $expense->fresh()->type);
        $this->assertSame(Transaction::INCOME_TRANSFER_TYPE, $income->fresh()->type);
        $this->assertDatabaseCount('transfers', 1);
    }

    public function testItShouldKeepTheOriginalRowsAndTheirReferences()
    {
        [$expense, $income] = $this->pair();

        $expense->update(['reference' => 'BANK-REF-1']);

        $this->api()
            ->postJson($this->url($expense), ['target_transaction_id' => $income->id])
            ->assertCreated();

        $this->assertDatabaseHas('transactions', ['id' => $expense->id, 'reference' => 'BANK-REF-1', 'deleted_at' => null]);
        $this->assertDatabaseHas('transactions', ['id' => $income->id, 'deleted_at' => null]);
    }

    public function testItShouldAssignTheLegsByTypeWhenCalledOnTheIncome()
    {
        [$expense, $income] = $this->pair();

        $this->api()
            ->postJson($this->url($income), ['target_transaction_id' => $expense->id])
            ->assertCreated()
            ->assertJsonPath('data.expense_transaction_id', $expense->id)
            ->assertJsonPath('data.income_transaction_id', $income->id);
    }

    public function testItShouldShowTheLinkedTransferThroughTheTransfersEndpoint()
    {
        [$expense, $income] = $this->pair();

        $id = $this->api()
            ->postJson($this->url($expense), ['target_transaction_id' => $income->id])
            ->assertCreated()
            ->json('data.id');

        $this->api()
            ->getJson('api/transfers/' . $id)
            ->assertOk()
            ->assertJsonPath('data.expense_transaction_id', $expense->id);
    }

    public function testItShouldRefuseTheSameAccount()
    {
        $account = Account::factory()->enabled()->default_currency()->create();
        $expense = $this->transaction('expense', $account);
        $income = $this->transaction('income', $account);

        $this->assertRefused($expense, $income, trans('transfers.errors.link_same_account'));
    }

    public function testItShouldRefuseTheSameDirection()
    {
        $one = $this->transaction('expense', $this->account());
        $two = $this->transaction('expense', $this->account());

        $this->assertRefused($one, $two, trans('transfers.errors.link_same_direction'));
    }

    public function testItShouldRefuseAReconciledTransaction()
    {
        [$expense, $income] = $this->pair();

        $income->reconciled = 1;
        $income->save();

        $this->assertRefused($expense, $income, trans('transfers.errors.link_reconciled'));
    }

    public function testItShouldRefuseATransactionWithADocument()
    {
        [$expense, $income] = $this->pair();

        $document = Document::factory()->bill()->create();
        $expense->update(['document_id' => $document->id]);

        $this->assertRefused($expense, $income, trans('transfers.errors.link_has_document'));
    }

    public function testItShouldRefuseATransactionThatIsAlreadyATransfer()
    {
        [$expense, $income] = $this->pair();

        $this->api()
            ->postJson($this->url($expense), ['target_transaction_id' => $income->id])
            ->assertCreated();

        $other = $this->transaction('income', $this->account());

        $this->assertRefused($expense->fresh(), $other, trans('transfers.errors.link_already_transfer'));

        $this->assertDatabaseCount('transfers', 1);
    }

    public function testItShouldRefuseLinkingATransactionToItself()
    {
        $expense = $this->transaction('expense', $this->account());

        $this->assertRefused($expense, $expense, trans('transfers.errors.link_same_transaction'));
    }

    public function testRefusedLinksShouldChangeNothing()
    {
        $account = Account::factory()->enabled()->default_currency()->create();
        $expense = $this->transaction('expense', $account);
        $income = $this->transaction('income', $account);

        $this->assertRefused($expense, $income, trans('transfers.errors.link_same_account'));

        $this->assertSame('expense', $expense->fresh()->type);
        $this->assertSame('income', $income->fresh()->type);
        $this->assertDatabaseCount('transfers', 0);
    }

    public function testItShouldRequireATargetThatExists()
    {
        $expense = $this->transaction('expense', $this->account());

        $this->withExceptionHandling()
            ->api()
            ->postJson($this->url($expense), [])
            ->assertStatus(422);

        $this->withExceptionHandling()
            ->api()
            ->postJson($this->url($expense), ['target_transaction_id' => 999999])
            ->assertStatus(422);
    }

    public function testItShouldNotLinkATransactionOfAnotherCompany()
    {
        $expense = $this->transaction('expense', $this->account());

        $foreign = $this->foreignTransaction('income');

        // As the target
        $this->withExceptionHandling()
            ->api()
            ->postJson($this->url($expense), ['target_transaction_id' => $foreign->id])
            ->assertStatus(422);

        // As the transaction in the path
        $this->withExceptionHandling()
            ->api()
            ->postJson($this->url($foreign), ['target_transaction_id' => $expense->id])
            ->assertNotFound();

        $this->assertSame('income', DB::table('transactions')->where('id', $foreign->id)->value('type'));
        $this->assertSame('expense', $expense->fresh()->type);
        $this->assertDatabaseCount('transfers', 0);
    }

    public function testItShouldRequireWritePermissions()
    {
        [$expense, $income] = $this->pair();

        $accountant = $this->dispatch(new CreateUser(User::factory()->enabled()->raw([
            'email' => 'readonly@company.com',
            'password' => 'secret-123',
            'roles' => (string) Role::where('name', 'accountant')->value('id'),
            'companies' => [(string) $this->company->id],
        ])));

        $this->withExceptionHandling()
            ->withBasicAuth($accountant->email, 'secret-123')
            ->postJson($this->url($expense), ['target_transaction_id' => $income->id])
            ->assertForbidden();

        $this->assertSame('expense', $expense->fresh()->type);
        $this->assertDatabaseCount('transfers', 0);
    }

    public function testItShouldRefuseAMissingTransaction()
    {
        $this->withExceptionHandling()
            ->api()
            ->postJson('api/transactions/999999/link-transfer', ['target_transaction_id' => 1])
            ->assertNotFound();
    }

    protected function assertRefused(Transaction $source, Transaction $target, string $message): void
    {
        $this->withExceptionHandling()
            ->api()
            ->postJson($this->url($source), ['target_transaction_id' => $target->id])
            ->assertStatus(400)
            ->assertJsonPath('message', $message);
    }

    protected function url(Transaction $transaction): string
    {
        return 'api/transactions/' . $transaction->id . '/link-transfer';
    }

    protected function account(): Account
    {
        return Account::factory()->enabled()->default_currency()->create();
    }

    /**
     * Plain expense on one account, matching income on another.
     */
    protected function pair(): array
    {
        return [
            $this->transaction('expense', $this->account()),
            $this->transaction('income', $this->account()),
        ];
    }

    protected function transaction(string $type, Account $account): Transaction
    {
        return Transaction::factory()->{$type}()->create([
            'type' => $type,
            'account_id' => $account->id,
            'amount' => 100,
            'currency_code' => $account->currency_code,
            'currency_rate' => '1.0',
            'document_id' => null,
            'reconciled' => 0,
        ]);
    }

    /**
     * Factories always build in the user's first company, so move the rows
     * into a second company afterwards.
     */
    protected function foreignTransaction(string $type): Transaction
    {
        $other = $this->dispatch(new CreateCompany(Company::factory()->enabled()->raw()));

        $this->company->makeCurrent();

        $account = $this->account();
        $transaction = $this->transaction($type, $account);

        DB::table('accounts')->where('id', $account->id)->update(['company_id' => $other->id]);
        DB::table('transactions')->where('id', $transaction->id)->update(['company_id' => $other->id]);

        return $transaction;
    }

    protected function api()
    {
        return $this->withBasicAuth('test@company.com', '123456');
    }
}
