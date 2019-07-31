<?php

use Illuminate\Database\Seeder;

class ResponsibilityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Responsibility::insert([
            ['name'=>'Программист'],
            ['name'=>'Контент Менеджер'],
            ['name'=>'Веб Мастер'],
            ['name'=>'Куратор Портфель ПК'],
            ['name'=>'НО'],
            ['name'=>'ПК'],
        ]);
    }
}
