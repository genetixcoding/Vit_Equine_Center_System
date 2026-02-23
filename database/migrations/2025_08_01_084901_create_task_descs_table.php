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
        Schema::create('task_descs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            //$table->foreign('task_id')->references('id')
            //->on('tasks')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedBigInteger('horse_id')->nullable();
            //$table->foreign('horse_id')->references('id')
            //->on('horses')->onDelete('cascade')->onUpdate('cascade');

            $table->string('task');
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_descs');
    }
};
