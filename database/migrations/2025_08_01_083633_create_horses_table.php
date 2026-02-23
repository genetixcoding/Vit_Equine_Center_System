<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('horses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stud_id');
            $table->foreign('stud_id')->references('id')->on("studs")
            ->onDelete('cascade')->onUpdate('cascade');

            $table->string('name');
            $table->string('image');
            $table->string('shelter')->nullable();
            $table->tinyInteger('gender')->default('0');
            $table->tinyInteger('status')->default('0');
            $table->text('description');
            $table->tinyInteger('age')->nullable();
            $table->date('birth_date')->nullable();
            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horses');
    }
};
