<?php

use Illuminate\Database\Seeder;
use App\Division;
use App\User;
use App\PositionLevel;
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
        // TODO fix many to many responsibilities seed code
        $this->seedUsers([
            [
                'img' => 'parviz.jpg',
                'name' => 'Parviz',
                'surname' => 'Jabborov',
                'email' => 'parviz@admin.com',
                'password' => 'admin',
                'division' => 'ОД',
                'positionLevel' => 'Руководитель',
                'responsibilities' => [],
            ],
            [
                'img' => 'bezhan.jpg',
                'name' => 'Bezhan',
                'surname' => 'Sufiev',
                'email' => 'bezhan@admin.com',
                'password' => 'admin',
                'division' => 'ОР',
                'positionLevel' => 'Руководитель',
                'responsibilities' => [],
            ],
            [
                'img' => 'nozim.jpg',
                'name' => 'Nozim',
                'surname' => 'Khakimov',
                'email' => 'nozim@admin.com',
                'password' => 'admin',
                'division' => 'НАП',
                'positionLevel' => 'Руководитель',
                'responsibilities' => ['Рук НАП'],
            ],
            [
                'img' => 'firdavs.jpg',
                'name' => 'Firdavs',
                'surname' => 'Kilichbekov',
                'email' => 'firdavs@admin.com',
                'password' => 'admin',
                'division' => 'ОМАР',
                'positionLevel' => 'Руководитель',
                'responsibilities' => [],
            ],
            [
                'img' => 'mehroj.jpg',
                'name' => 'Mehroj',
                'surname' => 'Khakimov',
                'email' => 'mehroj@admin.com',
                'password' => 'admin',
                'division' => 'НО',
                'positionLevel' => 'Руководитель',
                'responsibilities' => ['НО'],
            ],
            [
                'img' => 'inoyat.jpg',
                'name' => 'Inoyat',
                'surname' => 'Nasridinshoev',
                'email' => 'inoyat@admin.com',
                'password' => 'admin',
                'division' => 'ПК',
                'positionLevel' => 'Главный специалист',
                'responsibilities' => ['ПК'],
            ],
            [
                'img' => 'azimv.jpg',
                'name' => 'Azimjon',
                'surname' => 'Vohidi',
                'email' => 'azimjon@admin.com',
                'password' => 'admin',
                'division' => 'ОМАП',
                'positionLevel' => 'Руководитель',
                'responsibilities' => ['ПК'],
            ],
            [
                'img' => 'behruz.jpg',
                'name' => 'Behruz',
                'surname' => 'Kholov',
                'email' => 'behruz@admin.com',
                'password' => 'admin',
                'division' => 'НО',
                'positionLevel' => 'Главный специалист',
                'responsibilities' => ['НО'],
            ],
            [
                'img' => 'alisher.jpg',
                'name' => 'Alisher',
                'surname' => 'Baratov',
                'email' => 'alisher@admin.com',
                'password' => 'admin',
                'division' => 'ОМАР',
                'positionLevel' => 'Главный специалист',
                'responsibilities' => ['Куратор Портфеля ПК стран'],
            ],
            [
                'name' => 'Akbar',
                'surname' => 'Ergashev',
                'email' => 'ergashev.akb@admin.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'positionLevel' => 'Главный специалист',
                'responsibilities' => ['ПК'],
            ],
            [
                'name' => 'AbdulAziz',
                'surname' => 'Nurov',
                'img' => 'abdulaziz.jpg',
                'email' => 'nurovaziz@admin.com',
                'password' => 'admin',
                'division' => 'ОРПО',
                'positionLevel' => 'Руководитель',
                'responsibilities' => ['Программист'],
            ],
            [
                'name' => 'Anvar',
                'surname' => 'Jabarrov',
                'img' => 'anvar.jpg',
                'email' => 'anvar@admin.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'positionLevel' => 'Руководитель',
                'responsibilities' => [],
            ],
            [
                'name' => 'Sayora',
                'surname' => 'Mirzoeva',
                'email' => 'mirzoeva@admin.com',
                'password' => 'admin',
                'division' => 'Evolet',
                'positionLevel' => 'Руководитель',
                'responsibilities' => ['РВЗ'],
            ],
            [
                'name' => 'Jovidon',
                'surname' => 'Rahmonov',
                'email' => 'jovidon@admin.com',
                'password' => 'admin',
                'division' => 'ОЗк',
                'positionLevel' => 'Главный специалист',
                'responsibilities' => ['МРБ'],
            ],
            [
                'name' => 'Shuhrat',
                'surname' => 'Safarov',
                'email' => 'shuhrat@admin.com',
                'password' => 'admin',
                'division' => 'ДЧ',
                'positionLevel' => 'Специалист',
                'responsibilities' => ['HR'],
            ],
        ]);

        $this->userAsDivisionHead('nozim@admin.com');
        $this->userAsDivisionHead('firdavs@admin.com');
        $this->userAsDivisionHead('mehroj@admin.com');
        $this->userAsDivisionHead('nurovaziz@admin.com');
        $this->userAsDivisionHead('anvar@admin.com');
        $this->userAsDivisionHead('mirzoeva@admin.com');

        // factory(User::class, 40)->create()->each(function ($user){
        //     for($i = 1; $i <= random_int(1,4); $i++){
        //         $user->responsibilities()->attach(Responsibility::inRandomOrder()->first()->id);
        //     }
        // });
        
    }

    private function userAsDivisionHead($email)
    {
        $user = User::where('email', $email)->first();
        
        Division::find($user->division_id)->update(['head_id' => $user->id]);
    }

    private function seedUsers($credentials)
    {
        foreach ($credentials as $credential) {
            $divisionID = Division::where('abbreviation', $credential['division'])->first()->id;
            $userData = [
                'name' => $credential['name'],
                'surname' => $credential['surname'],
                'division_id' => $divisionID,
                'position_level_id' => PositionLevel::firstOrCreate(['name' => $credential['positionLevel']])->id,
                'email' => $credential['email'],
                'password' => Hash::make($credential['password']),
            ];

            
            
            if(array_key_exists('img', $credential))
            $userData['img'] = $credential['img'];
            
            $user = User::create($userData);
            foreach ($credential['responsibilities'] as $responsibility) {
                $user->responsibilities()->attach( Responsibility::firstOrCreate(['name' => $responsibility ])->id );
            }
            
        }
    }
}
