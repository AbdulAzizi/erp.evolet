<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormTetherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_tether', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('tether_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_tether');
    }
}
