<?php
namespace App\Http\Request\Person;

use App\Application\Person\Dtos\UpdatePersonDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest {
    public function rules() : array {
        return [
            'names' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'second_last_name' => 'sometimes|nullable|string|max:255',
            'curp' => 'sometimes|required|string|max:18|unique:person,curp',
            'rfc' => 'sometimes|nullable|string|max:13|unique:person,rfc',
            'birth_date' => 'sometimes|nullable|date',
            'sex' => 'sometimes|nullable|in:M,F,O',
        ];
    }

    public function authorize() : bool {
        return true;
    }

    public function toDto() : UpdatePersonDto {
        return UpdatePersonDto::fromObject((object) $this->validated());
    }
}