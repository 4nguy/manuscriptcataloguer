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
        Schema::create('manuscripts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('collection_id')->nullable()->constrained('collections');
            $table->foreignUuid('genre_id')->nullable()->constrained('genres');
            $table->string('catalogue_number')->nullable();
            $table->longText('general_description')->nullable();
            $table->string('format')->nullable();
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
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuscripts');
    }
};
