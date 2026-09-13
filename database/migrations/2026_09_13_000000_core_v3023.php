<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The language a customer's invoices are rendered in. Empty on an invoice
     * means the customer's language, and empty on a customer means the
     * company's default language (see the effective_language accessors).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('language', 20)->nullable()->after('currency_code');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->string('language', 20)->nullable()->after('currency_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('language');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
};
