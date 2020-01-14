<?php

use App\FieldType;
use Illuminate\Database\Seeder;

class FieldsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FieldType::insert([
            ['name' => 'string', 'label' => 'Текст'],
            ['name' => 'text', 'label' => 'Параграф'],
            ['name' => 'number', 'label' => 'Число'],
            ['name' => 'list', 'label' => 'Список'],
            ['name' => 'many-to-many-list', 'label' => 'Относительный Список'],
            ['name' => 'users', 'label' => 'Пользователи'],
            ['name' => 'date', 'label' => 'Дата'],
            ['name' => 'time', 'label' => 'Время'],
            ['name' => 'date-time', 'label' => 'Дата и Время'],
            ['name' => 'year', 'label' => 'Год'],
            ['name' => 'image', 'label' => 'Картинка']
        ]);
    }
}
