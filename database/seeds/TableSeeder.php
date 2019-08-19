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
        /*********************  BP1 *************************/
        $listFieldTypeID = FieldType::where('name', 'list')->first()->id;
        $bp1 = Process::create(['name' => 'Новое Лекарственное Средство']);
        
        $form2 = Form::where('name', 'Форма КП_ПК Этап 2')->first();
     
        $listFieldTypeID = FieldType::where('name', 'list')->first()->id;
        
        $klass_pd = Field::create(['label' => 'Класс Пд', 'name' => 'klass_pd', 'type_id' => $listFieldTypeID]);
        $atx = Field::create(['label' => 'АТХ', 'name' => 'atx', 'type_id' => $listFieldTypeID]);
        $fg = Field::create(['label' => 'ФГ', 'name' => 'fg']);
        $nozologiya = Field::create(['label' => 'Нозология', 'name' => 'nozologiya']);
        $vozrast_pol = Field::create(['label' => 'Возраст/Пол', 'name' => 'vozrast_pol']);
        $pnk_1 = Field::create(['label' => 'ПНК 1', 'name' => 'pnk_1', 'type_id' => $listFieldTypeID]);
        $spv = Field::create(['label' => 'СПВ', 'name' => 'spv']);
        
        DB::table('list_fields')->insert(['field_id' => $klass_pd->id, 'list_type' => 'gp_bu_list']);
        DB::table('list_fields')->insert(['field_id' => $atx->id, 'list_type' => 'atx_list']);
        DB::table('list_fields')->insert(['field_id' => $pnk_1->id, 'list_type' => 'pnk1_list']);

        $form2->fields()->attach([
            $klass_pd->id => ['required'=>true],
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
            'responsibility_id' => App\Responsibility::where('name','Куратор Портфель ПК')->first()->id
        ]);
                
        $perviy_god = Field::create(['label' => 'Первый год', 'name' => 'perviy_god']);
        $summa_prodazh_za_perviy_god = Field::create(['label' => 'Сумма продаж за первый год', 'name' => 'summa_prodazh_za_perviy_god']);
        $prodazhi_upakovok_za_perviy_god = Field::create(['label' => 'Продажы упаковок за первый год', 'name' => 'prodazhi_upakovok_za_perviy_god']);
        $vtoroi_god = Field::create(['label' => 'Второй год', 'name' => 'vtoroi_god']);
        $summa_prodazh_za_vtoroi_god = Field::create(['label' => 'Сумма продаж за второй год', 'name' => 'summa_prodazh_za_vtoroi_god']);
        $prodazhi_upakovok_za_vtoroi_god = Field::create(['label' => 'Продажы упаковок за второй год', 'name' => 'prodazhi_upakovok_za_vtoroi_god']);
        $kppr = Field::create(['label' => 'КППР', 'name' => 'kppr']);
        $dolya_bg = Field::create(['label' => 'Доля БГ', 'name' => 'dolya_bg']);
        $dolya_mst = Field::create(['label' => 'Доля Мст', 'name' => 'dolya_mst']);
        $prirost_mst = Field::create(['label' => 'Прир Мст', 'name' => 'prirost_mst']);
        $nkpn = Field::create(['label' => 'НКПН', 'name' => 'nkpn']);
        $nkpf = Field::create(['label' => 'НКПФ', 'name' => 'nkpf']);
                
                
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
    }
}
