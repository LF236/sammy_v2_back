<?php
namespace App\Application\Rols\UseCases;

use App\Domain\Rols\Repositories\RolsRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FindOneRole
{
	private $repository;

	public function __construct(RolsRepository $repository)
	{
		$this->repository = $repository;
	}

	public function handle(string $id)
	{
		$role = $this->repository->findById($id);
		if (!$role) {
			throw new BadRequestHttpException('Role with ID ' . $id . ' not found.');
		}
		return $role;
	}
}
