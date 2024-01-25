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
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 100);
            $table->string('vat', 15);
            $table->string('contact', 20);
            $table->string('email', 250);
            $table->string('address', 250);
            $table->string('logotipo', 250);
            $table->string('sender_email', 250);
            $table->string('sender_name', 100);
            $table->string('sender_cc_email', 250)->nullable();
            $table->string('sender_cc_name', 100)->nullable();
            $table->string('sender_bcc_email', 250)->nullable();
            $table->string('sender_bcc_name', 100)->nullable();
            $table->text('signature')->nullable();
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
        Schema::dropIfExists('config');
    }
};
