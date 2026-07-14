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
        Schema::table('user_roles', function (Blueprint $table) {
            $connection = Schema::getConnection();

            if ($connection->getDriverName() === 'pgsql') {
                // Laravel 10's Postgres grammar drops "user_roles_pkey", ignoring the table prefix
                $prefixed = $connection->getTablePrefix() . 'user_roles';

                $connection->statement("alter table \"{$prefixed}\" drop constraint \"{$prefixed}_pkey\"");
            } else {
                $table->dropPrimary(['user_id', 'role_id', 'user_type']);
            }

            $table->primary(['user_id', 'role_id']);
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
