<?php

use App\Responsibility;
use App\ResponsibilityDescription;
use Illuminate\Database\Seeder;

class ResponsibilityDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResponsibilityDescription::insert([
            [
                'text' => 'Прочее',
                'responsibility_id' => Responsibility::where('text', 'Прочее')->first()->id,
            ], 
            [
                'text' => 'Исправить ошибку',
                'responsibility_id' => Responsibility::where('text', 'Исправить ошибку')->first()->id,
            ],
            [
                'text' => 'SMM',
                'responsibility_id' => Responsibility::where('text', 'SMM')->first()->id,
            ],
            [
                'text' => 'Test',
                'responsibility_id' => Responsibility::where('text', 'Test')->first()->id,
            ],
            [
                'text' => 'Content',
                'responsibility_id' => Responsibility::where('text', 'Content')->first()->id,
            ]
        ]);
    }
}
