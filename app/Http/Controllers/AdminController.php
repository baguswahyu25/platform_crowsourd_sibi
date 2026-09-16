<?php

namespace App\Http\Controllers;

use App\Enums\DatasetNeedStatus;
use App\Enums\DatasetStatus;
use App\Enums\PriorityLevel;
use App\Models\Dataset;
use App\Models\DatasetNeed;
use App\Services\DatasetNeedService;
use App\Services\DatasetService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected DatasetService $datasetService,
        protected DatasetNeedService $needService
    ) {}

    public function dashboard(): View
    {
        $users = $this->userService->getAllUsers();
        $datasets = $this->datasetService->getAllDatasets();
        $needs = $this->needService->getAllNeeds();

        return view('pages.dashboard.admin', compact('users', 'datasets', 'needs'));
    }

    public function kebutuhan(Request $request): View
    {
        $categoryFilter = $request->query('category', 'all');
        $query = DatasetNeed::query();

        if ($categoryFilter !== 'all') {
            $query->where('category_id', $categoryFilter)
                  ->orWhere('category', $categoryFilter);
        }

        $rawNeeds = $query->get();
        $subOrder = [
            'letters' => 1,
            'numbers' => 2,
            'Kata ganti diri' => 1,
            'Kata kerja (kata dasar)' => 2,
            'Kata benda' => 3,
            'Kata sifat' => 4,
        ];

        $needs = $rawNeeds->sort(function ($a, $b) use ($subOrder) {
            $orderA = $subOrder[$a->subcategory ?? ''] ?? 99;
            $orderB = $subOrder[$b->subcategory ?? ''] ?? 99;

            if ($orderA !== $orderB) {
                return $orderA <=> $orderB;
            }

            return strnatcasecmp($a->title, $b->title);
        })->values();

        $sentenceSubmissions = Dataset::where('category', 'Sentence')
            ->orWhere('subcategory', 'sentence')
            ->orderBy('id', 'desc')
            ->get();

        $shortStorySubmissions = Dataset::where('category', 'Short Story')
            ->orWhere('subcategory', 'short_story')
            ->orderBy('id', 'desc')
            ->get();

        return view('pages.admin.kebutuhan.index', compact('needs', 'categoryFilter', 'sentenceSubmissions', 'shortStorySubmissions'));
    }

    public function storeNeed(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|string|in:alphabet,word,kata_imbuhan,idiom_expression',
            'subcategory' => 'nullable|string|max:255',
            'priority' => 'required|string|in:high,medium,low',
        ]);

        $catId = ($request->category_id === 'idiom_expression') ? 'kata_imbuhan' : $request->category_id;
        $categoryName = match($catId) {
            'alphabet' => 'Abjad',
            'word' => 'Kata',
            'kata_imbuhan' => 'Kata Imbuhan',
            default => 'Kata'
        };

        $adminId = auth()->id() ?? 1;

        DatasetNeed::create([
            'title' => $request->title,
            'category' => $categoryName,
            'category_id' => $catId,
            'subcategory' => $request->subcategory,
            'description' => "Label predefined untuk kategori {$categoryName}.",
            'current_count' => 0,
            'priority' => $request->priority,
            'status' => DatasetNeedStatus::ACTIVE,
            'created_by' => $adminId,
        ]);

        return redirect()->route('admin.kebutuhan.index')->with('success', 'Label predefined kebutuhan dataset berhasil ditambahkan.');
    }

    public function updateNeed(Request $request, int $id): RedirectResponse
    {
        $need = DatasetNeed::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|string|in:high,medium,low',
            'status' => 'required|string|in:active,fulfilled,inactive',
        ]);

        $need->update([
            'title' => $request->title,
            'priority' => $request->priority,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.kebutuhan.index')->with('success', 'Label predefined berhasil diperbarui.');
    }

    public function toggleNeedStatus(int $id): RedirectResponse
    {
        $need = DatasetNeed::findOrFail($id);
        $newStatus = ($need->status->value === 'active') ? DatasetNeedStatus::INACTIVE : DatasetNeedStatus::ACTIVE;
        $need->update(['status' => $newStatus]);

        return redirect()->route('admin.kebutuhan.index')->with('success', 'Status label predefined berhasil diubah.');
    }

    public function destroyNeed(int $id): RedirectResponse
    {
        $need = DatasetNeed::findOrFail($id);
        if ($need->datasets()->count() > 0) {
            $need->update(['status' => DatasetNeedStatus::INACTIVE]);
            return redirect()->route('admin.kebutuhan.index')->with('success', 'Label di-nonaktifkan karena sudah memiliki sampel video.');
        }

        $need->delete();
        return redirect()->route('admin.kebutuhan.index')->with('success', 'Label predefined berhasil dihapus.');
    }

    public function pengguna(): View
    {
        $users = $this->userService->getAllUsers();
        return view('pages.admin.pengguna.index', compact('users'));
    }

    public function laporan(): View
    {
        $datasets = $this->datasetService->getAllDatasets();
        return view('pages.admin.laporan.index', compact('datasets'));
    }
}
