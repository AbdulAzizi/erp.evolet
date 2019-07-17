<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Field;
use App\Process;
use App\Form;
use App\FieldType;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $strana = Field::create(['label' => 'Страна', 'name' => 'strana']);
        $pc = Field::create(['label' => 'ПК', 'name' => 'pc']);

        /***************** First Form *********************/
        
        $form1 = Form::create(['name' => 'АФДОТ']);

        $manyToManyListFieldTypeID = FieldType::where('name', 'many-to-many-list')->first()->id;

        $mnn = Field::create(['label' => 'МНН', 'name' => 'mnn', 'type_id' => $manyToManyListFieldTypeID]);
        $form = Field::create(['label' => 'Форма', 'name' => 'form', 'type_id' => $manyToManyListFieldTypeID]);

        $mnnListId = DB::table('list_fields')->insertGetId(['field_id' => $mnn->id, 'list_type' => 'mnns']);
        $formListId = DB::table('list_fields')->insertGetId(['field_id' => $form->id, 'list_type' => 'drug_forms']);

        DB::table('many_to_many_list_fields')->insert([
            'list_field_id' => $mnnListId,
            'foreign_list_field_id' => $formListId
        ]);
        
        $doza = Field::create(['label' => 'Доза', 'name' => 'doza']);
        $opu = Field::create(['label' => 'ОПУ', 'name' => 'opu']);
        $thchp = Field::create(['label' => 'ТХЧП', 'name' => 'thchp']);
        
        $form1->fields()->attach([
            $mnn->id => ['required'=>true],
            $form->id => ['required'=>true],
            $doza->id => ['required'=>true],
            $opu->id => ['required'=>true],
            $thchp->id => ['required'=>true],
        ]);
            
        /*********************  BP1 *************************/
        
        $bp1 = Process::create(['name' => 'Новое Лекарственное Средство']);
        
        $form2 = Form::create(['name' => 'ПНК']);
        
        $klass_pd = Field::create(['label' => 'Класс Пд', 'name' => 'klass_pd']);
        $rx_otc = Field::create(['label' => 'Rx/OTC', 'name' => 'rx_otc']);
        $atx = Field::create(['label' => 'АТХ', 'name' => 'atx']);
        $fg = Field::create(['label' => 'ФГ', 'name' => 'fg']);
        $nozologiya = Field::create(['label' => 'Нозология', 'name' => 'nozologiya']);
        $vozrast_pol = Field::create(['label' => 'Возраст/Пол', 'name' => 'vozrast_pol']);
        $pnk_1 = Field::create(['label' => 'ПНК 1', 'name' => 'pnk_1']);
        $spv = Field::create(['label' => 'СПВ', 'name' => 'spv']);
        
        $form2->fields()->attach([
            $klass_pd->id => ['required'=>true],
            $rx_otc->id => ['required'=>true],
            $atx->id => ['required'=>true],
            $fg->id => ['required'=>true],
            $nozologiya->id => ['required'=>true],
            $vozrast_pol->id => ['required'=>true],
            $pnk_1->id => ['required'=>true],
            $spv->id => ['required'=>true],
        ]);
            
        $bp2 = App\Process::create(['name' => 'Заполнение данных НО']);
            
        $tether1 = App\Tether::create([
            'from_process_id' => $bp1->id,
            'to_process_id' => $bp2->id,
            'form_id' => $form2->id,
            'action_text' => 'Отправить дальше'
        ]);

        App\ProcessTask::create([
            'process_id' => $bp1->id,
            'title' => 'Заполните следуйщие поля',
            'planned_time' => '180120000',
            'deadline' => date('Y-m-d H:i:s'),
            'responsibility_id' => App\Responsibility::where('name','НО')->first()->id
        ]);
                
        // $perviy_god = Field::create(['label' => 'Первый год', 'name' => 'perviy_god']);
        // $summa_prodazh_za_perviy_god = Field::create(['label' => 'Сумма продаж за первый год', 'name' => 'summa_prodazh_za_perviy_god']);
        // $prodazhi_upakovok_za_perviy_god = Field::create(['label' => 'Продажы упаковок за первый год', 'name' => 'prodazhi_upakovok_za_perviy_god']);
        // $vtoroi_god = Field::create(['label' => 'Второй год', 'name' => 'vtoroi_god']);
        // $summa_prodazh_za_vtoroi_god = Field::create(['label' => 'Сумма продаж за второй год', 'name' => 'summa_prodazh_za_vtoroi_god']);
        // $prodazhi_upakovok_za_vtoroi_god = Field::create(['label' => 'Продажы упаковок за второй год', 'name' => 'prodazhi_upakovok_za_vtoroi_god']);
        // $kppr = Field::create(['label' => 'КППР', 'name' => 'kppr']);
        // $dolya_bg = Field::create(['label' => 'Доля БГ', 'name' => 'dolya_bg']);
        // $dolya_mst = Field::create(['label' => 'Доля Мст', 'name' => 'dolya_mst']);
        // $prirost_mst = Field::create(['label' => 'Прир Мст', 'name' => 'prirost_mst']);
        // $nkpn = Field::create(['label' => 'НКПН', 'name' => 'nkpn']);
        // $nkpf = Field::create(['label' => 'НКПФ', 'name' => 'nkpf']);
                
                
        // $form_afdot->fields()->attach([
            //     $perviy_god->id => ['required'=>true],
            //     $summa_prodazh_za_perviy_god->id => ['required'=>true],
            //     $prodazhi_upakovok_za_perviy_god->id => ['required'=>true],
            //     $vtoroi_god->id => ['required'=>true],
            //     $summa_prodazh_za_vtoroi_god->id => ['required'=>true],
            //     $prodazhi_upakovok_za_vtoroi_god->id => ['required'=>true],
            //     $kppr->id => ['required'=>true],
            //     $dolya_bg->id => ['required'=>true],
            //     $dolya_mst->id => ['required'=>true],
            //     $prirost_mst->id => ['required'=>true],
            //     $nkpn->id => ['required'=>true],
            //     $nkpf->id => ['required'=>true],
        // ]);
        
        
        App\Task::create([
            'title'=>'Включить комп',
            'description'=>'Не забудь про разетку',
            'responsible_id' => 2,
            'priority' => 0,
            'status_id' => 1,
            'spent_time' => '9999999999',
            'planned_time' => '9999999999',
            'deadline' => date('Y-m-d H:i:s'),
            'from_id' => 1,
            'from_type' => 'App\Process'
        ]);

        App\Task::create([
            'title' => 'Сделать презентатцию',
            'description' => 'Хорошую',
            'responsible_id' => 2,
            'priority' => 1,
            'status_id' => 1,
            'spent_time' => '9999999999',
            'planned_time' => '9999999999',
            'deadline' => date('Y-m-d H:i:s'),
            'from_id' => 1,
            'from_type' => 'App\User'
        ]);

        App\Task::create([
            'title' => 'Сделать презентатцию',
            'description' => 'Хорошую',
            'responsible_id' => 1,
            'priority' => 2,
            'status_id' => 1,
            'spent_time' => '9999999999',
            'planned_time' => '9999999999',
            'deadline' => date('Y-m-d H:i:s'),
            'from_id' => 2,
            'from_type' => 'App\User'
        ]);
    }
}
