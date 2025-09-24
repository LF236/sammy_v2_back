<?php
namespace App\Http\Request\Rols;

use App\Application\Common\Dtos\SearchDto;
use Illuminate\Foundation\Http\FormRequest;

class CountRolsRequest extends FormRequest {
	public function authorize() {
		return true;
	}

	public function rules() {
		return [
			'search' => 'nullable|string|max:255'
		];
	}

	public function toSearchDto() : SearchDto  {
		return new SearchDto(
			$this->input('search', null)
		);
	}
}
