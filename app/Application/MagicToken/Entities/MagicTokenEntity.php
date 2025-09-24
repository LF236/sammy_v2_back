<?php
namespace App\Application\MagicToken\Entities;

use App\Infraestructure\Persistence\MagicToken\EloquentMaginToken;

class MagicTokenEntity {
	public function __construct(
		public ?string $id = null,
		public ?int $user_id = null,
		public ?string $expires_at = null,
		public ?bool $used = false
	) {}

	public static function fromEloquentEntity(EloquentMaginToken $token): self {
		return new self(
			id: $token->id,
			user_id: $token->user_id,
			expires_at: $token->expires_at,
			used: $token->used
		);
	}
}
