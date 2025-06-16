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
        Schema::create('export_doc_details', function (Blueprint $table) {
            $table->id();
            $table->string('sod_nbr')->index();
            $table->string('sod_part')->nullable();
            $table->string('pt_desc')->nullable();
            $table->string('pt_net_wt')->nullable();
            $table->string('sod_qty_ord')->nullable();
            $table->string('pt_um')->nullable();
            $table->string('so_ship')->nullable();
            $table->string('net_weight')->nullable();
            $table->timestamps();

            $table->foreign('sod_nbr')->references('so_nbr')->on('export_docs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_doc_details');
    }
};
