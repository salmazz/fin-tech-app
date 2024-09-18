<?php

namespace App\Http\Resources\Wallet;

use App\Common\Enums\Wallet\TransactionTypesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'wallet_number' => $this->wallet_id,
            'amount' => number_format($this->amount, 2),
            'type' => $this->type,
            'fee' => $this->fee !== null ? number_format($this->fee, 2) : '0.00',
            'sender' => new WalletResource($this->wallet),
            'recipient' => $this->when($this->type === TransactionTypesEnum::TRANSFER, function () {
                return new WalletResource($this->recipientWallet);
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
