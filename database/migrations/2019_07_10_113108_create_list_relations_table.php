<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_relations', function (Blueprint $table) { //TODO Make countries, pcs and managers as LISTS
            $table->bigIncrements('id');
            $table->string('list_type');
            $table->string('foreign_list_type');
            $table->bigInteger('list_id');
            $table->bigInteger('foreign_list_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('list_relations');
    }
}
