<?php

namespace Database\Seeders;

use App\Application\Rols\DTOs\CreateRolDto;
use App\Domain\Rols\Repositories\RolsRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddRoles extends Seeder {
	private $rolRepository;
	public function __construct(RolsRepository $rolsRepository) {
		$this->rolRepository = $rolsRepository;
	}
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$rols = [
			[
				'name' => 'Super Admin',
				'key' => 'super_admin',
				'description' => 'Super Admin, has all permissions',
				'is_active' => true,
			],
			[
				'name' => 'Admin',
				'key' => 'admin',
				'description' => 'Admin, has most permissions',
				'is_active' => true
			],
			[
				'name' => 'User',
				'key' => 'default_user',
				'description' => 'Default user, has basic permissions',
				'is_active' => true
			]
		];

		foreach ($rols as $rol) {
			$createRolDto = new CreateRolDto(
				$rol['name'],
				$rol['key'],
				$rol['description'],
				$rol['is_active']
			);
			$this->rolRepository->create($createRolDto);
		}
    }
}
