<?php
namespace App\Domain\Person\Repesitories;
use App\Application\Person\Dtos\CreatePersonDto;
use App\Domain\Person\Entities\PersonEntity;

interface PersonRepository {
    public function create(CreatePersonDto $dto, $userId) : PersonEntity;
    public function findByUserId(int $userId) : ?PersonEntity;
    public function existsByCurp(string $curp) : bool;
    public function findById(string $id) : ?PersonEntity;
}