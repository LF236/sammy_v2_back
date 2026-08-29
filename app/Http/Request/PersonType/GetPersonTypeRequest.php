<?php
namespace App\Http\Request\PersonType;

use App\Application\PersonType\Dtos\GetPersonTypeDto;
use App\Http\Request\Common\GetWithPaginationAndSearchRequest;

class GetPersonTypeRequest extends GetWithPaginationAndSearchRequest {

  public function rules(): array {
    return array_merge(parent::rules(), [
      'is_active'   => 'boolean|nullable',
      'description' => 'string|nullable',
    ]);
  }

  public function toGetPersonTypeDto(): GetPersonTypeDto {
    return new GetPersonTypeDto(
      is_active:   $this->input('is_active', null),
    );
  }
}