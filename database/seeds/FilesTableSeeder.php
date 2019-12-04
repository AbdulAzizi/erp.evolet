<?php

use App\Field;
use App\File;
use Illuminate\Database\Seeder;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = File::create(['name'=>'Файл МРБ']);
        $fields = Field::whereIn('label', [
            'МНН',
            'Ф',
            'Д',
            'ОПУ',
            'Класс Пд',
            'АТХ',

        ])->get();

        $file->fields()->attach($fields);
    }
}
