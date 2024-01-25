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
        Schema::create('tasks_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->bigInteger('location_id');
            $table->bigInteger('task_id');
            $table->mediumText('additional_description');
            $table->string('applicant_name',200);
            $table->string('applicant_contact',20);
            $table->date('preview_date');
            $table->time('preview_hour');
            $table->date('scheduled_date');
            $table->time('scheduled_hour');
            $table->bigInteger('tech_id');
            $table->mediumText('report')->nullable();
            $table->mediumText('conclusion')->nullable();
            $table->mediumText('confidential_information')->nullable();
            $table->boolean('taskConcluded',1)->default(0);
            $table->string('infoConcluded',200)->nullable();
            $table->integer('reportStatus')->default(0);
            $table->date('end_date')->nullable();
            $table->time('end_hour')->nullable();
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
        Schema::dropIfExists('tasks_reports');
    }
};
