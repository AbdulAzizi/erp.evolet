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
        $managers = \App\Responsibility::where('name', 'Куратор ПК')->first()->users;

        foreach ($managers as $manager) {
            for ($i=0; $i < 10; $i++) { 
                $manager->pcs()->attach(
                    getRandomId(Division::class, Division::withDepth()->having('depth','!=', 4)->pluck('id')->toArray()),
                    [
                        'country_id' => getRandomId(Country::class),
                        'no_id' => getRandomId(User::class),
                        'pc_representative_id' => getRandomId(User::class),
    
                    ]
                );
            }
        }
    }

    public function getRandomId($class, $exceptions = [])
    {
        return $class::all()->except($exceptions)->random()->id;
    }
}
