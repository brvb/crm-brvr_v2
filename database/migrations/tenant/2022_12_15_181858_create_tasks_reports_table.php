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
        Schema::table('tasks_reports', function (Blueprint $table) {
            $table->index(['customer_id']);
            $table->index(['location_id']);
            $table->index(['preview_date']);
            $table->index(['scheduled_date']);
            $table->index(['reportStatus']);
            $table->index(['tech_id']);
            $table->index(['end_date']);
        });
    }

};
