<?php
namespace App\Domain\PersonType\Repositories;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\PersonType\Dtos\CreatePersonTypeDto;
use App\Application\PersonType\Dtos\GetPersonTypeDto;
use App\Application\PersonType\Dtos\UpdatePersonTypeDto;
use App\Domain\PersonType\Entities\PersonTypeEntity;

interface PersonTypeRepository {
  public function create(CreatePersonTypeDto $dto) : PersonTypeEntity;
  public function findById(String $id) : PersonTypeEntity | null;
  public function existsByCode(String $code) : bool;
  public function get(PaginationDto $paginationDto, SearchDto $searchDto, GetPersonTypeDto $dto) : array;
  public function update(string $id, UpdatePersonTypeDto $dto) : PersonTypeEntity;
}