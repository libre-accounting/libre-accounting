<?php

use App\Traits\Permissions;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use Permissions;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Full access for admin and manager roles, read-only for accountant.
        $this->attachPermissionsToAdminRoles([
            'banking-statement-imports' => 'c,r,u,d',
        ]);

        $this->attachPermissionsByRoleNames([
            'accountant' => [
                'banking-statement-imports' => 'r',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->detachPermissionsFromAllRoles([
            'create-banking-statement-imports',
            'read-banking-statement-imports',
            'update-banking-statement-imports',
            'delete-banking-statement-imports',
        ]);
    }
};
