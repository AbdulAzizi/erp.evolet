de<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('process_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('planned_time');
            $table->unsignedInteger('position_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_tasks');
    }
}
