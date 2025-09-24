<?php
namespace App\Application\Rols\UseCases;

use App\Application\Rols\DTOs\CreateRolDto;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\Rols\Repositories\RolsRepository;
use App\Domain\RolsPermission\Repositories\RolsPermissionRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateRol {
	private $rolRepository;
	private $permissionRepository;
	private $rolPermissionRepository;
	public function __construct(
		RolsRepository $rolRepository,
		PermissionRepository $permissionRepository,
		RolsPermissionRepository $rolsPermissionRepository
	) {
		$this->rolRepository = $rolRepository;
		$this->permissionRepository = $permissionRepository;
		$this->rolPermissionRepository = $rolsPermissionRepository;
	}

	public function handle(CreateRolDto $dto) {
		$permissionsArray = [];
		if(count($dto->permissions) > 0) {
			$permissions = $this->permissionRepository->findByIds($dto->permissions);
			if(count($permissions) !== count($dto->permissions)) {
				$ids_not_found = array_diff($dto->permissions, array_map(function($permission) {
					return $permission->id;
				}, $permissions));
				throw new BadRequestHttpException(
					'Permissions not found: ' . implode(', ', $ids_not_found)
				);

			}

			$permissionsArray = $permissions;
		}

		$rol = $this->rolRepository->create($dto);

		if(count($dto->permissions) > 0) {
			foreach($dto->permissions as $permission_id) {
				$this->rolPermissionRepository->create($rol->id, $permission_id);
			}

			$rol->setPermissions($permissionsArray);
		}

		return $rol;
	}
}
