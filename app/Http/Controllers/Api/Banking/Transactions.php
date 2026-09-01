<?php

namespace App\Http\Controllers\Api\Banking;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\LinkTransfer as LinkTransferRequest;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Resources\Banking\Transaction as Resource;
use App\Http\Resources\Banking\Transfer as TransferResource;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Banking\LinkTransactionsAsTransfer;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Transaction;

class Transactions extends ApiController
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        // Methods outside the resource names get no permission check of their own.
        // Linking retypes two existing transactions and creates a transfer.
        $this->middleware('permission:update-banking-transactions')->only('linkTransfer');
        $this->middleware('permission:create-banking-transfers')->only('linkTransfer');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $transactions = Transaction::with(Resource::relations())->collect(['paid_at'=> 'desc']);

        return Resource::collection($transactions);
    }

    /**
     * Display the specified resource.
     *
     * @param  Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Transaction $transaction)
    {
        return new Resource($transaction->load(Resource::relations()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $transaction = $this->dispatch(new CreateTransaction($request));

        return $this->created(route('api.transactions.show', $transaction->id), new Resource($transaction));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $transaction
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Transaction $transaction, Request $request)
    {
        $transaction = $this->dispatch(new UpdateTransaction($transaction, $request));

        return new Resource($transaction->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        try {
            $this->dispatch(new DeleteTransaction($transaction));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Link this transaction with an existing one on another account, retyping
     * the pair in place as a transfer. Nothing is created or deleted besides
     * the transfer row, so bank references on the transactions are kept.
     *
     * @param  Transaction  $transaction
     * @param  LinkTransferRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function linkTransfer(Transaction $transaction, LinkTransferRequest $request)
    {
        try {
            $transfer = $this->dispatch(
                new LinkTransactionsAsTransfer($transaction, (int) $request->get('target_transaction_id'))
            );
        } catch (\Exception $e) {
            return $this->errorBadRequest($e->getMessage());
        }

        $transfer->load('expense_transaction.account', 'income_transaction.account');

        return $this->created(route('api.transfers.show', $transfer->id), new TransferResource($transfer));
    }
}
