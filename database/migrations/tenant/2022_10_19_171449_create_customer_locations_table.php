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
        Schema::create('customer_locations', function (Blueprint $table) {
            $table->id();
            $table->string('description', 255);
            $table->bigInteger('customer_id');
            $table->boolean('main');
            $table->string('address', 255);
            $table->string('zipcode', 10);
            $table->string('contact', 20);
            $table->bigInteger('district_id');
            $table->bigInteger('county_id');
            $table->string('manager_name', 255);
            $table->string('manager_contact', 20);
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
        Schema::dropIfExists('customer_locations');
    }
};
