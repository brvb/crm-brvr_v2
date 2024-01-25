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
        Schema::table('services', function (Blueprint $table) {
            $table->integer('periodicity')->nullable(true)->default(NULL)->after('payment');
            $table->integer('alert')->nullable(true)->default(NULL)->after('periodicity');
        });
    }


};
