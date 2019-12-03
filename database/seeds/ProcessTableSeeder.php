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
        // ----------------------- PROCESSES------------------------------
        $bp1 = Process::create(['name' => 'КАП']);
        $bp2 = Process::create(['name' => 'Опрос мнений']);
        $bp31 = Process::create(['name' => 'Отказ']);
        $bp32 = Process::create(['name' => 'ППК1']);
        // ----------------------- TETHERS -------------------------------
        $tether1 = App\Tether::create([
            'from_process_id' => $bp1->id,
            'to_process_id' => $bp2->id,
            'action_text' => '',
        ]);

        $tether2 = App\Tether::create([
            'from_process_id' => $bp2->id,
            'to_process_id' => $bp31->id,
            'action_text' => 'Отказаться',
        ]);
        $tether2->forms()->attach(Form::where('name', 'Форма коментарии при отказе')->first());

        $tether3 = App\Tether::create([
            'from_process_id' => $bp2->id,
            'to_process_id' => $bp32->id,
            'action_text' => 'Поставить в Поиск',
        ]);

        $tether3->forms()->attach(Form::where('name', 'Выбор источников')->first());
        // --------------------------- TASKS --------------------------------
        $bp1task1 = App\ProcessTask::create([
            'process_id' => $bp1->id,
            'title' => 'Заполните следующие поля',
            'planned_time' => '180120000',
            'deadline' => date('Y-m-d H:i:s'),
            'responsibility_id' => App\Responsibility::where('name', 'Куратор Портфеля ПК стран')->first()->id,
        ]);

        $bp1task1->forms()->attach(Form::where('name', 'Форма КП_ПК Этап 2')->first()->id);
        // --------------------------------------------------------------------
        $bp2task1 = App\ProcessTask::create([
            'process_id' => $bp2->id,
            'title' => 'Пройдите опрос',
            'planned_time' => '180120000',
            'deadline' => date('Y-m-d H:i:s'),
            'responsibility_id' => App\Responsibility::where('name', 'ПК')->first()->id,
        ]);

        $bp2task1->watchers()->attach([
            App\Responsibility::where('name', 'Рук НАП')->first()->id,
            App\Responsibility::where('name', 'РВЗ')->first()->id,
            App\Responsibility::where('name', 'Куратор Портфеля ПК стран')->first()->id,
        ]);

        $bp2task1->polls()->attach(Question::where('body', 'Стоит ли браться за этот продукт?')->first()->id);
        // -------------------------------------------------------------------------
        $bp2task2 = App\ProcessTask::create([
            'process_id' => $bp2->id,
            'title' => 'Сделайте выбор',
            'planned_time' => '180120000',
            'deadline' => date('Y-m-d H:i:s'),
            'responsibility_id' => App\Responsibility::where('name', 'Куратор Портфеля ПК стран')->first()->id,
        ]);

        // $bp1task1->forms()->attach(Form::where('name','Форма КП_ПК Этап 2')->first()->id);

        // factory(App\Process::class, 20)->create();
    }
}
