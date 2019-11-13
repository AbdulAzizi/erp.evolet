<?php

use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Tag::insert([
            ['name'=>'Упаковка'],
            ['name'=>'Макет'],
            ['name'=>'ПУРП'],
            ['name'=>'DISC'],
            ['name'=>'Lafeum'],
            ['name'=>'Разработка'],
            ['name'=>'SEO'],
            ['name'=>'SMM'],
        ]);
    }
}
