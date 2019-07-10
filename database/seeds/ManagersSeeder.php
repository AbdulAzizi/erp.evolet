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
            'country_id' => 1
        ]);

        $vegapharm->managers()->attach( $abdulaziz_nurov->id , [
            'country_id' => 2
        ]);

        $spey->managers()->attach( $abdulaziz_nurov->id , [
            'country_id' => 4
        ]);
        $spey->managers()->attach( $abdulaziz_nurov->id , [
            'country_id' => 1
        ]);
    }
}
