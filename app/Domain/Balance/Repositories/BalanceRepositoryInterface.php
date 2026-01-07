<?php
namespace App\Domain\Balance\Repositories;

interface BalanceRepositoryInterface
{
    public function getBalance(int $userId): int;
    public function updateBalance(int $userId, int $newBalance): void;
}

