<?php
namespace App\Application\Rols\UseCases;

use App\Domain\Rols\Repositories\RolsRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeleteRole {
	private $roleRepository;

	public function __construct(RolsRepository $roleRepository) {
		$this->roleRepository = $roleRepository;
	}


	public function handle(string $roleId) : void {
		// Check if the role exists
		if (!$this->roleRepository->exists($roleId)) {
			throw new BadRequestHttpException('Role with id ' . $roleId . ' does not exists');
		}

		// Delete the role
		$this->roleRepository->delete($roleId);
	}
}
