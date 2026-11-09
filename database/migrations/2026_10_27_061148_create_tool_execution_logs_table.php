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
        Schema::create('tool_execution_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('loggable');
            $table->string('tool_name');
            $table->json('arguments')->nullable();
            $table->json('result')->nullable();
            $table->string('status')->default('success');
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_execution_logs');
    }
};
