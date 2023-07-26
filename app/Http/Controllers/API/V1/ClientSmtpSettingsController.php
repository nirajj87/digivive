<?php

namespace App\Http\Controllers\API\V1;

use App\Models\ClientSmtpSettings;
use Illuminate\Http\Request;
use App\Http\Resources\ClientSmtpResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;

class ClientSmtpSettingsController extends BaseController
{
    /**
     * Display the specified resource.
     */
    /**
     * Get client SMTP settings details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/v1/smtp-setting/details",
     *     summary="Get client SMTP settings details",
     *     description="Returns the details of the client SMTP settings for a specific user",
     *     operationId="showClientSmtpSettings",
     *     tags={"Client SMTP Settings"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Client SMTP settings details not found",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function show(Request $request)
    {

        try {
            #validate role
            $validatedData = Validator::make($request->all(), $this->getValiditionRuleDetails());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            $user_id = $request->input('user_id');
            $clientSmtp = ClientSmtpSettings::where('user_id', $user_id)->first();

            if (!$clientSmtp) {
                return $this->sendError(__('client_smtp_settings.details_not_found'));
            }

            return $this->sendSuccess(new ClientSmtpResource($clientSmtp), __('client_smtp_settings.detail'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Update client SMTP settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientSmtpSettings  $clientSmtp
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *     path="/v1/smtp-setting/edit/",
     *     summary="Update client SMTP settings",
     *     description="Update the SMTP settings for a specific user",
     *     operationId="updateClientSmtpSettings",
     *     tags={"Client SMTP Settings"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="clientSmtp",
     *         in="path",
     *         description="Client SMTP Setting ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         
     *     )
     * )
     */
    public function update(Request $request, ClientSmtpSettings $clientSmtp): JsonResponse
    {
        try {
            // Validate Client SMTP Settings
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            // Find the specific clientSmtp by user_id if provided
            $user_id = $request->input('user_id');
            if ($user_id !== '') {
                $clientSmtpData = $clientSmtp->where('user_id', $user_id)->first();

                if ($clientSmtpData) {
                    // Update the existing clientSmtp
                    $clientSmtpData->fill($request->all());
                    $clientSmtpData->save();
                    // Return response
                    return $this->sendCreated([], __('client_smtp_settings.update_success'));
                } else {
                    // Create a new clientSmtp
                    $clientSmtpData = new ClientSmtpSettings($request->all());
                    $clientSmtpData->save();
                    // Return response
                    return $this->sendCreated([], __('client_smtp_settings.update_success'));
                }
            } else {
                // Update the clientSmtp properties
                $clientSmtp->fill($request->all());
                $clientSmtp->save();
                // Return response
                return $this->sendCreated([], __('client_smtp_settings.success'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Return the validation rule for add/update record 
     */
    private function getValiditionRule()
    {
        return [
            'host' => ['required'],
            'user_name' => ['required'],
            'password' => ['required'],
            'port' => ['required'],
            'encryption' => ['required'],
            'from_email' => ['required'],
            'user_id' => ['required']
        ];
    }
    private function getValiditionRuleDetails()
    {
        return [
            'user_id' => ['required', 'integer']
        ];
    }
}