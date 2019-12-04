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
        $yearFieldTypeID = FieldType::where('name', 'year')->first()->id;

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
        $drug_classification = Field::create(['label' => 'Классификация', 'name' => 'classification', 'type_id' => $listFieldTypeID]);
        $nov_kriteriya = Field::create(['label' => 'Нов критерия ', 'name' => 'nov_kriteriya']);

        DB::table('list_fields')->insertGetId(['field_id' => $atx->id, 'list_type' => 'atx_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $pnk_1->id, 'list_type' => 'pnk1_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $pnk_2_3->id, 'list_type' => 'pnk2_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $pnk_4_5->id, 'list_type' => 'pnk4_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $gp_stk_pk->id, 'list_type' => 'gp_stk_pk_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $gp_byu->id, 'list_type' => 'gp_bu_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $p_mt->id, 'list_type' => 'pmt_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $vozrast_pol->id, 'list_type' => 'age_gender_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $drug_classification->id, 'list_type' => 'drug_classification_list']);
        
        /********************************************************/
        
        $forma_kp_pk_etap_1 = Form::where('name', 'Форма КП_ПК Этап 1')->first();

        $forma_kp_pk_etap_1->fields()->attach([
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
            $drug_classification->id => ['required' => true]
        ]);

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
            $drug_classification->id => ['required' => true]
        ]);

        /********************************************************/
        
        $forma_no_etap_1 = Form::where('name', 'Форма НО Этап 1')->first();

        $forma_no_etap_1->fields()->attach([
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
            $drug_classification->id => ['required' => true]
        ]);

        /*********************************************************/

        $forma_no_etap_1 = Form::where('name', 'Форма КП_ПК Этап 2')->first();

        $currentYearSumUp = Field::create(['label' => 'Текущий год для сум и уп', 'name' => 'currentYearSumUp', 'type_id' => $yearFieldTypeID]);
        $sum2018 = Field::create(['label' => 'Текущий сум', 'name' => 'sum2018']);
        $up2018 = Field::create(['label' => 'Текущий Уп', 'name' => 'up2018']);
        $previousYearSumUp = Field::create(['label' => 'Предыдущий год для сум и уп', 'name' => 'previousYearSumUp', 'type_id' => $yearFieldTypeID]);
        $sum2017 = Field::create(['label' => 'Предыдущий сум', 'name' => 'sum2017']);
        $uo2017 = Field::create(['label' => 'Предыдуший Уп', 'name' => 'uo2017']);
        $prip = Field::create(['label' => 'Прир', 'name' => 'prip']);
        $kppr = Field::create(['label' => 'КППР', 'name' => 'kppr']);
        $kprr = Field::create(['label' => 'КПРР', 'name' => 'kprr']);
        $bgG = Field::create(['label' => 'БГ/Г', 'name' => 'bgG']);
        $dolya_bg = Field::create(['label' => 'Доля БГ', 'name' => 'dolya_bg']);
        $dolya_mst = Field::create(['label' => 'Доля Мст', 'name' => 'dolya_mst']);
        $dolya_in = Field::create(['label' => 'Доля Ин', 'name' => 'dolya_in']);
        $prir_mst = Field::create(['label' => 'Прир Мст', 'name' => 'prir_mst']);
        $nkpf = Field::create(['label' => 'НКПФ', 'name' => 'nkpf']);
        $s_knk1dl = Field::create(['label' => 'Ц_Кнк 1 ($)', 'name' => 's_knk1dl']);
        $s_knk1ns = Field::create(['label' => 'Ц_Кнк 1 (Нц)', 'name' => 's_knk1ns']);
        $be_knk_1 = Field::create(['label' => 'Бр_Кнк 1 ', 'name' => 'be_knk_1']);
        $pzh_up_knk_1 = Field::create(['label' => 'Пж Уп Кнк 1 ', 'name' => 'pzh_up_knk_1']);
        $ko_str_knk_1 = Field::create(['label' => 'Ко_Стр_Кнк 1', 'name' => 'ko_str_knk_1']);
        $s_knk_2_dl = Field::create(['label' => 'Ц_Кнк 2 ($)', 'name' => 's_knk_2_dl']);
        $s_knk_2_ns = Field::create(['label' => 'Ц_Кнк 2 (Нц)', 'name' => 's_knk_2_ns']);
        $br_knk_2 = Field::create(['label' => 'Бр_Кнк 2 ', 'name' => 'br_knk_2']);
        $pzh_up_knk2 = Field::create(['label' => 'Пж Уп Кнк 2 ', 'name' => 'pzh_up_knk2']);
        $ko_str_knk2 = Field::create(['label' => 'Ко_Стр_Кнк 2', 'name' => 'ko_str_knk2']);
        $s_knk_3_dl = Field::create(['label' => 'Ц_Кнк 3 ($)', 'name' => 's_knk_3_dl']);
        $s_knk_3_ns = Field::create(['label' => 'Ц_Кнк 3 (Нц)', 'name' => 's_knk_3_ns']);
        $br_knk_3 = Field::create(['label' => 'Бр_Кнк 3 ', 'name' => 'br_knk_3']);
        $pzh_up_knk_3 = Field::create(['label' => 'Пж Уп Кнк 3 ', 'name' => 'pzh_up_knk_3']);
        $ko_str_knk_3 = Field::create(['label' => 'Ко_Стр_Кнк 3', 'name' => 'ko_str_knk_3']);
        $s_knk_4_dl = Field::create(['label' => 'Ц_Кнк 4 ($)', 'name' => 's_knk_4_dl']);
        $s_knk_4_ns = Field::create(['label' => 'Ц_Кнк 4 (Нц)', 'name' => 's_knk_4_ns']);
        $br_knk_4 = Field::create(['label' => 'Бр_Кнк 4 ', 'name' => 'br_knk_4']);
        $pzh_up_knk_4 = Field::create(['label' => 'Пж Уп Кнк 4 ', 'name' => 'pzh_up_knk_4']);
        $ko_str_knk_4 = Field::create(['label' => 'Ко_Стр_Кнк 4', 'name' => 'ko_str_knk_4']);
        $osn_kok = Field::create(['label' => 'Осн Кок', 'name' => 'osn_kok']);
        $vivod = Field::create(['label' => 'Вывод', 'name' => 'vivod']);

        $forma_no_etap_1->fields()->attach([
            $currentYearSumUp->id => ['required' => true],
            $sum2018->id => ['required' => true],
            $up2018->id => ['required' => true],
            $previousYearSumUp->id => ['required' => true],
            $sum2017->id => ['required' => true],
            $uo2017->id => ['required' => true],
            $prip->id => ['required' => true],
            $kppr->id => ['required' => true],
            $kprr->id => ['required' => true],
            $bgG->id => ['required' => true],
            $dolya_bg->id => ['required' => true],
            $dolya_mst->id => ['required' => true],
            $dolya_in->id => ['required' => true],
            $prir_mst->id => ['required' => true],
            $nkpf->id => ['required' => true],
            $s_knk1dl->id => ['required' => true],
            $s_knk1ns->id => ['required' => true],
            $be_knk_1->id => ['required' => true],
            $pzh_up_knk_1->id => ['required' => true],
            $ko_str_knk_1->id => ['required' => true],
            $s_knk_2_dl->id => ['required' => true],
            $s_knk_2_ns->id => ['required' => true],
            $br_knk_2->id => ['required' => true],
            $pzh_up_knk2->id => ['required' => true],
            $ko_str_knk2->id => ['required' => true],
            $s_knk_3_dl->id => ['required' => true],
            $s_knk_3_ns->id => ['required' => true],
            $br_knk_3->id => ['required' => true],
            $pzh_up_knk_3->id => ['required' => true],
            $ko_str_knk_3->id => ['required' => true],
            $s_knk_4_dl->id => ['required' => true],
            $s_knk_4_ns->id => ['required' => true],
            $br_knk_4->id => ['required' => true],
            $pzh_up_knk_4->id => ['required' => true],
            $ko_str_knk_4->id => ['required' => true],
            $osn_kok->id => ['required' => true],
            $vivod->id => ['required' => true],
        ]);


    }
}
