<?php
namespace App\Application\Person\Dtos;

class UpdatePersonDto {
    public function __construct(
        public ?string $names,
        public ?string $last_name,
        public ?string $second_last_name,
        public ?string $curp,
        public ?string $rfc,
        public ?string $birth_date,
        public ?string $sex
    ) {}

    public static function fromObject(object $obj): UpdatePersonDto {
        return new UpdatePersonDto(
            names: $obj->names ?? null,
            last_name: $obj->last_name ?? null,
            second_last_name: $obj->second_last_name ?? null,
            curp: $obj->curp ?? null,
            rfc: $obj->rfc ?? null,
            birth_date: $obj->birth_date ?? null,
            sex: $obj->sex ?? null
        );
    }
}