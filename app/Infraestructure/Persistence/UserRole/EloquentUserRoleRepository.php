<?php
namespace App\Infraestructure\Persistence\UserRole;

use App\Domain\UserRole\Repositories\UserRoleRepository;

class EloquentUserRoleRepository implements UserRoleRepository {
	public function createOne(int $userId, string $role) : bool {
		$userRole = new EloquentUserRole();
		$userRole->user_id = $userId;
		$userRole->role_id = $role;
		return $userRole->save();
	}

	public function deleteByUserId(int $userId) : bool {
		EloquentUserRole::where('user_id', $userId)->delete();
		return true;
	}

	public function getByUserId(int $userId) : ?array {
		$userRole = EloquentUserRole::where('user_id', $userId)->get();
		$userRoles[] = [];	

		foreach ($userRole as $role) {
			$userRoles[] = [
				'user_id' => $role->user_id,
				'role_id' => $role->role,
			];
		}
		return $userRoles ?: null;
	}
}
