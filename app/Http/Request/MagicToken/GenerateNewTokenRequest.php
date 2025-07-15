<?php
namespace App\Http\Request\MagicToken;

use Illuminate\Foundation\Http\FormRequest;

class GenerateNewTokenRequest extends FormRequest {
	public function rules() : array {
		return [
			'email' => 'required|email',
		];
	}

	public function authorize() : bool {
		return true;
	}
}
