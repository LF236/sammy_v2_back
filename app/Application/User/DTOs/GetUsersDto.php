<?php
namespace App\Application\User\DTOs;

class GetUsersDto {
    public function __construct(
        public ?bool $is_active = null,
        public ?bool $is_verified = null,
        public ?array $roles = null,
    ) {}
}