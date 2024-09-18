<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful top-up.
     *
     * /** @test */
    public function test_successful_top_up()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/wallet/topup', [
            'amount' => 500
        ]);

        $wallet = $user->fresh()->wallet;

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Top-up successful',
                'data' => [
                    'balance' => [
                        'wallet_number' => $wallet->id,
                        'balance' => '500.00',
                        'user' => [
                            'name' => $user->name,
                            'email' => $user->email
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('wallets', [
            'id' => $wallet->id,
            'balance' => 500
        ]);
    }

    /**
     * Test top-up with invalid amount.
     *
     *
     */
    public function test_top_up_with_invalid_amount()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/wallet/topup', [
            'amount' => 'invalid_amount' // Invalid
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test unauthorized top-up.
     *
     */
    public function test_unauthorized_top_up()
    {
        $response = $this->postJson('/api/wallet/topup', [
            'amount' => 500
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test successful withdrawal.
     *
     */
    public function test_successful_withdraw()
    {
        $user = User::factory()->create();

        $wallet = $user->wallet;
        $wallet->balance = 1000;
        $wallet->save();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/wallet/withdraw', [
            'amount' => 500
        ]);

        $wallet = $user->fresh()->wallet;

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Withdrawal successful',
                'data' => [
                    'balance' => [
                        'wallet_number' => $wallet->id,
                        'balance' => '500.00',
                        'user' => [
                            'name' => $user->name,
                            'email' => $user->email
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('wallets', [
            'id' => $wallet->id,
            'balance' => 500
        ]);
    }

    /**
     * Test withdraw with insufficient balance
     *
     * @return void
     */
    public function test_withdraw_with_insufficient_balance()
    {
        $user = User::factory()->create();

        $wallet = $user->wallet;
        $wallet->balance = 100;
        $wallet->save();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/wallet/withdraw', [
            'amount' => 200
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'Withdrawal failed',
                'errors' => [
                    'exception' => 'Insufficient balance'
                ]
            ]);
    }

    /**
     * Test withdrawal with invalid amount.
     */
    public function test_withdraw_with_invalid_amount()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/wallet/withdraw', [
            'amount' => 'invalid_amount' // Invalid
        ]);

        $response->assertStatus(422); // Validation error
    }

    /**
     * Test unauthorized withdrawal.
     */
    public function test_unauthorized_withdraw()
    {
        $response = $this->postJson('/api/wallet/withdraw', [
            'amount' => 500
        ]);

        $response->assertStatus(401);
    }
}
