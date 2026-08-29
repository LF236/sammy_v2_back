<?php
namespace App\Application\PersonType\Dtos;
use Illuminate\Support\Str;

class CreatePersonTypeDto {
  public function __construct(
    public string $name,
    public string $code,
    public bool $is_active,
    public ?string $description
  ) {
    $this->code = $this->normalizeKey($this->code);
  }


  public static function fromObj(object $obj) : CreatePersonTypeDto {
    return new CreatePersonTypeDto(
      name: $obj->name,
      code: $obj->code,
      description: $obj->description ?? null,
      is_active: $obj->is_active ?? true
    );
  }

   private function normalizeKey(string $key): string {
		return Str::of($key)
			->lower()
			->replaceMatches('/\s+/', '_')
			->ascii();
	}
}