<?php
namespace App\Application\Rols\DTOs;
use Illuminate\Support\Str;

class UpdateRolDto {
	public function __construct(
		public ?string $name = null,
		public ?string $key = null,
		public ?string $description = null,
		public ?bool $is_active = null,
		public ?array $permissions = null
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
