<?php

use App\Division;
use App\Responsibility;
use Illuminate\Database\Seeder;

class ResponsibilityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Responsibility::insert([
            [
                'name' => 'Программист',
                'division_id' => Division::where('abbreviation', 'ОРПО')->first()->id
            ],
            [
                'name' => 'Контент Менеджер',
                'division_id' => Division::where('abbreviation', 'ОЦМ')->first()->id
            ],
            [
                'name' => 'Веб Мастер',
                'division_id' => Division::where('abbreviation', 'ОЦМ')->first()->id
            ],
            [
                'name' => 'Куратор Портфеля ПК стран',
                'division_id' => Division::where('abbreviation', 'ОМАР')->first()->id
            ],
            [
                'name' => 'НО',
                'division_id' => Division::where('abbreviation', 'НО')->first()->id
            ],
            [
                'name' => 'ПК',
                'division_id' => Division::where('abbreviation', 'ПК')->first()->id
            ],
            [
                'name' => 'Рук НАП',
                'division_id' => Division::where('abbreviation', 'НАП')->first()->id
            ],
            [
                'name' => 'РВЗ',
                'division_id' => Division::where('abbreviation', 'Evolet')->first()->id
            ],
        ]);
    }
}
