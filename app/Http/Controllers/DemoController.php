<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Rick\Laravel\Domain\Run\RunStatus;
use Rick\Laravel\Rick;

final class DemoController extends Controller
{
    public function __construct(private readonly Rick $rick) {}

    public function index(): View
    {
        return view('demo', [
            'run' => null,
            'review' => null,
            'metrics' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'task' => ['required', 'string', 'max:500'],
        ]);
        $task = trim($validated['task']);
        $workflow = $this->rick->workflow('choose-a-migration-plan')
            ->resolve($task, 'Five distinct implementation plans are available for human review.')
            ->plan(candidates: 5)
            ->manualJudge()
            ->outputGlue('plan')
            ->build();
        $run = $this->rick->run($workflow);

        return redirect()->route('runs.show', $run->id->toString());
    }

    public function show(string $runId): View
    {
        $run = $this->rick->snapshot($runId);
        $review = $run->status === RunStatus::AwaitingInput
            ? $this->rick->pendingReview($runId)
            : null;

        return view('demo', [
            'run' => $run->toArray(),
            'review' => $review?->toArray(),
            'metrics' => $this->rick->metrics($runId)->toArray(),
        ]);
    }

    public function select(string $runId, string $candidateId): RedirectResponse
    {
        $this->rick->selectCandidate($runId, $candidateId);

        return redirect()
            ->route('runs.show', $runId)
            ->with('selected', 'Candidate selected. Laravel Rick continued the run.');
    }
}
