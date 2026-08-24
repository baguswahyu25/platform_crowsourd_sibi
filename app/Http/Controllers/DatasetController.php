<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\DatasetNeed;
use App\Services\DatasetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\View\View;

class DatasetController extends Controller
{
    public function __construct(
        protected DatasetService $datasetService
    ) {}

    public function detail(int $id): View
    {
        $dataset = $this->datasetService->getAllDatasets()->firstWhere('id', $id);
        return view('pages.dataset.detail', compact('dataset', 'id'));
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Guard Kebutuhan Dataset
        if ($request->filled('dataset_need_id')) {
            $need = DatasetNeed::find($request->dataset_need_id);
            if ($need && ($need->status->value === 'fulfilled' || $need->current_count >= $need->target_count)) {
                return redirect()->back()->with('error', "Target kebutuhan dataset untuk \"{$need->title}\" sudah terpenuhi. Pengunggahan telah ditutup.");
            }
        }

        // 2. Simpan Berkas Video Asli ke Storage Disk (public/datasets)
        $file = $request->file('dataset_file');
        if (!$file) {
            return redirect()->back()->with('error', 'Berkas video tidak ditemukan. Silakan pilih berkas video.');
        }

        $path = $file->store('datasets', 'public');
        $extension = strtolower($file->getClientOriginalExtension());
        $fileSize = $file->getSize();

        // 3. Jalankan Skrip Python AI Validation (OpenCV & NumPy) secara Real Production
        $fullVideoPath = storage_path('app/public/' . $path);
        if (!file_exists($fullVideoPath)) {
            $fullVideoPath = storage_path('app/' . $path);
        }

        $pythonBinary = env('PYTHON_PATH');
        if (!$pythonBinary || !file_exists($pythonBinary)) {
            $candidates = [
                'C:/Users/asus/.pyenv/pyenv-win/versions/3.12.7/python.exe',
                'C:/Users/asus/.pyenv/pyenv-win/shims/python.bat',
                'C:/Users/asus/.pyenv/pyenv-win/shims/python.exe',
                'python',
            ];
            foreach ($candidates as $c) {
                if ($c === 'python' || file_exists($c)) {
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

        // Validasi respon dari Skrip Python
        if (!is_array($output) || !isset($output['success']) || !$output['success']) {
            Log::error('AI Validation Execution Error:', [
                'exit_code' => $pythonResult->exitCode(),
                'raw_output' => $rawOutput,
                'error_output' => $pythonResult->errorOutput(),
            ]);
            $message = $output['message'] ?? 'Video tidak dapat dianalisis oleh sistem AI.';
            return redirect()->back()->with('error', $message);
        }

        $userId = auth()->id() ?? 1;

        // 4. Simpan Hasil Analisis Nyata OpenCV ke Database MySQL
        $dataset = $this->datasetService->storeDataset([
            'title' => $request->title ?? 'Isyarat SIBI',
            'category' => $request->category ?? 'Abjad SIBI',
            'sign_label' => $request->sign_label ?? 'A',
            'description' => $request->description,
            'dataset_need_id' => $request->dataset_need_id,
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

            'auto_validation_status' => $output['validation_status'],
            'validation_message' => $output['validation_status'] === 'passed'
                ? 'Video memenuhi 4 kriteria analisis AI OpenCV.'
                : 'Video belum memenuhi kriteria AI. Terdeteksi ' . ($output['blur']['status'] === 'Buram' ? 'buram' : 'pencahayaan/kelancaran belum sesuai') . '.',

            'status' => $output['validation_status'] === 'passed'
                ? 'waiting_expert_validation'
                : 'failed',
        ], $userId);

        // 5. Redirect ke Halaman Animasi Progres Pemindaian Validasi AI (/contributor/ai-check/{id})
        return redirect()->route('contributor.dataset.ai_check', ['id' => $dataset->id]);
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
