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
        Schema::create('note_img', function (Blueprint $table) {
            $table->id('imgId');
            $table->unsignedBigInteger('noteId');
            $table->string('imgPath');
            $table->timestamps();
        
            $table->foreign('noteId')->references('noteId')->on('notes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_img');
    }
};
