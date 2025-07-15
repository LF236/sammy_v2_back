<?php
namespace App\Domain\RolsPermission\Repositories;

interface RolsPermissionRepository {
	public function create(string $rol_id, string $permission_id): bool;
	public function delete(string $rol_id, string $permission_id): bool;
	public function deleteManyByRolId(string $rol_id): bool;
}
