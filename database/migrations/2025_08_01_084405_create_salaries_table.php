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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_id')->nullable();
            //$table->foreign('finance_id')->references('id')
            //->on('financials')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('user_id');
            //$table->foreign('user_id')->references('id')
            //->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('salaryamount');
            $table->integer('decsalaryamount');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
