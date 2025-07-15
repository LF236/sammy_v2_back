<?php
namespace App\Application\Email\Services\Contracts;

interface MailSenderInterface {
	public function sendEmailLinkToValidateUser(string $email, string $token): void;
}
