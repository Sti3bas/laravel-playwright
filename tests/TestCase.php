<?php

namespace Saucebase\LaravelPlaywright\Tests;

use Saucebase\LaravelPlaywright\ServiceProvider;
use Saucebase\LaravelPlaywright\Services\DynamicConfig;
use Saucebase\LaravelPlaywright\Tests\Helpers\Migrations;

class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        Migrations::run();
    }


    protected function tearDown(): void
    {
        parent::tearDown();

        DynamicConfig::delete();
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

}
