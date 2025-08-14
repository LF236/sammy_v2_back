<?php
namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginUser {
	private $userRepository;

	public function __construct(UserRepositoryInterface $userRepository) {
		$this->userRepository = $userRepository;
	}

	public function handle(string $email, string $password) : string {
		$user = $this->userRepository->findByEmail($email);
		if(!$user) {
			throw new UnauthorizedHttpException('', 'Invalid credentials');
		}

		if(Hash::check($password, $user->password) === false) {
			throw new UnauthorizedHttpException('', 'Invalid credentials');
		}

		if($user->verifiedAt === null) {
			throw new UnauthorizedHttpException('', 'User not verified, please check your email');
		}

		if($user->is_active === false) {
			throw new UnauthorizedHttpException('', 'User not available, please contact support');
		}
	
		$token = $this->userRepository->generateToken($user);
		return $token;
	}
}
