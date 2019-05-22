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

        $this->seedEmployees([
            [
                'name' => 'Akbar',
                'surname' => 'Ergashev',
                'img' => 'daler',
                'email' => 'ergashev.akb@gmail.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'position' => 'Специалист',
                'responsibility' => 'Программист',
            ],
            [
                'name' => 'AbdulAziz',
                'surname' => 'Nurov',
                'img' => 'abdulaziz',
                'email' => 'nurovaziz@gmail.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'position' => 'Специалист',
                'responsibility' => 'Программист',
            ],
            [
                'name' => 'Анвар',
                'surname' => 'Джабаров',
                'img' => 'anvar',
                'email' => 'anvar@gmail.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'position' => 'Руководитель',
                'responsibility' => 'Программист',
            ],
        ]);

        factory(App\Employee::class, 40)->create();
    }

    private function seedEmployees($credentials)
    {
        foreach ($credentials as $credential) {
            $user = App\User::create([
                'name' => $credential['name'],
                'surname' => $credential['surname'],
                'img' => $credential['img'],
                'email' => $credential['email'],
                'password' => Hash::make($credential['password']),
            ]);
            
            $position = App\Position::where('name', $credential['position'])->first();
            $responsibility = App\Responsibility::where('name', $credential['responsibility'])->first();
            
            if(!$position)
                $position = App\Position::create([ 'name' => $credential['position']]);
            
            if(!$responsibility)
                $responsibility = App\Responsibility::create([ 'name' => $credential['responsibility']]);

            $user->employee()->create([
                'division_id' => App\Division::where('abbreviation', $credential['division'])->first()->id,
                'position_id' => $position->id,
                'responsibility_id' => $responsibility->id,
            ]);
        }
    }
}
