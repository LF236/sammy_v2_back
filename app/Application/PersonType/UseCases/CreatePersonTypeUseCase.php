<?php
namespace App\Application\PersonType\UseCases;

use App\Application\PersonType\Dtos\CreatePersonTypeDto;
use App\Domain\PersonType\Repositories\PersonTypeRepository;
use App\Exceptions\ApplicationException;
use App\Exceptions\ConflictException;

class CreatePersonTypeUseCase {
  public function __construct(
    private readonly PersonTypeRepository $personTypeRepository
  ) {}

  public function handle(CreatePersonTypeDto $dto) {
    $findByCode = $this->personTypeRepository->existsByCode($dto->code);
    if($findByCode) {
      throw new ConflictException('A person type with this code alredy exists');
    }
    
    try {
      $newPersonType = $this->personTypeRepository->create($dto);
      return $newPersonType;
    } catch(\Throwable $e) {
      throw new ApplicationException(
        'Could not create person type',
        previous: $e
      );
    }
  }
}