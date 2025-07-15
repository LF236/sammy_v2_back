<?php
namespace App\Infraestructure\Persistence\User;

use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infraestructure\Persistence\User\EloquentUser;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

class EloquentUserRepository implements UserRepositoryInterface {
	private $permissionsRepository;

	public function __construct(PermissionRepository $permissionsRepository) {
		$this->permissionsRepository = $permissionsRepository;
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

	public function all() {
		// Implementation for retrieving all users
		return [];
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
			->join('user_roles as ur', 'ur.user_id', '=', 'users.id')
			->join('roles as r', 'r.id', '=', 'ur.role_id')
			->select(
				'users.id as user_id',
				'users.name as name',
				'users.email as email',
				'users.verifiedAt as verifiedAt',
				'users.type as type',
				'users.password as password',
				'r.id as role_id',
				'r.name as role_name',
				'r.key as role_key'
			)
			->get();
		
		if(count($userFromEloquent) == 0) {
			return null;
		}


		$mapped = collect($userFromEloquent)->groupBy('id')->map(function ($role) {
			$firstUser = $role->first();
			$user = UserEntity::fromObj([
				'id' => $firstUser->user_id,
				'name' => $firstUser->name,
				'email' => $firstUser->email,
				'verified_at' => $firstUser->verified_at,
				'type' => $firstUser->type,
				'password' => $firstUser->password,
			]);
			$user->setRoles(
				$role->map(function ($item) {
					return [
						'id' => $item->role_id,
						'name' => $item->role_name,
						'key' => $item->role_key,
					];
				})->toArray()
			);

			return $user;
		});
		$mapped = $mapped->values()->first();
		$roles = $mapped->getRoles();
		$permission[] = [];
		foreach ($roles as $role) {
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
}
