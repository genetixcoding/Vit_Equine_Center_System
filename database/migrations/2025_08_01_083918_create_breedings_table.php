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
        Schema::create('breedings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('femalehorse')->nullable();
            //$table->foreign('femalehorse')->references('id')
            //->on('horses')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedInteger('malehorse')->nullable();
            //$table->foreign('malehorse')->references('id')
            //->on('horses')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('user_id')->nullable();
            //$table->foreign('user_id')->references('id')
            //->on('users')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedInteger('finance_id')->nullable();
            //$table->foreign('finance_id')->references('id')
            //->on('financials')->onDelete('cascade')->onUpdate('cascade');


            $table->string('horsename')->nullable();
            $table->string('stud')->nullable();
            $table->string('description')->nullable();
            $table->integer('cost')->nullable();
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
        Schema::dropIfExists('breedings');
    }
};
