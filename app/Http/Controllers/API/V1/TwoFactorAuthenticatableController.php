<?php
namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Carbon;
use App\Models\user;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;


class TwoFactorAuthenticatableController extends BaseController
{

    /**
     * @OA\Post(
     *   path="/v1/two-factor-authenticate",
     *   summary="Verify OTP",
     *   tags={"Two Factor Auth "},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Two factor authanticate otp verify",
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"email", "otp"},
     *         @OA\Property(
     *           property="email",
     *           type="string",
     *           description="Email"
     *         ),
     *         @OA\Property(
     *           property="otp",
     *           type="string",
     *           description="OTP"
     *         )
     *         
     *        
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
     * @OA\Tag(
     *     name="Two Factor Auth ",
     *     description="Two factor authentication related operations "
     * )
     */
    public function TwoFactorAuthenticate(Request $request): JsonResponse
    {

        try {

            # validate Language
            $validatedData = Validator::make($request->all(), $this->getValidationRule());

            # check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $email = $request->input('email');
            $userotp = $request->input('otp');

            # get user details
            $user = User::where('email', $email)->first();


            $twoFactorCreatedAt = Carbon::parse($user->two_factor_created_at);

            // Calculate the expiration time by adding 30 minutes to the creation time
            $expirationTime = $twoFactorCreatedAt->addMinutes(30);

            // Check if the current time is greater than or equal to the expiration time
            if (Carbon::now()->gte($expirationTime)) {
                $user->two_factor_codes = null;
                $user->two_factor_created_at = null;
                $user->otp_resend_count = 0;
                $user->save();
                return $this->sendError('Validation error',__('resendotp.otp_expired'));

            } else if ($user->two_factor_codes != $userotp) {

                return $this->sendError('Validation error',__('resendotp.otp_mismatch'));

            } else {

                #get token
                $token = Auth::login($user);
                //print_r($token);die;
                if (!$token) {
                    #return response
                    return $this->sendError(__('auth.failed'), []);
                }
                if ($user->status == 0) {
                    $data = [
                        'authorisation' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ]
                    ];
                    return $this->sendUnauthorizedResponse(__('auth.account_inactive'), $data);

                } else if ($user->status == 2) {
                    return $this->sendUnauthorizedResponse(__('auth.account_disabled'));

                } else {
                    $data = ['user' => new UserResource($user), 'authorisation' => ['token' => $token, 'type' => 'bearer']];
                    // print_r($data);
                    return $this->sendSuccess($data, __('auth.successful'));
                }
            }

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerErrorResponse(__('common.server_error'), $e->getMessage());
        }
    }

    //enable or disable two factor authentication
    /**
     * @OA\Post(
     *   path="/v1/two-factor-enable-disable",
     *   summary="Two Factor Authantication Enable/Disable",
     *   tags={"Two Factor Auth "},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Two factor authanticate activation/deactivation",
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"email"},
     *         @OA\Property(
     *           property="email",
     *           type="string",
     *           description="Email"
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
    public function enable_disable_two_fector_auth(Request $request): JsonResponse
    {
        try {
            # validate Language
            $validatedData = Validator::make($request->all(), $this->getValidationRule2fa());

            # check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            $email = $request->input('email');
            // Retrieve the record from the database
            $user = User::where('email', $email)->first();
            $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $currentDateTime = Carbon::now();
            // Check the current value of the status field
            $currentStatus = $user->is_two_factor_enable;

            // Update the status field based on its current value
            if (($currentStatus == 0) || ($currentStatus == '')) {
                $user->two_factor_codes = $otp;
                $user->two_factor_created_at = $currentDateTime;
                $user->save();
                $user->beforEnableDisableTwoFactorOtpNotification($otp, $currentStatus);

                return $this->sendSuccess([], __('resendotp.twofa_enable_otp'));
            } elseif ($currentStatus == 1) {

                $user->two_factor_codes = $otp;
                $user->two_factor_created_at = $currentDateTime;
                $user->save();
                $user->beforEnableDisableTwoFactorOtpNotification($otp, $currentStatus);
                return $this->sendSuccess([], __('resendotp.twofa_disable_otp'));
            }

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/two-factor-enable-disable-otp-verify",
     *   summary="Verify OTP for Activation/Deactivation",
     *   tags={"Two Factor Auth "},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Verify OTP for Activation/Deactivation",
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"email", "otp"},
     *         @OA\Property(
     *           property="email",
     *           type="string",
     *           description="Email"
     *         ),
     *         @OA\Property(
     *           property="otp",
     *           type="string",
     *           description="OTP"
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
    public function two_factor_otp_verifiy(Request $request)
    {
        try {

            # validate Language
            $validatedData = Validator::make($request->all(), $this->getValidationRule());

            # check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $email = $request->input('email');
            $userotp = $request->input('otp');

            # get user details
            $user = User::where('email', $email)->first();


            $twoFactorCreatedAt = Carbon::parse($user->two_factor_created_at);

            // Calculate the expiration time by adding 30 minutes to the creation time
            $expirationTime = $twoFactorCreatedAt->addMinutes(30);

            // Check if the current time is greater than or equal to the expiration time
            if (Carbon::now()->gte($expirationTime)) {
                $user->two_factor_codes = null;
                $user->two_factor_created_at = null;
                $user->otp_resend_count = 0;
                $user->save();
                return $this->sendError('Validation error',__('resendotp.otp_expired'));

            } else if ($user->two_factor_codes != $userotp) {

                return $this->sendError('Validation error',__('resendotp.otp_mismatch'));

            } else {
                $activeStatus = $user->is_two_factor_enable;
                if (isset($activeStatus)) {
                    $user->is_two_factor_enable = $activeStatus == 1 ? 0 : 1;
                    $user->two_factor_codes = null;
                    $user->two_factor_created_at = null;
                    $user->otp_resend_count = 0;
                    $user->save();
                    if($activeStatus ==0)
                    {
                    return $this->sendSuccess([], __('resendotp.twofa_enable'));
                    }else
                    {
                        return $this->sendSuccess([], __('resendotp.twofa_disable'));    
                    }
                }
            }

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerErrorResponse(__('common.server_error'), $e->getMessage());
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/resend-two-factor-auth-code",
     *   summary="Resend 2FA OTP Code",
     *   tags={"Two Factor Auth "},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Resend 2FA OTP Code",
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"email"},
     *         @OA\Property(
     *           property="email",
     *           type="string",
     *           description="Email"
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
    public function resendotp(Request $request): JsonResponse
    {
        # validate email
        $validatedData = Validator::make($request->all(), $this->getValidationRule2fa());

        # check validation
        if ($validatedData->fails()) {
            return $this->sendError(__('common.validaton_error'), $validatedData->errors());
        }
        $email = $request->input('email');

        // Retrieve the user record based on the email
        $user = User::where('email', $email)->first();

        // Check if the user exists
        if (!$user) {
            return $this->sendNotFound(__('auth.user_not_found'));
        }

        $twoFactorCreatedAt = Carbon::parse($user->two_factor_created_at);

        // Calculate the expiration time by adding 30 minutes to the creation time
        $expirationTime = $twoFactorCreatedAt->addMinutes(30);
        $resendCount = $user->otp_resend_count;
        $newCount = $resendCount + 1;
        $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $currentDateTime = Carbon::now();
        // OTP will expire after 30 minutes

        if (isset($resendCount) && ($resendCount > 3) && (Carbon::now()->lt($expirationTime))) {

            return $this->sendError('Validation error', __('resendotp.exceededotp'));

        } else if (isset($resendCount) && ($resendCount > 3) && (Carbon::now()->gte($expirationTime))) {
            
            // Send the two-factor OTP notification to the user
            $user->resendTwoFactorOtpNotification($otp);

            // Update the user record with the OTP and creation date
            $user->two_factor_codes = $otp;
            $user->two_factor_created_at = $currentDateTime;
            $user->otp_resend_count = 1;
            $user->save();
            // Return a JSON response indicating success or any other relevant data
            return $this->sendSuccess([], __('resendotp.successful'));
        } else if (isset($resendCount) && ($resendCount <= 3) && (Carbon::now()->lt($expirationTime))) {
            // If the user try to  Resend within 30 minutes, we will return the same OTP
            $user->otp_resend_count      = $newCount;
            $user->two_factor_codes      = $user->two_factor_codes ? $user->two_factor_codes : $otp;
            $user->two_factor_created_at = $user->two_factor_created_at ? $user->two_factor_created_at : $currentDateTime;
            $user->resendTwoFactorOtpNotification($otp);
            $user->save();
            return $this->sendSuccess([], __('resendotp.successful'));
        } else {
            $user->resendTwoFactorOtpNotification($otp);
            $user->two_factor_codes = $otp;
            $user->two_factor_created_at = $currentDateTime;
            $user->otp_resend_count = 1;
            $user->save();
            return $this->sendSuccess([], __('resendotp.successful'));
        }
    }


    private function getValidationRule()
    {
        return [
            'email' => ['required'],
            'otp' => ['required', 'numeric', 'digits_between:6,6'],
        ];
    }

    private function getValidationRule2fa()
    {
        return [
            'email' => ['required'],
        ];
    }
}