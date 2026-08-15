<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Stock tracking for product items. Stock on hand is the sum of the
     * item_stock_movements ledger: rows with a document_id are derived from
     * invoices and bills by App\Utilities\Stock and rewritten whenever the
     * document changes; rows without one are manual (adjustment, assembly).
     * item_components lists what a kit or an assembled item is built from.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('track_stock')->default(0)->after('purchase_price');
        });

        Schema::create('item_components', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('item_id');
            $table->integer('component_id');
            $table->double('quantity', 12, 2);
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'item_id']);
            $table->index('component_id');
        });

        Schema::create('item_stock_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('item_id');
            $table->integer('via_item_id')->nullable();      // the kit or assembled item the movement came through
            $table->integer('document_id')->nullable();      // derived rows only
            $table->integer('document_item_id')->nullable();
            $table->string('type', 20);                      // the document type, adjustment or assembly
            $table->double('quantity', 12, 2);               // signed; same precision as document_items.quantity
            $table->dateTime('moved_at');
            $table->text('description')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->index(['item_id', 'moved_at']);
            $table->index('document_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_stock_movements');
        Schema::dropIfExists('item_components');

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('track_stock');
        });
    }
};
