<?php

use App\Field;
use App\FieldType;
use App\Form;
use Illuminate\Database\Seeder;

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
        $numberFieldTypeId = FieldType::where('name', 'number')->first()->id;

        $mnn = Field::create(['name' => 'mnn', 'label' => 'Международные непатентованные  наименования', 'abbreviation' => 'МНН', 'type_id' => $manyToManyListFieldTypeID]);
        $form = Field::create(['name' => 'form', 'label' => 'Лекарственная форма', 'abbreviation' => 'Ф', 'type_id' => $manyToManyListFieldTypeID]);

        $mnnListId = DB::table('list_fields')->insertGetId(['field_id' => $mnn->id, 'list_type' => 'mnns_list']);
        $formListId = DB::table('list_fields')->insertGetId(['field_id' => $form->id, 'list_type' => 'drug_forms_list']);

        DB::table('many_to_many_list_fields')->insert([
            'list_field_id' => $mnnListId,
            'foreign_list_field_id' => $formListId,
        ]);

        $product_status = Field::create(['name' => 'product_status', 'label' => 'Статус продукта', 'abbreviation' => 'Статус Пд']);
        $doza = Field::create(['name' => 'doza', 'label' => 'Доза', 'abbreviation' => 'Д']);
        $opu = Field::create(['name' => 'opu', 'label' => 'Обьем продукта в упаковке', 'abbreviation' => 'ОПУ']);
        $thchp = Field::create(['name' => 'thchp', 'label' => 'Технические химические часть продукта', 'abbreviation' => 'ТХЧП']);
        $class_pd = Field::create(['name' => 'class_pd', 'label' => 'Класс Продукта', 'abbreviation' => 'Класс Пд']);
        $atx = Field::create(['name' => 'atx', 'label' => 'Анатомо-Терапевтически-Химическая классификация', 'abbreviation' => 'АТХ', 'type_id' => $listFieldTypeID]);
        $prognoz_s = Field::create(['name' => 'prognoz_s', 'label' => 'Прогноз Цены', 'abbreviation' => 'Прогноз Ц']);
        $ideya = Field::create(['name' => 'ideya', 'label' => 'Торговая марка/Идея', 'abbreviation' => 'ТМ/Идея']);
        $ich_pd_op = Field::create(['name' => 'ich_pd_op', 'label' => 'Источник Продукта/Ориентир', 'abbreviation' => 'Ич Пд/Ор']);
        $pnk_1 = Field::create(['name' => 'pnk_1', 'label' => 'Покупатель нашего класса 1', 'abbreviation' => 'ПНК 1', 'type_id' => $listFieldTypeID]);
        $nozologiya = Field::create(['name' => 'nozologiya', 'label' => 'Нозология', 'abbreviation' => 'Нозология']);
        $pnk_2_3 = Field::create(['name' => 'pnk_2_3', 'label' => 'Покупатель нашего класса 2/3', 'abbreviation' => 'ПНК 2/3', 'type_id' => $listFieldTypeID]);
        $pnk_4_5 = Field::create(['name' => 'pnk_4_5', 'label' => 'Покупатель нашего класса 4/5', 'abbreviation' => 'ПНК 4/5', 'type_id' => $listFieldTypeID]);
        $gp_stk_pk = Field::create(['name' => 'gp_stk_pk', 'label' => 'Группа/Структура Промо компания', 'abbreviation' => 'Гп/Стк ПК', 'type_id' => $listFieldTypeID]);
        $gp_byu = Field::create(['name' => 'gp_byu', 'label' => 'Группа/Бюджет', 'abbreviation' => 'Гп/Бю', 'type_id' => $listFieldTypeID]);
        $p_mt = Field::create(['name' => 'p_mt', 'label' => 'Промоция(продвижение)/Материал', 'abbreviation' => 'П/Мт ', 'type_id' => $listFieldTypeID]);
        $prognoz_pzh = Field::create(['name' => 'prognoz_pzh', 'label' => 'Прогноз Продажа', 'abbreviation' => 'Прогноз Пж']);
        $top_analogi = Field::create(['name' => 'top_analogi', 'label' => 'Топ Аналоги', 'abbreviation' => 'Топ Аналоги']);
        $pv_n = Field::create(['name' => 'pv_n', 'label' => 'Препарат выбора по нозология', 'abbreviation' => 'ПВ/Н']);
        $vozrast_pol = Field::create(['name' => 'vozrast_pol', 'label' => 'Возраст/Пол', 'abbreviation' => 'Возраст/Пол', 'type_id' => $listFieldTypeID]);
        $komm = Field::create(['name' => 'komm', 'label' => 'Комментарии', 'abbreviation' => 'Комм']);
        $drug_classification = Field::create(['name' => 'drug_classification', 'label' => 'Направление', 'abbreviation' => 'Напр', 'type_id' => $listFieldTypeID]);

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
            $mnn->id => ['required' => true, 'multiple' => false],
            $form->id => ['required' => true, 'multiple' => false],
            $doza->id => ['required' => true, 'multiple' => false],
            $opu->id => ['required' => true, 'multiple' => false],
            $thchp->id => ['required' => false, 'multiple' => false],
            $class_pd->id => ['required' => true, 'multiple' => false],
            $atx->id => ['required' => true, 'multiple' => false],
            $prognoz_s->id => ['required' => false, 'multiple' => false],
            $ideya->id => ['required' => true, 'multiple' => false],
            $ich_pd_op->id => ['required' => true, 'multiple' => false],
            $pnk_1->id => ['required' => true, 'multiple' => true],
            $nozologiya->id => ['required' => true, 'multiple' => false],
            $pnk_2_3->id => ['required' => true, 'multiple' => false],
            $pnk_4_5->id => ['required' => true, 'multiple' => false],
            $gp_stk_pk->id => ['required' => false, 'multiple' => false],
            $gp_byu->id => ['required' => false, 'multiple' => false],
            $p_mt->id => ['required' => false, 'multiple' => false],
            $prognoz_pzh->id => ['required' => false, 'multiple' => false],
            $top_analogi->id => ['required' => false, 'multiple' => false],
            $pv_n->id => ['required' => false, 'multiple' => false],
            $vozrast_pol->id => ['required' => true, 'multiple' => false],
            $komm->id => ['required' => false, 'multiple' => false],
            $drug_classification->id => ['required' => false, 'multiple' => false],
        ]);

        /********************************************************/

        $forma_pk_etap_1 = Form::where('name', 'Форма ПК Этап 1')->first();

        $forma_pk_etap_1->fields()->attach([
            $mnn->id => ['required' => true, 'multiple' => false],
            $form->id => ['required' => true, 'multiple' => false],
            $doza->id => ['required' => true, 'multiple' => false],
            $opu->id => ['required' => true, 'multiple' => false],
            $thchp->id => ['required' => false, 'multiple' => false],
            $class_pd->id => ['required' => true, 'multiple' => false],
            $atx->id => ['required' => true, 'multiple' => false],
            $prognoz_s->id => ['required' => false, 'multiple' => false],
            $ideya->id => ['required' => true, 'multiple' => false],
            $ich_pd_op->id => ['required' => true, 'multiple' => false],
            $pnk_1->id => ['required' => true, 'multiple' => true],
            $nozologiya->id => ['required' => true, 'multiple' => false],
            $pnk_2_3->id => ['required' => true, 'multiple' => false],
            $pnk_4_5->id => ['required' => true, 'multiple' => false],
            $gp_stk_pk->id => ['required' => false, 'multiple' => false],
            $gp_byu->id => ['required' => false, 'multiple' => false],
            $p_mt->id => ['required' => false, 'multiple' => false],
            $prognoz_pzh->id => ['required' => false, 'multiple' => false],
            $top_analogi->id => ['required' => false, 'multiple' => false],
            $pv_n->id => ['required' => false, 'multiple' => false],
            $vozrast_pol->id => ['required' => true, 'multiple' => false],
            $komm->id => ['required' => false, 'multiple' => false],
            $drug_classification->id => ['required' => false, 'multiple' => false],
        ]);

        /********************************************************/

        $forma_no_etap_1 = Form::where('name', 'Форма НО Этап 1')->first();

        $forma_no_etap_1->fields()->attach([
            $mnn->id => ['required' => true, 'multiple' => false],
            $form->id => ['required' => true, 'multiple' => false],
            $doza->id => ['required' => true, 'multiple' => false],
            $opu->id => ['required' => true, 'multiple' => false],
            $thchp->id => ['required' => false, 'multiple' => false],
            $class_pd->id => ['required' => true, 'multiple' => false],
            $atx->id => ['required' => true, 'multiple' => false],
            $pnk_1->id => ['required' => true, 'multiple' => true],
            $nozologiya->id => ['required' => true, 'multiple' => false],
            $pnk_2_3->id => ['required' => true, 'multiple' => false],
            $pnk_4_5->id => ['required' => true, 'multiple' => false],
            $top_analogi->id => ['required' => true, 'multiple' => false],
            $pv_n->id => ['required' => false, 'multiple' => false],
            $vozrast_pol->id => ['required' => true, 'multiple' => false],
            $komm->id => ['required' => false, 'multiple' => false],
            $drug_classification->id => ['required' => false, 'multiple' => false],
        ]);

        /*********************************************************/

        $forma_no_etap_1 = Form::where('name', 'Форма КП_ПК Этап 2')->first();

        $currentYearSumUp = Field::create(['name' => 'currentYearSumUp', 'label' => 'Текущий год', 'abbreviation' => 'Текущий год', 'type_id' => $yearFieldTypeID]);
        $sum2018 = Field::create(['name' => 'sum2018', 'label' => 'Сумма продаж за текущий год', 'abbreviation' => 'Текущая сум']);
        $up2018 = Field::create(['name' => 'up2018', 'label' => 'Сумма проданных упоковок за текущий год', 'abbreviation' => 'Текущий Уп']);
        $previousYearSumUp = Field::create(['name' => 'previousYearSumUp', 'label' => 'Предыдущий год', 'abbreviation' => 'Предыдущий год', 'type_id' => $yearFieldTypeID]);
        $sum2017 = Field::create(['name' => 'sum2017', 'label' => 'Сумма продаж за предыдущий года', 'abbreviation' => 'Предыдущий сум']);
        $uo2017 = Field::create(['name' => 'uo2017', 'label' => 'Сумма проданных упоковок за предыдущий год', 'abbreviation' => 'Предыдуший Уп']);
        $prip = Field::create(['name' => 'prip', 'label' => 'Прирост', 'abbreviation' => 'Прир']);
        $kppr = Field::create(['name' => 'kppr', 'label' => 'Количество продуктов, которые продаются на рынке', 'abbreviation' => 'КППР']);
        $kprr = Field::create(['name' => 'kprr', 'label' => 'Количество зарегистрированных продуктов на рынке', 'abbreviation' => 'КПРР']);
        $bgG = Field::create(['name' => 'bgG', 'label' => 'Бренд генерик/Генерик', 'abbreviation' => 'БГ/Г']);
        $dolya_bg = Field::create(['name' => 'dolya_bg', 'label' => 'Доля Бренда генерика', 'abbreviation' => 'Доля БГ']);
        $dolya_mst = Field::create(['name' => 'dolya_mst', 'label' => 'Доля местных производителей', 'abbreviation' => 'Доля Мст']);
        $dolya_in = Field::create(['name' => 'dolya_in', 'label' => 'Доля индийских производителей', 'abbreviation' => 'Доля Ин']);
        $prir_mst = Field::create(['name' => 'prir_mst', 'label' => 'Прирост местных производителей', 'abbreviation' => 'Прир Мст']);
        $nkpf = Field::create(['name' => 'nkpf', 'label' => 'Наш класс продукт по факту на рыноке', 'abbreviation' => 'НКПФ']);
        $s_knk1dl = Field::create(['name' => 's_knk1dl', 'label' => 'Цена продукта Конкурента №1 в USD', 'abbreviation' => 'Ц_Кнк 1 ($)']);
        $s_knk1ns = Field::create(['name' => 's_knk1ns', 'label' => 'Цена продукта Конкурента №1 в национальной валюте', 'abbreviation' => 'Ц_Кнк 1 (Нц)']);
        $be_knk_1 = Field::create(['name' => 'be_knk_1', 'label' => 'Бренда конкурента №1', 'abbreviation' => 'Бр_Кнк 1 ']);
        $pzh_up_knk_1 = Field::create(['name' => 'pzh_up_knk_1', 'label' => 'Продажа продукта Конкурента №1 по упаковкам в год', 'abbreviation' => 'Пж Уп Кнк 1 ']);
        $ko_str_knk_1 = Field::create(['name' => 'ko_str_knk_1', 'label' => 'Компания - страна Производитель Конкурент №1', 'abbreviation' => 'Ко_Стр_Кнк 1']);
        $s_knk_2_dl = Field::create(['name' => 's_knk_2_dl', 'label' => 'Цена продукта Конкурента №2 в USD', 'abbreviation' => 'Ц_Кнк 2 ($)']);
        $s_knk_2_ns = Field::create(['name' => 's_knk_2_ns', 'label' => 'Цена продукта Конкурента №2 в национальной валюте', 'abbreviation' => 'Ц_Кнк 2 (Нц)']);
        $br_knk_2 = Field::create(['name' => 'br_knk_2', 'label' => 'Бренда конкурента №2', 'abbreviation' => 'Бр_Кнк 2 ']);
        $pzh_up_knk2 = Field::create(['name' => 'pzh_up_knk2', 'label' => 'Продажа продукта Конкурента №2 по упаковкам в год', 'abbreviation' => 'Пж Уп Кнк 2 ']);
        $ko_str_knk2 = Field::create(['name' => 'ko_str_knk2', 'label' => 'Компания - страна Производитель Конкурент №2', 'abbreviation' => 'Ко_Стр_Кнк 2']);
        $s_knk_3_dl = Field::create(['name' => 's_knk_3_dl', 'label' => 'Цена продукта Конкурента №3 в USD', 'abbreviation' => 'Ц_Кнк 3 ($)']);
        $s_knk_3_ns = Field::create(['name' => 's_knk_3_ns', 'label' => 'Цена продукта Конкурента №3 в национальной валюте', 'abbreviation' => 'Ц_Кнк 3 (Нц)']);
        $br_knk_3 = Field::create(['name' => 'br_knk_3', 'label' => 'Бренда конкурента №3', 'abbreviation' => 'Бр_Кнк 3 ']);
        $pzh_up_knk_3 = Field::create(['name' => 'pzh_up_knk_3', 'label' => 'Продажа продукта Конкурента №3 по упаковкам в год', 'abbreviation' => 'Пж Уп Кнк 3 ']);
        $ko_str_knk_3 = Field::create(['name' => 'ko_str_knk_3', 'label' => 'Компания - страна Производитель Конкурент №3', 'abbreviation' => 'Ко_Стр_Кнк 3']);
        $s_knk_4_dl = Field::create(['name' => 's_knk_4_dl', 'label' => 'Цена продукта Конкурента №4 в USD', 'abbreviation' => 'Ц_Кнк 4 ($)']);
        $s_knk_4_ns = Field::create(['name' => 's_knk_4_ns', 'label' => 'Цена продукта Конкурента №4 в национальной валюте', 'abbreviation' => 'Ц_Кнк 4 (Нц)']);
        $br_knk_4 = Field::create(['name' => 'br_knk_4', 'label' => 'Бренда конкурента №4', 'abbreviation' => 'Бр_Кнк 4 ']);
        $pzh_up_knk_4 = Field::create(['name' => 'pzh_up_knk_4', 'label' => 'Продажа продукта Конкурента №4 по упаковкам в год', 'abbreviation' => 'Пж Уп Кнк 4 ']);
        $ko_str_knk_4 = Field::create(['name' => 'ko_str_knk_4', 'label' => 'Компания - страна Производитель Конкурент №4', 'abbreviation' => 'Ко_Стр_Кнк 4']);
        $osn_kok = Field::create(['name' => 'osn_kok', 'label' => 'Основной конкурент', 'abbreviation' => 'Осн Кок']);

        $forma_no_etap_1->fields()->attach([
            $currentYearSumUp->id => ['required' => true, 'multiple' => false],
            $sum2018->id => ['required' => true, 'multiple' => false],
            $up2018->id => ['required' => true, 'multiple' => false],
            $previousYearSumUp->id => ['required' => true, 'multiple' => false],
            $sum2017->id => ['required' => true, 'multiple' => false],
            $uo2017->id => ['required' => true, 'multiple' => false],
            $prip->id => ['required' => true, 'multiple' => false],
            $kppr->id => ['required' => false, 'multiple' => false],
            $kprr->id => ['required' => false, 'multiple' => false],
            $bgG->id => ['required' => false, 'multiple' => false],
            $dolya_bg->id => ['required' => false, 'multiple' => false],
            $dolya_mst->id => ['required' => false, 'multiple' => false],
            $dolya_in->id => ['required' => false, 'multiple' => false],
            $prir_mst->id => ['required' => false, 'multiple' => false],
            $nkpf->id => ['required' => false, 'multiple' => false],
            $s_knk1dl->id => ['required' => true, 'multiple' => false],
            $s_knk1ns->id => ['required' => true, 'multiple' => false],
            $be_knk_1->id => ['required' => true, 'multiple' => false],
            $pzh_up_knk_1->id => ['required' => true, 'multiple' => false],
            $ko_str_knk_1->id => ['required' => true, 'multiple' => false],
            $s_knk_2_dl->id => ['required' => false, 'multiple' => false],
            $s_knk_2_ns->id => ['required' => false, 'multiple' => false],
            $br_knk_2->id => ['required' => false, 'multiple' => false],
            $pzh_up_knk2->id => ['required' => false, 'multiple' => false],
            $ko_str_knk2->id => ['required' => false, 'multiple' => false],
            $s_knk_3_dl->id => ['required' => false, 'multiple' => false],
            $s_knk_3_ns->id => ['required' => false, 'multiple' => false],
            $br_knk_3->id => ['required' => false, 'multiple' => false],
            $pzh_up_knk_3->id => ['required' => false, 'multiple' => false],
            $ko_str_knk_3->id => ['required' => false, 'multiple' => false],
            $s_knk_4_dl->id => ['required' => false, 'multiple' => false],
            $s_knk_4_ns->id => ['required' => false, 'multiple' => false],
            $br_knk_4->id => ['required' => false, 'multiple' => false],
            $pzh_up_knk_4->id => ['required' => false, 'multiple' => false],
            $ko_str_knk_4->id => ['required' => false, 'multiple' => false],
            $osn_kok->id => ['required' => true, 'multiple' => false],
        ]);

        /*******************************************************/

        $viborIstochnikov = Form::where('name', 'Выбор источников')->first();
        $strani_poiska = Field::create(['name' => 'strani_poiska', 'label' => 'Страны источников', 'abbreviation' => 'Стр ист', 'type_id' => $listFieldTypeID]);
        DB::table('list_fields')->insertGetId(['field_id' => $strani_poiska->id, 'list_type' => 'strani_poiska']);

        $viborIstochnikov->fields()->attach([
            $strani_poiska->id => ['required' => true, 'multiple' => false],
        ]);

        /*******************************************************/
        $formaOtkaza = Form::where('name', 'Форма коментарии при отказе')->first();

        $kommentariyaOtkaza = Field::create(['name' => 'kommentariyaOtkaza', 'label' => 'Коментарии отказа', 'abbreviation' => 'Кмт Откз']);

        $formaOtkaza->fields()->attach([
            $kommentariyaOtkaza->id => ['required' => true, 'multiple' => false],
        ]);
        /********************************************************/
        $formaNovogoProduktaZavoda = Form::where('name', 'Форма нового продукта завода')->first();

        $tmPr = Field::create(['name' => 'tmPr', 'label' => 'Торговая Марка Производителя', 'abbreviation' => 'ТМ Пр*']);
        $strDlyaVb = Field::create(['name' => 'strDlyaVb', 'label' => 'Страна для Выбора', 'abbreviation' => 'Стр для Вб']);
        // $dosye = Field::create(['name'=>'dosye', 'label'=>'Досье','abbreviation' => 'Досье']);
        // $sg = Field::create(['name'=>'sg', 'label'=>'Срок годности','abbreviation' => 'СГ']);
        // $MOK = Field::create(['name'=>'MOK', 'label'=>'МОК','abbreviation' => 'МОК']);
        // $prognoz1 = Field::create(['name'=>'prognoz1', 'label'=>'Прогноз (1 год)','abbreviation' => 'Прогноз (1 год)']);
        // $prognoz2 = Field::create(['name'=>'prognoz2', 'label'=>'Прогноз (2 год)','abbreviation' => 'Прогноз (2 год)']);
        // $prognoz3 = Field::create(['name'=>'prognoz3', 'label'=>'Прогноз (3 год)','abbreviation' => 'Прогноз (3 год)']);
        // $vyu = Field::create(['name'=>'vyu', 'label'=>'Валюта','abbreviation' => 'Вю']);
        // $sPr = Field::create(['name'=>'sPr', 'label'=>'Цена Производителя','abbreviation' => 'Ц Пр*']);
        // $dpPr = Field::create(['name'=>'dpPr', 'label'=>'Даун пеймент Производителя','abbreviation' => 'ДП Пр*']);
        // $klinika = Field::create(['name'=>'klinika', 'label'=>'Клиника','abbreviation' => 'Клиника']);
        // $sorr = Field::create(['name'=>'sorr', 'label'=>'Certificate of Pharmaceutical Product','abbreviation' => 'COPP']);
        // $ruVStrPr = Field::create(['name'=>'ruVStrPr', 'label'=>'Регистрационное удостоврение в Странах Производителя','abbreviation' => 'РУ в Стр Пр*']);
        // $sst = Field::create(['name'=>'sst', 'label'=>'ССТ','abbreviation' => 'ССТ']);
        // $torgS = Field::create(['name'=>'torgS', 'label'=>'Торг Цены','abbreviation' => 'Торг Ц']);
        // $soglS = Field::create(['name'=>'soglS', 'label'=>'Согласованная Цена','abbreviation' => 'Согл Ц']);
        // $dpKo = Field::create(['name'=>'dpKo', 'label'=>'Департамент Компании','abbreviation' => 'ДП Ко']);
        // $kk = Field::create(['name'=>'kk', 'label'=>'Контракт','abbreviation' => 'Кк']);

        $formaNovogoProduktaZavoda->fields()->attach([
            $mnn->id => ['required' => true, 'multiple' => false],
            $form->id => ['required' => true, 'multiple' => false],
            $doza->id => ['required' => true, 'multiple' => false],
            $opu->id => ['required' => true, 'multiple' => false],
            $thchp->id => ['required' => true, 'multiple' => false],
            $class_pd->id => ['required' => true, 'multiple' => false],
            $tmPr->id => ['required' => true, 'multiple' => false],
            $strDlyaVb->id => ['required' => true, 'multiple' => false],
            // $strDlyaVb->id => ['required' => true, 'multiple'=> false],
            // $dosye->id => ['required' => true, 'multiple'=> false],
            // $sg->id => ['required' => true, 'multiple'=> false],
            // $MOK->id => ['required' => true, 'multiple'=> false],
            // $prognoz1->id => ['required' => true, 'multiple'=> false],
            // $prognoz2->id => ['required' => true, 'multiple'=> false],
            // $prognoz3->id => ['required' => true, 'multiple'=> false],
            // $vyu->id => ['required' => true, 'multiple'=> false],
            // $sPr->id => ['required' => true, 'multiple'=> false],
            // $dpPr->id => ['required' => true, 'multiple'=> false],
            // $klinika->id => ['required' => true, 'multiple'=> false],
            // $sorr->id => ['required' => true, 'multiple'=> false],
            // $ruVStrPr->id => ['required' => true, 'multiple'=> false],
            // $sst->id => ['required' => true, 'multiple'=> false],
            // $torgS->id => ['required' => true, 'multiple'=> false],
            // $soglS->id => ['required' => true, 'multiple'=> false],
            // $dpKo->id => ['required' => true, 'multiple'=> false],
            // $kk->id => ['required' => true, 'multiple'=> false],
        ]);
        /********************************************************/
        $formaNovogoZavoda = Form::where('name', 'Новый завод')->first();

        $name = Field::create(['name' => 'name', 'label' => 'Название', 'abbreviation' => 'Название']);
        $about = Field::create(['name' => 'about', 'label' => 'Об', 'abbreviation' => 'Об']);
        $country = Field::create(['name' => 'country_id', 'label' => 'Country', 'abbreviation' => 'Country', 'type_id' => $listFieldTypeID]);
        $stability_zone = Field::create(['name' => 'stability_zone', 'label' => 'Stability Zone', 'abbreviation' => 'Stability Zone', 'type_id' => $listFieldTypeID]);
        $website = Field::create(['name' => 'website', 'label' => 'Website', 'abbreviation' => 'Website']);
        $product_class = Field::create(['name' => 'product_class', 'label' => 'Product Class', 'abbreviation' => 'Product Class', 'type_id' => $listFieldTypeID]);
        $logo = Field::create(['name' => 'logo', 'label' => 'Лого', 'abbreviation' => 'Лого']);
        
        DB::table('list_fields')->insertGetId(['field_id' => $country->id, 'list_type' => 'countries_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $stability_zone->id, 'list_type' => 'stability_zone_list']);
        DB::table('list_fields')->insertGetId(['field_id' => $product_class->id, 'list_type' => 'product_class_list']);

        $formaNovogoZavoda->fields()->attach([
            $name->id => ['required' => true, 'multiple' => false],
            $about->id => ['required' => true, 'multiple' => false],
            $country->id => ['required' => true, 'multiple' => false],
            $stability_zone->id => ['required' => true, 'multiple' => false],
            $website->id => ['required' => true, 'multiple' => false],
            $product_class->id => ['required' => true, 'multiple' => false],
            $logo->id => ['required' => true, 'multiple' => false],
        ]);

    }
}
