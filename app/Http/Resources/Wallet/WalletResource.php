<?php

namespace App\Http\Resources\Wallet;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'wallet_number' => $this->id,
            'balance' => number_format($this->balance, 2),
            'user' => new UserResource($this->user),
        ];
    }
}
