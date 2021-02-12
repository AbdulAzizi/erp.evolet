<?php

use Illuminate\Database\Seeder;
use App\Division;
use App\User;
use App\PositionLevel;
use App\Position;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TODO fix many to many positions seed code
        $this->seedUsers([
            [
                'name' => 'Аслиддин',
                'surname' => 'Музафарзода',
                'email' => 'asliddin@admin.com',
                'password' => 'admin',
                'division' => 'ОРПО',
                'positionLevel' => 'Главный специалист',
                'positions' => ["Программист"],
            ],
            [
                'name' => 'Парвиз',
                'surname' => 'Иброхимбеков',
                'email' => 'parviz@admin.com',
                'password' => 'admin',
                'division' => 'ОД',
                'positionLevel' => 'Руководитель',
                'positions' => [],
            ],
            [
                'name' => 'Бежан',
                'surname' => 'Суфиев',
                'email' => 'bezhan@admin.com',
                'password' => 'admin',
                'division' => 'ОР',
                'positionLevel' => 'Руководитель',
                'positions' => [],
            ],
            [
                'name' => 'Нозим',
                'surname' => 'Хакимов',
                'email' => 'nozim@admin.com',
                'password' => 'admin',
                'division' => 'НАП',
                'positionLevel' => 'Руководитель',
                'positions' => ['Рук НАП'],
            ],
            [
                'name' => 'Фирдавс',
                'surname' => 'Киличбеков',
                'email' => 'firdavs@admin.com',
                'password' => 'admin',
                'division' => 'ОМАР',
                'positionLevel' => 'Руководитель',
                'positions' => [],
            ],
            [
                'name' => 'Мехродж',
                'surname' => 'Хакимов',
                'email' => 'mehroj@admin.com',
                'password' => 'admin',
                'division' => 'НО',
                'positionLevel' => 'Руководитель',
                'positions' => ['Научный аналитик'],
            ],
            [
                'name' => 'Иноят',
                'surname' => 'Насридиншоев',
                'email' => 'inoyat@admin.com',
                'password' => 'admin',
                'division' => 'ПК',
                'positionLevel' => 'Главный специалист',
                'positions' => ['ПК'],
            ],
            [
                'name' => 'Азимджон',
                'surname' => 'Вохид',
                'email' => 'azimjon@admin.com',
                'password' => 'admin',
                'division' => 'ОМАП',
                'positionLevel' => 'Руководитель',
                'positions' => ['ПК'],
            ],
            [
                'name' => 'Бехруз',
                'surname' => 'Холов',
                'email' => 'behruz@admin.com',
                'password' => 'admin',
                'division' => 'НО',
                'positionLevel' => 'Главный специалист',
                'positions' => ['Научный аналитик'],
            ],
            [
                'name' => 'Алишер',
                'surname' => 'Баратов',
                'email' => 'alisher@admin.com',
                'password' => 'admin',
                'division' => 'ОМАР',
                'positionLevel' => 'Главный специалист',
                'positions' => ['Куратор Промо кампания при Головном офисе (КПГ)'],
            ],
            [
                'name' => 'Абдулазиз',
                'surname' => 'Нуров',
                'email' => 'nurovaziz@admin.com',
                'password' => 'admin',
                'division' => 'ОРПО',
                'positionLevel' => 'Руководитель',
                'positions' => ['Программист'],
            ],
            [
                'name' => 'Анвар',
                'surname' => 'Джабаров',
                'email' => 'anvar@admin.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'positionLevel' => 'Руководитель',
                'positions' => [],
            ],
            [
                'name' => 'Сайёра',
                'surname' => 'Мирзоева',
                'email' => 'mirzoeva@admin.com',
                'password' => 'admin',
                'division' => 'Evolet',
                'positionLevel' => 'Руководитель',
                'positions' => ['РВЗ'],
            ],
            [
                'name' => 'Човидон',
                'surname' => 'Рахмонов',
                'email' => 'jovidon@admin.com',
                'password' => 'admin',
                'division' => 'ОЗк',
                'positionLevel' => 'Главный специалист',
                'positions' => ['МРБ'],
            ],
            [
                'name' => 'Шухрат',
                'surname' => 'Сафаров',
                'email' => 'shuhrat@admin.com',
                'password' => 'admin',
                'division' => 'ОУПС',
                'positionLevel' => 'Руководитель',
                'positions' => ['HR'],
            ],
            [
                'name' => 'Рукия',
                'surname' => 'Мирзоева',
                'email' => 'ruqiya@admin.com',
                'password' => 'admin',
                'division' => 'ОУПС',
                'positionLevel' => 'Главный специалист',
                'positions' => ['HR'],
            ],
            [
                'name' => 'Саидчон',
                'surname' => 'Комилов',
                'email' => 'saidjon@admin.com',
                'password' => 'admin',
                'division' => 'ОМАР',
                'positionLevel' => 'Главный специалист',
                'positions' => ['Куратор Промо кампания при Головном офисе (КПГ)'],
            ],
            [
                'name' => 'Парвина',
                'surname' => 'Мирахмедова',
                'email' => 'parvina@admin.com',
                'password' => 'admin',
                'division' => 'НО',
                'positionLevel' => 'Руководитель',
                'positions' => ['Научный аналитик'],
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
        //         $user->positions()->attach(Position::inRandomOrder()->first()->id);
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
            foreach ($credential['positions'] as $position) {
                $user->positions()->attach( Position::firstOrCreate(['name' => $position ])->id );
            }
            
        }
    }
}
