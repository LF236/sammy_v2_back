<?php
namespace App\Infraestructure\Persistence\PersonType;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\PersonType\Dtos\CreatePersonTypeDto;
use App\Application\PersonType\Dtos\GetPersonTypeDto;
use App\Application\PersonType\Dtos\UpdatePersonTypeDto;
use App\Domain\PersonType\Entities\PersonTypeEntity;
use App\Domain\PersonType\Repositories\PersonTypeRepository;
use App\Exceptions\ApplicationException;
use App\Infraestructure\Persistence\PersonType\EloquentPersonType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EloquentPersonTypeRepository implements PersonTypeRepository {
  public function create(CreatePersonTypeDto $dto) : PersonTypeEntity {
    $personType = new EloquentPersonType();
    $personType->name = $dto->name;
    $personType->description = $dto->description;
    $personType->is_active = $dto->is_active;
    $personType->code = $dto->code;
    $personType->save();
    return $this->findById($personType->id);
  }
  
  public function findById(string $id): ?PersonTypeEntity {
    $personType = DB::table(('person_types'))->where('id', $id)->first();
    if(!$personType) {
      return null;
    }
    return PersonTypeEntity::fromObj($personType);
  }

  public function existsByCode(string $code): bool {
    $item = DB::table('person_types')
      ->where('code', $code)
      ->exists();

    return $item;
  }

  public function get(PaginationDto $paginationDto, SearchDto $searchDto, GetPersonTypeDto $dto) : array {
    $query = DB::table('person_types');
    if($searchDto->search) {
      $query->where(function($q) use ($searchDto) {
        $q->whereRaw('LOWER(person_types.name) LIKE ?', ['%' . Str::lower($searchDto->search) . '%'])
          ->orWhereRaw('LOWER(person_types.code) LIKE ?', ['%' . Str::lower($searchDto->search . '%')]);
      });
    }

    if($dto->is_active !== null) {
      $query->where('person_types.is_active', $dto->is_active);
    }

    $query->limit($paginationDto->limit)
      ->offset($paginationDto->offset);

    $query->select([
      'id',
      'code',
      'name',
      'description',
      'is_active',
      'created_at',
      'updated_at',
    ]);

    $query = $query->get();
    return $query->map(function ($item) {
      return PersonTypeEntity::fromObj($item);
    })->toArray();
  }

  public function count(SearchDto $searchDto, GetPersonTypeDto $dto) {
    $query = DB::table('person_types');
    if($searchDto->search) {
      $query->where(function($q) use ($searchDto) {
        $q->whereRaw('LOWER(person_types.name) LIKE ?', ['%' . Str::lower($searchDto->search) . '%'])
          ->orWhereRaw('LOWER(person_types.code) LIKE ?', ['%' . Str::lower($searchDto->search . '%')]);
      });
    }
    
    if($dto->is_active !== null) {
      $query->where('person_types.is_active', $dto->is_active);
    }

    return $query->count();
  }  

  public function update(string $id, UpdatePersonTypeDto $dto): PersonTypeEntity {
    $item = EloquentPersonType::find($id);
    if($dto->name !== null) {
      $item->name = $dto->name;
    }

    if($dto->code !== null) {
      $item->code = $dto->code;
    }

    if($dto->description !== null) {
      $item->description = $dto->description;
    }
    
    if($dto->is_active !== null) {
      $item->is_active = $dto->is_active;
    }

    if(!$item->save()) throw new ApplicationException('Error to update person type');

    return $this->findById($id);
  }
}