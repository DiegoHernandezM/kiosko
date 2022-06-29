<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteColumnsToTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('task_time');
            $table->dropColumn('project_id');
            $table->dropColumn('percent');
            $table->dropColumn('observation');
            $table->dropColumn('description');
            $table->date('task_day')->nullable();
            $table->string('hours');
            $table->longText('task_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dateTime('task_time')->nullable();
            $table->unsignedInteger('project_id')->default(1);
            $table->string('percent')->nullable();
            $table->string('observation')->nullable();
            $table->string('description')->nullable();
            $table->dropColumn('task_day');
            $table->dropColumn('hours');
            $table->dropColumn('descripton')->nullable();
        });
    }
}
