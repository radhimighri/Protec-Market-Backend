<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Pasta & rice','Vegetables', 'Fruits','Dairy','Bakery', 'Meat', 'Snacks','Sauces & oils'  ,'Canned','Drinks'];
        $logos = ['002.png','003.png','004.png','005.png','006.png','007.png','008.png','009.png','010.png','011.png'];
        foreach(array_combine($names,$logos) as $name =>$logo){
        Categorie::factory()
           // ->make()
            ->create(['name' => $name,
                      'logo' =>$logo,
                      ])
            ;}
    }
}
