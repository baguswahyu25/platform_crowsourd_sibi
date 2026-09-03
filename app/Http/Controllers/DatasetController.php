<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\DatasetNeed;
use App\Services\DatasetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DatasetController extends Controller
{
    public function __construct(
        protected DatasetService $datasetService
    ) {}

    public function detail(int $id): View
    {
        $dataset = Dataset::with(['user', 'datasetNeed', 'validation'])->findOrFail($id);
        return view('pages.dataset.detail', compact('dataset', 'id'));
    }

    public function store(Request $request): RedirectResponse
    {
        $needId = $request->input('dataset_need_id');
        $catInput = strtolower($request->input('category') ?? $request->input('category_id') ?? '');
        $isShortStory = ($catInput === 'short_story');
        $isSentence = ($catInput === 'sentence');

        // Helper redirect jika terjadi kesalahan agar tetap berada pada halaman unggah yang sesuai
        $redirectWithError = function (string $errorMsg) use ($needId, $catInput) {
            if ($needId && $catInput !== 'short_story' && $catInput !== 'sentence') {
                return redirect()->route('contributor.dataset.upload', ['need_id' => $needId])
                    ->withInput()
                    ->with('error', $errorMsg);
            }
            return redirect()->route('contributor.dataset.upload', ['category' => $catInput])
                ->withInput()
                ->with('error', $errorMsg);
        };

        // 1. Guard Kebutuhan Dataset untuk Predefined Category
        if ($needId && !$isShortStory && !$isSentence) {
            $need = DatasetNeed::find($needId);
            if ($need && ($need->status->value === 'fulfilled' || $need->current_count >= $need->target_count)) {
                return $redirectWithError("Target kebutuhan dataset untuk \"{$need->title}\" sudah terpenuhi. Pengunggahan telah ditutup.");
            }
        }

        // 2. Cek Kevalidan Berkas Video PHP sebelum Validasi Form Request
        if ($request->hasFile('dataset_file')) {
            $file = $request->file('dataset_file');
            if (!$file || !$file->isValid()) {
                $errorCode = $file ? $file->getError() : UPLOAD_ERR_NO_FILE;
                $errorMsg = match ($errorCode) {
                    UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran berkas video melebihi batas maksimum pengunggahan server (3 MB). Silakan pilih video yang lebih kecil.',
                    UPLOAD_ERR_PARTIAL => 'Pengunggahan berkas video terputus. Silakan coba lagi.',
                    UPLOAD_ERR_NO_FILE => 'Berkas video tidak ditemukan. Silakan pilih berkas video.',
                    default => 'Berkas video tidak valid atau gagal diunggah ke server. Silakan periksa kembali berkas Anda.',
                };
                return $redirectWithError($errorMsg);
            }
        }

        // 3. Validasi Form Request Berkas Video (Berdasarkan Kategori)
        try {
            if ($isShortStory) {
                $request->validate([
                    'title' => 'required|string|max:255',
                    'sign_label' => 'required|string|max:255',
                    'story_content' => 'required|string',
                    'dataset_file' => 'required|file|mimes:mp4,mov,avi,mkv,webm|max:3072',
                ], [
                    'title.required' => 'Judul Cerita Pendek wajib diisi.',
                    'sign_label.required' => 'Label Cerita Pendek wajib diisi.',
                    'story_content.required' => 'Naskah / Transkrip Cerita Pendek wajib diisi.',
                    'dataset_file.required' => 'Berkas video wajib dipilih.',
                    'dataset_file.mimes' => 'Format berkas video harus berupa MP4, MOV, AVI, MKV, atau WEBM.',
                    'dataset_file.max' => 'Ukuran berkas video melebihi batas maksimum 3 MB.',
                ]);
            } elseif ($isSentence) {
                $request->validate([
                    'sentence_content' => 'required|string',
                    'dataset_file' => 'required|file|mimes:mp4,mov,avi,mkv,webm|max:3072',
                ], [
                    'sentence_content.required' => 'Teks Kalimat Bahasa Indonesia wajib diisi.',
                    'dataset_file.required' => 'Berkas video wajib dipilih.',
                    'dataset_file.mimes' => 'Format berkas video harus berupa MP4, MOV, AVI, MKV, atau WEBM.',
                    'dataset_file.max' => 'Ukuran berkas video melebihi batas maksimum 3 MB.',
                ]);
            } else {
                $request->validate([
                    'dataset_file' => 'required|file|mimes:mp4,mov,avi,mkv,webm|max:3072',
                ], [
                    'dataset_file.required' => 'Berkas video wajib dipilih sebelum melakukan pengunggahan.',
                    'dataset_file.file' => 'Berkas yang diunggah harus berupa file video valid.',
                    'dataset_file.mimes' => 'Format berkas video harus berupa MP4, MOV, AVI, MKV, atau WEBM.',
                    'dataset_file.max' => 'Ukuran berkas video melebihi batas maksimum 3 MB.',
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return $redirectWithError($firstError ?? 'Validasi form pengunggahan gagal.');
        }

        $file = $request->file('dataset_file');

        try {
            $path = $file->store('datasets', 'public');
            $extension = strtolower($file->getClientOriginalExtension());
            $fileSize = $file->getSize();
        } catch (\Throwable $e) {
            Log::error('Upload storage error: ' . $e->getMessage());
            return $redirectWithError('Gagal menyimpan berkas video ke storage server.');
        }

        // 4. Jalankan Skrip Python AI Validation (OpenCV & NumPy) secara Real Production
        $fullVideoPath = storage_path('app/public/' . $path);
        if (!file_exists($fullVideoPath)) {
            $fullVideoPath = storage_path('app/' . $path);
        }

        $pythonBinary = env('PYTHON_PATH');
        if (!$pythonBinary || !file_exists($pythonBinary)) {
            $candidates = [
                '/home/driveenusa.it.com/public_html/ai_validation/venv/bin/python',
                '/usr/bin/python3',
                '/usr/local/bin/python3',
                '/usr/bin/python',
                'python3',
                'python',
                'C:/Users/asus/.pyenv/pyenv-win/versions/3.12.7/python.exe',
                'C:/Users/asus/.pyenv/pyenv-win/shims/python.bat',
                'C:/Users/asus/.pyenv/pyenv-win/shims/python.exe',
            ];
            foreach ($candidates as $c) {
                if ($c === 'python3' || $c === 'python' || file_exists($c)) {
                    $pythonBinary = $c;
                    break;
                }
            }
        }

        $pythonResult = Process::run([
            $pythonBinary,
            base_path('ai_validation/analyze_video.py'),
            $fullVideoPath
        ]);

        $rawOutput = $pythonResult->output();
        $output = json_decode($rawOutput, true);

        if (!is_array($output) || !isset($output['success']) || !$output['success']) {
            Storage::disk('public')->delete($path);
            Log::error('AI Validation Execution Error:', [
                'exit_code' => $pythonResult->exitCode(),
                'raw_output' => $rawOutput,
                'error_output' => $pythonResult->errorOutput(),
            ]);
            $message = $output['message'] ?? 'Video tidak dapat dianalisis oleh sistem AI.';
            return $redirectWithError($message);
        }

        $isPassed = ($output['validation_status'] === 'passed');

        // Logging detail hasil analisis OpenCV AI
        Log::info('OpenCV AI Video Validation Result:', [
            'file_path' => $path,
            'file_exists' => file_exists($fullVideoPath),
            'file_size' => $fileSize,
            'video_width' => $output['video']['width'] ?? null,
            'video_height' => $output['video']['height'] ?? null,
            'video_fps' => $output['video']['fps'] ?? null,
            'fps_status' => $output['video']['fps_status'] ?? null,
            'resolution_status' => $output['video']['resolution_status'] ?? null,
            'brightness_score' => $output['brightness']['score'] ?? null,
            'brightness_status' => $output['brightness']['status'] ?? null,
            'blur_score' => $output['blur']['score'] ?? null,
            'blur_status' => $output['blur']['status'] ?? null,
            'freeze_percentage' => $output['freeze']['percentage'] ?? null,
            'freeze_status' => $output['freeze']['status'] ?? null,
            'final_status' => $output['validation_status'] ?? 'failed',
            'failure_reasons' => $output['failure_reasons'] ?? [],
        ]);

        if (!$isPassed) {
            $failedTitle = $isShortStory ? $request->input('title') : ($isSentence ? $request->input('sentence_content') : ($request->title ?? 'Peragaan SIBI'));
            $failedCategory = $isShortStory ? 'Short Story' : ($isSentence ? 'Sentence' : ($request->category ?? 'Alphabet'));
            $failedLabel = $isShortStory ? $request->input('sign_label') : ($isSentence ? $request->input('sentence_content') : ($request->sign_label ?? 'Label SIBI'));
            $storyContent = $isShortStory ? $request->input('story_content') : ($isSentence ? $request->input('sentence_content') : null);

            $userId = auth()->id() ?? 1;
            $failedReasonsStr = !empty($output['failure_reasons']) ? implode('; ', $output['failure_reasons']) : 'Video tidak memenuhi kriteria kualitas teknis AI.';

            $failedDatasetRecord = $this->datasetService->storeDataset([
                'title' => $failedTitle,
                'category' => $failedCategory,
                'subcategory' => $request->input('subcategory') ?? ($isShortStory ? 'short_story' : ($isSentence ? 'sentence' : null)),
                'sign_label' => $failedLabel,
                'description' => $request->input('description') ?? $storyContent,
                'story_content' => $storyContent,
                'dataset_need_id' => ($isShortStory || $isSentence) ? null : $request->dataset_need_id,
                'file_path' => $path,
                'file_type' => $extension,
                'file_size' => $fileSize,

                'brightness_score' => $output['brightness']['score'] ?? null,
                'brightness_status' => $output['brightness']['status'] ?? null,

                'blur_score' => $output['blur']['score'] ?? null,
                'blur_status' => $output['blur']['status'] ?? null,

                'freeze_percentage' => $output['freeze']['percentage'] ?? null,
                'freeze_status' => $output['freeze']['status'] ?? null,

                'video_width' => $output['video']['width'] ?? null,
                'video_height' => $output['video']['height'] ?? null,
                'video_fps' => $output['video']['fps'] ?? null,
                'resolution_status' => $output['video']['resolution_status'] ?? null,

                'auto_validation_status' => 'failed',
                'validation_message' => $failedReasonsStr,
                'rejection_reason' => $failedReasonsStr,
                'status' => 'failed',
            ], $userId);

            session()->flash('failed_dataset', [
                'id' => $failedDatasetRecord->id,
                'title' => $failedTitle,
                'category' => $failedCategory,
                'sign_label' => $failedLabel,
                'dataset_need_id' => $request->dataset_need_id,
                'story_content' => $storyContent,
                'brightness_score' => $output['brightness']['score'] ?? null,
                'brightness_status' => $output['brightness']['status'] ?? null,
                'blur_score' => $output['blur']['score'] ?? null,
                'blur_status' => $output['blur']['status'] ?? null,
                'freeze_percentage' => $output['freeze']['percentage'] ?? null,
                'freeze_status' => $output['freeze']['status'] ?? null,
                'video_width' => $output['video']['width'] ?? null,
                'video_height' => $output['video']['height'] ?? null,
                'video_fps' => $output['video']['fps'] ?? null,
                'fps_status' => $output['video']['fps_status'] ?? 'Sesuai Standar',
                'resolution_status' => $output['video']['resolution_status'] ?? null,
                'failure_reasons' => $output['failure_reasons'] ?? [],
                'auto_validation_status' => 'failed',
            ]);

            return redirect()->route('contributor.dataset.ai_check_failed', [
                'need_id' => $request->dataset_need_id
            ]);
        }

        // 5. Simpan ke MySQL & Storage untuk Dataset yang Lolos AI Validation
        $userId = auth()->id() ?? 1;

        if ($isShortStory) {
            $categoryName = 'Short Story';
            $title = $request->input('title');
            $signLabel = $request->input('sign_label');
            $storyContent = $request->input('story_content');
        } elseif ($isSentence) {
            $categoryName = 'Sentence';
            $sentence = $request->input('sentence_content');
            $title = Str::limit($sentence, 50);
            $signLabel = $sentence;
            $storyContent = $sentence;
        } else {
            $categoryName = match($catInput) {
                'alphabet', 'abjad' => 'Abjad',
                'word', 'kata' => 'Kata',
                'idiom_expression', 'idiom' => 'Idiom / Ungkapan / Kata Majemuk',
                default => $request->category ?? 'Abjad'
            };
            $title = $request->title ?? 'Peragaan SIBI';
            $signLabel = $request->sign_label ?? 'SIBI';
            $storyContent = null;
        }

        $dataset = $this->datasetService->storeDataset([
            'title' => $title,
            'category' => $categoryName,
            'subcategory' => $request->input('subcategory') ?? ($isShortStory ? 'short_story' : ($isSentence ? 'sentence' : null)),
            'sign_label' => $signLabel,
            'description' => $request->input('description') ?? $storyContent,
            'story_content' => $storyContent,
            'dataset_need_id' => ($isShortStory || $isSentence) ? null : $request->dataset_need_id,
            'file_path' => $path,
            'file_type' => $extension,
            'file_size' => $fileSize,

            'brightness_score' => $output['brightness']['score'],
            'brightness_status' => $output['brightness']['status'],

            'blur_score' => $output['blur']['score'],
            'blur_status' => $output['blur']['status'],

            'freeze_percentage' => $output['freeze']['percentage'],
            'freeze_status' => $output['freeze']['status'],

            'video_width' => $output['video']['width'],
            'video_height' => $output['video']['height'],
            'video_fps' => $output['video']['fps'],
            'resolution_status' => $output['video']['resolution_status'],

            'auto_validation_status' => 'passed',
            'validation_message' => 'Video memenuhi 4 kriteria analisis AI OpenCV.',
            'status' => 'waiting_expert_validation',
        ], $userId);

        return redirect()->route('contributor.dataset.ai_check', [
            'id' => $dataset->id,
            'need_id' => $request->dataset_need_id
        ]);
    }

    public function aiCheckFailed(Request $request): View|RedirectResponse
    {
        $failedDataset = session('failed_dataset');
        if (!$failedDataset) {
            return redirect()->route('contributor.dataset.upload');
        }

        session()->flash('failed_dataset', $failedDataset);
        $needId = $request->query('need_id');

        return view('pages.contributor.dataset.ai_check_failed', compact('failedDataset', 'needId'));
    }

    public function validationResultFailed(Request $request): View|RedirectResponse
    {
        $failedDataset = session('failed_dataset');
        if (!$failedDataset) {
            return redirect()->route('contributor.dataset.upload');
        }

        return view('pages.contributor.dataset.validation_result_failed', compact('failedDataset'));
    }

    public function validationResult(Dataset $dataset): View
    {
        return view('pages.contributor.dataset.validation_result', compact('dataset'));
    }

    public function aiCheck(Request $request, int $id): View
    {
        $dataset = Dataset::findOrFail($id);
        $needId = $request->query('need_id');

        return view('pages.contributor.dataset.ai_check', compact('dataset', 'needId'));
    }
}
