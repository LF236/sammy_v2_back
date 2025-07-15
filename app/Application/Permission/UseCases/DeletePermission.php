<?php
namespace App\Application\Permission\UseCases;

use App\Domain\Permission\Repositories\PermissionRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeletePermission {
	private $permissionRepository;

	public function __construct(PermissionRepository $permissionRepository) {
		$this->permissionRepository = $permissionRepository;
	}


	public function handle(string $id) : bool {
		// Validate if the permission exists
		$permission = $this->permissionRepository->findById($id);
		if (!$permission) {
			throw new BadRequestHttpException('Permission with id ' . $id . ' not found');
		}

		// Delete the permission
		return $this->permissionRepository->delete($id);
	}
}
