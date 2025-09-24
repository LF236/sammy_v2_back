<?php
namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ToggleEnableUser {
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }
    
    public function handle($action, $userId, $userSessionId) {
        $user = $this->userRepository->findById($userId);

        if($user->type === 'super_admin') {
            throw new HttpException(403, 'Super Admin cannot be disabled or enabled');
        }

        if($user->id === $userSessionId) {
            throw new HttpException(403, 'This action is not allowed on yourself');
        }

        switch(strtolower($action)) {
            case 'enable':
                if($user->is_active) {
                    throw new HttpException(400, 'User is already enabled');
                }
                $this->userRepository->enableUser($userId);
                break;
            case 'disable':
                if(!$user->is_active) {
                    throw new HttpException(400, 'User is already disabled');
                }
                $this->userRepository->disableUser($userId);
                break;
            default:
                throw new HttpException(400, 'Invalid action. Use "enable" or "disable".');
        }
    }
}