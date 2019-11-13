<?php

use App\Field;
use Illuminate\Database\Seeder;
use App\Division;
use App\Country;
use App\Process;
use App\Manager;
use App\Project;
use Illuminate\Support\Facades\DB;
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

        $dozas = ["40+12,5 MG","1760mg+///","80+12,5 MG","5 MG +5 MG","1 MG/ML","5 MG","4 MG","750mg+200mg+100mln ","100 MG","100 MG","200 mg","20mg/1ml","25mg","6,25mg","0.05","20MG","10 MG","5 MG/ML","100mg","500 mg + 100 mg","500mg+65,000+100,000 IU","50 mg","20mg 2 ml","3%+5%+3%+10%"];
        $opus = ["28","1x10","28","30","5 ML VIAL","20","20","1x7","20","20","#7","1x5,5ml","№30","№30","30 G","28","20","5 ML VIAL","#20","2x7","2x5","#7","3+3","30gx1"];

        foreach ($pcs as $pc) {
            for ($i=0; $i < 50; $i++) { 
                $product = \App\Product::create([
                    'process_id' => $this->getRandomId(Process::class),
                    'project_id' => $this->getRandomId(Project::class),
                ]);
    
                $faker = Faker\Factory::create();
    
                $preparedFields = $fields->mapWithKeys(function ($field) use ($faker, $dozas, $opus) {
                    if($field->type->name == 'list' || $field->type->name == 'many-to-many-list'){
                        $tableName = DB::table('list_fields')
                            ->where('field_id', $field->id)
                            ->pluck('list_type')
                            ->first();
                        
                        $numberOfListElement = DB::table($tableName)->count();

                        return [$field->id => ['value' => rand(1, $numberOfListElement)]];
                    }
                    if($field->name == "doza")
                        return [$field->id => ['value' => $dozas[array_rand($dozas)] ] ];
                    if($field->name == "opu")
                        return [$field->id => ['value' => $opus[array_rand($opus)] ] ];

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
