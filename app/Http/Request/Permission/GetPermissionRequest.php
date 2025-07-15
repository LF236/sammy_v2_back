<?php
namespace App\Http\Request\Permission;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use Illuminate\Foundation\Http\FormRequest;

class GetPermissionRequest extends FormRequest {
	public function autorize() : bool {
		return true;
	}	

	public function rules() : array {
		return [
			'offset' => 'integer|min:0',
			'limit' => 'integer|min:1|max:100',
			'search' => 'string|nullable',
		];
	}

	public function toPaginationDto() : PaginationDto {
		return new PaginationDto(
			offset: $this->input('offset', 0),
			limit: $this->input('limit', 10),
		);
	}

	public function toSearchDto() : SearchDto {
		return new SearchDto(
			search: $this->input('search', null)
		);
	}
}
