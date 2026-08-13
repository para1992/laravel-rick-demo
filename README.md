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
