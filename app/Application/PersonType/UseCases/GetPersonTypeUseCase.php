<?php
namespace App\Application\PersonType\UseCases;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\PersonType\Dtos\GetPersonTypeDto;
use App\Domain\PersonType\Repositories\PersonTypeRepository;

class GetPersonTypeUseCase {
  private $personTypeRepo;

  public function __construct(PersonTypeRepository $personTypeRepo) {
    $this->personTypeRepo = $personTypeRepo;
  }

  public function handle(PaginationDto $paginationDto, SearchDto $searchDto, GetPersonTypeDto $dto) : array {
    return $this->personTypeRepo->get($paginationDto, $searchDto, $dto);
  }
}