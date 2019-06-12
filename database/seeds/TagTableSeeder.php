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
            ['name'=>'Битрикс'],
            ['name'=>'Конкурс'],
            ['name'=>'Упаковка'],
            ['name'=>'Макет'],
            ['name'=>'ФУРП'],
        ]);
    }
}
