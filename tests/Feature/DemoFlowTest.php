<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DemoFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_person_can_generate_five_plans_and_select_one(): void
    {
        $start = $this->post(route('runs.store'), [
            'task' => 'Create a migration plan for a large Laravel application.',
        ]);

        $start->assertRedirect();
        $review = $this->get($start->headers->get('Location'));
        $review->assertOk()
            ->assertSee('Choose a plan')
            ->assertSee('Candidate 1')
            ->assertSee('Candidate 5')
            ->assertSee('Risk-weighted migration');

        self::assertSame(5, substr_count($review->getContent(), 'Select this plan'));
        self::assertMatchesRegularExpression(
            '#action="([^"]+/candidates/[^"]+)"#',
            $review->getContent(),
        );
        preg_match(
            '#action="([^"]+/candidates/[^"]+)"#',
            $review->getContent(),
            $matches,
        );

        $selected = $this->post(html_entity_decode($matches[1]));
        $selected->assertRedirect();
        $completed = $this->get($selected->headers->get('Location'));
        $completed->assertOk()
            ->assertSee('Workflow completed')
            ->assertSee('Strangler rollout')
            ->assertSee('5</strong> calls', escape: false)
            ->assertSee('75</strong> tokens', escape: false);
    }
}
