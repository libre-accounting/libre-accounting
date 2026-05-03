<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tracks the progress and result of a full-company backup (export) or
     * restore (import). Modeled on bank_statement_imports: a status column plus
     * total/processed counters, polled by the UI while the queued job runs.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_backups', function (Blueprint $table) {
            $table->increments('id');
            // For export: the source company. For import: the target company
            // (nullable until the shell company is created).
            $table->integer('company_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('type', 20);                 // export | import
            $table->string('status', 20)->default('pending'); // pending|processing|completed|failed
            $table->string('filename')->nullable();
            $table->unsignedInteger('media_id')->nullable(); // drives the download link (export)
            $table->integer('total')->default(0);
            $table->integer('processed')->default(0);
            $table->text('error')->nullable();
            $table->text('report')->nullable();         // JSON: warnings (missing files, skipped modules/reports)
            $table->unsignedInteger('created_by')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('user_id');
            $table->index('media_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_backups');
    }
};
