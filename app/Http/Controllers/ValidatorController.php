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
    ) {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user) {
                return redirect()->route('auth.login')->withErrors([
                    'email' => 'Silakan masuk (login) terlebih dahulu untuk mengakses halaman Validator / Pakar SIBI.',
                ]);
            }

            $role = is_object($user->role) ? $user->role->value : $user->role;
            if ($role !== 'validator') {
                abort(403, 'Akses ditolak. Halaman validasi ini khusus untuk pengguna dengan hak akses Validator / Pakar SIBI.');
            }

            return $next($request);
        });
    }

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

        $dataset = Dataset::find($id);
        if ($dataset) {
            $dataset->update([
                'status' => $request->status,
                'rejection_reason' => ($request->status === 'rejected' || $request->status === 'revision') ? $request->notes : null
            ]);
        }

        $this->validationService->validateDataset($id, $validatorId, $request->status, $request->notes);

        return redirect()->route('validator.antrean')->with('success', 'Keputusan evaluasi validasi dataset berhasil disimpan.');
    }
}
