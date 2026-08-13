<?php

namespace App\Providers;

use App\Rick\DemoGateway;
use Illuminate\Support\ServiceProvider;
use Rick\Laravel\Application\Execution\Support\Llm\Interface\GatewayBase;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GatewayBase::class, DemoGateway::class);
    }
}
