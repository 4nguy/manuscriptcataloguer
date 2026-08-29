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
        Schema::create('hands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreign('scribe_id')->references('id')->on('contributors');
            $table->longText('description')->nullable();
            $table->string('sample_img1_path')->nullable();
            $table->string('sample_img2_path')->nullable();
            $table->string('sample_img3_path')->nullable();
            $table->string('sample_img4_path')->nullable();
            $table->string('sample_img5_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hands');
    }
};
