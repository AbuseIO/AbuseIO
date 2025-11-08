<?php

namespace Database\Seeders;

use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use DateTime;
use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        // Resolve the default brand dynamically (created during install). If not present, create a placeholder.
        $brand = Brand::query()->first();
        if (!$brand) {
            $brand = Brand::query()->create([
                'name'        => 'Default Brand',
                'description' => 'Placeholder brand created by seeder',
                'enabled'     => true,
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
            ]);
        }

        // Seed accounts without hardcoded IDs; link to the brand dynamically
        $data = [
            [
                'name'        => 'Customer Internet',
                'description' => 'Customer internet department',
            ],
            [
                'name'        => 'Business Internet',
                'description' => 'Business internet department',
            ],
        ];

        foreach ($data as $row) {
            /** @var Account $account */
            $account = Account::query()->firstOrCreate(
                ['name' => $row['name'], 'brand_id' => $brand->id],
                [
                    'description' => $row['description'],
                    'token'       => generateApiToken(),
                    'created_at'  => new DateTime(),
                    'updated_at'  => new DateTime(),
                ]
            );

            // If it existed, ensure description/token are up to date without changing ID
            $account->description = $row['description'];
            if (empty($account->token)) {
                $account->token = generateApiToken();
            }
            $account->save();
        }
    }
}
