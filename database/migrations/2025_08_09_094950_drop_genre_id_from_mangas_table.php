<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('mangas', 'genre_id')) {
            Schema::table('mangas', function (Blueprint $table) {
                // If a foreign key exists, drop it first
                try {
                    $table->dropForeign(['genre_id']);
                } catch (\Exception $e) {
                    // ignore if not exists
                }
                $table->dropColumn('genre_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->foreignId('genre_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }
};
