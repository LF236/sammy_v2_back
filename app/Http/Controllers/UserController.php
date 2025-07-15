<?php
namespace App\Http\Controllers;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\UseCases\CreateUser;
use App\Application\User\UseCases\GetMe;
use App\Application\User\UseCases\LoginUser;
use App\Application\User\UseCases\LogoutUser;
use App\Http\Controllers\Controller;
use App\Http\Request\User\CreateUserRequest;
use App\Http\Request\User\LoginRequest;
use Illuminate\Http\Request;

class UserController extends Controller {
	/**
	 * Create a new user
	 * 
	 * This endpoint allows you to create a new user in the system.
	 * 
	 * @group Users
	 * @headerParam Accept application/json
	 *
	 *
	 * @BodyParam name string required The name of user.
	 * @BodyParam email string required The email of user.
	 * @BodyParam password string required The password of user.
	 * @BodyParam password_confirmation string required The password confirmation of user.
	 *
	 * @response 201 {
	 *		"message": "User retrieved successfully",
	 * }
	 *
	*/
	public function store(CreateUserRequest $request, CreateUser $useCase) {
		try {
			$data = new CreateUserDTO(
				$request->input('name'),
				$request->input('email'),
				$request->input('password'),
			);
			
			$useCase->handle($data);
			return response()->json(['message' => 'User created successfully'], 201);
		} catch (\Throwable $e) {
			return $this->handleException($e);
		}
	}

	/**
	 * Login a user
	 * 
	 * This endpoint allows you to login a user in the system.
	 * 
	 * @group Users
	 * @headerParam Accept application/json
	 *
	 * @BodyParam email string required The email of user.
	 * @BodyParam password string required The password of user.
	 *
	 * @response 200 {
	 *		"message": "Login successful",
	 *		"token": "your_token_here",
	 *		"type": "Bearer"
	 * }
	 * @response 401 {
	 *		"message": "Invalid credentials"
	 * }
	 *
	*/

	public function login(LoginRequest $request, LoginUser $useCase) {
		try {
			$email = $request->input('email');
			$password = $request->input('password');

			$token = $useCase->handle($email, $password);

			return response()->json([
				'message' => 'Login successful',
				'token' => $token,
				'type' => 'Bearer'
			], 200);
		} catch (\Throwable $e) {	
			return $this->handleException($e);	
		}
	}

	public function me(Request $request, GetMe $useCase) {
		try {
			$user = $request->user();
			$me = $useCase->handle($user->id);
			return response()->json([
				'me' => $me,
				'message' => 'User retrieved successfully'
			], 200);
		} catch (\Throwable $e) {
			return $this->handleException($e);
		}
	}

	public function logout(Request $request, LogoutUser $useCase) {
		try {
			$user = $request->user();
			$token = $user->currentAccessToken();	
			$useCase->handle($token->id);
			return response()->json(['message' => 'Logout successful'], 200);
		} catch (\Throwable $e) {
			return $this->handleException($e);
		}
	}


	private function handleException(\Throwable $e) {
		return response()->json([
			'message' => 'An error occurred',
			'error' => $e->getMessage()
		], 500);
	}
}
