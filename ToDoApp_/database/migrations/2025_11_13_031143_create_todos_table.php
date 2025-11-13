<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi untuk membuat tabel todos.
     */
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string('task');
            $table->text('description')->nullable();
            $table->boolean('is_done')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Batalkan migrasi (hapus tabel todos).
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
