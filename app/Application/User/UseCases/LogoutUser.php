<?php
namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepositoryInterface;

class LogoutUser {
	private $userRepository;

	public function __construct(UserRepositoryInterface $userRepository) {
		$this->userRepository = $userRepository;
	}
	
	public function handle($access_token) : void {
		$this->userRepository->revokeToken($access_token);
	}	
}
