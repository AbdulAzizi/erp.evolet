<?php

use Illuminate\Database\Seeder;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $akbar = App\User::create([
            'name'=>'Akbar',
            'surname'=>'Ergashev',
            'email'=>'ergashev.akb@gmail.com',
            'password'=>Hash::make('admin')
        ]);
        $akbar->employee()->create([
            'division_id' => App\Division::where('abbreviation','ОЦМ')->first()->id,
            'position_id' => App\Position::where('name','Специалист')->first()->id
        ]);

        $abdulaziz = App\User::create([
            'name'=>'AbdulAziz',
            'surname'=>'Nurov',
            'email'=>'nurovaziz@gmail.com',
            'password'=>Hash::make('admin')
        ]);
        $abdulaziz->employee()->create([
            'division_id' => App\Division::where('abbreviation','ОЦМ')->first()->id,
            'position_id' => App\Position::where('name', 'Специалист')->first()->id
        ]);
    }
}
