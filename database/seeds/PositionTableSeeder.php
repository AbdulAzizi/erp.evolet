<?php

use App\Division;
use App\Position;
use Illuminate\Database\Seeder;

class PositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Position::insert([
            [
                'name' => 'Программист',
                'label' => 'Программист',
                'division_id' => Division::where('abbreviation', 'ОРПО')->first()->id
            ],
            [
                'name' => 'Контент Менеджер',
                'lebel' => 'Контент Менеджер',
                'division_id' => Division::where('abbreviation', 'ОЦМ')->first()->id
            ],
            [
                'name' => 'Веб Мастер',
                'label' => 'Веб Мастер',
                'division_id' => Division::where('abbreviation', 'ОЦМ')->first()->id
            ],
            [
                'name' => 'Куратор Портфеля ПК стран',
                'label' => 'Куратор Портфеля ПК стран',
                'division_id' => Division::where('abbreviation', 'ОМАР')->first()->id
            ],
            [
                'name' => 'НО',
                'label' => 'НО',
                'division_id' => Division::where('abbreviation', 'НО')->first()->id
            ],
            [
                'name' => 'ПК',
                'label' => 'ПК',
                'division_id' => Division::where('abbreviation', 'ПК')->first()->id
            ],
            [
                'name' => 'Рук НАП',
                'label' => 'Рук НАП',
                'division_id' => Division::where('abbreviation', 'НАП')->first()->id
            ],
            [
                'name' => 'РВЗ',
                'label' => 'РВЗ',
                'division_id' => Division::where('abbreviation', 'Evolet')->first()->id
            ],
            [
                'name' => 'МРБ',
                'label' => 'МРБ',
                'division_id' => Division::where('abbreviation', 'ОЗк')->first()->id
            ],
            [
                'name' => 'HR',
                'label' => 'HR',
                'division_id' => Division::where('abbreviation', 'ОУПС')->first()->id
            ],
        ]);
    }
}
