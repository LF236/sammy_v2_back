<?php
namespace App\Application\User\DTOs;

class UpdateUserDto {
    public function __construct(
        public ?string $type = null,
        public ?array $roles_ids = null,
        public ?bool $is_active = null
    ){}
}