<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Wallet\WalletService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletWebTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test web successful topup
     *
     * @return void
     */
    public function test_web_successful_topup()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('wallet.topUp.submit'), [
            'amount' => 100
        ]);

        $response->assertRedirect(route('wallet.topUp.submit'))
            ->assertSessionHas('success', 'Top-up successful! Your new balance is 100');

        $this->assertDatabaseHas('wallets', [
            'user_id' => $user->id,
            'balance' => 100
        ]);
    }

    /**
     * Test web failed topup duo to exception
     *
     * @return void
     */
    public function test_web_failed_topup_due_to_exception()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mock(WalletService::class, function ($mock) {
            $mock->shouldReceive('topUp')->andThrow(new Exception('Top-up failed'));
        });

        $response = $this->post(route('wallet.topUp.submit'), [
            'amount' => 100
        ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['error' => 'Top-up failed. Please try again later.']);
    }

    /**
     * Test web successful withdraw
     *
     * @return void
     */
    public function test_web_successful_withdraw()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $user->wallet->update([
            'user_id' => $user->id,
            'balance' => 1000
        ]);

        $response = $this->post(route('wallet.withdraw.submit'), [
            'amount' => 500
        ]);

        $response->assertRedirect(route('wallet.withdraw'))
            ->assertSessionHas('success', 'Withdrawal successful! Your new balance is 500');

        $this->assertDatabaseHas('wallets', [
            'user_id' => $user->id,
            'balance' => 500
        ]);
    }
}
