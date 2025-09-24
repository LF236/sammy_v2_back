<?php
namespace App\Http\Request\User;

use App\Application\User\DTOs\GetUsersDto;
use Illuminate\Foundation\Http\FormRequest;

class GetUsersFilters extends FormRequest {
    
    public function rules() : array {
        return [
            'is_active' => 'boolean|nullable',
            'roles' => 'array|nullable',
            'roles.*' => 'string|exists:roles,id',
            'is_verified' => 'boolean|nullable',
        ];
    }
    
    public function authorize() : bool {
        return true;
    }

    public function toDto() : GetUsersDto {
        $is_active = $this->has('is_active')
            ? filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            : null;

        $is_verified = $this->has('is_verified')
            ? filter_var($this->input('is_verified'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            : null;

        return new GetUsersDto(
            $is_active,
            $is_verified,
            $this->input('roles', null)
        );
    }
}