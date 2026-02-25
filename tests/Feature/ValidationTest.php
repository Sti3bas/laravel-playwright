<?php

namespace Saucebase\LaravelPlaywright\Tests\Feature;

use Saucebase\LaravelPlaywright\Tests\TestCase;

class ValidationTest extends TestCase
{

    // factory

    public function testFactoryRequiresModel(): void
    {
        $this->postJson('/playwright/factory', [])
            ->assertUnprocessable();
    }

    public function testFactoryRejectsNonExistentClass(): void
    {
        $this->postJson('/playwright/factory', [
            'model' => 'NonExistentModelClass',
        ])->assertUnprocessable();
    }

    public function testFactoryRejectsModelWithoutFactory(): void
    {
        $this->postJson('/playwright/factory', [
            'model' => 'stdClass',
        ])->assertUnprocessable();
    }

    // query

    public function testQueryRequiresQuery(): void
    {
        $this->postJson('/playwright/query', [])
            ->assertUnprocessable();
    }

    // function

    public function testFunctionRejectsNonCallable(): void
    {
        $this->postJson('/playwright/function', [
            'function' => 'this_function_does_not_exist_anywhere',
        ])->assertUnprocessable();
    }

    // truncate

    public function testTruncateRejectsNonArrayConnections(): void
    {
        $this->postJson('/playwright/truncate', [
            'connections' => 'not-an-array',
        ])->assertUnprocessable();
    }

}
