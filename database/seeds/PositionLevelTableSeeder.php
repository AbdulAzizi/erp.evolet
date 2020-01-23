<?php

use Illuminate\Database\Seeder;

class PositionLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positionLevels = App\PositionLevel::insert([
            ['name' => 'Руководитель'],
            ['name' => 'Главный специалист'],
            ['name' => 'Ведущий специалист'],
            ['name' => 'Младший специалист'],
            ['name' => 'Стажёр'],
        ]);
    }
}
