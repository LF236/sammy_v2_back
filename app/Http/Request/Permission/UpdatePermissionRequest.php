<?php
namespace App\Http\Request\Permission;

use App\Application\Permission\DTOs\UpdatePermissionDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest {
	public function authorize() {
		return true;
	}

	public function rules() {
		return [
			'name' => 'sometimes|string|max:255|unique:permissions,name',
			'key' => 'sometimes|string|max:255|unique:permissions,key',
			'description' => 'sometimes|string|max:500',
		];
	}

	public function toDto() : UpdatePermissionDto {
		return new UpdatePermissionDto(
			name: $this->input('name'),
			key: $this->input('key'),
			description: $this->input('description'),
			is_active: $this->input('is_active')
		);
	}
}
