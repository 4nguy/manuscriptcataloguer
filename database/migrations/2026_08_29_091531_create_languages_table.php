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
        Schema::create('languages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('text_direction')->default('ltr');
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
        Schema::dropIfExists('languages');
    }
};
