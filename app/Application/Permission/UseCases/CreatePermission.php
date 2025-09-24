<?php
namespace App\Application\Permission\UseCases;

use App\Application\Permission\DTOs\CreatePermissionDto;
use App\Domain\Permission\Entities\PermissionEntity;
use App\Domain\Permission\Repositories\PermissionRepository;

class CreatePermission {
	private $permissionRepository;
	
	public function __construct(PermissionRepository $permissionRepository) {
		$this->permissionRepository = $permissionRepository;
		
	}


	public function handle(CreatePermissionDto $dto) : PermissionEntity | null {
		$permission = $this->permissionRepository->create($dto);
		if ($permission) {
			return $permission;
		}
		return null;
	}

}
