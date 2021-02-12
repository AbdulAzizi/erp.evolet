<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListsMigration extends Migration
{

    private $lists = [
        'mnns_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'drug_forms_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'countries_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'stability_zone_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'product_class_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'pnk1_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'pnk2_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'pnk4_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'gp_bu_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'gp_stk_pk_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'atx_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'age_gender_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'pmt_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'strani_poiska' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
        'drug_classification_list' => [
            'bigIncrements' => 'id',
            'longText' => 'name',
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        foreach ($this->lists as $listName => $listSchema) {

            Schema::create($listName, function (Blueprint $table) use ($listSchema) {

                foreach ($listSchema as $fieldType => $fieldName) {
                    $table->$fieldType($fieldName);
                }

            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->lists as $listName => $listSchema) {
            Schema::dropIfExists($listName);
        }
    }

}
