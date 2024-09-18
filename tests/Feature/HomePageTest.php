<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test shows home page with user wallet balance
     *
     * @test
     */
    public function test_shows_home_page_with_user_wallet_balance()
    {
        $user = User::factory()->create();

        $user->wallet->update(['balance' => 1000]);

        $this->actingAs($user);

        $response = $this->get(route('home'));

        $response->assertStatus(200);

        $response->assertSeeText('Welcome, ' . $user->name)
            ->assertSeeText('Your Current Balance')
            ->assertSeeText('1,000.00 USD');
    }
}
