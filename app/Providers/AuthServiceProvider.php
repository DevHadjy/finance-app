<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Despesa;
use App\Policies\DespesaPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Despesa::class => DespesaPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}