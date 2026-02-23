<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('embryos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('breeding_id');
            //$table->foreign('breeding_id')->references('id')
            //->on('breedings')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('finance_id')->nullable();
            //$table->foreign('finance_id')->references('id')
            //->on('financials')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('user_id')->nullable();
            //$table->foreign('user_id')->references('id')
            //->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('localhorsename');
            $table->text('description')->nullable();
            $table->integer('cost');
            $table->integer('paid')->nullable();
            $table->tinyInteger('status')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embryos');
    }
};
