<?php
namespace App\Application\Common\Dtos;


class PaginationDto {
	public function __construct(
		public int $offset = 0,
		public int $limit = 10,
		public ?string $sortBy = null,
		public ?string $sortDirection = 'asc',
	) {}
}
