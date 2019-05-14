<?php

use Illuminate\Database\Seeder;

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
    }
}
