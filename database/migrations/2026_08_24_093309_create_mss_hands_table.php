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
        Schema::create('mss_hands', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('ms_id')->nullable()->constrained('manuscripts');
            $table->foreignUuid('ms_section_id')->nullable()->constrained('ms_sections');
            $table->foreignUuid('hand_id')->constrained('hands');
            $table->string('applicable_to');
            $table->string('from_page_no')->nullable();
            $table->char('from_page_size', 1)->nullable();
            $table->string('to_page_no')->nullable();
            $table->char('to_page_size', 1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mss_hands');
    }
};
