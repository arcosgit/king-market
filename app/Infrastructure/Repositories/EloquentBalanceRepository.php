<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Balance\Repositories\BalanceRepositoryInterface;
use App\Models\BalanceModel;

class EloquentBalanceRepository implements BalanceRepositoryInterface
{
    public function getBalance(int $userId): int
    {
        $balance = BalanceModel::where('user_id', $userId)->first();
        return $balance ? (int) $balance->amount : 0;
    }

    public function updateBalance(int $userId, int $newBalance): void
    {
        BalanceModel::where('user_id', $userId)->update(['amount' => $newBalance]);
    }
}

