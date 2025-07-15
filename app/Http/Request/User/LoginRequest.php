<?php
namespace App\Http\Request\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {
	public function rules() : array {
		return [
			'email' => 'required|email|max:255',
			'password' => 'required|string|min:8',
		];
	}

	public function authorize() : bool {
		return true;
	}
}
