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
        $admin = App\User::create([
            'name' => 'admin',
            'surname' => 'admin',
            'img' => '',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
        ]);

        $akbar = App\User::create([
            'name'=>'Akbar',
            'surname'=>'Ergashev',
            'img'=>'akbar',
            'email'=>'ergashev.akb@gmail.com',
            'password'=>Hash::make('admin')
        ]);
        $akbar->employee()->create([
            'division_id' => App\Division::where('abbreviation','ОЦМ')->first()->id,
            'position_id' => App\Position::where('name','Специалист')->first()->id,
            'responsibility_id' => App\Responsibility::where('name','Программист')->first()->id,
        ]);
        

        $abdulaziz = App\User::create([
            'name'=>'AbdulAziz',
            'surname'=>'Nurov',
            'img' => 'abdulaziz',
            'email'=>'nurovaziz@gmail.com',
            'password'=>Hash::make('admin')
        ]);
        $abdulaziz->employee()->create([
            'division_id' => App\Division::where('abbreviation','ОЦМ')->first()->id,
            'position_id' => App\Position::where('name', 'Специалист')->first()->id,
            'responsibility_id' => App\Responsibility::where('name', 'Программист')->first()->id,
        ]);
    }
}
