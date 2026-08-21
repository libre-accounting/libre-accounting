<?php

use App\Models\Auth\User;
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
        // Sanctum 4 stores an optional expiry on each token
        if (! Schema::hasColumn('personal_access_tokens', 'expires_at')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                $table->timestamp('expires_at')->nullable()->after('last_used_at');
            });
        }

        // Laratrust 8 types the roles relation as polymorphic, which needs the
        // user_type column that core_v305 dropped; existing rows belong to users.
        if (! Schema::hasColumn('user_roles', 'user_type')) {
            Schema::table('user_roles', function (Blueprint $table) {
                $table->string('user_type')->default((new User)->getMorphClass())->after('role_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('user_roles', 'user_type')) {
            Schema::table('user_roles', function (Blueprint $table) {
                $table->dropColumn('user_type');
            });
        }

        if (Schema::hasColumn('personal_access_tokens', 'expires_at')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                $table->dropColumn('expires_at');
            });
        }
    }
};
