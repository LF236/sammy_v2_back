<?php
namespace App\Application\Permission\UseCases;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Domain\Permission\Repositories\PermissionRepository;

class GetPermission {
	private $repository;
	
	public function __construct(PermissionRepository $repository) {
		$this->repository = $repository;
	}

	public function handle(PaginationDto $pagination, SearchDto $search) {
		$permissions = $this->repository->all($pagination, $search);

		if(empty($permissions)) {
			return null;
		}

		return [
			'permissions' => $permissions
		];
	}

}
