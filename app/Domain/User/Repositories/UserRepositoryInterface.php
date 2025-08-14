<?php

namespace App\Domain\User\Repositories;
use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\Entities\UserEntity;

interface UserRepositoryInterface {
	public function create(CreateUserDTO $data);
	public function activateUser(int $userId) : bool;
	public function enableUser(int $userId) : bool;
	public function disableUser(int $userId) : bool;
	public function all();
	public function findByEmail(string $email) : UserEntity | null;
	public function generateToken(UserEntity $user) : string;
	public function findById(int $id) : UserEntity | null;
	public function revokeToken(string $token) : void;
	public function setUserType(int $userId, string $type) : bool;
	public function findOneByUserType(string $type) : UserEntity | null;
}
