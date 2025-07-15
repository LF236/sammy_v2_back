<?php
namespace App\Application\Rols\UseCases;

use App\Application\Common\Dtos\SearchDto;
use App\Domain\Rols\Repositories\RolsRepository;

class CountRols {
	private $rolRepository;

	public function __construct(RolsRepository $rolRepository) {
		$this->rolRepository = $rolRepository;
	}
	
	public function handle(SearchDto $searchDto) : int {
		return $this->rolRepository->count($searchDto);
	}
}
