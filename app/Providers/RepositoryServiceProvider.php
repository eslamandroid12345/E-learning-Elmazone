<?php

namespace App\Providers;

use App\Http\Interfaces\AuthRepositoryInterface;
use App\Http\Interfaces\CommentRepositoryInterface;
use App\Http\Interfaces\DegreeExamsDetailsRepositoryInterface;
use App\Http\Interfaces\ReportRepositoryInterface;
use App\Http\Repositories\AuthRepository;
use App\Http\Repositories\CommentRepository;
use App\Http\Repositories\DegreeExamsDetailsRepository;
use App\Http\Repositories\ReportRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    public function register(){

        $this->app->bind(AuthRepositoryInterface::class,AuthRepository::class);
        $this->app->bind(ReportRepositoryInterface::class,ReportRepository::class);
        $this->app->bind(CommentRepositoryInterface::class,CommentRepository::class);
        $this->app->bind(DegreeExamsDetailsRepositoryInterface::class,DegreeExamsDetailsRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
