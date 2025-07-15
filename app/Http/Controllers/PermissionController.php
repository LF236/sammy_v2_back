<?php
namespace App\Http\Controllers;

use App\Application\Common\Dtos\PaginationDto;
use App\Application\Common\Dtos\SearchDto;
use App\Application\Permission\DTOs\CreatePermissionDto;
use App\Application\Permission\UseCases\CountPermission;
use App\Application\Permission\UseCases\CreatePermission;
use App\Application\Permission\UseCases\DeletePermission;
use App\Application\Permission\UseCases\FindPermissionById;
use App\Application\Permission\UseCases\GetPermission;
use App\Application\Permission\UseCases\UpdatePermission;
use App\Http\Request\Permission\CountPermissionRequest;
use App\Http\Request\Permission\CreatePermissionRequest;
use App\Http\Request\Permission\GetPermissionRequest;
use App\Http\Request\Permission\UpdatePermissionRequest;
use Illuminate\Routing\Controller;

class PermissionController extends Controller {
	public function get(GetPermissionRequest $request, GetPermission $useCase) {
		$dto = new PaginationDto(
			$request->input('offset'),
			$request->input('limit'),
		);

		$dtoSearch = new SearchDto($request->input('search'));

		$permissions = $useCase->handle($dto, $dtoSearch);
			

		return response()->json([
			'message' => 'Permissions retrieved successfully',
			'data' => $permissions ? $permissions : []	
		]);
	}

	public function findById(string $id, FindPermissionById $useCase) {
		$permission = $useCase->handle($id);

		return response()->json([
			'message' => 'Permission retrieved successfully',
			'data' => $permission ? $permission : null
		]);
	}


	public function count(CountPermissionRequest $request, CountPermission $useCase) {
		$dtoSearch = new SearchDto($request->input('search'));

		$count = $useCase->handle($dtoSearch);

		return response()->json([
			'message' => 'Permissions count retrieved successfully',
			'data' => $count
		]);
	}

	public function create(CreatePermissionRequest $request, CreatePermission $useCase) {
		$dto = new CreatePermissionDto(
			name: $request->input('name'),
			description: $request->input('description'),
			key: $request->input('key'),
		);

		$permission = $useCase->handle($dto);

		return response()->json([
			'message' => 'Permission created successfully',
			'data' => $permission ? $permission : null
		], 201);
	}

	public function update($id, UpdatePermissionRequest $request, UpdatePermission $useCase) {
		$dto = $request->toDto();

		$permission = $useCase->handle($id, $dto);

		return response()->json([
			'message' => 'Permission updated successfully',
			'data' => $permission ? $permission : null
		]);
	}

	public function delete($id, DeletePermission $useCase) {
		$deleted = $useCase->handle($id);

		if (!$deleted) {
			return response()->json([
				'message' => 'Permission not found or could not be deleted',
			], 404);
		}

		return response()->json([
			'message' => 'Permission deleted successfully',
		]);
	}
}
