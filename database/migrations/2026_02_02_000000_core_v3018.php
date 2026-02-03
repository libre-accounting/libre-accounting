<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_statement_imports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('account_id');
            $table->string('filename')->nullable();
            $table->string('statement_id')->nullable();
            $table->string('iban', 50)->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->double('opening_balance', 15, 4)->nullable();
            $table->double('closing_balance', 15, 4)->nullable();
            $table->dateTime('statement_from')->nullable();
            $table->dateTime('statement_to')->nullable();
            $table->integer('total_lines')->default(0);
            $table->integer('imported_lines')->default(0);
            $table->string('status', 20)->default('pending');
            $table->string('file_hash', 64);
            $table->unsignedInteger('created_by')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('account_id');
            $table->index('file_hash');
        });

        Schema::create('bank_statement_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bank_statement_import_id');
            $table->integer('account_id');
            $table->integer('transaction_id')->nullable();
            $table->string('type', 20);
            $table->dateTime('booked_at')->nullable();
            $table->dateTime('valued_at')->nullable();
            $table->double('amount', 15, 4)->default('0.0000');
            $table->string('currency_code', 3);
            $table->string('bank_reference')->nullable();
            $table->string('end_to_end_id')->nullable();
            $table->string('counterparty_name')->nullable();
            $table->string('counterparty_iban', 50)->nullable();
            $table->text('remittance_info')->nullable();
            $table->text('description')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('contact_id')->nullable();
            $table->integer('document_id')->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('hash', 64);
            $table->unsignedInteger('created_by')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('bank_statement_import_id');
            $table->index('transaction_id');
            $table->index('hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_statement_lines');
        Schema::dropIfExists('bank_statement_imports');
    }
};
