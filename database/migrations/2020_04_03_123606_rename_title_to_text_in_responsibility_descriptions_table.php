<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTitleToTextInResponsibilityDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsibility_descriptions', function (Blueprint $table) {
            $table->renameColumn('title', 'text');
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
            $table->renameColumn('text', 'title');
        });
    }
}
