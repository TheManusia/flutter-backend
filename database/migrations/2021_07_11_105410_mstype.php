<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mstype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mstype', function (Blueprint $table) {
            $table->integer('id')->unsigned()->unique();
            $table->integer('parentid')->unsigned();
            $table->primary(['id', 'parentid']);
            $table->foreign(['id', 'parentid'])->references(['id', 'parentid'])->on('mstype');
            $table->string('typecd');
            $table->string('typenm');
            $table->integer('typeseq');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mstype');
    }
}
