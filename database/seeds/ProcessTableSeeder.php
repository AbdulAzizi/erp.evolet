<?php

use App\Form;
use App\Process;
use App\Question;
use Illuminate\Database\Seeder;

class ProcessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bp1 = Process::create(['name' => 'КАП']);            
        $bp2 = Process ::create(['name' => 'Опрос мнений']);
            
        $tether1 = App\Tether::create([
            'from_process_id' => $bp1->id,
            'to_process_id' => $bp2->id,
            // 'form_id' => Form::where('name','Форма КП_ПК Этап 2')->first()->id,
            'action_text' => 'Отправить дальше'
        ]);

        $bp1task1 = App\ProcessTask::create([
            'process_id' => $bp1->id,
            'title' => 'Заполните следующие поля',
            'planned_time' => '180120000',
            'deadline' => date('Y-m-d H:i:s'),
            'responsibility_id' => App\Responsibility::where('name','Куратор Портфеля ПК стран')->first()->id
        ]);

        $bp1task1->forms()->attach(Form::where('name','Форма КП_ПК Этап 2')->first()->id);

        $bp1task2 = App\ProcessTask::create([
            'process_id' => $bp2->id,
            'title' => 'Сделайте выбор',
            'planned_time' => '180120000',
            'deadline' => date('Y-m-d H:i:s'),
            'responsibility_id' => App\Responsibility::where('name','Рук НАП')->first()->id
        ]);

        $bp1task2->watchers()->attach([
            App\Responsibility::where('name','ПК')->first()->id,
            App\Responsibility::where('name','РВЗ')->first()->id,
            App\Responsibility::where('name','Куратор Портфеля ПК стран')->first()->id,
        ]);

        $bp1task2->polls()->attach( Question::where('body', 'Стоит ли браться за этот продукт?')->first()->id );

        // $bp1task1->forms()->attach(Form::where('name','Форма КП_ПК Этап 2')->first()->id);

        // factory(App\Process::class, 20)->create();
    }
}
