<?php

use App\Models\Auth\Role;
use App\Traits\Permissions;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use Permissions;

    /**
     * Run the migrations.
     *
     * This only grants the permission to roles that ALREADY exist, so it is a
     * no-op on a fresh install (the Permissions seeder handles those). It must
     * never create a role: doing so during the migration phase would insert a
     * role ahead of the seeded ones and shift their IDs.
     *
     * @return void
     */
    public function up()
    {
        // Full access for admin and manager roles (only if they exist).
        $this->attachPermissionsToAdminRoles([
            'banking-statement-imports' => 'c,r,u,d',
        ]);

        // Read-only for the accountant role, without creating it when absent.
        if ($accountant = Role::where('name', 'accountant')->first()) {
            $this->attachPermissionsByAction($accountant, 'banking-statement-imports', 'r');
        }
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
