<?php
namespace App\Application\PersonType\UseCases;

use App\Domain\PersonType\Entities\PersonTypeEntity;
use App\Domain\PersonType\Repositories\PersonTypeRepository;
use App\Exceptions\NotFoundException;

class GetOnePersonTypeUseCase {
  protected $repo;

  public function __construct(PersonTypeRepository $repo) {
    $this->repo = $repo;
  }

  public function handle(string $id) : PersonTypeEntity {
    $item = $this->repo->findById($id);
    if(!$item) {
      throw new NotFoundException('Type with id ' . $id . ' not found');
    }
    
    return $item;
  }

}