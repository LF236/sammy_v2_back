<?php
namespace App\Application\PersonType\Dtos;

class GetPersonTypeDto {
  public function __construct(
    public ?bool $is_active,
  ) {}
}