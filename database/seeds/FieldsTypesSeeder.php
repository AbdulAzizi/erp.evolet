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
            ['name' => 'string'],
            ['name' => 'text'],
            ['name' => 'number'],

            ['name' => 'list'],
            ['name' => 'many-to-many-list'],
            
            ['name' => 'users'],
            ['name' => 'date'],
            ['name' => 'time'],
            ['name' => 'date-time'],
            ['name' => 'year'],
            ['name' => 'image']
        ]);
    }
}
