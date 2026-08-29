<?php
namespace App\Application\PersonType\Dtos;
use Illuminate\Support\Str;

class UpdatePersonTypeDto {
  public function __construct(
    public ?string $name = null,
    public ?string $code = null,
    public ?bool $is_active = null,
    public ?string $description = null
  ) {
    if($this->code) {
      $this->code = $this->normalizeKey($code);
    }
  }

  public static function fromObj(object $obj) : UpdatePersonTypeDto {
    return new UpdatePersonTypeDto(
      name: $obj->name ?? null,
      code: $obj->code ?? null,
      description: $obj->description ?? null,
      is_active: $obj->is_active ?? null
    );
  }

  private function normalizeKey(string $key): string {
		return Str::of($key)
			->lower()
			->replaceMatches('/\s+/', '_')
			->ascii();
	}
}