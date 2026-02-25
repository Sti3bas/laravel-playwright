<?php

namespace Saucebase\LaravelPlaywright\Tests\Feature;

use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;
use Saucebase\LaravelPlaywright\ServiceProvider;
use Saucebase\LaravelPlaywright\Tests\TestCase;

class SecurityTest extends TestCase
{

    private function reloadRoutes(): void
    {
        assert($this->app !== null);
        Route::setRoutes(new RouteCollection());
        (new ServiceProvider($this->app))->boot();
    }

    public function testRequestPassesWithoutSecretConfigured(): void
    {
        // tearDown just deletes a file — no DB dependency, always succeeds
        $this->postJson('/playwright/tearDown')
            ->assertOk();
    }

    public function testRequestBlockedWithoutSecretHeader(): void
    {
        config(['laravel-playwright.secret' => 'test123']);
        $this->reloadRoutes();

        $this->postJson('/playwright/tearDown')
            ->assertUnauthorized();
    }

    public function testRequestBlockedWithWrongSecret(): void
    {
        config(['laravel-playwright.secret' => 'test123']);
        $this->reloadRoutes();

        $this->postJson('/playwright/tearDown', [], [
            'X-Playwright-Secret' => 'wrong-secret',
        ])->assertUnauthorized();
    }

    public function testRequestPassesWithCorrectSecretHeader(): void
    {
        config(['laravel-playwright.secret' => 'test123']);
        $this->reloadRoutes();

        $this->postJson('/playwright/tearDown', [], [
            'X-Playwright-Secret' => 'test123',
        ])->assertOk();
    }

    public function testRequestPassesWithSecretInBody(): void
    {
        config(['laravel-playwright.secret' => 'test123']);
        $this->reloadRoutes();

        $this->postJson('/playwright/tearDown', [
            '_secret' => 'test123',
        ])->assertOk();
    }

}
