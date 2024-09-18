<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserWalletTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create wallet for each newly registered user
     *
     * @test
     */
    public function test_creates_wallet_for_each_newly_registered_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'testuser@example.com')->first();

        $this->assertNotNull($user->wallet, 'Wallet was not created for the new user');

        $this->assertEquals(0, $user->wallet->balance);
    }
}
