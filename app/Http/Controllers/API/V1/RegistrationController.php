<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Exception;
use Mail; 
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password as PasswordRules;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;

class RegistrationController extends BaseController
{
    use Notifiable;

    /**
     * Create a new user in system
     * 
     * @OA\Post(
     * path="/v1/register",
     *   tags={"Signup"},
     *   summary="Register new user",
     *
     *   @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ), 
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
     * @OA\Tag(
     *     name="Signup",
     *     description="Create a new user"
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) : JsonResponse
    {
        try {
            
            #validate user
            $validatedData = Validator::make($request->all(), [
                'name'      => ['required'],
                'email'     => ['required','email','unique:users,email'],
                'role_id'   => ['nullable', 'integer']
            ]);
            
            #check validation
            if ($validatedData->fails()){
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());  
            }
            $randomPassword = Str::random(10);
            #user data
            $data = [
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'password'  => Hash::make($randomPassword),
                'role_id'   => $request->input('role_id') ?? 1,
                'permission'=> $request->input('permission') ?? null,
            ];
            
            #invoke register event of laravel
            event(new Registered($user = User::create($data)));
            //echo $randomPassword;die;
            #send verification email
            $user->sendEmailVerificationNotification();
            $user->sendRandomPasswordNotification($randomPassword);

            #return response
            return $this->sendCreated([], __('register.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }
    

    /**
     * Forgot Password
     * 
     * * @OA\POST(
     ** path="/v1/password/forgot",
     *   tags={"Signup"},
     *   summary="Forgot password",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
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
    public function forgotPassword(Request $request) : JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required','email', 'exists:users'],
            ]);
    
            if ($validator->fails()){
               return $this->sendError(__('common.validaton_error'), $validator->errors());  
            }
    
            $status = Password::sendResetLink($request->only('email'));
            
            if ($status == Password::RESET_LINK_SENT) {
                return $this->sendCreated([], __($status));
            } else {
                return $this->sendError(__('register.password_link_error'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }

    /**
     * Reset Password
     * 
     * @OA\Post(
     ** path="/v1/password/reset",
     *   tags={"Signup"},
     *   summary="Reset password",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="token",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ), 
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
    public function resetPassword(Request $request) : JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'exists:users'],
                'token' => ['required'],
                'password'  => ['required', PasswordRules::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed']
            ]);
    
            if ($validator->fails()){
                return $this->sendError(__('common.validaton_error'), $validator->errors());  
             }
    
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();
    
                    //$user->tokens()->delete();

                    //event(new PasswordReset($user));
                }
            );
    
            if ($status == Password::PASSWORD_RESET) {
                return $this->sendCreated([], __('register.password_reset_success'));
            } else {
                return $this->sendUnauthorized(__('register.password_reset_token_expired', []));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }
}
