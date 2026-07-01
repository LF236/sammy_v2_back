<?php
namespace App\Http\Controllers\MagicToken;

use App\Application\MagicToken\Services\Contracts\MagicLinkSeenderInterface;
use App\Http\Request\MagicToken\GenerateNewTokenRequest;
use App\Http\Request\MagicToken\ValidateUserRequest;
use Illuminate\Routing\Controller;

class MagicTokenController extends Controller  {
	protected $magicLinkService;
	public function __construct(
		MagicLinkSeenderInterface $magicLinkService
	) {
		$this->magicLinkService = $magicLinkService;
	}

	public function validateUser(ValidateUserRequest $request) {	
		$isValid = $this->magicLinkService->validateToken($request->input('token'));

		if (!$isValid) {
			return response()->json(['message' => 'Invalid or expired token'], 400);
		}

		return response()->json(['message' => 'User validated successfully'], 200);
	}

	public function generateNewToken(GenerateNewTokenRequest $request) {
		$email = $request->input('email');
		$this->magicLinkService->generateToken($email);
		return response()->json(['message' => 'New token generated and sent to email'], 200);
	}
}
