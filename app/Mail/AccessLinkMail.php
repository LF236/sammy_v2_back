<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccessLinkMail extends Mailable {
	use Queueable, SerializesModels;

	public string $url;

	public function __construct(
		string $url	
	) {
		$this->url = $url;
	}


	public function build() : self {
		return $this->markdown('emails.access_link')
			->subject('Validate your account')
			->with([
				'url' => $this->url,
			]);
	}

}
