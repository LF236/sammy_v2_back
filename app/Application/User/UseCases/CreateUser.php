<?php
namespace App\Application\User\UseCases;

use App\Application\MagicToken\Services\Contracts\MagicLinkSeenderInterface;
use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\Rols\Repositories\RolsRepository;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\UserRole\Repositories\UserRoleRepository;
use Illuminate\Support\Facades\Hash;

class CreateUser {
	private $userRepository;
	private $magicTokenRepository;
	private $userRoleRepository;
	private $roleRepository;

	public function __construct(
		UserRepositoryInterface $userRepository,
		MagicLinkSeenderInterface $magicTokenRepository,
		RolsRepository $roleRepository,
		UserRoleRepository $userRoleRepository
	) {
		$this->userRepository = $userRepository;
		$this->magicTokenRepository = $magicTokenRepository;
		$this->roleRepository = $roleRepository;
		$this->userRoleRepository = $userRoleRepository;
	}
	
	public function handle(CreateUserDTO $data) {
		$data->password = Hash::make($data->password);
		$newUser = $this->userRepository->create($data);
		$userEntity = UserEntity::fromModel($newUser);
		$this->createUserRoleDefault($userEntity->id);
		$this->magicTokenRepository->sendToken($userEntity);
		$this->userRepository->enableUser($userEntity->id);
	}

	private function createUserRoleDefault(int $userId) {
		$defaultRole = $this->roleRepository->findByKey('default_user');
		if($defaultRole) {
			$this->userRoleRepository->createOne($userId, $defaultRole->id);
		}
	}
}
