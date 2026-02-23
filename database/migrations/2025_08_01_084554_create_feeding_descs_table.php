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
        Schema::create('feeding_descs', function (Blueprint $table) {
            $table->id();
            //$table->foreign('feedbed_id')->references('id')
            //->on('feeding_beddings')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('feedbed_id');

            $table->unsignedBigInteger('horse_id');
            //$table->foreign('horse_id')->references('id')
            //->on('horses')->onDelete('cascade')->onUpdate('cascade');

            $table->decimal('qty', 4, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_descs');
    }
};
