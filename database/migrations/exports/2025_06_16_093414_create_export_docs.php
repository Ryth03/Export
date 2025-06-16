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
          Schema::create('export_docs', function (Blueprint $table) {
            $table->id();
            $table->string('so_nbr')->index();
            $table->string('so_po')->nullable();
            $table->string('ad_sort')->nullable();
            $table->string('ad_name')->nullable();
            $table->string('ad_line1')->nullable();
            $table->string('ad_line2')->nullable();
            $table->string('ad_line3')->nullable();
            $table->string('ad_city')->nullable();
            $table->string('ad_country')->nullable();
            $table->string('ad_phone')->nullable();
            $table->string('ad_phone2')->nullable();
            $table->string('ad_fax')->nullable();
            $table->string('ad_fax2')->nullable();
            $table->string('ship_to_name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('export_docs');
    }
};
