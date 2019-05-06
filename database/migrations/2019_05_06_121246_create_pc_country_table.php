<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePcCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pc_country', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pc_id');
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('analitik_id');
            $table->unsignedInteger('no_id');
            $table->unsignedInteger('pc_representive_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pc_country');
    }
}
