<?php
namespace App\Domain\Rols\Entities;

class RolEntity {
	public function __construct(
		public string $id,
		public string $name,
		public string $key,
		public ?string $description = null,
		public bool $is_active = false,
		public ?array $permissions = []
	) {}

	public static function fromObj(array $data): self {
		return new self(
			id: $data['id'],
			name: $data['name'],
			key: $data['key'],
			description: $data['description'] ?? null,
			is_active: $data['is_active'] ?? false,
			permissions: $data['permissions'] ?? []
		);
	}

	public function setPermissions(array $permissions): void {
		$this->permissions = $permissions;
	}
}
