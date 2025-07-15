<?php
namespace App\Application\Permission\UseCases;

use App\Domain\Permission\Repositories\PermissionRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FindPermissionById {
	private $permissionRepository;

	public function __construct(PermissionRepository $permissionRepository) {
		$this->permissionRepository = $permissionRepository;
	}

	public function handle(string $id) {
		$permission = $this->permissionRepository->findById($id);
		if (!$permission) {
			throw new BadRequestHttpException('Permission with id ' . $id . ' not found');
		}

		return $permission;
	}
}
