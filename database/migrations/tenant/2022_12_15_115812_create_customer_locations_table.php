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
        Schema::table('customer_locations', function (Blueprint $table) {
            $table->index(['customer_id']);
            $table->index(['description']);
            $table->index(['district_id']);
            $table->index(['county_id']);
        });
    }

};
