<?php
namespace App\Application\MagicToken\Services;

use App\Application\Email\Services\MailSenderService;
use App\Application\MagicToken\Services\Contracts\MagicLinkSeenderInterface;
use App\Domain\MagicToken\Repositories\MagicTokenRepositoryInterface;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MagicLinkService implements MagicLinkSeenderInterface {
	
	public function __construct(
		private MagicTokenRepositoryInterface $magicTokenRepository,
		private MailSenderService $mailSenderService,
		private UserRepositoryInterface $userRepository
	){}

	public function sendToken(UserEntity $user): void {
		$newToken = $this->magicTokenRepository->create(
			$user
		);
		$url = config('app.frontend_domain') . '/validate-account/' . $newToken;

		$this->mailSenderService->sendEmailLinkToValidateUser(
			$user->email,
			$url
		);
	}

	public function validateToken(string $token) : bool {
		$tokenUpdated = $this->magicTokenRepository->validate($token);
		if($tokenUpdated) {
			$this->userRepository->activateUser($tokenUpdated->user_id);
			return true;
		}
		return false;
	}

	public function generateToken(string $email): void {
		$user = $this->userRepository->findByEmail($email);
		if(!$user) {
			throw new BadRequestHttpException(
				'User not found with this email, please try again.'
			);
		}
		if($user->verifiedAt) {
			throw new BadRequestHttpException(
				'This user is already verified, please login.'
			);
		}

		$lastToken = $this->magicTokenRepository->findLastByUserId($user->id);

		$now = now();
		$diff = $now->diffInMinutes($lastToken?->expires_at ?? $now);

		if($lastToken && $diff < 15) {
			throw new BadRequestHttpException(
				'You can only request a new token every 15 minutes.'
			);
		}

		return;
	}
}
