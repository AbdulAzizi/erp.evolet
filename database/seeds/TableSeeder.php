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
        $mnn = App\Field::create(['label' => 'МНН', 'name' => 'mnn']);
        $form = App\Field::create(['label' => 'Форма', 'name' => 'form']);
        $doza = App\Field::create(['label' => 'Доза', 'name' => 'doza']);
        $opu = App\Field::create(['label' => 'ОПУ', 'name' => 'opu']);
        $thchp = App\Field::create(['label' => 'ТХЧП', 'name' => 'thchp']);
        $perviy_god = App\Field::create(['label' => 'Первый год', 'name' => 'perviy_god']);
        $summa_prodazh_za_perviy_god = App\Field::create(['label' => 'Сумма продаж за первый год', 'name' => 'summa_prodazh_za_perviy_god']);
        $prodazhi_upakovok_za_perviy_god = App\Field::create(['label' => 'Продажы упаковок за первый год', 'name' => 'prodazhi_upakovok_za_perviy_god']);
        $vtoroi_god = App\Field::create(['label' => 'Второй год', 'name' => 'vtoroi_god']);
        $summa_prodazh_za_vtoroi_god = App\Field::create(['label' => 'Сумма продаж за второй год', 'name' => 'summa_prodazh_za_vtoroi_god']);
        $prodazhi_upakovok_za_vtoroi_god = App\Field::create(['label' => 'Продажы упаковок за второй год', 'name' => 'prodazhi_upakovok_za_vtoroi_god']);
        $kppr = App\Field::create(['label' => 'КППР', 'name' => 'kppr']);
        $dolya_bg = App\Field::create(['label' => 'Доля БГ', 'name' => 'dolya_bg']);
        $dolya_mst = App\Field::create(['label' => 'Доля Мст', 'name' => 'dolya_mst']);
        $prirost_mst = App\Field::create(['label' => 'Прир Мст', 'name' => 'prirost_mst']);
        $nkpn = App\Field::create(['label' => 'НКПН', 'name' => 'nkpn']);
        $nkpf = App\Field::create(['label' => 'НКПФ', 'name' => 'nkpf']);

        $forma1_go = App\Form::create(['name' => 'Форма ГО']);

        $forma1_go->fields()->attach([
            $mnn->id,
            $form->id,
            $doza->id,
            $opu->id,
            $thchp->id,
            $perviy_god->id,
            $summa_prodazh_za_perviy_god->id,
            $prodazhi_upakovok_za_perviy_god->id,
            $vtoroi_god->id,
            $summa_prodazh_za_vtoroi_god->id,
            $prodazhi_upakovok_za_vtoroi_god->id,
            $kppr->id,
            $dolya_bg->id,
            $dolya_mst->id,
            $prirost_mst->id,
            $nkpn->id,
            $nkpf->id,
        ]);

        App\Process::create([
            'name' => 'Подтверждение'
        ]);

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
