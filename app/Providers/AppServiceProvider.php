<?php

namespace App\Providers;

use App\Rick\DemoGateway;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Rick\Laravel\Application\Execution\Support\Llm\Interface\GatewayBase;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $gateway = config('demo.gateway');
        if ($gateway === 'laravel-ai') {
            config()->set('rick.llm.models.medium', [
                'provider' => config('demo.provider'),
                'model' => config('demo.model'),
            ]);

            return;
        }
        if ($gateway !== 'deterministic') {
            throw new InvalidArgumentException(
                'RICK_DEMO_GATEWAY must be deterministic or laravel-ai.',
            );
        }

        $this->app->singleton(GatewayBase::class, DemoGateway::class);
    }
}
