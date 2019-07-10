<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $strana = App\Field::create(['label' => 'Страна', 'name' => 'strana']);
        $pc = App\Field::create(['label' => 'ПК', 'name' => 'pc']);

        /***************** First Form *********************/
        
        $form1 = App\Form::create(['name' => 'АФДОТ']);
        
        $mnn = App\Field::create(['label' => 'МНН', 'name' => 'mnn']);
        $form = App\Field::create(['label' => 'Форма', 'name' => 'form']);
        $doza = App\Field::create(['label' => 'Доза', 'name' => 'doza']);
        $opu = App\Field::create(['label' => 'ОПУ', 'name' => 'opu']);
        $thchp = App\Field::create(['label' => 'ТХЧП', 'name' => 'thchp']);
        
        $form1->fields()->attach([
            $mnn->id => ['required'=>true],
            $form->id => ['required'=>true],
            $doza->id => ['required'=>true],
            $opu->id => ['required'=>true],
            $thchp->id => ['required'=>true],
        ]);
            
        /*********************  BP1 *************************/
        
        $bp1 = App\Process::create(['name' => 'Новое Лекарственное Средство']);
        
        $form2 = App\Form::create(['name' => 'ПНК']);
        
        $klass_pd = App\Field::create(['label' => 'Класс Пд', 'name' => 'klass_pd']);
        $rx_otc = App\Field::create(['label' => 'Rx/OTC', 'name' => 'rx_otc']);
        $atx = App\Field::create(['label' => 'АТХ', 'name' => 'atx']);
        $fg = App\Field::create(['label' => 'ФГ', 'name' => 'fg']);
        $nozologiya = App\Field::create(['label' => 'Нозология', 'name' => 'nozologiya']);
        $vozrast_pol = App\Field::create(['label' => 'Возраст/Пол', 'name' => 'vozrast_pol']);
        $pnk_1 = App\Field::create(['label' => 'ПНК 1', 'name' => 'pnk_1']);
        $spv = App\Field::create(['label' => 'СПВ', 'name' => 'spv']);
        
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
                
        // $perviy_god = App\Field::create(['label' => 'Первый год', 'name' => 'perviy_god']);
        // $summa_prodazh_za_perviy_god = App\Field::create(['label' => 'Сумма продаж за первый год', 'name' => 'summa_prodazh_za_perviy_god']);
        // $prodazhi_upakovok_za_perviy_god = App\Field::create(['label' => 'Продажы упаковок за первый год', 'name' => 'prodazhi_upakovok_za_perviy_god']);
        // $vtoroi_god = App\Field::create(['label' => 'Второй год', 'name' => 'vtoroi_god']);
        // $summa_prodazh_za_vtoroi_god = App\Field::create(['label' => 'Сумма продаж за второй год', 'name' => 'summa_prodazh_za_vtoroi_god']);
        // $prodazhi_upakovok_za_vtoroi_god = App\Field::create(['label' => 'Продажы упаковок за второй год', 'name' => 'prodazhi_upakovok_za_vtoroi_god']);
        // $kppr = App\Field::create(['label' => 'КППР', 'name' => 'kppr']);
        // $dolya_bg = App\Field::create(['label' => 'Доля БГ', 'name' => 'dolya_bg']);
        // $dolya_mst = App\Field::create(['label' => 'Доля Мст', 'name' => 'dolya_mst']);
        // $prirost_mst = App\Field::create(['label' => 'Прир Мст', 'name' => 'prirost_mst']);
        // $nkpn = App\Field::create(['label' => 'НКПН', 'name' => 'nkpn']);
        // $nkpf = App\Field::create(['label' => 'НКПФ', 'name' => 'nkpf']);
                
                
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
