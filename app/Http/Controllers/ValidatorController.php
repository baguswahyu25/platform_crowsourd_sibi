<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
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
        $dataset = Dataset::with(['user', 'datasetNeed', 'validation'])->findOrFail($id);

        return view('pages.validator.detail', compact('dataset'));
    }

    public function status(): View
    {
        $allDatasets = $this->datasetService->getAllDatasets();
        return view('pages.validator.status', compact('allDatasets'));
    }

    public function riwayat(): View
    {
        $validations = \App\Models\Validation::with(['dataset', 'dataset.user', 'validator'])
            ->latest()
            ->get();
        return view('pages.validator.riwayat', compact('validations'));
    }

    public function processValidation(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:validated,rejected',
            'notes' => 'nullable|string',
        ]);

        $validatorId = auth()->id() ?? 1;

        $this->validationService->validateDataset($id, $validatorId, $request->status, $request->notes);

        $msg = ($request->status === 'validated')
            ? 'Dataset berhasil divalidasi oleh Pakar SIBI dan masuk ke Repository Dataset.'
            : 'Dataset ditolak oleh Pakar SIBI dan telah dibersihkan dari antrean.';

        return redirect()->route('validator.antrean')->with('success', $msg);
    }
}
