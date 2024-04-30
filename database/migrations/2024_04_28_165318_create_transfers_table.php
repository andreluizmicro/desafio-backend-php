<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payer_id')->unsigned();
            $table->foreign('payer_id')->references('id')->on('accounts');
            $table->bigInteger('payee_id')->unsigned();
            $table->foreign('payee_id')->references('id')->on('accounts');
            $table->float('value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
