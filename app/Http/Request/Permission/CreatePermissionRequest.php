<?php
namespace App\Http\Request\Permission;

use Illuminate\Foundation\Http\FormRequest;

class CreatePermissionRequest extends FormRequest {
	public function rules() : array {
		return [
			'name' => 'required|string|max:255|unique:permissions,name',
			'key' => 'optional|string|max:255|unique:permissions,key',
			'description' => 'nullable|string|max:500',
		];
	}

	public function authorize() : bool {
		return true;
	}
}
