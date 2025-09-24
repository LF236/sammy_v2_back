<?php
namespace App\Application\Permission\DTOs;
use Illuminate\Support\Str;

class CreatePermissionDto {
	public function __construct(
		public string $name,
		public ?string $key,
		public ?string $description = null,
		public ?bool $is_active = true
	) {
		$this->key = $this->normalizeKey($key ?? $name);
	}

	public function toArray(): array {
		return [
			'name' => $this->name,
			'description' => $this->description,
			'is_active' => $this->is_active,
			'key' => $this->key
		];
	}

	private function normalizeKey(string $key): string {
		return Str::of($key)
			->lower()
			->replaceMatches('/\s+/', '_')
			->ascii();
	}
}
