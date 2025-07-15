<?php
namespace App\Domain\MagicToken\Repositories;

use App\Application\MagicToken\Entities\MagicTokenEntity;
use App\Domain\User\Entities\UserEntity;

interface MagicTokenRepositoryInterface {
	public function create(UserEntity $user, int $expiresIn = 15): string;
	public function validate(string $token) : MagicTokenEntity | null;
	public function findLastByUserId(int $userId): ?MagicTokenEntity;
}
