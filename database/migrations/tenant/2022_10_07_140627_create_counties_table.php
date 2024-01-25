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
        Schema::create('counties', function (Blueprint $table) {
            $table->integer('id', 2)->autoIncrement(false);
            $table->integer('district_id', 2)->autoIncrement(false);
            $table->string('name', 40);
            $table->boolean('active')->default(0);
            $table->timestamps();
            $table->unique(array('id', 'district_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('counties');
    }
};
