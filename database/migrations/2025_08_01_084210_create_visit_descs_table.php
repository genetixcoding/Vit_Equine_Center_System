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
        Schema::create('visit_descs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('visit_id');
            //$table->foreign('visit_id')->references('id')
            //->on('visits')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('horse_id');
            //$table->foreign('horse_id')->references('id')
            //->on('horses')->onDelete('cascade')->onUpdate('cascade');

            $table->string('case');
            $table->text('description')->nullable();
            $table->text('treatment')->nullable();
            $table->string('image');
            $table->integer('caseprice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_descs');
    }
};
