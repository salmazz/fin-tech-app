<?php

namespace Tests\Feature;

use App\Common\Enums\Wallet\TransactionTypesEnum;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test paginated transactions retrieval for an authenticated user.
     *
     * @return void
     */
    public function test_transactions_pagination()
    {
        $user = User::factory()->create();

        Transaction::factory()->count(15)->create([
            'wallet_id' => $user->wallet->id,
            'amount' => 100,
            'type' => TransactionTypesEnum::WITHDRAW
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/wallet/transactions');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'data',
                'links',
                'meta' => [
                    'current_page',
                    'last_page',
                    'from',
                    'to',
                    'total',
                    'per_page'
                ]
            ]
        ]);

        $this->assertCount(10, $response->json('data.data'));

        $this->assertEquals(15, $response->json('data.meta.total'));

        $this->assertEquals(1, $response->json('data.meta.current_page'));
        $this->assertEquals(2, $response->json('data.meta.last_page'));
    }

    /**
     * Test unauthorized transaction retrieval
     *
     * @return void
     */
    public function test_unauthorized_transaction_retrieval()
    {
        $response = $this->getJson('/api/wallet/transactions');

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }
}
