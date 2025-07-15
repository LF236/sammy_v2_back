<?php
namespace App\Http\Request\Permission;

use Illuminate\Foundation\Http\FormRequest;

class CountPermissionRequest extends FormRequest {
	public function rules() : array {
		return [
			'search' => 'nullable|string|max:255'
		];
	}

	public function authorize() : bool {
		return true;
	}
}
