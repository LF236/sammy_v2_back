<?php
namespace App\Application\User\UseCases;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\User\DTOs\GetUsersDto;
use App\Domain\User\Repositories\UserRepositoryInterface;

class GetUsers {
    protected $userRepository;

    public function __construct(UserRepositoryInterface  $userRepository) {
        $this->userRepository = $userRepository;
    }
    
    public function execute(PaginationDto $paginationDto, SearchDto $searchDto, GetUsersDto $getUsersDto) {
        
        $users = $this->userRepository->all($paginationDto, $searchDto, $getUsersDto);
        return $users;
    }
}