<?php
namespace App\Infraestructure\Persistence\RolsPermission;

use App\Domain\RolsPermission\Repositories\RolsPermissionRepository;
use App\Infraestructure\Persistence\RolsPermission\EloquentRolPermission;

class EloquentRolPermissionRepository implements RolsPermissionRepository {
	public function create(string $rol_id, string $permission_id): bool {
		$rolPermission = new EloquentRolPermission();
		$rolPermission->role_id = $rol_id;
		$rolPermission->permission_id = $permission_id;
		return $rolPermission->save();
	}

	public function delete(string $rol_id, string $permission_id): bool {
		$rolPermission = new EloquentRolPermission();	
		return $rolPermission->where('rol_id', $rol_id)
			->where('permission_id', $permission_id)
			->delete();
	}

	public function deleteManyByRolId(string $rol_id): bool {
		$rolPermission = new EloquentRolPermission();
		return $rolPermission->where('role_id', $rol_id)->delete();
	}
}
