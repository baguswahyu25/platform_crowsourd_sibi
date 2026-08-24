<?php

namespace App\Providers;

use App\Repositories\Contracts\DatasetNeedRepositoryInterface;
use App\Repositories\Contracts\DatasetRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\DatasetNeedRepository;
use App\Repositories\Eloquent\DatasetRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DatasetRepositoryInterface::class, DatasetRepository::class);
        $this->app->bind(DatasetNeedRepositoryInterface::class, DatasetNeedRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
