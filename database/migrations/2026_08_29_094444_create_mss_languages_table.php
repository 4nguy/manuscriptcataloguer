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
        Schema::create('mss_languages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ms_id')->nullable()->constrained('manuscripts');
            $table->foreignUuid('ms_section_id')->nullable()->constrained('ms_sections');
            $table->foreignUuid('language_id')->constrained('languages');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mss_languages');
    }
};
