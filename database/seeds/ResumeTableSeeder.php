<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Achievment;
use App\Resume;
use App\Job;
use App\Education;
use App\Family;
use App\Language;
use App\User;


class ResumeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abdulazizResume = Resume::create([

            'name' => 'Abdulaziz',
            'surname' => 'Nurov',
            'male_female' => 'Мужской',
            'birthday' => new Carbon('1996-01-17'),
            'email' => 'abdulaziz@gmail.com',
            'phone' => '985100888',
            'creator' => 7

        ]);


        Education::create([

            'degree' => 'Бакалавр',
            'start_at' => new Carbon('2014-09-01'),
            'end_at' => new Carbon('2018-06-20'),
            'name' => 'Misis',
            'specialty' => 'Computer Science',
            'resume_id' => $abdulazizResume->id

        ]);

        Education::create([

            'degree' => 'Магистр',
            'start_at' => new Carbon('2018-09-01'),
            'end_at' => new Carbon('2020-06-20'),
            'name' => 'MGU',
            'specialty' => 'Computer Science',
            'resume_id' => $abdulazizResume->id

        ]);

        Education::create([

            'degree' => 'PHD',
            'start_at' => new Carbon('2020-09-01'),
            'end_at' => new Carbon('2024-06-20'),
            'name' => 'Bauman',
            'specialty' => 'Computer Science',
            'resume_id' => $abdulazizResume->id

        ]);


        Job::create([

            'start_at' => new Carbon('2017-09-01'),
            'end_at' => new Carbon('2018-09-01'),
            'company_name' => 'Alif',
            'positionLevel' => 'Dev',
            'location' => 'Dushanbe',
            'resume_id' => $abdulazizResume->id

        ]);


        Family::create([

            'relation' => 'Жена',
            'birthday' => new Carbon('1997-08-04'),
            'name' => 'Евгения',
            'resume_id' => $abdulazizResume->id

        ]);

        Family::create([

            'relation' => 'Сын',
            'birthday' => new Carbon('2000-09-21'),
            'name' => 'Джумахон',
            'resume_id' => $abdulazizResume->id

        ]);

        Family::create([

            'relation' => 'Дочь',
            'birthday' => new Carbon('2002-10-28'),
            'name' => 'Лола',
            'resume_id' => $abdulazizResume->id

        ]);


        Language::create([

            'name' => 'Английский',
            'level' => 'Intermidiate',
            'resume_id' => $abdulazizResume->id

        ]);

        Language::create([

            'name' => 'Немецкий',
            'level' => 'Pre-Intermidiate',
            'resume_id' => $abdulazizResume->id

        ]);

        Language::create([

            'name' => 'Русский',
            'level' => 'За школой',
            'resume_id' => $abdulazizResume->id

        ]);

        Achievment::create([

            'type' => 'Спорт',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.
                             Assumenda provident inventore eum sapiente! Odit modi
                             consequuntur dicta laboriosam itaque.
                             Nemo natus voluptatem accusamus recusandae aliquid maxime.
                             Commodi ex distinctio perspiciatis.',

            'resume_id' => $abdulazizResume->id

        ]);

        Achievment::create([

            'type' => 'Наука',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.
                             Assumenda provident inventore eum sapiente! Odit modi
                             consequuntur dicta laboriosam itaque.
                             Nemo natus voluptatem accusamus recusandae aliquid maxime.
                             Commodi ex distinctio perspiciatis.',

            'resume_id' => $abdulazizResume->id

        ]);
    }
}
