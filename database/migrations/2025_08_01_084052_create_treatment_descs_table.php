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
        Schema::create('treatment_descs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id');
            //$table->foreign('pharmacy_id')->references('id')
            //->on('pharmacies')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('treatment_id');
            //$table->foreign('treatment_id')->references('id')
            //->on('treatments')->onDelete('cascade')->onUpdate('cascade');


            $table->text('description');
            $table->integer('qty');
            $table->string('type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_descs');
    }
};
