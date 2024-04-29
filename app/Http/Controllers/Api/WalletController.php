<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\BuyCookieRequest;
use App\Http\Requests\Api\WalletRequest;
use App\Http\Resources\WalletResource;
use App\Models\Transaction;
use DB;

class WalletController extends ApiController
{
    public function addMoney(WalletRequest $request)
    {
        try {
            $user = auth()->user();

            $amount = $request->amount;

            $user->wallet += $amount;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'type' => 'credit'
            ]);

            $response = [
                'message' => 'Money added successfully in wallet',
                'balance' => new WalletResource($user)
            ];
            return $this->showMessage($response, 200);
        } catch (\Exception $e) {
            \Log::error('Error while adding money: ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    public function buyCookie(BuyCookieRequest $request)
    {
        try {
            $user = auth()->user();

            if ($user->wallet < $request->noofcookie) {
                return $this->error('Insufficient funds', 400);
            }

            $user->wallet -= $request->noofcookie;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'amount' => $request->noofcookie,
                'type' => 'debit'
            ]);

            $response = [
                'message' => 'Cookie bought successfully',
                'data' => new WalletResource($user)
            ];
            return $this->showMessage($response, 200);
        } catch (\Exception $e) {
            \Log::error('Error buying cookie: ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }
}
