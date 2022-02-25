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
        Schema::create('transfer', function (Blueprint $table) {
            $table->id();
            $table->uuid('track_id');

            $table->unsignedBigInteger('transfer_from_user_id');
            $table->foreign('transfer_from_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('transfer_to_user_id');
            $table->foreign('transfer_to_user_id')->references('id')->on('users');

            $table->integer('amount');
            $table->enum('status',['DONE','FAILED'])->default('FAILED');

            $table->json('result')->nullable();

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
        Schema::dropIfExists('payment');
    }
};
