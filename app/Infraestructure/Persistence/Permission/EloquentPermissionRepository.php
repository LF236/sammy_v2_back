<?php
namespace App\Infraestructure\Persistence\Permission;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\Permission\DTOs\CreatePermissionDto;
use App\Application\Permission\DTOs\GetPermissionDto;
use App\Application\Permission\DTOs\UpdatePermissionDto;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\Permission\Entities\PermissionEntity;
use App\Infraestructure\Persistence\Permission\EloquentPermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EloquentPermissionRepository implements PermissionRepository {
	public function all(PaginationDto $pagination, SearchDto $search, GetPermissionDto $getPermissionDto) : array {
		$query = EloquentPermission::query();

		if ($search->search) {
			$query->whereRaw('LOWER(permissions.name) LIKE ?', ['%' . Str::lower($search->search) . '%'])
				->orWhereRaw('LOWER(permissions.key) LIKE ?', ['%' . Str::lower($search->search) . '%']);
		}

		if ($getPermissionDto->is_active !== null) {
			$query->where('permissions.is_active', $getPermissionDto->is_active);
		}

		$query->limit($pagination->limit)
			->offset($pagination->offset);
		
		$query = $query->leftJoin('role_permission as rp', 'rp.permission_id', '=', 'permissions.id')
				 ->leftJoin('roles as r', 'r.id', '=', 'rp.role_id');

		if(count($getPermissionDto->roles_ids) > 0) {
			$query->whereIn('r.id', $getPermissionDto->roles_ids);
		}

		$query->select([
			'permissions.id as id',
			'permissions.name as name',
			'permissions.description as description',
			'permissions.is_active as is_active',
			'permissions.key as key',
			DB::raw('JSON_ARRAYAGG(JSON_OBJECT("id", r.id, "name", r.name, "description", r.description, "is_active", r.is_active, "key", r.key)) as roles'),
		]);

		$query = $query->groupBy('permissions.id', 'permissions.name', 'permissions.description', 'permissions.is_active', 'permissions.key');

		$permissions = $query->get();

		if ($permissions->isEmpty()) {
			return [];
		}

		return $permissions->map(function ($permission) {
			$roles = json_decode($permission->roles, true) ?? [];
			$firsRole = $roles[0] ?? null;

			if($firsRole && isset($firsRole['id'])) {
				$permission->roles = $roles;
			} else {
				$permission->roles = [];
			}
			
			return PermissionEntity::fromObject($permission);
		})->toArray();
	}

	public function count(SearchDto $search) : int {
		$query = EloquentPermission::query();

		if ($search->search) {
			$query->whereRaw('LOWER(name) LIKE ?', ['%' . Str::lower($search->search) . '%'])
				->orWhereRaw('LOWER(description) LIKE ?', ['%' . Str::lower($search->search) . '%']);
		}

		return $query->count();
	}	

	public function findById(string $id) : PermissionEntity | null {
		$permission = EloquentPermission::query()
			->leftJoin('role_permission as rp', 'rp.permission_id', '=', 'permissions.id')
			->leftJoin('roles as r', 'r.id', '=', 'rp.role_id')
			->where('permissions.id', $id)
			->where('permissions.deleted_at', null)
			->where('r.deleted_at', null)
			->select([
				'permissions.id as id',
				'permissions.name as name',
				'permissions.description as description',
				'permissions.is_active as is_active',
				'permissions.key as key',
				DB::raw('JSON_ARRAYAGG(JSON_OBJECT("id", r.id, "name", r.name, "description", r.description, "is_active", r.is_active, "key", r.key)) as roles'),
			])
			->groupBy('permissions.id', 'permissions.name', 'permissions.description', 'permissions.is_active', 'permissions.key')
			->first();

		if (!$permission) return null;
		if($permission->roles) {
			$roles = json_decode($permission->roles, true) ?? [];
			$firsRole = $roles[0] ?? null;

			if($firsRole && isset($firsRole['id'])) {
				$permission->roles = $roles;
			} else {
				$permission->roles = [];
			}
		} else {
			$permission->roles = [];
		}

		return PermissionEntity::fromObject($permission);
	}

	public function create(CreatePermissionDto $dto) : PermissionEntity | null {
		$permission = EloquentPermission::create($dto->toArray());
		if(!$permission) return null;
		
		return PermissionEntity::fromEloquentEntity(
			$permission
		);
	}

	public function update(string $id, UpdatePermissionDto $data) : PermissionEntity | null {
		$permission = EloquentPermission::find($id);
		if (!$permission) return null;

		if ($data->name) {
			$permission->name = $data->name;
		}
		if ($data->key) {
			$permission->key = $data->key;
		}
		if ($data->description) {
			$permission->description = $data->description;
		}
		if ($data->is_active !== null) {
			$permission->is_active = $data->is_active;
		}

		if (!$permission->save()) return null;

		return $this->findById($id);
	}

	public function delete(string $id) : bool {
		$permission = EloquentPermission::find($id);
		if (!$permission) return false;

		return $permission->delete();
	}

	public function findByIds(array $ids) : array {
		$permissions = EloquentPermission::query()
			->whereIn('id', $ids)
			->select([
				'id',
				'name',
			])
			->get();

		if ($permissions->isEmpty()) {
			return [];
		}

		return $permissions->map(function ($permission) {
			return PermissionEntity::fromEloquentEntity($permission);
		})->toArray();
	}

	public function findByKey(string $key) : PermissionEntity | null {
		$permission = EloquentPermission::query()
			->where('key', $key)
			->select([
				'id',
				'name',
				'description',
				'is_active',
				'key'
			])
			->first();

		if (!$permission) return null;
		
		return PermissionEntity::fromEloquentEntity($permission);
	}

	public function findByRoleId(string $roleId) : array {
		$permissions = EloquentPermission::query()
			->join('role_permission', 'role_permission.permission_id', '=', 'permissions.id')
			->where('role_permission.role_id', $roleId)
			->select([
				'permissions.id',
				'permissions.name',
				'permissions.description',
				'permissions.is_active',
				'permissions.key'
			])
			->get();

		if ($permissions->isEmpty()) {
			return [];
		}

		return $permissions->map(function ($permission) {
			return PermissionEntity::fromEloquentEntity($permission);
		})->toArray();
	}

	public function getAllWithoutPagination() : array {
		$permissions = EloquentPermission::query()
			->select([
				'id',
				'name',
				'description',
				'is_active',
				'key'
			])
			->get();

		if ($permissions->isEmpty()) {
			return [];
		}

		return $permissions->map(function ($permission) {
			return PermissionEntity::fromEloquentEntity($permission);
		})->toArray();
	}
}
