<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->unsignedInteger('status_id')->default( 1 );
            $table->unsignedInteger('priority')->default(1);
            $table->string('spent_time')->nullable();
            $table->string('planned_time')->nullable();
            $table->timestamp('deadline')->nullable();;
            $table->unsignedInteger('responsible_id');
            $table->unsignedInteger('from_id');
            $table->string('from_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
