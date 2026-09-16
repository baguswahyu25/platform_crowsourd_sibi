<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update category "Idiom / Ungkapan / Kata Majemuk" or "idiom_expression" to "Kata Imbuhan" & "kata_imbuhan"
        DB::table('dataset_needs')
            ->whereIn('category_id', ['idiom_expression', 'idiom'])
            ->orWhere('category', 'like', '%Idiom%')
            ->update([
                'category' => 'Kata Imbuhan',
                'category_id' => 'kata_imbuhan',
            ]);

        DB::table('datasets')
            ->whereIn('category', ['Idiom / Ungkapan / Kata Majemuk', 'idiom_expression', 'idiom'])
            ->update([
                'category' => 'Kata Imbuhan',
            ]);

        // 3. Map existing subcategories for "Kata" to the 4 official subcategories
        // - personal_pronouns -> "Kata ganti diri"
        // - verbs -> "Kata kerja (kata dasar)"
        // - nouns_body_parts, nouns_clothing, nouns_stationery, nouns_eating_utensils -> "Kata benda"
        // - adjectives -> "Kata sifat"

        DB::table('dataset_needs')
            ->where('category_id', 'word')
            ->where('subcategory', 'personal_pronouns')
            ->update(['subcategory' => 'Kata ganti diri']);

        DB::table('dataset_needs')
            ->where('category_id', 'word')
            ->where('subcategory', 'verbs')
            ->update(['subcategory' => 'Kata kerja (kata dasar)']);

        DB::table('dataset_needs')
            ->where('category_id', 'word')
            ->whereIn('subcategory', ['nouns_body_parts', 'nouns_clothing', 'nouns_stationery', 'nouns_eating_utensils'])
            ->update(['subcategory' => 'Kata benda']);

        DB::table('dataset_needs')
            ->where('category_id', 'word')
            ->where('subcategory', 'adjectives')
            ->update(['subcategory' => 'Kata sifat']);

        DB::table('datasets')
            ->where('category', 'Kata')
            ->where('subcategory', 'personal_pronouns')
            ->update(['subcategory' => 'Kata ganti diri']);

        DB::table('datasets')
            ->where('category', 'Kata')
            ->where('subcategory', 'verbs')
            ->update(['subcategory' => 'Kata kerja (kata dasar)']);

        DB::table('datasets')
            ->where('category', 'Kata')
            ->whereIn('subcategory', ['nouns_body_parts', 'nouns_clothing', 'nouns_stationery', 'nouns_eating_utensils'])
            ->update(['subcategory' => 'Kata benda']);

        DB::table('datasets')
            ->where('category', 'Kata')
            ->where('subcategory', 'adjectives')
            ->update(['subcategory' => 'Kata sifat']);
    }

    public function down(): void
    {
        // Non-destructive rollback
    }
};
