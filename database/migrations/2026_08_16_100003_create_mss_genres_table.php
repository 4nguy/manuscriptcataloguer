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
        Schema::create('mss_genres', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ms_id')->constrained('manuscripts');
            $table->foreignUuid('genre_id')->constrained('genres');
            $table->boolean('is_major')->nullable();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mss_genres');
    }
};
