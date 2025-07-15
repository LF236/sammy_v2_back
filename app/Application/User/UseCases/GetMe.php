<?php
namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepositoryInterface;

class GetMe {
	private $userRepository;

	public function __construct(UserRepositoryInterface $userRepository) {
		$this->userRepository = $userRepository;
	}

	public function handle(int $userId) {
		$user = $this->userRepository->findById($userId);
		$user = $user->dropSensitiveData();
		if (!$user) {
			throw new \Exception('User not found');
		}
		return $user;
	}
}
