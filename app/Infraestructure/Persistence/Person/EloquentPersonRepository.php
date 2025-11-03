<?php
namespace App\Infraestructure\Persistence\Person;

use App\Application\Person\Dtos\CreatePersonDto;
use App\Domain\Person\Repesitories\PersonRepository;
use App\Domain\Person\Entities\PersonEntity;
use Illuminate\Support\Facades\DB;
use App\Infraestructure\Persistence\Person\EloquentPerson;

class EloquentPersonRepository implements PersonRepository {
    public function create(CreatePersonDto $dto, $userId) : PersonEntity {
        $person = new EloquentPerson();
        \Log::info(json_encode($dto));
        \Log::info("Creating person for user ID: " . $userId);
        \Log::info(gettype($userId));
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
        \Log::info(json_encode($person));
        return PersonEntity::fromObject($person);
    }

    public function findById(string $id) : ?PersonEntity {
        $person = DB::table('person')->where('id', $id)->first();
        if(!$person) {
            return null;
        }
        // GET PERSON
        \Log::info('GET PERSON');
        \Log::info(json_encode($person));

        return PersonEntity::fromObject($person);
    }

    public function existsByCurp(string $curp) : bool {
        $count = DB::table('person')->where('curp', $curp)->count();
        return $count > 0;
    }
}