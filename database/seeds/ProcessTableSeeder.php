<?php

use App\Form;
use App\Process;
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
        $bp1 = Process::create(['name' => 'Новое Лекарственное Средство']);            
        $bp2 = Process ::create(['name' => 'Заполнение данных НО']);
            
        $tether1 = App\Tether::create([
            'from_process_id' => $bp1->id,
            'to_process_id' => $bp2->id,
            'form_id' => Form::where('name','Форма КП_ПК Этап 2')->first()->id,
            'action_text' => 'Отправить дальше'
        ]);

        App\ProcessTask::create([
            'process_id' => $bp1->id,
            'title' => 'Заполните следуйщие поля',
            'planned_time' => '180120000',
            'deadline' => date('Y-m-d H:i:s'),
            'responsibility_id' => App\Responsibility::where('name','Куратор Портфел ПК стран')->first()->id
        ]);

        // factory(App\Process::class, 20)->create();
    }
}
