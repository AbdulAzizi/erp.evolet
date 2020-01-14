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
            'label' => 'КАП'
        ]);
        Form::create([
            'name' => 'Форма КП_ПК Этап 1',
            'label' => 'КАП'
        ]);
        Form::create([
            'name' => 'Форма НО Этап 1',
            'label' => 'КАП'
        ]);
        Form::create([
            'name' => 'Форма КП_ПК Этап 2',
            'label' => 'Дополнительная информация КАП'
        ]);
        Form::create([
            'name' => 'Выбор источников',
            'label' => 'Выбор источников'
        ]);
        Form::create([
            'name' => 'Форма коментарии при отказе',
            'label' => 'Прокоментируйте ваш отказ'
        ]);
        Form::create([
            'name' => 'Форма нового продукта завода',
            'label' => 'Форма нового продукта завода'
        ]);
        Form::create([
            'name' => 'Новый завод',
            'label' => 'Новый завод'
        ]);
        
    }
}
