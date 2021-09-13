<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Features extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            Feature::firstOrCreate([
                'name' => 'Merchandise Inventory',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Inventory History',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Gdn Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Grn Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Transfer Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Damage Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Inventory Adjustment',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Siv Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Sale Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Proforma Invoice',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Customer Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Purchase Order',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Tender Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Purchase Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Supplier Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Product Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Warehouse Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'User Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'General Settings',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Notification Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Return Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Reservation Management',
                'is_enabled' => 1,
            ]);

            $professional = Plan::where('name', 'professional')->first();
            $premium = Plan::where('name', 'premium')->first();
            $enterprise = Plan::where('name', 'enterprise')->first();

            $professional->features()->sync(
                Feature::all()->pluck('id')->toArray()
            );

            $premium->features()->sync(
                Feature::all()->pluck('id')->toArray()
            );

            $enterprise->features()->sync(
                Feature::all()->pluck('id')->toArray()
            );
        });
    }
}
