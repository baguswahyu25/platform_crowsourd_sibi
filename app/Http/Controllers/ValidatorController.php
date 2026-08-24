<?php

namespace App\Http\Controllers;

use App\Services\DatasetService;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ValidatorController extends Controller
{
    public function __construct(
        protected DatasetService $datasetService,
        protected ValidationService $validationService
    ) {}

    public function dashboard(): View
    {
        $pendingDatasets = $this->datasetService->getPendingDatasets();
        $allDatasets = $this->datasetService->getAllDatasets();

        return view('pages.dashboard.validator', compact('pendingDatasets', 'allDatasets'));
    }

    public function antrean(): View
    {
        $pendingDatasets = $this->datasetService->getPendingDatasets();
        return view('pages.validator.antrean', compact('pendingDatasets'));
    }

    public function detail(int $id): View
    {
        $dataset = $this->datasetService->getAllDatasets()->firstWhere('id', $id) ?? (object)[
            'id' => $id,
            'title' => 'Isyarat SIBI - Halo',
            'sign_label' => 'HALO',
            'category' => 'Kata Kunci',
            'status' => 'pending',
            'created_at' => now(),
            'user' => (object)['name' => 'Ahmad Risyad', 'institution' => 'Universitas Indonesia'],
            'file_path' => 'demo/halo.mp4'
        ];

        return view('pages.validator.detail', compact('dataset'));
    }

    public function status(): View
    {
        $allDatasets = $this->datasetService->getAllDatasets();
        return view('pages.validator.status', compact('allDatasets'));
    }

    public function riwayat(): View
    {
        $allDatasets = $this->datasetService->getAllDatasets();
        return view('pages.validator.riwayat', compact('allDatasets'));
    }

    public function processValidation(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:validated,rejected,revision',
            'notes' => 'nullable|string',
        ]);

        $validatorId = auth()->id() ?? 1;
        $this->validationService->validateDataset($id, $validatorId, $request->status, $request->notes);

        return redirect()->route('validator.antrean')->with('success', 'Validasi dataset berhasil diperbarui.');
    }
}
