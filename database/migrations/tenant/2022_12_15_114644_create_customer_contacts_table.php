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
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->bigInteger('location_id')->nullable();
            $table->string('name',255);
            $table->string('job_description',255)->nullable();
            $table->string('mobile_phone',20)->nullable();
            $table->string('landline',20)->nullable();
            $table->string('email',255);
            $table->boolean('all_mails',1)->default(0);
            $table->timestamps();
            $table->index(['customer_id']);
            $table->index(['location_id']);
        });
        Schema::dropIfExists('contacts_customers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_contacts');
    }
};
