<?php
namespace App\Application\Person\UseCases;

use App\Application\Person\Dtos\UpdatePersonDto;
use App\Domain\Auth\AuthUserProviderInterface;
use App\Domain\Person\Repesitories\PersonRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UpdatePersonUseCase {
    public function __construct(
        private AuthUserProviderInterface $authUserProvider,
        private PersonRepository $personRepository
    ) {}

    public function handle(UpdatePersonDto $dto) {
        $user_id = $this->authUserProvider->getUserId();
        $exists = $this->personRepository->exixtsByUserId($user_id);

        if(!$exists) {
            throw new BadRequestException('Person does not exist for this user');
        }

        $updatedPerson = $this->personRepository->updateByUserId($user_id, $dto);
        return $updatedPerson;
    }
}