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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('vat', 11);
            $table->string('contact', 20);
            $table->string('email', 255);
            $table->string('address', 255);
            $table->string('zipcode', 8);
            $table->string('district', 2);
            $table->string('county', 2);
            $table->bigInteger('zone')->nullable()->default(0)->autoIncrement(false);
            $table->bigInteger('account_manager')->nullable()->default(0)->autoIncrement(false);
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
        Schema::dropIfExists('customers');
    }
};
