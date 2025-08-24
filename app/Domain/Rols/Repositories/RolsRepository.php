<?php
namespace App\Domain\Rols\Repositories;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\Rols\DTOs\CreateRolDto;
use App\Application\Rols\DTOs\UpdateRolDto;
use App\Domain\Rols\Entities\RolEntity;

interface RolsRepository {
	public function all(PaginationDto $paginationDto, SearchDto $searchDto): array;
	public function findById(string $id): ?RolEntity;
	public function create(CreateRolDto $data): ?RolEntity;
	public function update(string $id, UpdateRolDto $dto): ?RolEntity;
	public function delete(string $id): bool;
	public function exists(string $id): bool;
	public function count(SearchDto $searchDto);
	public function findByKey(string $key): ?RolEntity;
	public function findByName(string $name): ?RolEntity;
}
