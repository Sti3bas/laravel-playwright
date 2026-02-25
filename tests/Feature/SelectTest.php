<?php

namespace Saucebase\LaravelPlaywright\Tests\Feature;

use Saucebase\LaravelPlaywright\Tests\Helpers\UserModel;
use Saucebase\LaravelPlaywright\Tests\TestCase;

class SelectTest extends TestCase
{
    public function testSelectsAUser(): void
    {
        $user = UserModel::factory()->create();

        $response = $this->postJson('/playwright/select', [
            'query' => 'select * from users where id = ' . $user->id
        ]);

        $response->assertOk();
        $response->assertJsonPath('0.id', $user->id);
    }
}