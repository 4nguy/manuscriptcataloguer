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
        Schema::create('ms_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ms_id')->constrained('manuscripts');
            $table->string('from_page_no')->nullable();
            $table->char('from_page_size', 1)->nullable();
            $table->string('to_page_no')->nullable();
            $table->char('to_page_size', 1)->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('ms_sections');
    }
};
