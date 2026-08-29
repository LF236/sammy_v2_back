<?php
namespace App\Domain\Speciality\Entities;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

class SpecialityEntity {
  public function __construct(
    public string $id,
    public string $code,
    public string $name,
    public ?string $description,
    public bool $is_active,
    public Carbon $created_at,
    public Carbon $updated_at,
    public ?Carbon $deleted_at
  ) {}

  public static function fromObj(object $obj) : SpecialityEntity {
    return new SpecialityEntity(
      id: $obj->id,
      code: $obj->code,
      name: $obj->name,
      description: $obj->description ?? '',
      is_active: $obj->is_active,
      created_at: isset($obj->created_at) ? Date::parse($obj->created_at) : null,
      updated_at: isset($obj->updated_at) ? Date::parse($obj->updated_at) : null,
      deleted_at: isset($obj->deleted_at) ? Date::parse($obj->deleted_at) : null
    );
  }
}