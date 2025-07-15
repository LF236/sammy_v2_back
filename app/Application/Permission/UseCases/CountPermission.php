<?php
namespace App\Application\Permission\UseCases;

use App\Application\Common\Dtos\SearchDto;
use App\Domain\Permission\Repositories\PermissionRepository;

class CountPermission {
	private $repository;

	public function __construct(PermissionRepository $repository) {
		$this->repository = $repository;
	}

	public function handle(SearchDto $search) : int {
		return $this->repository->count($search);
	}
}
