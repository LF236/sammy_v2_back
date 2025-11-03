<?php
namespace App\Application\Person\UseCases;

use App\Application\Person\Dtos\CreatePersonDto;
use App\Domain\Auth\AuthUserProviderInterface;
use App\Domain\Person\Repesitories\PersonRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CreatePersonUseCase {
    public function __construct(
        private AuthUserProviderInterface $authUserProvider,
        private PersonRepository $personRepository
    ) {}
    
    public function handle(CreatePersonDto $dto) {
        $user_id = $this->authUserProvider->getUserId();
        $person = $this->personRepository->findByUserId($user_id);
        if($person) {
            throw new BadRequestException('Person already exists for this user');
        }

        $curp = $dto->curp;
        if($this->personRepository->existsByCurp($curp)) {
            throw new BadRequestException('CURP already exists');
        }

        $newPerson = $this->personRepository->create($dto, $user_id);
        return $newPerson;        
    }
}