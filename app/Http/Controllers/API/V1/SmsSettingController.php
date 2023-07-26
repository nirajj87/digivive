<?php

namespace App\Http\Controllers\API\V1;

use App\Models\SmsSetting;
use Illuminate\Http\Request;

use App\Http\Resources\SmsSettingResources;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;

class SmsSettingController extends BaseController
{
    /**
     * Display the SMS setting details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/v1/sms-setting/details",
     *     operationId="showSmsSetting",
     *     tags={"SMS Setting"},
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
     *         description="SMS setting details not found",
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
            $clientSmtp = SmsSetting::where('user_id', $user_id)->first();

            if (!$clientSmtp) {
                return $this->sendError(__('sms_setting.details_not_found'));
            }

            return $this->sendSuccess(new SmsSettingResources($clientSmtp), __('sms_setting.detail'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Update the SMS setting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SmsSetting  $smsSetting
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *     path="/v1/sms-setting/edit/",
     *     operationId="updateSmsSetting",
     *     tags={"SMS Setting"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="smsSetting",
     *         in="path",
     *         description="SMS Setting ID",
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
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function update(Request $request, SmsSetting $smsSetting): JsonResponse
    {
        try {
            // Validate Client SMTP Settings
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            // Find the specific smsSetting by user_id if provided
            $user_id = $request->input('user_id');
            if ($user_id !== '') {
                $smsSettingData = $smsSetting->where('user_id', $user_id)->first();

                if ($smsSettingData) {
                    // Update the existing smsSetting
                    $smsSettingData->fill($request->all());
                    $smsSettingData->save();
                    // Return response
                    return $this->sendCreated([], __('sms_setting.update_success'));
                } else {
                    // Create a new smsSetting
                    $smsSettingData = new SmsSetting($request->all());
                    $smsSettingData->save();
                    // Return response
                    return $this->sendCreated([], __('sms_setting.update_success'));
                }
            } else {
                // Update the smsSetting properties
                $smsSetting->fill($request->all());
                $smsSetting->save();
                // Return response
                return $this->sendCreated([], __('sms_setting.success'));
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
            'title' => ['required'],
            'template' => ['required'],
            'variables' => ['required'],
            'status' => ['required'],
            'user_id' => ['required', 'integer']
        ];
    }
    private function getValiditionRuleDetails()
    {
        return [
            'user_id' => ['required', 'integer']
        ];
    }
}