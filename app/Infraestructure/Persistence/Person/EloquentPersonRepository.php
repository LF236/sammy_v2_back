<?php
namespace App\Infraestructure\Persistence\Person;

use App\Application\Person\Dtos\CreatePersonDto;
use App\Application\Person\Dtos\UpdatePersonDto;
use App\Domain\Person\Repesitories\PersonRepository;
use App\Domain\Person\Entities\PersonEntity;
use Illuminate\Support\Facades\DB;
use App\Infraestructure\Persistence\Person\EloquentPerson;

class EloquentPersonRepository implements PersonRepository {
    public function create(CreatePersonDto $dto, $userId) : PersonEntity {
        $person = new EloquentPerson();
        $person->user_id = $userId;
        $person->names = $dto->names;
        $person->last_name = $dto->last_name;
        $person->second_last_name = $dto->second_last_name;
        $person->birth_date = $dto->birth_date;
        $person->curp = $dto->curp;
        $person->rfc = $dto->rfc;
        $person->save();

        return $this->findById($person->id);
    }

    public function findByUserId(int $userId) : ?PersonEntity {
        $person = DB::table('person')->where('user_id', $userId)->first();
        if(!$person) {
            return null;
        }

        return PersonEntity::fromObject($person);
    }

    public function findById(string $id) : ?PersonEntity {
        $person = DB::table('person')->where('id', $id)->first();
        if(!$person) {
            return null;
        }

        return PersonEntity::fromObject($person);
    }

    public function existsByCurp(string $curp) : bool {
        $count = DB::table('person')->where('curp', $curp)->count();
        return $count > 0;
    }

    public function exixtsByUserId(int $userId) : bool {
        $count = DB::table('person')->where('user_id', $userId)->count();
        return $count > 0;
    }

    public function updateByUserId(int $userId, UpdatePersonDto $data) : PersonEntity {
        $person = EloquentPerson::where('user_id', $userId)->first();

        if($data->names !== null) {
            $person->names = $data->names;
        }
        if($data->last_name !== null) {
            $person->last_name = $data->last_name;
        }
        if($data->second_last_name !== null) {
            $person->second_last_name = $data->second_last_name;
        }
        if($data->curp !== null) {
            $person->curp = $data->curp;
        }
        if($data->rfc !== null) {
            $person->rfc = $data->rfc;
        }
        if($data->birth_date !== null) {
            $person->birth_date = $data->birth_date;
        }
        if($data->sex !== null) {
            $person->sex = $data->sex;
        }

        $person->save();

        return $this->findById($person->id);
    }
}