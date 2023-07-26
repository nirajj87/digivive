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

class AuthController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
        // $this->middleware('cors');
    }


    /**
     * @OA\Post(
     *   path="/v1/login",
     *   tags={"Auth"},
     *   summary="Login",
     *   description="Login user",
     *
     *   @OA\RequestBody(
     *       @OA\JsonContent(),
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *       response=201,
     *       description="Login Successfully",
     *       @OA\JsonContent()
     *   ),
     *   @OA\Response(
     *       response=200,
     *       description="Login Successfully",
     *       @OA\JsonContent()
     *   ),
     *   @OA\Response(
     *       response=422,
     *       description="Unprocessable Entity",
     *       @OA\JsonContent()
     *   ),
     *   @OA\Response(response=400, description="Bad request"),
     *   @OA\Response(response=404, description="Resource Not Found")
     * )
     *
     * @OA\Tag(
     *   name="Auth",
     *   description="Authentication and authorization operations"
     * )
     *
     * Login users in the system
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'     => ['required','email'],
                'password'  => ['required']
            ]);

            #check credentials
            $credentials = $request->only('email', 'password');

            #get token
            $token = Auth::attempt($credentials);
            
            if (!$token) {
                #return response
                return $this->sendServerError(__('auth.failed'), []);
            }

            #get user details
            $user = Auth::user();
            
            if ($user->status == 0) {
                $data = ['authorisation' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ]];
                return $this->sendUnauthorized(__('auth.account_inactive'), $data);
                
            } else if ($user->status == 2) {
                return $this->sendUnauthorized(__('auth.account_disabled'));

            //temporary 2factor login dessable original ""is_two_factor_enable" => value is 1 or 0 but currently set 10
            }else if($user->is_two_factor_enable == 10){

                $this->sendOtp($user);

                return $this->sendError(__('auth.two_factor_otp'));
            }else {

                $data = ['user' => new UserResource($user), 'authorisation' => ['token' => $token, 'type' => 'bearer']];
                return $this->sendSuccess($data, __('auth.successful'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }

    
    /**
     * Logout User 
     * 
     * @OA\Post(
     ** path="/v1/logout",
     *   tags={"Auth"},
     *   summary="Logout",
     *   description="Logout user",
     *   security={{"bearerAuth": {}}},
     * 
     *   @OA\Response(
    *          response=201,
    *          description="Logout Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=200,
    *          description="Logout Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=422,
    *          description="Unprocessable Entity",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    * )
     * Logout users from system
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            #invalidate JWT Token
            Auth::logout();
 
            #return response
            return $this->sendSuccess([], __('auth.logout'));

        } catch (Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }

    /**
     * Refresh token for user
     * 
     * * @OA\POST(
     ** path="/v1/refresh",
     *   tags={"Auth"},
     *   summary="Refresh token",
     *   security={{"bearerAuth": {}}},
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden"
     *   )
     *)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request): JsonResponse
    {
        try { 
            if (auth()->user()) {
                
                $user   = auth()->user();
                $token  = auth()->refresh();
                
                #return response
                $data = ['user' => new UserResource($user), 'authorisation' => ['token' => $token, 'type' => 'bearer']];
                return $this->sendCreated($data, __('auth.refresh_token'));
            } else {

            }
        } catch (Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }
    //send 2FA otp on email and save users table
    public function sendOtp($user): JsonResponse
    {
        $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $currentDateTime = Carbon::now();
        $user->sendTwoFactorOtpNotification($otp);
        $user->two_factor_codes = $otp;
        $user->two_factor_created_at = $currentDateTime;
        $user->save();

        // Return a JSON response indicating success or any other relevant data
        return $this->sendSuccess([], __('auth.successful'));
    }
   
}
