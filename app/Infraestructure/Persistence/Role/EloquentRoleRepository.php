<?php
namespace App\Infraestructure\Persistence\Role;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\Rols\DTOs\CreateRolDto;
use App\Application\Rols\DTOs\UpdateRolDto;
use App\Domain\Permission\Entities\PermissionEntity;
use App\Domain\Rols\Entities\RolEntity;
use App\Domain\Rols\Repositories\RolsRepository;
use Illuminate\Support\Facades\DB;

class EloquentRoleRepository implements RolsRepository {

	public function all(PaginationDto $paginationDto, SearchDto $searchDto) : array {
		$query = EloquentRole::query();
		if($searchDto->search) {
			$query->whereRaw('LOWER(roles.name) LIKE ?', ['%' . strtolower($searchDto->search) . '%'])
				->orWhereRaw('LOWER(roles.description) LIKE ?', ['%' . strtolower($searchDto->search) . '%']);
		}

		$rolesIds = $query->select('id')
			->limit($paginationDto->limit)
			->offset($paginationDto->offset)
			->pluck('id');


		$permissionsQuery = DB::table('roles')
			->leftJoin('role_permission as rp', 'rp.role_id', '=', 'roles.id')
			->leftJoin('permissions as p', 'p.id', '=', 'rp.permission_id')
			->whereIn('roles.id', $rolesIds)
			->select(
				'roles.id as id',
				'roles.name as name',
				'roles.key as key',
				'roles.description as description',
				'roles.is_active as is_active',
				'p.id as permission_id',
				'p.name as permission_name',
				'p.key as permission_key',
				'p.description as permission_description',
				'p.is_active as permission_is_active'
			)
			->get();
	
		if($rolesIds->isEmpty()) {
			return [];
		}
		
		$aux = collect($permissionsQuery)->groupBy('id')->map(function ($group) {
			$firstRole = $group->first();
			$role = RolEntity::fromObj([
				'id' => $firstRole->id,
				'name' => $firstRole->name,
				'key' => $firstRole->key,
				'description' => $firstRole->description,
				'is_active' => $firstRole->is_active,
			]);

			$permission = $group->map(function ($item) {
				if($item->permission_id && $item->permission_id !== null) {	
					$parsePermission = PermissionEntity::fromObject(
						(object) [
							'id' => $item->permission_id,
							'name' => $item->permission_name,
							'key' => $item->permission_key,
							'description' => $item->permission_description,
							'is_active' => $item->permission_is_active
						]
					);
					$parsePermission->dropPropertyRol();
					return $parsePermission;
				}
			})->filter();

			if(count($permission) === 0) {
				$role->setPermissions([]);
			} else {
				$role->setPermissions($permission->toArray());
			}
			return $role;
		})->values();
		return $aux->toArray();
	}

	public function findById(string $id) : ?RolEntity {
		$rol = EloquentRole::query();
		$rol->leftJoin('role_permission as rp', 'rp.role_id', '=', 'id');
		$rol->leftJoin('permissions as p', 'p.id', '=', 'rp.permission_id');

		$rol->where('roles.id', $id);

		$rol->select(
			'roles.id as id',
			'roles.name as name',
			'roles.key as key',
			'roles.description as description',
			'roles.is_active as is_active',
			'p.id as permission_id',
			'p.name as permission_name',
			'p.key as permission_key',
			'p.description as permission_description',
			'p.is_active as permission_is_active'
		);

		$rol = $rol->get();
		if(!$rol) {
			return null;
		}

		$aux = collect($rol)->groupBy('id')->map(function ($group) {
			$firstRole = $group->first();
			$role = RolEntity::fromObj([
				'id' => $firstRole->id,
				'name' => $firstRole->name,
				'key' => $firstRole->key,
				'description' => $firstRole->description,
				'is_active' => $firstRole->is_active,
			]);

			$permission = $group->map(function ($item) {
				if($item->permission_id) {	
					return PermissionEntity::fromObject(
						(object) [
							'id' => $item->permission_id,
							'name' => $item->permission_name,
							'key' => $item->permission_key,
							'description' => $item->permission_description,
							'is_active' => $item->permission_is_active
						]
					);
				}
			});

			if(count($permission) === 0) {
				$role->setPermissions([]);
			} else {
				$role->setPermissions($permission->toArray());
			}
			return $role;
		})->values();

		$first = $aux->first();
		if(!$first) {
			return null;
		}

		return $first;
	}

	public function create(CreateRolDto $data) : ?RolEntity {
		$rol = new EloquentRole();
		$rol->name = $data->name;
		$rol->key = $data->key;
		$rol->description = $data->description ?? null;
		$rol->is_active = $data->is_active;
		$rol->save();

		return RolEntity::fromObj([
			'id' => $rol->id,
			'name' => $rol->name,
			'key' => $rol->key,
			'description' => $rol->description,
			'is_active' => $rol->is_active,
			'permissions' => []
		]);
		
	}

	public function update(string $id, UpdateRolDto $data) : ?RolEntity {
		$rol = EloquentRole::find($id);

		if($data->name !== null) {
			$rol->name = $data->name;
		}

		if($data->key !== null) {
			$rol->key = $data->key;
		}

		if($data->description !== null) {
			$rol->description = $data->description;
		}

		if($data->is_active !== null) {
			$rol->is_active = $data->is_active;
		}

		$rol->save();
		return $rol ? RolEntity::fromObj([
			'id' => $rol->id,
			'name' => $rol->name,
			'key' => $rol->key,
			'description' => $rol->description,
			'is_active' => $rol->is_active,
			'permissions' => []
		]) : null;
	
	}

	public function delete(string $id) : bool {
		return EloquentRole::where('id', $id)->delete();			
	}

	public function exists(string $id) : bool {
		return EloquentRole::where('id', $id)->exists();
	}

	public function findByKey(string $key) : ?RolEntity {
		$rol = EloquentRole::where('key', $key)->first();
		if(!$rol) {
			return null;
		}
		return RolEntity::fromObj([
			'id' => $rol->id,
			'name' => $rol->name,
			'key' => $rol->key,
			'description' => $rol->description,
			'is_active' => $rol->is_active,
			'permissions' => []
		]);
	}

	public function count(SearchDto $searchDto) : int {
		$query = EloquentRole::query();
		if($searchDto->search) {
			$query->whereRaw('LOWER(roles.name) LIKE ?', ['%' . strtolower($searchDto->search) . '%'])
				->orWhereRaw('LOWER(roles.description) LIKE ?', ['%' . strtolower($searchDto->search) . '%']);
		}
		return $query->count();
	}
}
