<?php

namespace App\Application\MagicToken\Services\Contracts;

use App\Domain\User\Entities\UserEntity;

interface MagicLinkSeenderInterface {
	public function sendToken(UserEntity $user): void;
	public function validateToken(string $token): bool;
	public function generateToken(string $email): void;
}
