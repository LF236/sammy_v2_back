<?php

namespace App\Console\Commands;

use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\Rols\Repositories\RolsRepository;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\UserRole\Repositories\UserRoleRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
	protected $userRepository;
	protected $roleRepository;
	protected $userRoleRepository;

	public function __construct(
		UserRepositoryInterface $userRepository,
		RolsRepository $roleRepository,
		UserRoleRepository $userRoleRepository
	) {
		parent::__construct();
		$this->userRepository = $userRepository;
		$this->roleRepository = $roleRepository;
		$this->userRoleRepository = $userRoleRepository;
	}
	

	protected $signature = 'app:create-admin-user';

   
    protected $description = 'This command creates an Super Admin with the specified credentials.';



	private function rules(): array {
		return [
			'name' => 'required|string|max:255|min:3|unique:users,name',
			'email' => 'required|email|max:255|unique:users,email',
			'password' => 'required|string|min:8',
			'confirm_password' => 'required|string|same:password|min:8',
		];
	}

    public function handle()
    {
		$name = $this->ask('User Name for the Super Admin User');
		$email = $this->ask('Enter the email for the Super Admin User');
		$password = $this->secret('Enter the password for the Super Admin User');
		$confirmPassword = $this->secret('Confirm the password for the Super Admin User');

		$validator = Validator::make([
			'name' => $name,
			'email' => $email,
			'password' => $password,
			'confirm_password' => $confirmPassword
		], $this->rules());	

		if($validator->fails()) {
			$this->error('Validation failed:');
			foreach ($validator->errors()->all() as $error) {
				$this->error($error);
			}
			return 1;
		}

		$data = new CreateUserDTO(
			$name,
			$email,
			$password
		);
		

		$data->password = Hash::make($data->password);
		$newUser = $this->userRepository->create($data);
		$userEntity = UserEntity::fromModel($newUser);
		$this->createUserRoleSuperAdmin($userEntity->id);
	}

	private function createUserRoleSuperAdmin(int $userId) {
		$defaultRole = $this->roleRepository->findByKey('super_admin');
		if($defaultRole) {
			$this->userRoleRepository->createOne($userId, $defaultRole->id);
		}
	}
}
