<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up()
    {
        Schema::table('customer_services', function (Blueprint $table) {
            $table->integer('alert')->after('type')->nullable();
        });
    }

};
