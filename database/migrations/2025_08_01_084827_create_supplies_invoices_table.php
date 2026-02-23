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
        Schema::create('supplies_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('internal_invoice_id')->nullable();
            //$table->foreign('internal_invoice_id')->references('id')
            //->on('invoices')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('external_invoice_id')->nullable();
            //$table->foreign('external_invoice_id')->references('id')
            //->on('externalinvoices')->onDelete('cascade')->onUpdate('cascade');


            $table->text('item');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('totalprice')->virtualAs('qty * price');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies_invoices');
    }
};
