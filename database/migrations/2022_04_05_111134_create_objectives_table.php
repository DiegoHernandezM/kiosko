<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objectives', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('associate_id');
            $table->string('name');
            $table->longText('description');
            $table->integer('weighing');
            $table->json('evidence')->nullable();
            $table->boolean('approved');
            $table->string('observation')->nullable();
            $table->integer('real_weighing')->nullable();
            $table->year('year');
            $table->string('quarter');
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
        Schema::dropIfExists('objectives');
    }
}
