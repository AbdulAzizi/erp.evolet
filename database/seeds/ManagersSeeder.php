<?php

use Illuminate\Database\Seeder;

class ManagersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nozim_hakimov = \App\User::where('name','Нозим')->where('surname','Хакимов')->first();
        $abdulaziz_nurov = \App\User::where('name','AbdulAziz')->where('surname','Nurov')->first();

        $vegapharm = \App\Division::where('name','Vegapharm')->first();
        $spey = \App\Division::where('name','Spey')->first();
        
        $vegapharm->managers()->attach( $nozim_hakimov->id , [
            'country_id' => \App\Country::where('abbreviation','UZ')->first()->id,
            'no_id' => \App\User::where('name','Akbar')->first()->id,
            'pc_representative_id' => \App\User::where('name','AbdulAziz')->first()->id,
        ]);

        $vegapharm->managers()->attach( $abdulaziz_nurov->id , [
            'country_id' => \App\Country::where('abbreviation','KZ')->first()->id,
            'no_id' => \App\User::where('name','Анвар')->first()->id,
            'pc_representative_id' => \App\User::where('name','AbdulAziz')->first()->id,
        ]);

        $spey->managers()->attach( $abdulaziz_nurov->id , [
            'country_id' => \App\Country::where('abbreviation','TJ')->first()->id,
            'no_id' => \App\User::where('name','Нозим')->first()->id,
            'pc_representative_id' => \App\User::where('name','AbdulAziz')->first()->id,
        ]);
        $spey->managers()->attach( $abdulaziz_nurov->id , [
            'country_id' => \App\Country::where('abbreviation','GE')->first()->id,
            'no_id' => \App\User::where('name','AbdulAziz')->first()->id,
            'pc_representative_id' => \App\User::where('name','Сайёра')->first()->id,
        ]);
    }
}
