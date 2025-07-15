<?php
namespace App\Http\Request\Rols;

use App\Application\Rols\DTOs\CreateRolDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateRolRequest extends FormRequest {

	public function rules(): array {
		return [
			'name' => 'required|string|max:255|unique:roles,name',
			'key' => 'nullable|string|max:255|unique:roles,key',
			'description' => 'nullable|string|max:1000',
			'is_active' => 'nullable|boolean',
			'permissions' => 'nullable|array',
		];
	}
	
	public function authorize(): bool {
		return true;
	}

	public function toDto() : CreateRolDto {
		return new CreateRolDto(
			name: $this->input('name'),
			key: $this->input('key'),
			description: $this->input('description'),
			is_active: $this->input('is_active', true),
			permissions: $this->input('permissions', null)
		);
	}
}
