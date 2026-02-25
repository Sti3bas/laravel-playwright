<?php

namespace Saucebase\LaravelPlaywright\Tests\Feature;

use Saucebase\LaravelPlaywright\Tests\TestCase;

class TravelTest extends TestCase
{

    public function testTravel(): void
    {

        $this->postJson('/playwright/travel', [
            'to' => '2025-01-01',
        ])->assertOk();

        $this->reloadApplication();
        $this->assertEquals('2025-01-01', now()->format('Y-m-d'));

    }

}