<?php

namespace Database\Seeders;

use App\Application\Permission\DTOs\CreatePermissionDto;
use App\Domain\Permission\Repositories\PermissionRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddPermission extends Seeder {
	private $permissionRepository;

	public function __construct(PermissionRepository $permissionRepository) {
		$this->permissionRepository = $permissionRepository;
	}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		//
		$permissions = [
			[
				'key' => 'show_panel',
				'name' => 'Show Panel',
				'description' => 'Permission to show the panel',
				'is_active' => true
			],
			[
				'key' => 'show_users',
				'name' => 'Show Users',
				'description' => 'Permission to show users',
				'is_active' => true
			],
			[
				'key' => 'edit_users',
				'name' => 'Edit Users',
				'description' => 'Permission to edit users',
				'is_active' => true
			],
			[
				'key' => 'delete_users',
				'name'=> 'Delete Users',
				'description' => 'Permission to delete users',
				'is_active' => true
			],
			[
				'key' => 'create_users',
				'name' => 'Create Users',
				'description' => 'Permission to create users',
				'is_active' => true
			],
			[
				'key' => 'show_roles',
				'name' => 'Show Roles',
				'description' => 'Permission to show roles',
				'is_active' => true
			],
			[
				'key' => 'edit_roles',
				'name' => 'Edit Roles',
				'description' => 'Permission to edit roles',
				'is_active' => true
			],
			[
				'key' => 'delete_roles',
				'name'=> 'Delete Roles',
				'description' => 'Permission to delete roles',
				'is_active' => true
			],
			[
				'key' => 'create_roles',
				'name' => 'Create Roles',
				'description' => 'Permission to create roles',
				'is_active' => true
			],
			[
				'key' => 'show_permissions',
				'name' => 'Show Permissions',
				'description' => 'Permission to show permissions',
				'is_active' => true
			],
			[
				'key' => 'edit_permissions',
				'name' => 'Edit Permissions',
				'description' => 'Permission to edit permissions',
				'is_active' => true
			],
			[
				'key' => 'delete_permissions',
				'name'=> 'Delete Permissions',
				'description' => 'Permission to delete permissions',
				'is_active' => true
			],
			[
				'key' => 'create_permissions',
				'name' => 'Create Permissions',
				'description' => 'Permission to create permissions',
				'is_active' => true
			]
		];

		foreach ($permissions as $permission) {
			$dto = new CreatePermissionDto(
				$permission['key'],
				$permission['name'],
				$permission['description'],
				$permission['is_active']
			);

			$this->permissionRepository->create($dto);
		}
    }
}
