<?php

namespace Database\Seeders;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\Permission\DTOs\UpdatePermissionDto;
use App\Application\Rols\DTOs\UpdateRolDto;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\Rols\Repositories\RolsRepository;
use App\Domain\RolsPermission\Repositories\RolsPermissionRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RelationRolesPermissions extends Seeder
{
	private $rolsRepository;
	private $permissionRepository;
	private $rolesPermissionsRepository;

	public function __construct(PermissionRepository $permissionRepository, RolsRepository $rolsRepository, RolsPermissionRepository $rolsPermissionRepository) {
		$this->rolsRepository = $rolsRepository;
		$this->permissionRepository = $permissionRepository;
		$this->rolesPermissionsRepository = $rolsPermissionRepository;
	}

	/**
     * Run the database seeds.
     */
    public function run(): void
    {
		//
		$this->fillSuperAdmin();
		$this->fillAdmin();
		$this->fillUser();
	}

	private function fillSuperAdmin() {
		$super = $this->rolsRepository->findByKey('super_admin');
		$allPermissions = $this->permissionRepository->getAllWithoutPagination();

		$ids = array_map(function($permission) {
			return $permission->getId();
		}, $allPermissions);

		foreach($ids as $id) {
			$this->rolesPermissionsRepository->create(
				$super->id,
				$id
			);
		}
	}

	private function fillAdmin() {
		$admin = $this->rolsRepository->findByKey('admin');
		$default_permission = $this->permissionRepository->findByKey('show_panel');

		$this->rolesPermissionsRepository->create(
			$admin->id,
			$default_permission->getId()
		);
	}

	private function fillUser() {
		$user = $this->rolsRepository->findByKey('default_user');
		$default_permission = $this->permissionRepository->findByKey('show_panel');

		$this->rolesPermissionsRepository->create(
			$user->id,
			$default_permission->getId()
		);
	}
}
