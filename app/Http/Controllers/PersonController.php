<?php
namespace App\Http\Controllers;

use App\Application\Person\Dtos\CreatePersonDto;
use App\Application\Person\UseCases\CreatePersonUseCase;
use App\Application\Person\UseCases\UpdatePersonUseCase;
use App\Http\Request\Person\CreatePersonRequest;
use App\Http\Request\Person\UpdatePersonRequest;
use Illuminate\Routing\Controller;

class PersonController extends Controller {
    public function store(CreatePersonRequest $request, CreatePersonUseCase $useCase) {
        try {
            $dto = $request->toDto();
            $person = $useCase->handle($dto);
            return response()->json($person, 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(UpdatePersonRequest $request, UpdatePersonUseCase $useCase) {
        try {
            $dto = $request->toDto();
            $newPerson = $useCase->handle($dto);
            return response()->json($newPerson, 200);
        } catch(\Throwable $e) {
            return $this->handleException($e);
        }
    }

    private function handleException(\Throwable $e) {
		$statusCode = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException
			? $e->getStatusCode()
			: ($e->getCode() >= 100 && $e->getCode() < 600 
				? $e->getCode() 
			: 500);
       
		return response()->json([
			'message' => 'An error occurred',
			'error' => $e->getMessage()
		], $statusCode);
	}
}