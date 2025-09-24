<?php
namespace App\Domain\Permission\Entities;

use App\Infraestructure\Persistence\Permission\EloquentPermission;

class PermissionEntity {
	public function __construct(
		public string $id,
		public string $name,
		public? string $description,
		public? bool $is_active,
		public ?string $key = null,
		public ?array $roles = []
	) {}


	public static function fromEloquentEntity(EloquentPermission $permission) : self {
		return new self(
			$permission->id,
			$permission->name,
			$permission->description,
			$permission->is_active,
			$permission->key,
		);
	}

	public static function fromObject(object $permission) : self {
		return new self(
			$permission->id,
			$permission->name,
			$permission->description ?? null,
			$permission->is_active ?? null,
			$permission->key ?? null,
			$permission->roles ?? []
		);
	}

	public function getId(): string {
		return $this->id;
	}

	public function dropPropertyRol(): void {
		unset($this->roles);
	}
}
