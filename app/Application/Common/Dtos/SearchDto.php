<?php
namespace App\Application\Common\Dtos;

class SearchDto {
	public function __construct(
		public ?string $search = null,
	) {}
}
