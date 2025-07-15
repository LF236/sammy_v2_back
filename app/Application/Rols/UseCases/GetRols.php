<?php
namespace App\Application\Rols\UseCases;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Domain\Rols\Repositories\RolsRepository;

class GetRols {
	private $rolRepository;
	public function __construct(RolsRepository $rolsRepository) {
		$this->rolRepository = $rolsRepository;
	}

	public function handle(PaginationDto $paginationDto, SearchDto $searchDto) {
		$rols = $this->rolRepository->all($paginationDto, $searchDto);
		if (count($rols) === 0) {
			return [];
		}
		return $rols;
	}
}
