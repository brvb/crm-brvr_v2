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
        Schema::table('task_services', function (Blueprint $table) {
            $table->bigInteger('task_id')->after('id');
            $table->bigInteger('task_service_id')->after('task_id');
        });
    }

};
