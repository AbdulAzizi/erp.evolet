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
        // TODO fix many to many responsibilities seed code
        $this->seedUsers([
            [
                'name' => 'Nozim',
                'surname' => 'Khakimov',
                'email' => 'nozim@admin.com',
                'password' => 'admin',
                'division' => 'НАП',
                'position' => 'Руководитель',
                'responsibilities' => ['Куратор Портфел ПК стран'],
            ],
            [
                'name' => 'Firdavs',
                'surname' => 'Kilichbekov',
                'email' => 'firdavs@admin.com',
                'password' => 'admin',
                'division' => 'ОМАР',
                'position' => 'Руководитель',
                'responsibilities' => [],
            ],
            [
                'name' => 'Mehroj',
                'surname' => 'Khakimov',
                'email' => 'mehroj@admin.com',
                'password' => 'admin',
                'division' => 'НО',
                'position' => 'Руководитель',
                'responsibilities' => ['НО'],
            ],
            [
                'name' => 'Inoyat',
                'surname' => 'Nasridinshoev',
                'email' => 'inoyat@admin.com',
                'password' => 'admin',
                'division' => 'ОМАР',
                'position' => 'Специалист',
                'responsibilities' => ['Куратор Портфел ПК стран'],
            ],
            [
                'name' => 'Alisher',
                'surname' => 'Baratov',
                'email' => 'alisher@admin.com',
                'password' => 'admin',
                'division' => 'ОМАР',
                'position' => 'Специалист',
                'responsibilities' => ['Куратор Портфел ПК стран'],
            ],
            [
                'name' => 'Akbar',
                'surname' => 'Ergashev',
                'email' => 'ergashev.akb@gmail.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'position' => 'Специалист',
                'responsibilities' => ['ПК'],
            ],
            [
                'name' => 'AbdulAziz',
                'surname' => 'Nurov',
                'img' => 'abdulaziz.jpg',
                'email' => 'nurovaziz@gmail.com',
                'password' => 'admin',
                'division' => 'ОРПО',
                'position' => 'Руководитель',
                'responsibilities' => ['Куратор Портфел ПК стран','Программист'],
            ],
            [
                'name' => 'Анвар',
                'surname' => 'Джабаров',
                'img' => 'anvar.jpg',
                'email' => 'anvar@gmail.com',
                'password' => 'admin',
                'division' => 'ОЦМ',
                'position' => 'Руководитель',
                'responsibilities' => ['НО'],
            ],
            [
                'name' => 'Сайёра',
                'surname' => 'Мирзоева',
                'email' => 'mirzoeva@gmail.com',
                'password' => 'admin',
                'division' => 'Evolet',
                'position' => 'Руководитель',
                'responsibilities' => ['Директор'],
            ],
        ]);

        $this->userAsDivisionHead('nozim@admin.com');
        $this->userAsDivisionHead('firdavs@admin.com');
        $this->userAsDivisionHead('mehroj@admin.com');
        $this->userAsDivisionHead('nurovaziz@gmail.com');
        $this->userAsDivisionHead('anvar@gmail.com');
        $this->userAsDivisionHead('mirzoeva@gmail.com');

        factory(User::class, 40)->create()->each(function ($user){
            for($i = 1; $i <= random_int(1,4); $i++){
                $user->responsibilities()->attach(Responsibility::inRandomOrder()->first()->id);
            }
        });
        
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
                'position_id' => Position::firstOrCreate(['name' => $credential['position']])->id,
                'email' => $credential['email'],
                'password' => Hash::make($credential['password']),
            ];

            
            
            if(array_key_exists('img', $credential))
            $userData['img'] = $credential['img'];
            
            $user = User::create($userData);
            foreach ($credential['responsibilities'] as $responsibility) {
                $user->responsibilities()->attach( Responsibility::firstOrCreate(['name' => $responsibility, 'division_id' => $divisionID ])->id );
            }
            
        }
    }
}
