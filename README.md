# Laravel Rick Demo

A runnable human-in-the-loop example for [`rickphp/laravel-rick`](https://github.com/para1992/laravel-rick).

It creates five independent migration plans, pauses for a human decision, and continues the workflow with the selected result. The bundled deterministic gateway needs no API key and makes the demo free to run.

## Run it

```bash
git clone https://github.com/para1992/laravel-rick-demo.git
cd laravel-rick-demo
composer setup
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000).

## Use a real model (optional)

The default `deterministic` gateway is free, needs no credentials, and always
returns the same five plans. To run the exact workflow through OpenRouter,
create an API key in the [OpenRouter dashboard](https://openrouter.ai/settings/keys)
and change these values in your local `.env`:

```dotenv
RICK_DEMO_GATEWAY=laravel-ai
RICK_DEMO_PROVIDER=openrouter
RICK_DEMO_MODEL=google/gemini-3.5-flash-lite
OPENROUTER_API_KEY=your-key-here
```

Then clear cached configuration and start the app:

```bash
php artisan config:clear
php artisan serve
```

Each demo run generates five candidates, so live mode makes five billable
provider requests. You can choose another model from the
[OpenRouter model catalog](https://openrouter.ai/models). Other providers
supported by the [Laravel AI SDK](https://laravel.com/docs/13.x/ai-sdk) work by
changing `RICK_DEMO_PROVIDER`, `RICK_DEMO_MODEL`, and the corresponding provider
API key in `.env`.

## The workflow

```php
$workflow = $rick->workflow('choose-a-migration-plan')
    ->resolve($task, 'Five distinct implementation plans are available for human review.')
    ->plan(candidates: 5)
    ->manualJudge()
    ->outputGlue('plan')
    ->build();

$run = $rick->run($workflow);
$review = $rick->pendingReview($run->id);
$rick->selectCandidate($run->id, $review->candidates[0]->id);
```

Laravel Rick handles the workflow state, fan-out, persistence, review barrier, continuation, metrics, and final output.

## Test

```bash
composer test
```

MIT licensed.
