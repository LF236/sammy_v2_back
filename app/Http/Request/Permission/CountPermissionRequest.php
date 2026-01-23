<?php
namespace App\Http\Request\Permission;

use App\Application\Permission\DTOs\CountPermissionDto;
use Illuminate\Foundation\Http\FormRequest;

class CountPermissionRequest extends FormRequest {
	public function rules() : array {
		return [
			'search' => 'nullable|string|max:255',
			'is_active' => 'nullable|boolean',
			'roles_ids' => 'nullable|array'
		];
	}

	public function authorize() : bool {
		return true;
	}

	public function toCountPermissionDto() : CountPermissionDto {
		return new CountPermissionDto(
			is_active: $this->input('is_active', null),
			roles_ids: $this->input('roles_ids', [])
		);
	}
}
