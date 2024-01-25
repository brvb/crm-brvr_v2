<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_id',20)->autoIncrement(false);
            $table->bigInteger('task_id',20)->autoIncrement(false);
            $table->date('date_begin');
            $table->time('hour_begin');
            $table->date('date_end');
            $table->time('hour_end');
            $table->float('total_hours');
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
        Schema::dropIfExists('tasks_times');
    }
};
