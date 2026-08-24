<?php

namespace Database\Seeders;

use App\Enums\DatasetNeedStatus;
use App\Enums\DatasetStatus;
use App\Enums\PriorityLevel;
use App\Enums\UserRole;
use App\Models\Dataset;
use App\Models\DatasetNeed;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Administrator Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@sibi.id'],
            [
                'name' => 'Administrator SIBI',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'institution' => 'Kementerian Riset & Teknologi',
                'phone' => '081122334455',
                'is_active' => true,
            ]
        );

        // 2. Validator SIBI Account
        $validator = User::firstOrCreate(
            ['email' => 'validator@sibi.id'],
            [
                'name' => 'Dr. Hendra Wijaya',
                'password' => Hash::make('password'),
                'role' => UserRole::VALIDATOR,
                'institution' => 'Pusat Bahasa Isyarat Indonesia',
                'phone' => '081233445566',
                'is_active' => true,
            ]
        );

        // 3. Contributor Accounts (Kontributor 1, Kontributor 2, Kontributor 3)
        $contributor1 = User::firstOrCreate(
            ['email' => 'kontributor@sibi.id'],
            [
                'name' => 'Ahmad Risyad',
                'password' => Hash::make('password'),
                'role' => UserRole::CONTRIBUTOR,
                'institution' => 'Universitas Indonesia',
                'phone' => '081344556677',
                'is_active' => true,
            ]
        );

        $contributor2 = User::firstOrCreate(
            ['email' => 'budi@sibi.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => UserRole::CONTRIBUTOR,
                'institution' => 'Institut Teknologi Bandung',
                'phone' => '081455667788',
                'is_active' => true,
            ]
        );

        $contributor3 = User::firstOrCreate(
            ['email' => 'citra@sibi.id'],
            [
                'name' => 'Citra Dewi',
                'password' => Hash::make('password'),
                'role' => UserRole::CONTRIBUTOR,
                'institution' => 'Universitas Gadjah Mada',
                'phone' => '081566778899',
                'is_active' => true,
            ]
        );

        // 4. Seed 76 Dataset Needs (Abjad, Angka, Kata)
        $abjadList = range('A', 'Z');
        $demoCurrentAbjad = [
            'A' => 10, 'B' => 20, 'C' => 5, 'D' => 18, 'E' => 0, 'F' => 12, 'G' => 3, 'H' => 20,
            'I' => 8,  'J' => 14, 'K' => 2, 'L' => 20, 'M' => 6, 'N' => 11, 'O' => 4, 'P' => 15,
            'Q' => 1,  'R' => 9,  'S' => 20, 'T' => 7,  'U' => 13, 'V' => 16, 'W' => 0, 'X' => 2,
            'Y' => 19, 'Z' => 10
        ];

        foreach ($abjadList as $char) {
            $current = $demoCurrentAbjad[$char] ?? 0;
            $isFulfilled = ($current >= 20);

            DatasetNeed::updateOrCreate(
                ['title' => "Huruf {$char}"],
                [
                    'category' => 'Abjad SIBI',
                    'description' => "Video Bahasa Isyarat Indonesia untuk label Huruf {$char}.",
                    'target_count' => 20,
                    'current_count' => $current,
                    'priority' => PriorityLevel::HIGH,
                    'status' => $isFulfilled ? DatasetNeedStatus::FULFILLED : DatasetNeedStatus::ACTIVE,
                    'created_by' => $admin->id,
                ]
            );
        }

        $angkaList = range(0, 9);
        $demoCurrentAngka = [0 => 15, 1 => 8, 2 => 20, 3 => 4, 4 => 12, 5 => 2, 6 => 18, 7 => 9, 8 => 20, 9 => 6];

        foreach ($angkaList as $num) {
            $current = $demoCurrentAngka[$num] ?? 0;
            $isFulfilled = ($current >= 20);

            DatasetNeed::updateOrCreate(
                ['title' => "Angka {$num}"],
                [
                    'category' => 'Angka SIBI',
                    'description' => "Video Bahasa Isyarat Indonesia untuk label Angka {$num}.",
                    'target_count' => 20,
                    'current_count' => $current,
                    'priority' => PriorityLevel::MEDIUM,
                    'status' => $isFulfilled ? DatasetNeedStatus::FULFILLED : DatasetNeedStatus::ACTIVE,
                    'created_by' => $admin->id,
                ]
            );
        }

        $kataCategories = [
            'Kata Dasar' => ['AKU', 'KAMU', 'DIA', 'KITA', 'MEREKA', 'AYAH', 'IBU', 'KAKAK', 'ADIK', 'TEMAN'],
            'Kata Kerja' => ['MAKAN', 'MINUM', 'TIDUR', 'DUDUK', 'BERDIRI', 'PERGI', 'DATANG', 'BELAJAR', 'MEMBACA', 'MENULIS'],
            'Kata Benda' => ['BUKU', 'PENSIL', 'MEJA', 'KURSI', 'RUMAH', 'SEKOLAH', 'KAMAR', 'MOTOR', 'MOBIL', 'TELEPON'],
            'Kata Sifat' => ['BESAR', 'KECIL', 'PANJANG', 'PENDEK', 'BAIK', 'BURUK', 'CEPAT', 'LAMBAT', 'SENANG', 'SEDIH'],
        ];

        foreach ($kataCategories as $catName => $words) {
            foreach ($words as $idx => $word) {
                $current = (($idx + 1) * 3) % 22;
                if ($current > 20) $current = 20;
                $isFulfilled = ($current >= 20);

                DatasetNeed::updateOrCreate(
                    ['title' => $word],
                    [
                        'category' => $catName,
                        'description' => "Video Bahasa Isyarat Indonesia untuk label \"{$word}\".",
                        'target_count' => 20,
                        'current_count' => $current,
                        'priority' => PriorityLevel::HIGH,
                        'status' => $isFulfilled ? DatasetNeedStatus::FULFILLED : DatasetNeedStatus::ACTIVE,
                        'created_by' => $admin->id,
                    ]
                );
            }
        }

        // 5. Seed Sample Datasets showing Multiple Uploads per Contributor + Auto Validation Scores:
        $needHurufA = DatasetNeed::where('title', 'Huruf A')->first();
        $needHurufB = DatasetNeed::where('title', 'Huruf B')->first();
        $needAngka1 = DatasetNeed::where('title', 'Angka 1')->first();
        $needAku = DatasetNeed::where('title', 'AKU')->first();

        // Datasets for Kontributor 1 (Ahmad Risyad) - Total 3 Uploads
        Dataset::create([
            'user_id' => $contributor1->id,
            'dataset_need_id' => $needHurufA?->id,
            'contributor_code' => 'Kontributor 1',
            'title' => 'Peragaan Huruf A - Pengunggahan 1',
            'category' => 'Abjad SIBI',
            'sign_label' => 'Huruf A',
            'description' => 'Rekaman peragaan Huruf A variasi 1',
            'file_path' => 'datasets/huruf_a_1.mp4',
            'file_type' => 'mp4',
            'file_size' => 12400000,
            // AUTO VALIDATION
            'brightness_score' => 84.5,
            'brightness_status' => 'passed',
            'blur_score' => 172.0,
            'blur_status' => 'passed',
            'freeze_percentage' => 0.0,
            'freeze_status' => 'passed',
            'video_width' => 1920,
            'video_height' => 1080,
            'video_fps' => 60.0,
            'resolution_status' => 'passed',
            'auto_validation_status' => 'passed',
            'validation_message' => 'Lolos 4 kriteria otomatis AI.',
            'status' => DatasetStatus::PENDING,
        ]);

        Dataset::create([
            'user_id' => $contributor1->id,
            'dataset_need_id' => $needHurufA?->id,
            'contributor_code' => 'Kontributor 1',
            'title' => 'Peragaan Huruf A - Pengunggahan 2',
            'category' => 'Abjad SIBI',
            'sign_label' => 'Huruf A',
            'description' => 'Rekaman peragaan Huruf A variasi 2',
            'file_path' => 'datasets/huruf_a_2.mp4',
            'file_type' => 'mp4',
            'file_size' => 13100000,
            // AUTO VALIDATION
            'brightness_score' => 88.0,
            'brightness_status' => 'passed',
            'blur_score' => 190.2,
            'blur_status' => 'passed',
            'freeze_percentage' => 0.0,
            'freeze_status' => 'passed',
            'video_width' => 1920,
            'video_height' => 1080,
            'video_fps' => 60.0,
            'resolution_status' => 'passed',
            'auto_validation_status' => 'passed',
            'validation_message' => 'Lolos 4 kriteria otomatis AI.',
            'status' => DatasetStatus::VALIDATED,
        ]);

        Dataset::create([
            'user_id' => $contributor1->id,
            'dataset_need_id' => $needAku?->id,
            'contributor_code' => 'Kontributor 1',
            'title' => 'Peragaan Kata AKU - Pengunggahan 3',
            'category' => 'Kata Dasar',
            'sign_label' => 'AKU',
            'description' => 'Rekaman isyarat AKU',
            'file_path' => 'datasets/aku_1.mp4',
            'file_type' => 'mp4',
            'file_size' => 15200000,
            // AUTO VALIDATION
            'brightness_score' => 81.2,
            'brightness_status' => 'passed',
            'blur_score' => 160.8,
            'blur_status' => 'passed',
            'freeze_percentage' => 0.0,
            'freeze_status' => 'passed',
            'video_width' => 1920,
            'video_height' => 1080,
            'video_fps' => 60.0,
            'resolution_status' => 'passed',
            'auto_validation_status' => 'passed',
            'validation_message' => 'Lolos 4 kriteria otomatis AI.',
            'status' => DatasetStatus::PENDING,
        ]);

        // Datasets for Kontributor 2 (Budi Santoso) - Total 5 Uploads
        for ($i = 1; $i <= 5; $i++) {
            Dataset::create([
                'user_id' => $contributor2->id,
                'dataset_need_id' => $needHurufB?->id,
                'contributor_code' => 'Kontributor 2',
                'title' => "Peragaan Huruf B - Pengunggahan {$i}",
                'category' => 'Abjad SIBI',
                'sign_label' => 'Huruf B',
                'description' => "Rekaman peragaan Huruf B variasi {$i}",
                'file_path' => "datasets/huruf_b_{$i}.mp4",
                'file_type' => 'mp4',
                'file_size' => 11000000 + ($i * 500000),
                // AUTO VALIDATION
                'brightness_score' => 80.0 + $i,
                'brightness_status' => 'passed',
                'blur_score' => 150.0 + ($i * 5),
                'blur_status' => 'passed',
                'freeze_percentage' => 0.0,
                'freeze_status' => 'passed',
                'video_width' => 1920,
                'video_height' => 1080,
                'video_fps' => 60.0,
                'resolution_status' => 'passed',
                'auto_validation_status' => 'passed',
                'validation_message' => 'Lolos 4 kriteria otomatis AI.',
                'status' => ($i % 2 === 0) ? DatasetStatus::VALIDATED : DatasetStatus::PENDING,
            ]);
        }

        // Datasets for Kontributor 3 (Citra Dewi) - Total 2 Uploads
        for ($i = 1; $i <= 2; $i++) {
            Dataset::create([
                'user_id' => $contributor3->id,
                'dataset_need_id' => $needAngka1?->id,
                'contributor_code' => 'Kontributor 3',
                'title' => "Peragaan Angka 1 - Pengunggahan {$i}",
                'category' => 'Angka SIBI',
                'sign_label' => 'Angka 1',
                'description' => "Rekaman peragaan Angka 1 variasi {$i}",
                'file_path' => "datasets/angka_1_{$i}.mp4",
                'file_type' => 'mp4',
                'file_size' => 10500000,
                // AUTO VALIDATION
                'brightness_score' => 86.0,
                'brightness_status' => 'passed',
                'blur_score' => 175.0,
                'blur_status' => 'passed',
                'freeze_percentage' => 0.0,
                'freeze_status' => 'passed',
                'video_width' => 1920,
                'video_height' => 1080,
                'video_fps' => 60.0,
                'resolution_status' => 'passed',
                'auto_validation_status' => 'passed',
                'validation_message' => 'Lolos 4 kriteria otomatis AI.',
                'status' => DatasetStatus::PENDING,
            ]);
        }
    }
}
