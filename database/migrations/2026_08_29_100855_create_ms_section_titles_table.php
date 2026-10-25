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
        Schema::create('ms_section_titles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ms_section_id')->constrained('ms_sections');
            $table->foreignUuid('language_id')->constrained('languages');
            $table->longText('title');
            $table->boolean('is_translation')->default(false);
            $table->foreignId('created_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->foreignId('updated_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->foreignId('deleted_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_sections_titles');
    }
};
