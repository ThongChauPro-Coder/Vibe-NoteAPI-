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
        Schema::create('notes', function (Blueprint $table) {
            $table->id('noteId');
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            $table->text('noteTitle');
            $table->text('noteContent');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('lastModified')->nullable();
            $table->boolean('isPinned')->default(false);
            $table->timestamp('pinnedAt')->nullable();
            $table->boolean('isLocked')->default(false);
            $table->string('notePassword')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
