<?php
namespace App\Http\Request\User;

use Illuminate\Foundation\Http\FormRequest;

class ToggleEnableUserRequest extends FormRequest {
    public function rules() : array {
        return [
            'action' => 'required|in:enable,disable',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    public function authorize() : bool {
        return true;
    }
}