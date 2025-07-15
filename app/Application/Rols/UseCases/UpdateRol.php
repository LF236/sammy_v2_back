<?php
namespace App\Application\Rols\UseCases;

use App\Application\Rols\DTOs\UpdateRolDto;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\Rols\Repositories\RolsRepository;
use App\Domain\RolsPermission\Repositories\RolsPermissionRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateRol {
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

	public function handle(string $id, UpdateRolDto $dto) {
		$rol = $this->rolRepository->findById($id);

		if (!$rol) {
			throw new BadRequestHttpException('Rol not found');
		}

		$permissionsArray = [];
		if(isset($dto->permissions) && count($dto->permissions) > 0) {
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

		$updatedRol = $this->rolRepository->update($id, $dto);
		
		if(isset($dto->permissions)) {
			if(count($dto->permissions) > 0) {
				// Remove existing permissions
				$this->rolPermissionRepository->deleteManyByRolId($id, $dto->permissions);

				// Add new permissions
				foreach($dto->permissions as $permission_id) {
					$this->rolPermissionRepository->create($id, $permission_id);
				}

				$updatedRol->setPermissions($permissionsArray);
			} else if (count($dto->permissions) === 0) {
				// If no permissions are provided, clear existing permissions
				$this->rolPermissionRepository->deleteManyByRolId($id);
				$updatedRol->setPermissions([]);
			}
		}

		return $updatedRol;
	}
}
