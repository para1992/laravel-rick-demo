<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A zero-configuration human-in-the-loop demo for Laravel Rick.">
    <title>Laravel Rick Demo</title>
    <style>
        :root { color-scheme: dark; --bg: #090d13; --panel: #111823; --line: #273244; --text: #edf3fa; --muted: #91a1b5; --red: #ff5f57; --amber: #ffbd2e; --green: #28c840; --accent: #8b5cf6; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: radial-gradient(circle at 75% 0%, #241443 0, transparent 34rem), var(--bg); color: var(--text); font: 16px/1.55 ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        a { color: inherit; }
        .shell { width: min(1120px, calc(100% - 32px)); margin: 0 auto; padding: 34px 0 64px; }
        nav, .hero, .bar, .stats, .pipeline { display: flex; align-items: center; }
        nav { justify-content: space-between; margin-bottom: 72px; }
        .brand { font-weight: 800; letter-spacing: -.04em; text-decoration: none; }
        .brand span, .eyebrow, .arrow { color: #b69cff; }
        .repo { color: var(--muted); font-size: 13px; text-underline-offset: 4px; }
        .hero { align-items: flex-start; justify-content: space-between; gap: 42px; margin-bottom: 40px; }
        .copy { max-width: 690px; }
        .eyebrow { font-size: 12px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 12px 0 18px; font: 800 clamp(42px, 7vw, 76px)/.98 ui-sans-serif, system-ui, sans-serif; letter-spacing: -.065em; }
        h1 em { color: #b69cff; font-style: normal; }
        .lead { max-width: 650px; margin: 0; color: #b6c2d1; font: 19px/1.65 ui-sans-serif, system-ui, sans-serif; }
        .zero { flex: 0 0 auto; margin-top: 32px; padding: 14px 18px; border: 1px solid #4c3a6f; border-radius: 12px; background: #171025; color: #d7c6ff; font-size: 13px; }
        .terminal { overflow: hidden; border: 1px solid var(--line); border-radius: 16px; background: rgba(17, 24, 35, .92); box-shadow: 0 24px 90px #0008; }
        .bar { gap: 8px; padding: 13px 16px; border-bottom: 1px solid var(--line); background: #0e141d; }
        .dot { width: 11px; height: 11px; border-radius: 50%; background: var(--red); }
        .dot:nth-child(2) { background: var(--amber); } .dot:nth-child(3) { background: var(--green); }
        .bar small { margin-left: 8px; color: #657388; }
        .inside { padding: clamp(22px, 5vw, 42px); }
        label { display: block; margin-bottom: 10px; color: var(--muted); font-size: 13px; }
        textarea { width: 100%; min-height: 108px; resize: vertical; padding: 17px; border: 1px solid #344155; border-radius: 10px; outline: none; background: #0a1018; color: var(--text); font: inherit; }
        textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px #8b5cf628; }
        button, .new { display: inline-flex; align-items: center; justify-content: center; gap: 10px; margin-top: 16px; padding: 13px 18px; border: 0; border-radius: 9px; background: #8b5cf6; color: white; cursor: pointer; font: 800 14px ui-monospace, monospace; text-decoration: none; }
        button:hover, .new:hover { background: #9d74fa; transform: translateY(-1px); }
        .error { margin-top: 8px; color: #ff8d87; font-size: 13px; }
        .pipeline { flex-wrap: wrap; gap: 12px; margin-bottom: 34px; color: var(--muted); font-size: 13px; }
        .pipeline b { padding: 8px 11px; border: 1px solid #303c50; border-radius: 7px; color: #d7e0eb; font-weight: 600; }
        .run-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 30px; }
        h2 { margin: 0 0 8px; font: 800 28px/1.2 ui-sans-serif, system-ui, sans-serif; letter-spacing: -.035em; }
        .task { margin: 0; color: var(--muted); font: 15px/1.6 ui-sans-serif, system-ui, sans-serif; }
        .status { flex: 0 0 auto; padding: 7px 11px; border: 1px solid #5b467b; border-radius: 999px; background: #23153a; color: #d5c0ff; font-size: 11px; text-transform: uppercase; }
        .notice { margin-bottom: 24px; padding: 12px 14px; border: 1px solid #266e3a; border-radius: 8px; background: #102719; color: #89e79a; font-size: 13px; }
        .candidates { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .candidate { display: flex; flex-direction: column; min-height: 255px; padding: 22px; border: 1px solid #2b3749; border-radius: 12px; background: #0d141e; }
        .candidate:hover { border-color: #6f55a0; background: #101724; }
        .candidate h3 { margin: 0 0 13px; font: 750 17px ui-sans-serif, system-ui, sans-serif; }
        .candidate p { flex: 1; margin: 0; color: #aab7c7; white-space: pre-line; font: 14px/1.65 ui-sans-serif, system-ui, sans-serif; }
        .candidate button { width: 100%; background: #263246; }
        .candidate button:hover { background: #8b5cf6; }
        .result { padding: 25px; border: 1px solid #2f6541; border-radius: 12px; background: #0d1d14; }
        .result h3 { margin: 0 0 16px; color: #7ce495; }
        .result p { margin: 0; color: #cbd8ce; white-space: pre-line; font-family: ui-sans-serif, system-ui, sans-serif; }
        .stats { gap: 12px; margin-top: 26px; flex-wrap: wrap; }
        .stat { padding: 10px 13px; border: 1px solid #293549; border-radius: 8px; color: var(--muted); font-size: 12px; }
        .stat strong { color: var(--text); }
        footer { margin-top: 30px; color: #637087; font-size: 12px; text-align: center; }
        @media (max-width: 760px) { nav { margin-bottom: 45px; } .hero { display: block; } .zero { display: inline-block; } .candidates { grid-template-columns: 1fr; } .run-head { display: block; } .status { display: inline-block; margin-top: 14px; } }
    </style>
</head>
<body>
<main class="shell">
    <nav>
        <a class="brand" href="{{ route('demo') }}"><span>rickphp/</span>laravel-rick-demo</a>
        <a class="repo" href="https://github.com/para1992/laravel-rick" rel="noreferrer">package source ↗</a>
    </nav>

    <section class="hero">
        <div class="copy">
            <div class="eyebrow">Human-in-the-loop workflow</div>
            <h1>Generate five plans.<br><em>You pick the winner.</em></h1>
            <p class="lead">A runnable Laravel Rick example: fan out into five candidates, pause for human review, then continue with the selected result.</p>
        </div>
        @if (config('demo.gateway') === 'deterministic')
            <div class="zero">$0 · no API key · deterministic</div>
        @else
            <div class="zero">live · {{ config('demo.provider') }} · billable API</div>
        @endif
    </section>

    <section class="terminal">
        <div class="bar"><i class="dot"></i><i class="dot"></i><i class="dot"></i><small>php artisan serve</small></div>
        <div class="inside">
            <div class="pipeline"><b>resolve</b><span class="arrow">→</span><b>plan × 5</b><span class="arrow">→</span><b>manualJudge</b><span class="arrow">→</span><b>outputGlue</b></div>

            @if ($run === null)
                <form method="post" action="{{ route('runs.store') }}">
                    @csrf
                    <label for="task">Migration task</label>
                    <textarea id="task" name="task" required maxlength="500">{{ old('task', 'Create a migration plan for a large Laravel application.') }}</textarea>
                    @error('task')<div class="error">{{ $message }}</div>@enderror
                    <button type="submit">Run workflow <span>→</span></button>
                </form>
            @else
                @if (session('selected'))<div class="notice">{{ session('selected') }}</div>@endif
                <div class="run-head">
                    <div><h2>{{ $run['status'] === 'completed' ? 'Selected result' : 'Choose a plan' }}</h2><p class="task">{{ $run['task'] }}</p></div>
                    <span class="status">{{ str_replace('_', ' ', $run['status']) }}</span>
                </div>

                @if ($review && $review['exists'])
                    <div class="candidates">
                        @foreach ($review['candidates'] as $candidate)
                            <article class="candidate">
                                <h3>{{ $candidate['title'] }}</h3>
                                <p>{{ $candidate['content'] }}</p>
                                <form method="post" action="{{ route('runs.select', [$run['id'], $candidate['id']]) }}">
                                    @csrf
                                    <button type="submit">Select this plan <span>→</span></button>
                                </form>
                            </article>
                        @endforeach
                    </div>
                @elseif ($run['status'] === 'completed')
                    <div class="result"><h3>✓ Workflow completed</h3><p>{{ $run['output'] }}</p></div>
                @else
                    <div class="result"><h3>Run status: {{ $run['status'] }}</h3><p>Refresh this page after the queue worker processes the continuation.</p></div>
                @endif

                <div class="stats">
                    <span class="stat"><strong>{{ $metrics['totals']['calls'] }}</strong> calls</span>
                    <span class="stat"><strong>{{ $metrics['totals']['tokens']['total_tokens'] }}</strong> tokens</span>
                    <span class="stat"><strong>{{ $run['version'] }}</strong> transitions</span>
                    <span class="stat">run <strong>{{ substr($run['id'], 0, 8) }}</strong></span>
                </div>
                <a class="new" href="{{ route('demo') }}">Start another run</a>
            @endif
        </div>
    </section>
    <footer>Laravel 12/13 · PHP 8.3+ · powered by rickphp/laravel-rick</footer>
</main>
</body>
</html>
