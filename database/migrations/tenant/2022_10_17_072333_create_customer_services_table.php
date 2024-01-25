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
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->autoIncrement(false);
            $table->bigInteger('service_id')->autoIncrement(false);
            $table->bigInteger('location_id')->autoIncrement(false);
            $table->date('start_date');
            $table->date('end_date')->nullable(true);
            $table->string('type')->nullable(true);
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
        Schema::dropIfExists('customer_services');
    }
};
