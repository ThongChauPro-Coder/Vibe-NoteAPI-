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
        Schema::create('note_link_label', function (Blueprint $table) {
            $table->foreignId('noteId')->constrained('notes')->onDelete('cascade');
            $table->foreignId('labelId')->constrained('labels')->onDelete('cascade');
            $table->primary(['noteId', 'labelId']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_link_label');
    }
};
