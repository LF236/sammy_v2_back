<?php
namespace App\Infraestructure\Persistence\User;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\GetUsersDto;
use App\Application\User\DTOs\UpdateUserDto;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\Person\Entities\PersonEntity;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\UserRole\Repositories\UserRoleRepository;
use App\Infraestructure\Persistence\User\EloquentUser;
use Error;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class EloquentUserRepository implements UserRepositoryInterface {
	private $permissionsRepository;
	private $userRoleRepository;

	public function __construct(PermissionRepository $permissionsRepository, UserRoleRepository $userRoleRepository) {
		$this->permissionsRepository = $permissionsRepository;
		$this->userRoleRepository = $userRoleRepository;
	}

	public function count(SearchDto $searchDto, GetUsersDto $getUsersDto) : int {
		$query = EloquentUser::query('users');
		$search = $searchDto->search;

		if($search) {
			$query = $query->where(function($q) use ($search) {
				$q->where('name', 'like', "%${search}%")
					->orWhere('email', 'like', "%${search}%");
			});
		}

		if($getUsersDto->is_active !== null) {
			$query = $query->where('is_active', $getUsersDto->is_active);
		}

		if($getUsersDto->is_verified !== null) {
			if($getUsersDto->is_verified) {
				$query = $query->whereNotNull('verifiedAt');
			} else {
				$query = $query->whereNull('verifiedAt');
			}
		}

		if($getUsersDto->roles !== null && count($getUsersDto->roles) > 0) {
			$query = $query->whereIn('id', function($q) use ($getUsersDto) {
				$q->select('user_id')
					->from('user_roles')
					->whereIn('role_id', $getUsersDto->roles);
			});
		}


		$count = $query->count();
		if($count) return $count;
		return 0;
	}
	
	public function create(CreateUserDTO $data) {
		return EloquentUser::create([
			'name' => $data->name,
			'email' => $data->email,
			'password' => $data->password,
			'verifiedAt' => null,
			'type' => 'user'
		]);
	}

	public function all(PaginationDto $paginationDto, SearchDto $searchDto, GetUsersDto $getUsersDto) : array {
		$limit = $paginationDto->limit;
		$offset = $paginationDto->offset;
		$search = $searchDto->search;
		$query = EloquentUser::query('users');

		if($search) {
			$query = $query->where(function($q) use ($search) {
				$q->where('name', 'like', "%${search}%")
					->orWhere('email', 'like', "%${search}%");
			});
		}

		if($getUsersDto->is_active !== null) {
			$query = $query->where('is_active', $getUsersDto->is_active);
		}

		if($getUsersDto->is_verified !== null) {
			if($getUsersDto->is_verified) {
				$query = $query->whereNotNull('verifiedAt');
			} else {
				$query = $query->whereNull('verifiedAt');
			}
		}
		
		if($getUsersDto->roles !== null && count($getUsersDto->roles) > 0) {
			$query = $query->whereIn('id', function($q) use ($getUsersDto) {
				$q->select('user_id')
					->from('user_roles')
					->whereIn('role_id', $getUsersDto->roles);
			});
		}

		$query = $query->select('id')
		->offset($offset)
		->limit($limit)
		->pluck('id');
		
		$query = EloquentUser::query('users')
			->whereIn('users.id', $query)
			->leftJoin('user_roles as ur', 'ur.user_id', '=', 'users.id')
			->leftJoin('roles as r', 'r.id', '=', 'ur.role_id')
			->leftJoin('person', 'person.user_id', '=', 'users.id')
			->select(
				'users.id as user_id',
				'users.name as name',
				'users.email as email',
				'users.verifiedAt as verifiedAt',
				'users.type as type',
				'users.password as password',
				'users.is_active as is_active',
				'r.id as role_id',
				'r.name as role_name',
				'r.key as role_key',
				DB::raw("
					CASE
						WHEN person.id IS NOT NULL THEN
							JSON_OBJECT(
								'names', person.names,
								'last_name', person.last_name,
								'second_last_name', person.second_last_name,
								'birth_date', person.birth_date,
								'curp', person.curp,
								'rfc', person.rfc,
								'sex', person.sex,
								'created_at', person.created_at
							)
						ELSE NULL
					END AS person
				")
			)->get();

		$users = $query->groupBy('user_id')->map(function ($role) {
			$fistUser = $role->first();
			$userEntity = UserEntity::fromObj(
				[
					'id' => $fistUser->user_id,
					'name' => $fistUser->name,
					'email' => $fistUser->email,
					'verifiedAt' => $fistUser->verifiedAt,
					'type' => $fistUser->type,
					'password' => $fistUser->password,
					'is_active' => $fistUser->is_active,
				]
			);

			if(isset($fistUser->person)) {
				$personData = json_decode($fistUser->person);
				$personData = PersonEntity::fromObject($personData);
				$personData = $personData->dropInnecesaryData();
				$userEntity->setPerson($personData);
			}

			$roles = $role->filter(fn($row) => $row->role_id !== null)
				->map(fn ($item) => [
					'id' => $item->role_id,
					'name' => $item->role_name,
					'key' => $item->role_key,
				])->values()->toArray();

			$userEntity->setRoles($roles);
			$userEntity->dropPermissions();
			$userEntity->hidePassword();

			return $userEntity;
		})->values()->toArray();

		return $users;
	}

	public function findByEmail(string $email): UserEntity | null {
		$userFromEloquent =  EloquentUser::where('email', $email)->first();
		if(!$userFromEloquent) return null;
		return UserEntity::fromModel($userFromEloquent);
	}

	public function activateUser(int $userId): bool {
		$user = EloquentUser::find($userId);
		if(!$user) return false;
		$user->verifiedAt = now();
		return $user->save();
	}

	public function enableUser(int $userId): bool {
		$user = EloquentUser::find($userId);
		if(!$user) return false;
		$user->is_active = true;
		return $user->save();
	}

	public function disableUser(int $userId): bool {
		$user = EloquentUser::find($userId);
		if(!$user) return false;
		$user->is_active = false;
		return $user->save();
	}

	public function generateToken(UserEntity $user): string {
		$eloquentUser = EloquentUser::find($user->id);
		return $eloquentUser->createToken(
			name: 'auth_token_from_fronted',
			expiresAt: Carbon::now()->addHours((int) env('TOKEN_EXPIRATION_HOURS', 2))
		)->plainTextToken;
	}

	public function findById(int $id): ?UserEntity {
		$userFromEloquent = EloquentUser::query('users')
			->where('users.id', $id)
			->leftJoin('user_roles as ur', 'ur.user_id', '=', 'users.id')
			->leftJoin('roles as r', 'r.id', '=', 'ur.role_id')
			->leftJoin('person', 'person.user_id', '=', 'users.id')
			->select(
				'users.id as user_id',
				'users.name as name',
				'users.email as email',
				'users.verifiedAt as verifiedAt',
				'users.type as type',
				'users.password as password',
				'users.is_active as is_active',
				'r.id as role_id',
				'r.name as role_name',
				'r.key as role_key',
				DB::raw("
					CASE
						WHEN person.id IS NOT NULL THEN
							JSON_OBJECT(
								'names', person.names,
								'last_name', person.last_name,
								'second_last_name', person.second_last_name,
								'birth_date', person.birth_date,
								'curp', person.curp,
								'rfc', person.rfc,
								'sex', person.sex,
								'created_at', person.created_at
							)
						ELSE NULL
					END AS person
				")
			)
		->get();
		
		if(count($userFromEloquent) == 0) {
			return null;
		}

		$mapped = collect($userFromEloquent)->groupBy('user_id')->map(function ($role) {
			$firstUser = $role->first();
			$personData = null;
			$user = UserEntity::fromObj([
				'id' => $firstUser->user_id,
				'name' => $firstUser->name,
				'email' => $firstUser->email,
				'verified_at' => $firstUser->verifiedAt,
				'type' => $firstUser->type,
				'password' => $firstUser->password,
				'is_active' => $firstUser->is_active,

			]);

			if (isset($firstUser->person)) {
				$personData = json_decode($firstUser->person);
				$personData = PersonEntity::fromObject($personData);
				$personData = $personData->dropInnecesaryData();
				$user->setPerson($personData);
			}

			$roles = $role->map(fn($row) => $row->role_id !== null ? $row : null)
				->filter();

			$user->setRoles($roles->map(function ($item) {
				return [
					'id' => $item->role_id,
					'name' => $item->role_name,
					'key' => $item->role_key,
				];
			})->values()->toArray());
			return $user;
		});
		$mapped = $mapped->values()->first();
		$roles = $mapped->getRoles();
		$permission[] = [];
		foreach ($roles as $role) {
			if(!$role['id']) continue;
			$rolePermissions = $this->permissionsRepository->findByRoleId($role['id']);
			if(count($rolePermissions) > 0) {
				$permission[] = $rolePermissions;
			}
		}
		if(count($permission) > 0) {
			$mapped->setPermissions(collect($permission)->flatten(1)->toArray());
		} else {
			$mapped->setPermissions([]);
		}

		return $mapped;	
	}

	public function revokeToken(string $token): void {
		$token = PersonalAccessToken::find($token);
		$token?->delete();
	}

	public function setUserType(int $userId, string $type): bool {
		$user = EloquentUser::find($userId);
		if(!$user) return false;
		$user->type = $type;
		return $user->save();
	}

	public function findOneByUserType(string $type): ?UserEntity {
		$userFromEloquent = EloquentUser::where('type', $type)->first();
		if(!$userFromEloquent) return null;
		return UserEntity::fromModel($userFromEloquent);
	}

	public function update(int $userId, UpdateUserDto $updateUser) : UserEntity {
		DB::beginTransaction();
		try {
			$user = EloquentUser::find($userId);
			if($updateUser->type !== null) {
				$user->type = $updateUser->type;
			}

			$rolesIdsToUpdate = $updateUser->roles_ids ?? [];

			if($updateUser->is_active !== null) {
				$user->is_active = $updateUser->is_active;
			}

			if(count($rolesIdsToUpdate) === 0) {
				$this->userRoleRepository->deleteByUserId($userId);
			} else {
				$this->userRoleRepository->deleteByUserId($userId);
				foreach($rolesIdsToUpdate as $roleId) {
					$this->userRoleRepository->createOne($userId, $roleId);
				}
			}
			$user->save();
			DB::commit();
			return $this->findById($userId) ?? new UserEntity();
		} catch (\Throwable $e) {
			DB::rollBack();
			throw $e;
		}
	}
}
