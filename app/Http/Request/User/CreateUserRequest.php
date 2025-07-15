<?php
namespace App\Http\Request\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest {
	public function rules() : array {
		return [
			'name' => 'required|string|max:255|min:3|unique:users,name',
			'email' => 'required|email|max:255|unique:users,email',
			'password' => 'required|string|min:8|confirmed',
			'password_confirmation' => 'required|string|min:8',
		];
	}

	public function authorize() : bool {
		return true;
	}
}
