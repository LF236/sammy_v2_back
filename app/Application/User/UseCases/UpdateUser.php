<?php
namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UpdateUserDto;
use App\Domain\Rols\Repositories\RolsRepository;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateUser {
    protected $userRepository;
    protected $roleRepository;
    public function __construct(
        UserRepositoryInterface $userRepository,
        RolsRepository $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function handle($userId, UpdateUserDto $updateUserDto) {
        $userId = (int)$userId;
        if($userId <= 0) {
            throw new \InvalidArgumentException('User ID is invalid');
        }
        
        $findedUser = $this->userRepository->findById($userId);
        if(!$findedUser) {
            throw new NotFoundHttpException('User not found');
        }

        if($findedUser->type === 'super_admin') {
            throw new BadRequestHttpException('This action is invalid');
        }

        $superAdminRol = $this->roleRepository->findByName('super_admin');
        if($superAdminRol) {
            $id = $superAdminRol->getId();
            if(in_array($id, $updateUserDto->roles_ids ?? [])) {
                throw new BadRequestHttpException('This action is invalid');
            }
        }
        $user = $this->userRepository->update($userId, $updateUserDto);
        $user->dropPermissions();
        $user->hidePassword();
        return $user;
    }
}