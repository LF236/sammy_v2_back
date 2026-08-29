<?php
namespace App\Application\PersonType\UseCases;

use App\Application\PersonType\Dtos\UpdatePersonTypeDto;
use App\Domain\PersonType\Entities\PersonTypeEntity;
use App\Domain\PersonType\Repositories\PersonTypeRepository;
use App\Exceptions\NotFoundException;

class UpdatePersonTypeUseCase {
  protected PersonTypeRepository $repo;

  public function __construct(PersonTypeRepository $repo) {
    $this->repo = $repo;
  }

  public function handle(string $id, UpdatePersonTypeDto $dto) : PersonTypeEntity {
    $item = $this->repo->findById($id);
    if(!$item) {
      throw new NotFoundException('Type with id ' . $id . ' not found');
    }
    return $this->repo->update($id, $dto);
  }
}