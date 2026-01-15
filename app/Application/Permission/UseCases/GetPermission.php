<?php
namespace App\Application\Permission\UseCases;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\Permission\DTOs\GetPermissionDto;
use App\Domain\Permission\Repositories\PermissionRepository;

class GetPermission {
	private $repository;
	
	public function __construct(PermissionRepository $repository) {
		$this->repository = $repository;
	}
	// 1 = true, 0 = false
	public function handle(PaginationDto $pagination, SearchDto $search, GetPermissionDto $getPermissionDto) {
		$permissions = $this->repository->all($pagination, $search, $getPermissionDto);

		if(empty($permissions)) {
			return null;
		}

		return [
			'permissions' => $permissions
		];
	}

}
