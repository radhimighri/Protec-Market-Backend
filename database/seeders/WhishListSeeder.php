<?php

namespace Database\Seeders;

use App\Models\WhishList;
use Illuminate\Database\Seeder;

class WhishListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WhishList::factory()
            ->count(5)
            ->create();
    }
}
