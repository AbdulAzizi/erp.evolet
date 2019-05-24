<?php

use Illuminate\Database\Seeder;
use App\Division;
use App\User;
use App\Employee;

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
            [
                'name' => 'Сайёра',
                'surname' => 'Мирзоева',
                'email' => 'sayora@gmail.com',
                'password' => 'admin',
                'division' => 'Evolet',
                'position' => 'Руководитель',
                'responsibility' => 'Директор',
            ],
        ]);

        $this->employeeAsDivisionHead('Мирзоева');
        $this->employeeAsDivisionHead('Джабаров');

        factory(Employee::class, 40)->create();
    }

    private function employeeAsDivisionHead($surname)
    {
        $employee = User::where('surname', $surname)->first()->employee;
        
        Division::find($employee->division_id)->update(['head_id' => $employee->id]);
    }

    private function seedEmployees($credentials)
    {
        foreach ($credentials as $credential) {
            $userData = [
                'name' => $credential['name'],
                'surname' => $credential['surname'],
                'email' => $credential['email'],
                'password' => Hash::make($credential['password']),
            ];
            
            if(array_key_exists('img', $credential))
                $userData['img'] = $credential['img'];

            $user = App\User::create($userData);
            
            $user->employee()->create([
                'division_id' => App\Division::where('abbreviation', $credential['division'])->first()->id,
                'position_id' => App\Position::firstOrCreate(['name' => $credential['position']])->id,
                'responsibility_id' => App\Responsibility::firstOrCreate(['name' => $credential['responsibility']])->id,
            ]);
        }
    }
}
