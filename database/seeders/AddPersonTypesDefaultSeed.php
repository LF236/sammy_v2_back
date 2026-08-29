<?php

namespace Database\Seeders;

use App\Application\PersonType\Dtos\CreatePersonTypeDto;
use App\Domain\PersonType\Repositories\PersonTypeRepository;
use Illuminate\Database\Seeder;

class AddPersonTypesDefaultSeed extends Seeder
{
  private PersonTypeRepository $personTypeRepository;

  public function __construct(PersonTypeRepository $repo)
  {
    $this->personTypeRepository = $repo;
  }

  public function run(): void
  {
    $personTypesDefault = [
      [
        'name' => 'Médico',
        'code' => 'med',
        'is_active' => true,
        'description' => 'Médico'
      ],
      [
        'name' => 'Administrativo',
        'code' => 'adm',
        'is_active' => true,
        'description' => 'Personal administrativo'
      ]
    ];

    foreach($personTypesDefault as $personType) {
      $dto = new CreatePersonTypeDto(
        $personType['name'],
        $personType['code'],
        $personType['is_active'],
        $personType['description']
      );

      $this->personTypeRepository->create($dto);
    }
  }
}
