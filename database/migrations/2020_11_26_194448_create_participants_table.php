<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->uuid('contest_template_id');
            $table->uuid('referral_id')->nullable();
            $table->integer('referral_quantity')->default(0);
            $table->string('facebook_id')->nullable();

            $table->tinyInteger('registration_status');
            $table->string('registration_confirmation_code');
            $table->integer('registration_confirmations_received')->default(0);
            $table->integer('registration_confirmations_attempts')->default(0);
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('registration_confirmation_received_at')->nullable();
            $table->timestamp('registration_confirmation_last_attempt_at')->nullable();
            $table->timestamp('registration_confirmed_at')->nullable();

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
        Schema::dropIfExists('participants');
    }
}
