<?php
namespace App\Application\Permission\DTOs;
use Illuminate\Support\Str;

class UpdatePermissionDto {
	public function __construct(
		public ?string $name = null,
		public ?string $key = null,
		public ?string $description = null,
		public ?bool $is_active = null
	) {
		if($this->key) {
			$this->key = $this->normalizeKey($this->key);
		}
	}
	
	
	private function normalizeKey(string $key): string {
		return Str::of($key)
			->lower()
			->replaceMatches('/\s+/', '_')
			->ascii();
	}
}
