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
        Schema::create('contributors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name_in_orig_lang')->comment('Name in original language');
            $table->string('name_orig_lang_id')->nullable();
            $table->string('name_in_romanized_lang')->nullable();
            $table->string('name_romanized_lang_id')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributors');
    }
};
