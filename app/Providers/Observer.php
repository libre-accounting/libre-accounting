<?php

namespace App\Providers;

use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use Illuminate\Support\ServiceProvider as Provider;

class Observer extends Provider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Transaction::observe('App\Observers\Transaction');

        // Keep the stock ledger in step with invoices and bills
        Document::observe('App\Observers\Document');
        DocumentItem::observe('App\Observers\DocumentItem');
    }
}
