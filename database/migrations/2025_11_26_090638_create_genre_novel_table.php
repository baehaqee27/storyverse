<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genre_novel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Migrate existing data
        $novels = DB::table('novels')->whereNotNull('genre_id')->get();
        foreach ($novels as $novel) {
            DB::table('genre_novel')->insert([
                'novel_id' => $novel->id,
                'genre_id' => $novel->genre_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('novels', function (Blueprint $table) {
            $table->dropForeign(['genre_id']);
            $table->dropColumn('genre_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('novels', function (Blueprint $table) {
            $table->foreignId('genre_id')->nullable()->constrained();
        });

        // Restore data (simplified, takes first genre)
        $relations = DB::table('genre_novel')->get();
        foreach ($relations as $relation) {
            DB::table('novels')->where('id', $relation->novel_id)->update(['genre_id' => $relation->genre_id]);
        }

        Schema::dropIfExists('genre_novel');
    }
};
