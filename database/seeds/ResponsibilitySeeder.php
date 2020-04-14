<?php

use App\Responsibility;
use Illuminate\Database\Seeder;

class ResponsibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Responsibility::truncate();
        // factory(Responsibility::class, 20)->create();
        Responsibility::insert([
            ['text' => 'Прочее', 'position_id' => 0],
            ['text' => 'Исправить ошибку', 'position_id' => 0],
            ['text' => 'SMM', 'position_id' => 0],
            ['text' => 'Test', 'position_id' => 0],
            ['text' => 'Content', 'position_id' => 0]
        ]);
    }
}
