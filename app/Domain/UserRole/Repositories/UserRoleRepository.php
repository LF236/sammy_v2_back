<?php
namespace App\Domain\UserRole\Repositories;

interface UserRoleRepository {
	public function createOne(int $userId, string $role) : bool;
	public function deleteByUserId(int $userId) : bool;
	public function getByUserId(int $userId) : ?array;
}
