<?php
namespace App\Http\Request\MagicToken;

use Illuminate\Foundation\Http\FormRequest;

class ValidateUserRequest extends FormRequest {
	public function rules() : array {
		return [
			'token' => 'required|string|uuid',
		];
	}

	public function authorize() : bool {
		return true;
	}	
}
