<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('fee_cancels', function (Blueprint $table) {
            $table->id('id_fee_cancels'); 
            $table->unsignedBigInteger('user_id');
            $table->integer('fee');
            $table->timestamps();

            $table->foreign('user_id')->references('iduser')->on('users')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_cancels');
    }
};
