<?php
namespace App\Domain\Permission\Repositories;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\Permission\DTOs\CountPermissionDto;
use App\Application\Permission\DTOs\CreatePermissionDto;
use App\Application\Permission\DTOs\UpdatePermissionDto;
use App\Application\Permission\DTOs\GetPermissionDto;
use App\Domain\Permission\Entities\PermissionEntity;

interface PermissionRepository {
	public function all(PaginationDto $paginationDto, SearchDto $searchDto, GetPermissionDto $getPermissionDto) : array;
	public function count(SearchDto $searchDto, CountPermissionDto $countPermissionDto) : int;
	public function findById(string $id) : PermissionEntity | null;
	public function create(CreatePermissionDto $dto) : PermissionEntity | null;
	public function update(string $id, UpdatePermissionDto $data) : PermissionEntity | null;
	public function delete(string $id) : bool;
	public function findByIds(array $ids) : array;
	public function findByKey(string $key) : PermissionEntity | null;
	public function findByRoleId(string $roleId) : array;
	public function getAllWithoutPagination() : array;
}
