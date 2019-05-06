<?php

use Illuminate\Database\Seeder;

class PositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = App\Position::create([
            ['name'=>'Руководитель'],
            ['name'=>'Сотрудник'],
            ['name'=>'Специалист'],
        ]);
    }
}
