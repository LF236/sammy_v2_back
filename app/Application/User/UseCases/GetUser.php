<?php
namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepositoryInterface;

class GetUser {
    protected $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }


    public function handle($userId) {
        $userId = (int) $userId;
        if($userId <= 0) {
            throw new \InvalidArgumentException('Invalid user ID provided');
        }

        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User not found');
        }

        $user->dropPermissions();
        $user->hidePassword();
        return $user;
    }
}