<?php
namespace App\Domain\Person\Repesitories;
use App\Application\Person\Dtos\CreatePersonDto;
use App\Application\Person\Dtos\UpdatePersonDto;
use App\Domain\Person\Entities\PersonEntity;

interface PersonRepository {
    public function create(CreatePersonDto $dto, $userId) : PersonEntity;
    public function updateByUserId(int $userId, UpdatePersonDto $data) : PersonEntity;
    public function findByUserId(int $userId) : ?PersonEntity;
    public function existsByCurp(string $curp) : bool;
    public function exixtsByUserId(int $userId) : bool;
    public function findById(string $id) : ?PersonEntity;
}