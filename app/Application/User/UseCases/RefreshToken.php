<?php
namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepositoryInterface;

class RefreshToken {
    protected $userRepository;
    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function handle($token, $email) : string {
        $user = $this->userRepository->findByEmail($email);
        
        if(!$user) {
            throw new \Exception('Invalid authentication');
        }

        $this->userRepository->revokeToken($token);
        return $this->userRepository->generateToken($user);
    }
}