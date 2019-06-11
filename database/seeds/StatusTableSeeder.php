<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Status::insert([
            ['name'=>'Новый'],
            ['name'=>'В процессе'],
            ['name'=>'Закрытый'],
        ]);
    }
}
