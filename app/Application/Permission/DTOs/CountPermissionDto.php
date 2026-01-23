<?php
namespace App\Application\Permission\DTOs;

class CountPermissionDto {
  public function __construct(
    public? bool $is_active = null,
    public? array $roles_ids = []
  ) {}

    public function toArray() : array {
      return [
        'is_active' => $this->is_active,
        'roles_ids' => $this->roles_ids
      ];
    }
}