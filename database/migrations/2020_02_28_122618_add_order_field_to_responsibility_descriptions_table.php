<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderFieldToResponsibilityDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsibility_descriptions', function (Blueprint $table) {
            $table->integer('order')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responsibility_descriptions', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
