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
        ResponsibilityDescription::create([
            'text' => 'Прочее',
            'responsibility_id' => Responsibility::where('text', 'Прочее')->first()->id,
        ]);
    }
}
