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
        Schema::create('feeding_beddings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_id');
            //$table->foreign('finance_id')->references('id')
            //->on('financials')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedBigInteger('supplier_id');
            //$table->foreign('supplier_id')->references('id')
            //->on('suppliers')->onDelete('cascade')->onUpdate('cascade');

            $table->string('item');
            $table->bigInteger('qty');
            $table->bigInteger('price');
            $table->bigInteger('paid');
            $table->decimal('decqty', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_beddings');
    }
};

