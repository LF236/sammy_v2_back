<?php
namespace App\Application\Permission\UseCases;

use App\Application\Permission\DTOs\UpdatePermissionDto;
use App\Domain\Permission\Repositories\PermissionRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdatePermission {
	private $permissionRepository;

	public function __construct(PermissionRepository $permissionRepository) {
		$this->permissionRepository = $permissionRepository;
	}

	public function handle(string $id, UpdatePermissionDto $dto) {
		$findPermission = $this->permissionRepository->findById($id);
		if(!$findPermission) {
			throw new BadRequestHttpException('Permission with id ' . $id . ' not found');	
		}

		return $this->permissionRepository->update($id, $dto);	
	}
}
