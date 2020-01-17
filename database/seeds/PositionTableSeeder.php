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
        $positions = App\Position::insert([
            ['name' => 'Руководитель'],
            ['name' => 'Главный специалист'],
            ['name' => 'Ведущий специалист'],
            ['name' => 'Младший специалист'],
            ['name' => 'Стажёр'],
        ]);
    }
}
