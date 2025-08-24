<?php
namespace App\Domain\User\Entities;

use App\Infraestructure\Persistence\User\EloquentUser;

class UserEntity {
	public function __construct(
		public ?int $id = null,
		public string $name = '',
		public string $email = '',
		public ?string $verifiedAt = null,
		public string $type = 'user',
		public ?string $password = null,
		public ?bool $is_active = null,
		public ?array $roles = [],
		public ?array $permissions = []
	) {}


	public static function fromModel(EloquentUser $user) : self {
		return new self(
			id: $user->id,
			name: $user->name,
			email: $user->email,
			verifiedAt: $user->verifiedAt,
			type: $user->type,
			password: $user->password,
			is_active: $user->is_active,
		);
	}

	public static function fromObj(array $data) : self {
		return new self(
			id: $data['id'] ?? null,
			name: $data['name'] ?? '',
			email: $data['email'] ?? '',
			verifiedAt: $data['verifiedAt'] ?? null,
			type: $data['type'] ?? 'user',
			password: $data['password'] ?? null,
			roles: $data['roles'] ?? [],
			permissions: $data['permissions'] ?? [],
			is_active: $data['is_active'] ?? null,
		);
	}

	public function setRoles(array $roles) : self {
		$this->roles = $roles;
		return $this;
	}

	public function setPermissions(array $permissions) : self {
		$this->permissions = $permissions;
		return $this;
	}

	public function getRoles() : array {
		return $this->roles;
	}

	public function dropSensitiveData() : self {
		unset($this->password);
		unset($this->verifiedAt);
		unset($this->type);

		if(count($this->roles) > 0) {
			$this->roles = array_map(function($role) {
				return $role['key'];
			}, $this->roles);
		}

		if(count($this->permissions) > 0) {
			$this->permissions = array_map(function($permission) {
				return $permission->key;
			}, $this->permissions);
		}
		return $this;
	}

	public function dropPermissions() : self {
		unset($this->permissions);	
		return $this;
	}

	public function dropRoles() : self {
		unset($this->roles);
		return $this;
	}

	public function hidePassword() : self {
		unset($this->password);
		return $this;
	}
}
