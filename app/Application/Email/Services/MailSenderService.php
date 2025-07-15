<?php
namespace App\Application\Email\Services;

use App\Application\Email\Services\Contracts\MailSenderInterface;
use App\Mail\AccessLinkMail;
use Illuminate\Support\Facades\Mail;

class MailSenderService implements MailSenderInterface {
	public function sendEmailLinkToValidateUser(string $email, string $url): void {
		Mail::to($email)->send(new AccessLinkMail($url));
	}
}
