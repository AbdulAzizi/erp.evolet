<?php

use App\Option;
use App\Question;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // make question
        $question = Question::create(['body' => 'Стоит ли браться за этот продукт?']);
        // attach options to question
        $question->options()->saveMany([
            new Option(['body' => 'Да']),
            new Option(['body' => 'Нет']),
        ]);
    }
}
