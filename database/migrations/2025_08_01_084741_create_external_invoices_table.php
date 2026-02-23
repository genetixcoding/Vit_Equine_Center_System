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
        Schema::create('external_invoices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('stud_id');
            $table->foreign('stud_id')->references('id')->on("studs")
            ->onDelete('cascade')->onUpdate('cascade');


            $table->bigInteger('paid');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_invoices');
    }
};
