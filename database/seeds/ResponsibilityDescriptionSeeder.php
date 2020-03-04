<?php

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
            'title' => 'Прочее',
            'responsibility_id' => 0,
        ]);
    }
}
