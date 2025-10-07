<?php

namespace App\Domain\User\Repositories;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\GetUsersDto;
use App\Application\User\DTOs\UpdateUserDto;
use App\Domain\User\Entities\UserEntity;

interface UserRepositoryInterface {
	public function create(CreateUserDTO $data);
	public function update(int $userId, UpdateUserDto $updateUserDto) : UserEntity;
	public function activateUser(int $userId) : bool;
	public function enableUser(int $userId) : bool;
	public function disableUser(int $userId) : bool;
	
	/**
     * @return UserEntity[]
	*/
	public function all(PaginationDto $paginationDto, SearchDto $searchDto, GetUsersDto $getUsersDto) : array;

	public function findByEmail(string $email) : UserEntity | null;
	public function generateToken(UserEntity $user) : string;
	public function findById(int $id) : UserEntity | null;
	public function revokeToken(string $token) : void;
	public function setUserType(int $userId, string $type) : bool;
	public function findOneByUserType(string $type) : UserEntity | null;
	public function count(SearchDto $searchDto, GetUsersDto $getUsersDto) : int;
}
