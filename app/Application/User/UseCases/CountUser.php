<?php
namespace App\Application\User\UseCases;

use App\Application\Common\Dtos\SearchDto;
use App\Application\User\DTOs\GetUsersDto;
use App\Domain\User\Repositories\UserRepositoryInterface;

class CountUser {
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function execute(SearchDto $searchDto, GetUsersDto $getUsersDto) : int {
        $count = $this->userRepository->count($searchDto, $getUsersDto);
        return $count;
    }
}