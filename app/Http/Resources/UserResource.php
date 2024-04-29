<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->method() === 'POST') {
            return [
                'token' => $this->token,
                'name' => $this->name,
                'email' => $this->email,
                'balance' => $this->wallet ? number_format($this->wallet, 2) : 0.00,
                'credit' => TransactionResource::collection($this->credit),
                'debit' => TransactionResource::collection($this->debit),
            ];
        } else {
            return [
                'name' => $this->name,
                'email' => $this->email,
                'balance' => $this->wallet ? number_format($this->wallet, 2) : 0.00,
                'credit' => TransactionResource::collection($this->credit),
                'debit' => TransactionResource::collection($this->debit),
            ];
        }
    }
}
