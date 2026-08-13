<?php

declare(strict_types=1);

namespace App\Rick;

use Rick\Laravel\Application\Execution\Support\Llm\Interface\GatewayBase;
use Rick\Laravel\Domain\Llm\ValueObject\CompletionMetrics;
use Rick\Laravel\Domain\Llm\ValueObject\CompletionRequest;
use Rick\Laravel\Domain\Llm\ValueObject\CompletionResponse;
use Rick\Laravel\Domain\Metrics\ValueObject\TokenUsage;

final readonly class DemoGateway implements GatewayBase
{
    /** @var list<string> */
    private const PLANS = [
        "Strangler rollout\n\nMove one bounded context at a time behind stable interfaces. Ship adapters first, migrate traffic gradually, and keep rollback paths until production metrics are clean.",
        "Test-first upgrade\n\nFreeze behavior with characterization tests, upgrade framework dependencies in small batches, and make every compatibility change visible in CI before touching architecture.",
        "Parallel application\n\nBuild a clean Laravel application beside the legacy system. Mirror writes, compare reads, and cut over endpoint groups only after both implementations agree.",
        "Domain extraction\n\nExtract the highest-change domain into a framework-free core. Keep Laravel at the boundary, replace infrastructure adapters incrementally, and measure coupling after each release.",
        "Risk-weighted migration\n\nInventory modules by business impact and technical risk. Start with low-risk, high-learning slices, automate rollback, then use the evidence to sequence the remaining work.",
    ];

    public function complete(CompletionRequest $request): CompletionResponse
    {
        $index = $request->metadata['candidate_index'] ?? 0;
        $index = is_int($index) && isset(self::PLANS[$index]) ? $index : 0;
        $content = self::PLANS[$index];

        return new CompletionResponse(
            text: $content,
            structured: ['content' => $content],
            provider: 'demo',
            model: 'deterministic-planner',
            metrics: new CompletionMetrics(
                tokens: new TokenUsage(inputTokens: 10, outputTokens: 5),
                latencyMilliseconds: 1,
            ),
        );
    }
}
