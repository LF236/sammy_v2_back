<?php

namespace App\Infraestructure\Persistence\MagicToken;

use App\Application\MagicToken\Entities\MagicTokenEntity;
use App\Domain\MagicToken\Repositories\MagicTokenRepositoryInterface;
use App\Domain\User\Entities\UserEntity;
use App\Infraestructure\Persistence\MagicToken\EloquentMaginToken;

class EloquentMaginTokenRepository implements MagicTokenRepositoryInterface {
	public function create(UserEntity $user, int $expiresIn = 15) : string {
		$newToken = EloquentMaginToken::create([
			'user_id' => $user->id,
			'expires_at' => now()->addMinutes($expiresIn),
			'used' => false,
		]);

		return $newToken->id;
	}

	public function validate(string $token) : MagicTokenEntity | null {
		$magicToken = EloquentMaginToken::from('magic_tokens as mt')
			->where('mt.id', $token)
			->where('mt.used', false)
			->where('mt.expires_at', '>=', now())
			->first();

		if(!$magicToken) {
			return null;
		}

		$magicToken->used = true;
		$magicToken->save();
		$magicTokenEntity = MagicTokenEntity::fromEloquentEntity($magicToken);
		return $magicTokenEntity;
	}

	public function findLastByUserId(int $userId) : ?MagicTokenEntity {
		$tokenFromEloquent = EloquentMaginToken::where('user_id', $userId)
			->orderBy('created_at', 'desc')
			->first();

		if(!$tokenFromEloquent) {
			return null;
		}

		return MagicTokenEntity::fromEloquentEntity($tokenFromEloquent);
	}
}
