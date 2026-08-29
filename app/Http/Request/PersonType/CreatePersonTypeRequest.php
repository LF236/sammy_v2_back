<?php
namespace App\Http\Request\PersonType;

use App\Application\PersonType\Dtos\CreatePersonTypeDto;
use Illuminate\Foundation\Http\FormRequest;

class CreatePersonTypeRequest extends FormRequest {
  public function rules() : array {
    return [
      'name' => 'required|string|max:255',
      'description' => 'nullable|string|max:255',
      'code' => 'required|string|max:255',
      'is_active' => 'nullable|boolean'
    ];
  }

  public function authorize() : bool {
    return true;
  }

  public function toDto() : CreatePersonTypeDto {
    return CreatePersonTypeDto::fromObj((object) $this->validated());
  }
}