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
            $table->index(['task_id']);
            $table->index(['task_service_id']);
            $table->index(['service_id']);
        });
    }

};
