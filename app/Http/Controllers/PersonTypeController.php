<?php
namespace App\Http\Controllers;

use App\Application\PersonType\UseCases\CountPersonTypeUseCase;
use App\Application\PersonType\UseCases\CreatePersonTypeUseCase;
use App\Application\PersonType\UseCases\GetOnePersonTypeUseCase;
use App\Application\PersonType\UseCases\GetPersonTypeUseCase;
use App\Application\PersonType\UseCases\UpdatePersonTypeUseCase;
use App\Http\Request\PersonType\CreatePersonTypeRequest;
use App\Http\Request\PersonType\GetPersonTypeRequest;
use App\Http\Request\PersonType\UpdatePersonTypeRequest;
use Illuminate\Routing\Controller;

class PersonTypeController extends Controller {
  public function store(CreatePersonTypeRequest $request, CreatePersonTypeUseCase $useCase) {
    $dto = $request->toDto();
    $newPersonType = $useCase->handle($dto);
    return response()->json($newPersonType, 201);
  }

  public function get(GetPersonTypeRequest $request, GetPersonTypeUseCase $useCase) {
    $paginationDto = $request->toPaginationDto();
		$searchDto = $request->toSearchDto();
    $getPersonTypeDto = $request->toGetPersonTypeDto();

    $data = $useCase->handle($paginationDto, $searchDto, $getPersonTypeDto);
    return response()->json($data, 200);
  }

  public function count(GetPersonTypeRequest $request, CountPersonTypeUseCase $useCase) {
    $searchDto = $request->toSearchDto();
    $dto = $request->toGetPersonTypeDto();

    $count = $useCase->handle($searchDto, $dto);
    return response()->json($count, 200);
  }

  public function getOne(string $id, GetOnePersonTypeUseCase $useCase) {
    $item = $useCase->handle($id);
    return response()->json($item, 200);
  }

  public function update(string $id, UpdatePersonTypeRequest $request, UpdatePersonTypeUseCase $useCase) {
    $dto = $request->toDto();
    $newPersonType = $useCase->handle($id, $dto);
    return response()->json($newPersonType, 200);
  }
}