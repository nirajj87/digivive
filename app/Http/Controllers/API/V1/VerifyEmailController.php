<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Http\Controllers\API\V1\BaseController as BaseController;

class VerifyEmailController extends BaseController
{
    /**
     * Verify user email
     *
     * @OA\Get(
     * path="/verify/{id}/{hash}",
     *   tags={"Signup"},
     *   summary="Verify user email",
     *   @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of the user to retrieve",
    *         required=true,
    *         @OA\Schema(
    *             type="integer"
    *         )
    *     ),
    *      @OA\Parameter(
    *         name="hash",
    *         in="path",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
     *   @OA\Parameter(
     *      name="expires",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="signature",
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
    public function verify(Request $request): JsonResponse
    {
        try {
            if (!$request->hasValidSignature()) {
                return $this->sendError(__('common.validaton_error'), __('register.invalid_verification_url'));
            }
    
            $user = User::find($request->route('id'));
            
            if ($user->hasVerifiedEmail()) {
                return $this->sendError([], __('register.already_verified'));
            }
    
            if ($user->markEmailAsVerified()) {

                #activate user
                $user->status = 1;
                $user->save();

                event(new Verified($user));
            }
            
            #return response
            return $this->sendCreated([], __('register.verified'));

        } catch (\Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }

    /**
     * Resend user verification email
     * 
     * @OA\Get(
     ** path="/email/verify/resend",
     *   tags={"Signup"},
     *   summary="Resend email",
     *  security={{"bearerAuth": {}}},
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
    public function resend(Request $request): JsonResponse
    {
        try {
            if (auth()->user()->hasVerifiedEmail()) {
                return $this->sendError([], __('register.already_verified'));
            }
            
            #send verification email
            auth()->user()->sendEmailVerificationNotification();
        
            #return response
            return $this->sendSuccess([], __('register.verification_link_resent'));

        } catch (\Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
        }
    }
}