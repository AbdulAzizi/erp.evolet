<?php

use App\Field;
use Illuminate\Database\Seeder;
use App\Division;
use App\Country;
use App\Process;
use App\Manager;
use App\Project;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pcs =  Division::withDepth()->having('depth','=', 4)->get();
        $fields = Field::all();

        foreach ($pcs as $pc) {
            for ($i=0; $i < 50; $i++) { 
                $product = \App\Product::create([
                    'process_id' => $this->getRandomId(Process::class),
                    'project_id' => $this->getRandomId(Project::class),
                ]);
    
                $faker = Faker\Factory::create();
    
                $preparedFields = $fields->mapWithKeys(function ($field) use ($faker) {
                    if($field->type->name == 'list'){
                        return [$field->id => ['value' => rand(1, 5)]];
                    }
                    return [$field->id => ['value' => $faker->word]];
                });
    
                $product->fields()->attach($preparedFields->toArray());   
            }
        }
    }

    public function getRandomId($class, $exceptions = [])
    {
        return $class::all()->except($exceptions)->random()->id;
    }
}
