<?php

use App\Form;
use App\Field;
use Illuminate\Database\Seeder;
use App\FieldType;

class FieldTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /********************************************************/

        $manyToManyListFieldTypeID = FieldType::where('name', 'many-to-many-list')->first()->id;
        $listFieldTypeID = FieldType::where('name', 'list')->first()->id;

        $mnn = Field::create(['label' => 'МНН', 'name' => 'mnn', 'type_id' => $manyToManyListFieldTypeID]);
        $form = Field::create(['label' => 'Ф', 'name' => 'form', 'type_id' => $manyToManyListFieldTypeID]);
        
        $mnnListId = DB::table('list_fields')->insertGetId(['field_id' => $mnn->id, 'list_type' => 'mnns_list']);
        $formListId = DB::table('list_fields')->insertGetId(['field_id' => $form->id, 'list_type' => 'drug_forms_list']);
        
        DB::table('many_to_many_list_fields')->insert([
            'list_field_id' => $mnnListId,
            'foreign_list_field_id' => $formListId
        ]);

        $doza = Field::create(['label' => 'Д', 'name' => 'doza']);
        $opu = Field::create(['label' => 'ОПУ', 'name' => 'opu']);
        $thchp = Field::create(['label' => 'ТХЧП', 'name' => 'thchp']);
        $class_pd = Field::create(['label' => 'Класс Пд', 'name' => 'class_pd']);
        $atx = Field::create(['label' => 'АТХ', 'name' => 'atx', 'type_id' => $listFieldTypeID]);
        $prognoz_s = Field::create(['label' => 'Прогноз Ц', 'name' => 'prognoz_s']);
        $ideya = Field::create(['label' => 'ТМ/Идея', 'name' => 'tm/ideya']);
        $ich_pd_op = Field::create(['label' => 'Ич Пд/Ор', 'name' => 'ich_pd_op']);
        $pnk_1 = Field::create(['label' => 'ПНК 1', 'name' => 'pnk_1', 'type_id' => $listFieldTypeID]);
        $nozologiya = Field::create(['label' => 'Нозология', 'name' => 'nozologiya']);
        $pnk_2_3 = Field::create(['label' => 'ПНК 2/3', 'name' => 'pnk_2_3', 'type_id' => $listFieldTypeID]);
        $pnk_4_5 = Field::create(['label' => 'ПНК 4/5', 'name' => 'pnk_4_5', 'type_id' => $listFieldTypeID]);
        $gp_stk_pk = Field::create(['label' => 'Гп/Стк ПК', 'name' => 'gp_stk_pk', 'type_id' => $listFieldTypeID]);
        $gp_byu = Field::create(['label' => 'Гп/Бю', 'name' => 'gp_byu', 'type_id' => $listFieldTypeID]);
        $p_mt = Field::create(['label' => 'П/Мт ', 'name' => 'p_mt', 'type_id' => $listFieldTypeID]);
        $prognoz_pzh = Field::create(['label' => 'Прогноз Пж', 'name' => 'prognoz_pzh']);
        $top_analogi = Field::create(['label' => 'Топ Аналоги', 'name' => 'top_analogi']);
        $pv_n = Field::create(['label' => 'ПВ/Н', 'name' => 'pv_n']);
        $vozrast_pol = Field::create(['label' => 'Возраст/Пол', 'name' => 'vozrast_pol', 'type_id' => $listFieldTypeID]);
        $komm = Field::create(['label' => 'Комм', 'name' => 'komm']);
        $nov_kriteriya = Field::create(['label' => 'Нов критерия ', 'name' => 'nov_kriteriya']);

        DB::table('list_fields')->insertGetId(['field_id' => $atx->id, 'list_type' => 'atx_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $pnk_1->id, 'list_type' => 'pnk1_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $pnk_2_3->id, 'list_type' => 'pnk2_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $pnk_4_5->id, 'list_type' => 'pnk4_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $gp_stk_pk->id, 'list_type' => 'gp_stk_pk_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $gp_byu->id, 'list_type' => 'gp_bu_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $p_mt->id, 'list_type' => 'pmt_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $vozrast_pol->id, 'list_type' => 'age_gender_list']);
        
        /********************************************************/

        $forma_pk_etap_1 = Form::where('name', 'Форма ПК Этап 1')->first();

        $forma_pk_etap_1->fields()->attach([
            $mnn->id => ['required' => true],
            $form->id => ['required' => true],
            $doza->id => ['required' => true],
            $opu->id => ['required' => true],
            $thchp->id => ['required' => true],
            $class_pd->id => ['required' => true],
            $atx->id => ['required' => true],
            $prognoz_s->id => ['required' => true],
            $ideya->id => ['required' => true],
            $ich_pd_op->id => ['required' => true],
            $pnk_1->id => ['required' => true],
            $nozologiya->id => ['required' => true],
            $pnk_2_3->id => ['required' => true],
            $pnk_4_5->id => ['required' => true],
            $gp_stk_pk->id => ['required' => true],
            $gp_byu->id => ['required' => true],
            $p_mt->id => ['required' => true],
            $prognoz_pzh->id => ['required' => true],
            $top_analogi->id => ['required' => true],
            $pv_n->id => ['required' => true],
            $vozrast_pol->id => ['required' => true],
            $komm->id => ['required' => true],
        ]);

        /********************************************************/
        
        $forma_pk_etap_1 = Form::where('name', 'Форма НО Этап 1')->first();

        $forma_pk_etap_1->fields()->attach([
            $mnn->id => ['required' => true],
            $form->id => ['required' => true],
            $doza->id => ['required' => true],
            $opu->id => ['required' => true],
            $thchp->id => ['required' => true],
            $class_pd->id => ['required' => true],
            $atx->id => ['required' => true],
            $pnk_1->id => ['required' => true],
            $nozologiya->id => ['required' => true],
            $pnk_2_3->id => ['required' => true],
            $pnk_4_5->id => ['required' => true],
            $top_analogi->id => ['required' => true],
            $pv_n->id => ['required' => true],
            $vozrast_pol->id => ['required' => true],
            $komm->id => ['required' => true],
        ]);

    }
}
