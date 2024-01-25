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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->bigInteger('location_id');
            $table->mediumText('additional_description');
            $table->string('applicant_name', 200);
            $table->string('applicant_contact', 20);
            $table->date('preview_date')->nullable(now());
            $table->time('preview_hour')->nullable(true)->default(NULL);
            $table->date('scheduled_date')->nullable(true)->default(NULL);
            $table->time('scheduled_hour')->nullable(true)->default(NULL);
            $table->bigInteger('tech_id');
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
};
