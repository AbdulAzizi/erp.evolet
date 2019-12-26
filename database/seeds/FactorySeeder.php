<?php

use App\Country;
use App\Factory;
use App\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory::insert([
            [
                'name' => 'Antibiotic Razgrad AD',
                'about' => 'Antibiotic-Razgrad AD is the successor of one of the largest pharmaceutical manufacturers in Bulgaria with over 50 years of experience in the development, production and distribution of generic medicines for human and veterinary use.',
                'country_id' => Country::where('name', 'BULGARIA')->first()->id,
                'stability_zone' => 'III',
                'website' => 'antibiotic.com',
                'product_class' => 'MED',
                'logo' => 'antibiotic.png',
                'created_at' => date(now()),
                'updated_at' => date(now()),
            ],
            [
                'name' => 'Azevedos',
                'about' => 'Antidiabetic. Anti-Infective. Blood. Cardiovascular System. Musculoskeletal System. Nervous System.',
                'country_id' => Country::where('name', 'PORTUGAL')->first()->id,
                'stability_zone' => 'I',
                'website' => 'azevedos.com',
                'product_class' => 'COSM',
                'logo' => 'azevedos.png',
                'created_at' => date(now()),
                'updated_at' => date(now()),
            ],
            [
                'name' => 'Help SA',
                'about' => 'To fully satisfy the tailor made needs of our customers in a diverse and changing local and global environment.',
                'country_id' => Country::where('name', 'GREECE')->first()->id,
                'stability_zone' => 'II',
                'website' => 'helpsa.com',
                'product_class' => 'MD',
                'logo' => 'help.png',
                'created_at' => date(now()),
                'updated_at' => date(now()),
            ],
            [
                'name' => 'Normon',
                'about' => 'Laboratorios Normon España. Experiencia y tecnología al servicio de la salud.',
                'country_id' => Country::where('name', 'SPAIN')->first()->id,
                'stability_zone' => 'IVA',
                'website' => 'normon.com',
                'product_class' => 'MD',
                'logo' => 'normon.png',
                'created_at' => date(now()),
                'updated_at' => date(now()),
            ],
            [
                'name' => 'Olimp Laboratories',
                'about' => 'Olimp Laboratories Sp. z o.o. is a Polish pharmaceutical company which was established in 1990.',
                'country_id' => Country::where('name', 'POLAND')->first()->id,
                'stability_zone' => 'IVA',
                'website' => 'olimp.com',
                'product_class' => 'FS',
                'logo' => 'olimp.png',
                'created_at' => date(now()),
                'updated_at' => date(now()),
            ],
            [
                'name' => 'Santa Farma Ilac San. A.S.',
                'about' => 'Santa Farma, which was incorporated in 1944 as a privately-owned pharmaceutical company, celebrates its 75th anniversary in pharma industry.',
                'country_id' => Country::where('name', 'TURKEY')->first()->id,
                'stability_zone' => 'IVB',
                'website' => 'santafarma.com',
                'product_class' => 'COSM',
                'logo' => 'santa.png',
                'created_at' => date(now()),
                'updated_at' => date(now()),
            ],
            [
                'name' => 'Acs Dobfar Spa',
                'about' => 'ACS Dobfar is an Italian, privately held, chemical-pharmaceutical Company with headquarters',
                'country_id' => Country::where('name', 'ITALY')->first()->id,
                'stability_zone' => 'I',
                'website' => 'Acs.com',
                'product_class' => 'MED',
                'logo' => 'acs.png',
                'created_at' => date(now()),
                'updated_at' => date(now()),
            ],
        ]);

        $fields = Form::where('name', 'Форма нового продукта завода')->first()->fields;
        $factories = Factory::all();
        $faker = Faker\Factory::create();
        $dozas = ["40+12,5 MG","1760mg+///","80+12,5 MG","5 MG +5 MG","1 MG/ML","5 MG","4 MG","750mg+200mg+100mln ","100 MG","100 MG","200 mg","20mg/1ml","25mg","6,25mg","0.05","20MG","10 MG","5 MG/ML","100mg","500 mg + 100 mg","500mg+65,000+100,000 IU","50 mg","20mg 2 ml","3%+5%+3%+10%"];
        $opus = ["28","1x10","28","30","5 ML VIAL","20","20","1x7","20","20","#7","1x5,5ml","№30","№30","30 G","28","20","5 ML VIAL","#20","2x7","2x5","#7","3+3","30gx1"];

        foreach ($factories as $factory) {
            for ($i = 0; $i < rand(7, 15); $i++) {
                $product = \App\Product::create([
                    'process_id' => 0,
                    'project_id' => 0,
                ]);

                $preparedFields = $fields->mapWithKeys(function ($field) use ($faker, $dozas, $opus) {
                    if ($field->type->name == 'list' || $field->type->name == 'many-to-many-list') {
                        $tableName = DB::table('list_fields')
                            ->where('field_id', $field->id)
                            ->pluck('list_type')
                            ->first();

                        $numberOfListElement = DB::table($tableName)->count();

                        return [$field->id => ['value' => rand(1, $numberOfListElement)]];
                    }
                    if ($field->abbreviation == "Д") {
                        return [$field->id => ['value' => $dozas[array_rand($dozas)]]];
                    }

                    if ($field->abbreviation == "ОПУ") {
                        return [$field->id => ['value' => $opus[array_rand($opus)]]];
                    }

                    return [$field->id => ['value' => $faker->word]];
                });

                $product->fields()->attach($preparedFields->toArray());
                $factory->products()->attach($product);
            }
        }
    }
}
