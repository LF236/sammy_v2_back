<?php
namespace App\Http\Controllers\MagicToken;

use App\Application\MagicToken\Services\Contracts\MagicLinkSeenderInterface;
use App\Http\Controllers\Controller;
use App\Http\Request\MagicToken\GenerateNewTokenRequest;
use App\Http\Request\MagicToken\ValidateUserRequest;

class MagicTokenController extends Controller  {
	protected $magicLinkService;
	public function __construct(
		MagicLinkSeenderInterface $magicLinkService
	) {
		$this->magicLinkService = $magicLinkService;
	}

	/**
	 * @OA\Post(
	 *     path="/api/magic-token/validate",
	 *     summary="Validate user with magic link token",
	 *     tags={"Magic Token"},
	 *     @OA\Parameter(
	 *         name="Accept",
	 *         in="header",
	 *         required=true,
	 *         description="Debe ser 'application/json'",
	 *         @OA\Schema(
	 *             type="string",
	 *             default="application/json"
	 *         )
	 *     ),
	 *     @OA\RequestBody(
	 *         required=true,
	 *         @OA\JsonContent(
	 *             required={"token"},
	 *             @OA\Property(property="token", type="string", example="your-magic-link-token")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="User validated successfully",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="User validated successfully")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid or expired token",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="Invalid or expired token")
	 *         )
	 *     )
	 * )
	 */	

	public function validateUser(ValidateUserRequest $request) {	
		$isValid = $this->magicLinkService->validateToken($request->input('token'));

		if (!$isValid) {
			return response()->json(['message' => 'Invalid or expired token'], 400);
		}

		return response()->json(['message' => 'User validated successfully'], 200);
	}

	/**
	 * @OA\Post(
	 *     path="/api/magic-token/generate",
	 *     summary="Generate a new magic link token",
	 *     operationId="generateMagicToken",
	 *     tags={"Magic Token"},
	 *     
	 *     @OA\Parameter(
	 *         name="Accept",
	 *         in="header",
	 *         required=true,
	 *         description="Must be 'application/json'",
	 *         @OA\Schema(
	 *             type="string",
	 *             default="application/json"
	 *         )
	 *     ),
	 *     
	 *     @OA\RequestBody(
	 *         required=true,
	 *         description="User email address",
	 *         @OA\JsonContent(
	 *             required={"email"},
	 *             @OA\Property(
	 *                 property="email",
	 *                 type="string",
	 *                 format="email",
	 *                 example="user@example.com",
	 *                 description="Valid email address"
	 *             )
	 *         )
	 *     ),
	 *     
	 *     @OA\Response(
	 *         response=200,
	 *         description="Magic token generated and sent to email",
	 *         @OA\JsonContent(
	 *             @OA\Property(
	 *                 property="message",
	 *                 type="string",
	 *                 example="New token generated and sent to email"
	 *             )
	 *         )
	 *     ),
	 *     
	 *     @OA\Response(
	 *         response=400,
	 *         description="Possible error responses",
	 *         @OA\JsonContent(
	 *             oneOf={
	 *                 @OA\Schema(
	 *                     @OA\Property(
	 *                         property="message",
	 *                         type="string",
	 *                         example="This user is already verified, please login."
	 *                     ),
	 *                     description="The user is already verified"
	 *                 ),
	 *                 @OA\Schema(
	 *                     @OA\Property(
	 *                         property="message",
	 *                         type="string",
	 *                         example="User not found with this email, please try again."
	 *                     ),
	 *                     description="The email is not valid"
	 *                 ),
	 *                 @OA\Schema(
	 *                     @OA\Property(
	 *                         property="message",
	 *                         type="string",
	 *                         example="You can only request a new token every 15 minutes."
	 *                     ),
	 *                     description="The token was requested too soon"
	 *                 )
	 *             }
	 *         )
	 *     )
	 * )
	 */
	public function generateNewToken(GenerateNewTokenRequest $request) {
		$email = $request->input('email');
		$this->magicLinkService->generateToken($email);
		return response()->json(['message' => 'New token generated and sent to email'], 200);
	}
}
