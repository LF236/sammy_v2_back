<?php
namespace App\Http\Request\PersonType;

use App\Application\PersonType\Dtos\UpdatePersonTypeDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonTypeRequest extends FormRequest {
  public function authorize() {
    return true;
  }

  public function rules() {
    return [
      'name' => 'sometimes|string|max:255',
      'description' => 'sometimes|string|max:255',
      'code' => 'sometimes|string|max:255',
      'is_active' => 'sometimes|boolean'
    ];
  }

  public function toDto() : UpdatePersonTypeDto {
    return UpdatePersonTypeDto::fromObj((object) $this->validated());
  }
}