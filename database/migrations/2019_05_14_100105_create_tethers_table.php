<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTethersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tethers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('from_process_id');
            $table->unsignedInteger('to_process_id');
            $table->string('action_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tethers');
    }
}
