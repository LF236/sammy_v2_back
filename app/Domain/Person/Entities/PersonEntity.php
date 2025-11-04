<?php
namespace App\Domain\Person\Entities;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;

class PersonEntity {
    public function __construct(
        public ?string $id,
        public ?int $user_id,
        public string $names,
        public string $last_name,
        public ?string $second_last_name,
        public ?Carbon $birth_date,
        public string $curp,
        public ?string $rfc,
        public ?string $sex,
        public ?Carbon $created_at,
        public ?Carbon $updated_at,
        public ?Carbon $deleted_at
    ) {}

    public static function fromObject(object $obj): PersonEntity {
        return new PersonEntity(
            id: $obj->id ?? null,
            user_id: $obj->user_id ?? null,
            names: $obj->names,
            last_name: $obj->last_name,
            second_last_name: $obj->second_last_name ?? null,
            birth_date: isset($obj->birth_date) ? Date::parse($obj->birth_date) : null,
            curp: $obj->curp,
            rfc: $obj->rfc ?? null,
            sex: $obj->sex ?? null,
            created_at: isset($obj->created_at) ? Date::parse($obj->created_at) : null,
            updated_at: isset($obj->updated_at) ? Date::parse($obj->updated_at) : null,
            deleted_at: isset($obj->deleted_at) ? Date::parse($obj->deleted_at) : null
        );
    }

    public function dropInnecesaryData(): self {
        unset($this->updated_at);
        unset($this->deleted_at);
        unset($this->id);
        unset($this->user_id);
        return $this;
    }
};