<?php

namespace Database\Seeders;

use AbuseIO\Models\Permission;
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

        // Give the users their roles (admin and/or abusedesk)
        DB::table('role_user')->delete();
        $role_user = [
            [
                'id'         => 1,
                'role_id'    => 1,   // Admin role
                'user_id'    => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => 2,
                'role_id'    => 2,   // Abusedesk user role
                'user_id'    => 2,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => 3,
                'role_id'    => 1,   // Admin user role
                'user_id'    => 3,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => 4,
                'role_id'    => 2,   // Abusedesk user role
                'user_id'    => 4,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => 5,
                'role_id'    => 1,   // Admin user role
                'user_id'    => 5,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ];
        DB::table('role_user')->insert($role_user);
    }
}
