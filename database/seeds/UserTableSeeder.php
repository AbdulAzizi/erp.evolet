<?php

use Illuminate\Database\Seeder;
use App\Division;
use App\User;
use App\Position;
use App\Responsibility;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedUsers([
            [
                'name' => 'Akbar',
                'surname' => 'Ergashev',
                'email' => 'ergashev.akb@gmail.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'position' => 'Специалист',
                'responsibility' => 'Программист',
            ],
            [
                'name' => 'AbdulAziz',
                'surname' => 'Nurov',
                'img' => 'abdulaziz.jpg',
                'email' => 'nurovaziz@gmail.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'position' => 'Специалист',
                'responsibility' => 'Программист',
            ],
            [
                'name' => 'Анвар',
                'surname' => 'Джабаров',
                'img' => 'anvar.jpg',
                'email' => 'anvar@gmail.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'position' => 'Руководитель',
                'responsibility' => 'Программист',
            ],
            [
                'name' => 'Сайёра',
                'surname' => 'Мирзоева',
                'email' => 'mirzoeva@gmail.com',
                'password' => 'admin',
                'division' => 'Evolet',
                'position' => 'Руководитель',
                'responsibility' => 'Директор',
            ],
            [
                'name' => 'Нозим',
                'surname' => 'Хакимов',
                'email' => 'hakimov@gmail.com',
                'password' => 'admin',
                'division' => 'НАП',
                'position' => 'Руководитель',
                'responsibility' => 'Аналитик',
            ],
        ]);

        $this->userAsDivisionHead('Мирзоева');
        $this->userAsDivisionHead('Джабаров');
        $this->userAsDivisionHead('Хакимов');

        factory(User::class, 40)->create();
    }

    private function userAsDivisionHead($surname)
    {
        $user = User::where('surname', $surname)->first();
        
        Division::find($user->division_id)->update(['head_id' => $user->id]);
    }

    private function seedUsers($credentials)
    {
        foreach ($credentials as $credential) {
            $userData = [
                'name' => $credential['name'],
                'surname' => $credential['surname'],
                'division_id' => Division::where('abbreviation', $credential['division'])->first()->id,
                'position_id' => Position::firstOrCreate(['name' => $credential['position']])->id,
                'responsibility_id' =>  Responsibility::firstOrCreate(['name' => $credential['responsibility']])->id,
                'email' => $credential['email'],
                'password' => Hash::make($credential['password']),
            ];
            
            if(array_key_exists('img', $credential))
                $userData['img'] = $credential['img'];

            $user = User::create($userData);
            
        }
    }
}
