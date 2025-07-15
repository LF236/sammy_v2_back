<?php
namespace App\Application\User\DTOs;
class CreateUserDTO {
	public function __construct(
		public string $name,
		public string $email,
		public string $password,
	){}
}
