<?php

namespace Database\Seeders;

use App\Enums\DatasetNeedStatus;
use App\Enums\PriorityLevel;
use App\Models\DatasetNeed;
use App\Models\User;
use Illuminate\Database\Seeder;

class FiveCategorySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $adminId = $admin ? $admin->id : 1;

        // -------------------------------------------------------------------------
        // A. ALPHABET CATEGORY (category_id: 'alphabet')
        // -------------------------------------------------------------------------
        // 1. Letters (A-Z)
        $letters = range('A', 'Z');
        foreach ($letters as $char) {
            $title = "Huruf {$char}";
            $existing = DatasetNeed::where('title', $title)->first();
            if (!$existing) {
                DatasetNeed::create([
                    'title' => $title,
                    'category' => 'Abjad',
                    'category_id' => 'alphabet',
                    'subcategory' => 'letters',
                    'description' => "Video Bahasa Isyarat Indonesia untuk label Huruf {$char}.",
                    'target_count' => 20,
                    'current_count' => 0,
                    'priority' => PriorityLevel::HIGH,
                    'status' => DatasetNeedStatus::ACTIVE,
                    'created_by' => $adminId,
                ]);
            } else {
                $existing->update([
                    'category' => 'Abjad',
                    'category_id' => 'alphabet',
                    'subcategory' => 'letters',
                    'status' => DatasetNeedStatus::ACTIVE,
                ]);
            }
        }

        // 2. Numbers (1-10) - Handle 'Angka 0' as inactive if present
        DatasetNeed::where('title', 'Angka 0')->update(['status' => DatasetNeedStatus::INACTIVE]);

        $numbers = range(1, 10);
        foreach ($numbers as $num) {
            $title = "Angka {$num}";
            $existing = DatasetNeed::where('title', $title)->first();
            if (!$existing) {
                DatasetNeed::create([
                    'title' => $title,
                    'category' => 'Abjad',
                    'category_id' => 'alphabet',
                    'subcategory' => 'numbers',
                    'description' => "Video Bahasa Isyarat Indonesia untuk label Angka {$num}.",
                    'target_count' => 20,
                    'current_count' => 0,
                    'priority' => PriorityLevel::MEDIUM,
                    'status' => DatasetNeedStatus::ACTIVE,
                    'created_by' => $adminId,
                ]);
            } else {
                $existing->update([
                    'category' => 'Abjad',
                    'category_id' => 'alphabet',
                    'subcategory' => 'numbers',
                    'status' => DatasetNeedStatus::ACTIVE,
                ]);
            }
        }

        // -------------------------------------------------------------------------
        // B. WORD CATEGORY (category_id: 'word')
        // -------------------------------------------------------------------------
        // Deactivate old single 'Bangun' title if present
        DatasetNeed::where('title', 'Bangun')->update(['status' => DatasetNeedStatus::INACTIVE]);

        $wordGroups = [
            'personal_pronouns' => ['Aku', 'Saya', 'Kamu', 'Dia', 'Kami', 'Mereka'],
            'verbs' => [
                'Makan', 'Minum', 'Mandi', 'Duduk',
                'Bangun – Versi 1', 'Bangun – Versi 2',
                'Tidur', 'Jalan', 'Langkah', 'Lari', 'Lompat', 'Baca', 'Bicara', 'Tulis',
                'Main', 'Senyum', 'Sedih', 'Marah', 'Dengar', 'Lihat', 'Pegang', 'Lepas',
                'Angkat', 'Lempar', 'Dorong', 'Tarik', 'Tendang', 'Tangkap', 'Kejar', 'Tari', 'Nyanyi', 'Lukis'
            ],
            'nouns_body_parts' => ['Rambut', 'Mata', 'Hidung', 'Telinga', 'Mulut', 'Gigi', 'Lidah', 'Leher', 'Bahu', 'Dada', 'Perut', 'Punggung', 'Lengan', 'Siku', 'Tangan', 'Jari', 'Kaki'],
            'nouns_clothing' => ['Celana', 'Rok', 'Baju', 'Topi', 'Sepatu', 'Sandal'],
            'nouns_stationery' => ['Pensil', 'Buku', 'Tas'],
            'nouns_eating_utensils' => ['Sendok', 'Garpu', 'Piring', 'Cangkir', 'Gelas'],
            'adjectives' => [
                'Pintar', 'Bodoh', 'Rajin', 'Malas', 'Ramah', 'Berani', 'Besar', 'Kecil', 'Tinggi', 'Panjang', 'Pendek',
                'Bersih', 'Kotor', 'Rapi', 'Cantik', 'Tampan', 'Panas', 'Dingin', 'Wangi', 'Busuk', 'Manis', 'Asam',
                'Pahit', 'Asin', 'Ramai', 'Sepi', 'Kenyang', 'Lapar', 'Terang', 'Gelap', 'Jauh', 'Dekat'
            ]
        ];

        // Collect all new active word titles
        $activeWordTitles = [];

        foreach ($wordGroups as $group => $words) {
            foreach ($words as $w) {
                $wTrimmed = trim($w);
                $activeWordTitles[] = $wTrimmed;
                $existing = DatasetNeed::where('title', $wTrimmed)->first();

                if (!$existing) {
                    DatasetNeed::create([
                        'title' => $wTrimmed,
                        'category' => 'Kata',
                        'category_id' => 'word',
                        'subcategory' => $group,
                        'description' => "Video Bahasa Isyarat Indonesia untuk kata \"{$wTrimmed}\".",
                        'target_count' => 20,
                        'current_count' => 0,
                        'priority' => PriorityLevel::HIGH,
                        'status' => DatasetNeedStatus::ACTIVE,
                        'created_by' => $adminId,
                    ]);
                } else {
                    $existing->update([
                        'category' => 'Kata',
                        'category_id' => 'word',
                        'subcategory' => $group,
                        'status' => DatasetNeedStatus::ACTIVE,
                    ]);
                }
            }
        }

        // Deactivate old legacy words that are not part of the active 100 Word labels list
        DatasetNeed::where('category_id', 'word')
            ->whereNotIn('title', $activeWordTitles)
            ->update(['status' => DatasetNeedStatus::INACTIVE]);

        // -------------------------------------------------------------------------
        // C. IDIOM / UNGKAPAN / KATA MAJEMUK CATEGORY (category_id: 'idiom_expression')
        // -------------------------------------------------------------------------
        $idioms = [
            'Selamat Pagi',
            'Selamat Siang',
            'Selamat Malam',
            'Selamat Datang',
            'Selamat Tinggal',
            'Selamat Ulang Tahun',
            'Terima Kasih'
        ];

        foreach ($idioms as $phrase) {
            $existing = DatasetNeed::where('title', $phrase)->first();
            if (!$existing) {
                DatasetNeed::create([
                    'title' => $phrase,
                    'category' => 'Idiom / Ungkapan / Kata Majemuk',
                    'category_id' => 'idiom_expression',
                    'subcategory' => 'expressions',
                    'description' => "Video Bahasa Isyarat Indonesia untuk ungkapan \"{$phrase}\".",
                    'target_count' => 20,
                    'current_count' => 0,
                    'priority' => PriorityLevel::HIGH,
                    'status' => DatasetNeedStatus::ACTIVE,
                    'created_by' => $adminId,
                ]);
            } else {
                $existing->update([
                    'category' => 'Idiom / Ungkapan / Kata Majemuk',
                    'category_id' => 'idiom_expression',
                    'subcategory' => 'expressions',
                    'status' => DatasetNeedStatus::ACTIVE,
                ]);
            }
        }

        // Deactivate legacy Phrase items that are not in the 7 predefined Idiom/Ungkapan list
        DatasetNeed::where('category_id', 'phrase')
            ->whereNotIn('title', $idioms)
            ->update(['status' => DatasetNeedStatus::INACTIVE]);

        // -------------------------------------------------------------------------
        // D. SENTENCE & E. SHORT STORY CATEGORIES
        // -------------------------------------------------------------------------
        // Sentence & Short Story use ONLY contributor-generated content (No predefined dataset needs)
        DatasetNeed::where('category_id', 'sentence')->update(['status' => DatasetNeedStatus::INACTIVE]);
        DatasetNeed::where('category_id', 'short_story')->update(['status' => DatasetNeedStatus::INACTIVE]);
    }
}
