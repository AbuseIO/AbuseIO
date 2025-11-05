<?php

namespace Database\Seeders;

use AbuseIO\Models\Permission;
use AbuseIO\Models\Role;
use AbuseIO\Models\User;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Permissions for the User role
        $permissions = [
            'login_portal',
            'netblocks_view', 'netblocks_create', 'netblocks_edit', 'netblocks_delete', 'netblocks_export',
            'domains_view', 'domains_create', 'domains_edit', 'domains_delete', 'domains_export',
            'tickets_view', 'tickets_create', 'tickets_edit', 'tickets_delete', 'tickets_export',
            'search_view', 'search_create', 'search_edit', 'search_delete', 'search_export',
            'analytics_view', 'analytics_create', 'analytics_edit', 'analytics_delete', 'analytics_export',
            'accounts_view', 'accounts_create', 'accounts_edit', 'accounts_delete', 'accounts_export',
            'profile_manage',
            'users_view', 'users_edit',
            'evidence_view',
        ];

        // User role permissions
        foreach ($permissions as $permission_name) {
            $permission = Permission::where('name', '=', $permission_name)->first();

            $permission_role[] = [
                'permission_id' => $permission->id,
                'role_id'       => '2',
                'created_at'    => new DateTime(),
                'updated_at'    => new DateTime(),
            ];
        }

        DB::table('permission_role')->insert($permission_role);

        // Optionally assign default roles to known users, but only if they exist.
        // This avoids pre-populating the pivot with non-existent user IDs.
        $defaultAssignments = [
            1 => 1, // user_id 1 => Admin role
            2 => 2, // user_id 2 => Abusedesk role
            3 => 1, // user_id 3 => Admin role
            4 => 2, // user_id 4 => Abusedesk role
            5 => 1, // user_id 5 => Admin role
        ];

        foreach ($defaultAssignments as $userId => $roleId) {
            $user = User::find($userId);
            $role = Role::find($roleId);
            if ($user && $role) {
                // Attach without removing existing roles; skip if already attached
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }
    }
}
