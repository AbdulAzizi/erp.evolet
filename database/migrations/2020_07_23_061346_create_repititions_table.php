<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepititionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repititions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('range');
            $table->string('range_period');
            $table->unsignedBigInteger('action');
            $table->string('end_date')->nullable();
            $table->string('weekdays');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repititions');
    }
}
