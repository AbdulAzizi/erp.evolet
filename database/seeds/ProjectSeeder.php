<?php

use App\Division;
use Illuminate\Database\Seeder;
use App\Country;
use Illuminate\Foundation\Auth\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $managers = \App\Responsibility::where('name', 'Куратор Портфел ПК стран')->first()->users;

        foreach ($managers as $manager) {
            factory(App\Project::class, 10)->create();
        }
    }
}
