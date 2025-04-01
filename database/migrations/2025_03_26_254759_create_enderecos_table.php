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
        Schema::create('endereco', function (Blueprint $table) {
            $table->bigIncrements('end_id')->primary();
            $table->string('end_tipo_logradouro');
            $table->string('end_logradouro');
            $table->integer('end_numero');
            $table->string('end_bairro');
            $table->unsignedBigInteger('cid_id');
            $table->foreign('cid_id')->references('cid_id')->on('cidade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endereco');
    }
};
