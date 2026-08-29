<?php
namespace App\Application\PersonType\UseCases;

use App\Application\Common\Dtos\SearchDto;
use App\Application\PersonType\Dtos\GetPersonTypeDto;
use App\Domain\PersonType\Repositories\PersonTypeRepository;

class CountPersonTypeUseCase {
  protected PersonTypeRepository $repository;

  public function __construct(PersonTypeRepository $repo) {
    $this->repository = $repo;
  }

  public function handle(SearchDto $searchDto, GetPersonTypeDto $dto) {
    return $this->repository->count($searchDto, $dto);
  }
}