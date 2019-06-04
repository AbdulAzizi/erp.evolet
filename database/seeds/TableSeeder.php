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
        $mnn = App\Field::create(['name' => 'МНН', 'slug' => 'mnn']);
        $form = App\Field::create(['name' => 'Форма', 'slug' => 'form']);
        $doza = App\Field::create(['name' => 'Доза', 'slug' => 'doza']);
        $opu = App\Field::create(['name' => 'ОПУ', 'slug' => 'opu']);
        $thchp = App\Field::create(['name' => 'ТХЧП', 'slug' => 'thchp']);
        $perviy_god = App\Field::create(['name' => 'Первый год', 'slug' => 'perviy_god']);
        $summa_prodazh_za_perviy_god = App\Field::create(['name' => 'Сумма продаж за первый год', 'slug' => 'summa_prodazh_za_perviy_god']);
        $prodazhi_upakovok_za_perviy_god = App\Field::create(['name' => 'Продажы упаковок за первый год', 'slug' => 'prodazhi_upakovok_za_perviy_god']);
        $vtoroi_god = App\Field::create(['name' => 'Второй год', 'slug' => 'vtoroi_god']);
        $summa_prodazh_za_vtoroi_god = App\Field::create(['name' => 'Сумма продаж за второй год', 'slug' => 'summa_prodazh_za_vtoroi_god']);
        $prodazhi_upakovok_za_vtoroi_god = App\Field::create(['name' => 'Продажы упаковок за второй год', 'slug' => 'prodazhi_upakovok_za_vtoroi_god']);
        $kppr = App\Field::create(['name' => 'КППР', 'slug' => 'kppr']);
        $dolya_bg = App\Field::create(['name' => 'Доля БГ', 'slug' => 'dolya_bg']);
        $dolya_mst = App\Field::create(['name' => 'Доля Мст', 'slug' => 'dolya_mst']);
        $prirost_mst = App\Field::create(['name' => 'Прир Мст', 'slug' => 'prirost_mst']);
        $nkpn = App\Field::create(['name' => 'НКПН', 'slug' => 'nkpn']);
        $nkpf = App\Field::create(['name' => 'НКПФ', 'slug' => 'nkpf']);

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
            'status' => false,
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
            'status' => true,
            'spent_time' => '9999999999',
            'planned_time' => '9999999999',
            'deadline' => date('Y-m-d H:i:s'),
            'from_id' => 1,
            'from_type' => 'App\Employee'
        ]);

        App\Task::create([
            'title' => 'Сделать презентатцию',
            'description' => 'Хорошую',
            'responsible_id' => 1,
            'priority' => 2,
            'status' => false,
            'spent_time' => '9999999999',
            'planned_time' => '9999999999',
            'deadline' => date('Y-m-d H:i:s'),
            'from_id' => 2,
            'from_type' => 'App\Employee'
        ]);
    }
}
