<?php
namespace App\Domain\Speciality\Repositories;

use App\Domain\Speciality\Entities\SpecialityEntity;

interface SpecialityRepository {
  public function get() : array;
  public function create() : SpecialityEntity;
  public function findById(string $id) : SpecialityEntity | null;
  public function existsByCode(string $code) : bool;
  public function update(string $id) : SpecialityEntity;
  public function count() : int;
} 