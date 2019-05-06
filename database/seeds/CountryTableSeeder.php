<?php

use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Country::insert([
            [
                'name' => 'Tajikistan',
                'abbreviation' => 'TJ'
            ],
            [
                'name' => 'Kyrgizistan',
                'abbreviation' => 'KG'
            ],
        ]);
    }
}
