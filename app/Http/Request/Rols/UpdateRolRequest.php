<?php
namespace App\Http\Request\Rols;

use App\Application\Rols\DTOs\UpdateRolDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRolRequest extends FormRequest {
	public function authorize() {
		return true;
	}

	public function rules() {
		return [
			'name' => 'nullable|string|max:255|unique:roles,name',
			'description' => 'nullable|string|max:1000',
			'is_active' => 'nullable|boolean',
			'permissions' => 'nullable|array',
		];
	}

	public function toDto() : UpdateRolDto {
		return new UpdateRolDto(
			name: $this->input('name'),
			key: $this->input('key'),
			description: $this->input('description'),
			is_active: $this->input('is_active'),
			permissions: $this->input('permissions')
		);
	}
}
