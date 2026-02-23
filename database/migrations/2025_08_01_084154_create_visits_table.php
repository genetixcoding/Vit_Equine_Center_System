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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            //$table->foreign('user_id')->references('id')
            //->on('users')->onDelete('cascade')->onUpdate('cascade');



            $table->unsignedBigInteger('stud_id')->nullable();
            //$table->foreign('stud_id')->references('id')
            //->on('studs')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('visitprice')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('paid')->nullable();

            $table->integer('totalprice')->virtualAs('(visitprice - discount)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
