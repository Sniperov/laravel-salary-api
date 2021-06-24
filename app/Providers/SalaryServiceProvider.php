<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SalaryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('calculateSalary', 'App\Services\SalaryService');
    }
}