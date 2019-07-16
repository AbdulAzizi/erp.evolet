<?php

use App\Field;
use Illuminate\Database\Seeder;
use App\Division;
use App\Country;
use App\Process;

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
                    'pc_id' => $pc->id,
                    'country_id' => $this->getRandomId(Country::class),
                ]);
    
                $faker = Faker\Factory::create();
    
                $preparedFields = $fields->mapWithKeys(function ($field) use ($faker) {
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
