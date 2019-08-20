<?php

use App\Form;
use Illuminate\Database\Seeder;

class FormTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Form::create([
            'name' => 'Форма ПК Этап 1',
            'label' => 'Новое Лекарственное Средство'
        ]);
        Form::create([
            'name' => 'Форма КП_ПК Этап 1',
            'label' => 'Новое Лекарственное Средство'
        ]);
        Form::create([
            'name' => 'Форма НО Этап 1',
            'label' => 'Новое Лекарственное Средство'
        ]);
        Form::create([
            'name' => 'Форма КП_ПК Этап 2',
            'label' => 'Дополнительная информация КАП'
        ]);
        
    }
}
