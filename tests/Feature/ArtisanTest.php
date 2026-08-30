<?php

namespace Saucebase\LaravelPlaywright\Tests\Feature;

use Illuminate\Support\Facades\Process;
use Saucebase\LaravelPlaywright\Tests\TestCase;

class ArtisanTest extends TestCase
{

    public function testRunsArtisanCommand(): void
    {

        /** @var array<string|int> $json */
        $json = $this->post('playwright/artisan', [
            'command' => 'route:list'
        ])
            ->assertOk()
            ->json();

        $this->assertEquals(0, $json['code']);
        $this->assertStringContainsString('playwright/artisan', (string) $json['output']);

    }

    public function testScoutPrefixUsesWorkerPrefixConfig(): void
    {
        config()->set('laravel-playwright.worker_prefix', 'my_worktree');

        Process::fake();

        $this->post('playwright/artisan', [
            'command' => 'typesense:init',
        ], ['X-Playwright-Worker' => '2'])
            ->assertOk();

        Process::assertRan(function ($process) {
            return ($process->environment['SCOUT_PREFIX'] ?? null) === 'my_worktree_2_'
                && ($process->environment['DB_CONNECTION'] ?? null) === 'playwright_2';
        });
    }

}