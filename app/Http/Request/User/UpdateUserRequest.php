<?php
namespace App\Http\Request\User;

use App\Application\User\DTOs\UpdateUserDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest {

    public function rules() : array {
        return [
            'type' => 'sometimes|string|in:admin,user',
            'roles_ids' => 'sometimes|array|exists:roles,id',
            'is_active' => 'sometimes|boolean'
        ];
    }

    public function authorize() : bool {
        return true;
    }

    public function toDto() : UpdateUserDto {
        return new UpdateUserDto(
            type: $this->input('type'),
            roles_ids: $this->input('roles_ids'),
            is_active: $this->input('is_active')
        );
    }
}