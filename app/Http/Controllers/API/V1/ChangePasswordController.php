<?php
namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends BaseController
{
    /**
     * @OA\Post(
     *   path="/v1/password/change-password",
     *   summary="Change Password",
     *   tags={"Users"},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Change Password Request",
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"email", "current_password", "new_password", "confirm_password"},
     *         @OA\Property(
     *           property="email",
     *           type="string",
     *           description="User's email"
     *         ),
     *         @OA\Property(
     *           property="current_password",
     *           type="string",
     *           description="User's current password"
     *         ),
     *         @OA\Property(
     *           property="new_password",
     *           type="string",
     *           description="User's new password"
     *         ),
     *         @OA\Property(
     *           property="confirm_password",
     *           type="string",
     *           description="Confirm new password"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Invalid request"
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error"
     *   )
     * )
     */
    public function changepassword(Request $request): JsonResponse
    {
        try {
            # Validate request data
            $validatedData = Validator::make($request->all(), $this->getValidationRule());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validation_error'), $validatedData->errors());
            }

            # Check if email matches the logged-in user's email
            $user = $request->user();
            if ($request->email !== $user->email) {
                return $this->sendError('Validation error', __('changepassword.email_mismatch'));
            }
            if ($request->new_password !== $request->conform_pass) {
                return $this->sendError('Validation error', __('changepassword.passmismatch'));
            }
            if (!Hash::check($request->current_password, $user->password)) {
                return $this->sendError('Validation error', __('changepassword.incorrectpass'));

            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->sendCreated([], __('changepassword.successful'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValidationRule()
    {
        return [
            'email' => ['required'],
            'current_password' => ['required'],
            'new_password' => ['required']
        ];
    }
}