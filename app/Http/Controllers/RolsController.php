<?php
namespace App\Http\Controllers;

use App\Application\Rols\UseCases\CountRols;
use App\Application\Rols\UseCases\CreateRol;
use App\Application\Rols\UseCases\DeleteRole;
use App\Application\Rols\UseCases\FindOneRole;
use App\Application\Rols\UseCases\GetRols;
use App\Application\Rols\UseCases\UpdateRol;
use App\Http\Request\Common\GetWithPaginationAndSearchRequest;
use App\Http\Request\Rols\CountRolsRequest;
use App\Http\Request\Rols\CreateRolRequest;
use App\Http\Request\Rols\UpdateRolRequest;
use Illuminate\Routing\Controller;

class RolsController extends Controller {
	public function get(GetWithPaginationAndSearchRequest $request, GetRols $useCase) {
		$paginationDto = $request->toPaginationDto();
		$searchDto = $request->toSearchDto();


		$rols = $useCase->handle($paginationDto, $searchDto);
		return response()->json([
			'data' => $rols,
			'msg' => 'Rols retrieved successfully',
		]);
	}

	public function create(CreateRolRequest $request, CreateRol $useCase) {
		$dto = $request->toDto();	
		$useCase->handle($dto);

		return response()->json([
			'msg' => 'Rol created successfully',
		]);
	}

	public function update(string $id, UpdateRolRequest $request, UpdateRol $useCase) {
		$dto = $request->toDto();
		$useCase->handle($id, $dto);
		return response()->json([
			'msg' => 'Rol updated successfully',
		]);
	}

	public function findById(string $id, FindOneRole $useCase) {
		$rol = $useCase->handle($id);
		return response()->json([
			'data' => $rol,
			'msg' => 'Rol retrieved successfully',
		]);
	}

	public function delete(string $id, DeleteRole $useCase) {
		$useCase->handle($id);
		return response()->json([
			'data' => true,
			'msg' => 'Role deleted successfully'
		]);
	}

	public function count(CountRolsRequest $request, CountRols $useCase) {
		$searchDto = $request->toSearchDto();
		$count = $useCase->handle($searchDto);
		return response()->json([
			'data' => $count,
			'msg' => 'Count retrieved successfully',
		]);
	}
}
