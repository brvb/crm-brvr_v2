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
        Schema::table('customers', function (Blueprint $table) {
            $table->index(['name']);
            $table->index(['vat']);
            $table->index(['account_manager']);
            $table->index(['district']);
            $table->index(['county']);
        });
    }

};
