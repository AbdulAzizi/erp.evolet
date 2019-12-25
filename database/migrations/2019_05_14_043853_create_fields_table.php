<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $stringFieldTypeID = 1;

        Schema::create('fields', function (Blueprint $table) use($stringFieldTypeID) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('label');
            $table->string('abbreviation');
            $table->integer('type_id')->default($stringFieldTypeID);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
