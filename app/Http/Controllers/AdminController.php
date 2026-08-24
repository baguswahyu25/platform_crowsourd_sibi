<?php

namespace App\Http\Controllers;

use App\Services\DatasetNeedService;
use App\Services\DatasetService;
use App\Services\UserService;
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

    public function kebutuhan(): View
    {
        $needs = $this->needService->getAllNeeds();
        return view('pages.admin.kebutuhan.index', compact('needs'));
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
