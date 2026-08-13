<?php

declare(strict_types=1);

return [
    'gateway' => env('RICK_DEMO_GATEWAY', 'deterministic'),
    'provider' => env('RICK_DEMO_PROVIDER', 'openrouter'),
    'model' => env('RICK_DEMO_MODEL', 'google/gemini-3.5-flash-lite'),
];
