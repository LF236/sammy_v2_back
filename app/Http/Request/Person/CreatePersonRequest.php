<?php
namespace App\Http\Request\Person;

use App\Application\Person\Dtos\CreatePersonDto;
use Illuminate\Foundation\Http\FormRequest;

class CreatePersonRequest extends FormRequest {
    public function rules(): array {
        return [
            'names' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'curp' => 'required|string|max:18|unique:person,curp',
            'rfc' => 'nullable|string|max:13|unique:person,rfc',
            'birth_date' => 'nullable|date',
            'sex' => 'nullable|in:M,F,O',
        ];
    }

    public function authorize(): bool {
        return true;
    }

    public function toDto() : CreatePersonDto {
        return CreatePersonDto::fromObject((object) $this->validated());
    }
}