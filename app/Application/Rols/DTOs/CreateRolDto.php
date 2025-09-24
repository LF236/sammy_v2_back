<?php
namespace App\Application\Rols\DTOs;
use Illuminate\Support\Str;

class CreateRolDto {
	public function __construct(
		public string $name,
		public ?string $key,
		public ?string $description = null,
		public bool $is_active = false,
		public ?array $permissions = null
	) {
		if ($this->permissions === null) {
			$this->permissions = [];
		}

		if(!$key) {
			$this->key = $this->normalizeKey($name);
		} else {
			$this->key = $this->normalizeKey($key);
		}
	}

	private function normalizeKey(string $key): string {
		return Str::of($key)
			->lower()
			->replaceMatches('/\s+/', '_')
			->ascii();
	}	
}
