<?php

namespace App\Http\Controllers;

use App\Services\DatasetNeedService;
use App\Services\DatasetService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContributorController extends Controller
{
    public function __construct(
        protected DatasetService $datasetService,
        protected DatasetNeedService $needService
    ) {}

    public function dashboard(): View
    {
        $userId = auth()->id() ?? 1;
        $myDatasets = $this->datasetService->getUserDatasets($userId);
        $needs = $this->needService->getActiveNeeds();

        return view('pages.dashboard.contributor', compact('myDatasets', 'needs'));
    }

    public function datasets(): View
    {
        $userId = auth()->id() ?? 1;
        $myDatasets = $this->datasetService->getUserDatasets($userId);
        return view('pages.contributor.dataset.index', compact('myDatasets'));
    }

    public function upload(Request $request): View
    {
        $needs = $this->needService->getAllNeeds();
        $selectedNeed = null;

        if ($request->has('need_id')) {
            $selectedNeed = $needs->firstWhere('id', (int) $request->query('need_id'));
        } elseif ($request->has('label')) {
            $selectedNeed = $needs->firstWhere('title', $request->query('label'));
        }

        return view('pages.contributor.dataset.upload', compact('needs', 'selectedNeed'));
    }

    public function kebutuhan(): View
    {
        $needs = $this->needService->getAllNeeds();
        return view('pages.contributor.kebutuhan.index', compact('needs'));
    }
}
