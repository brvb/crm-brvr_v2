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
        Schema::table('customer_services', function (Blueprint $table) {
            $table->bigInteger('location_id')->nullable()->default('NULL')->change();
            $table->bigInteger('service_id')->nullable()->default('NULL')->change();
            $table->bigInteger('customer_id')->nullable()->default('NULL')->change();
            $table->string('type')->nullable()->default('NULL')->change();
        });
    }

};
