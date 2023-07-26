<?php

namespace App\Http\Controllers\API\V1;

use App\Models\ClientDeviceRestriction;
use Illuminate\Http\Request;
use App\Http\Resources\ClientDeviceRestrictionResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\V1\BaseController as BaseController;

class ClientDeviceRestrictionController extends BaseController
{



    /**
     * Display the specified resource.
     */
    /**
     * @OA\Post(
     *     path="/device-restrictions/details/",
     *     summary="Get device restriction details",
     *     description="Returns the details of a specific device restriction",
     *     operationId="showDeviceRestriction",
     *     tags={"Client Device Restrictions (Advance Setting)"},
     *     security={{"Bearer": {}}},
     *     @OA\Parameter(
     *         name="deviceRestriction",
     *         in="path",
     *         description="ID of the device restriction",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
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
            $deviceData = ClientDeviceRestriction::where('user_id', $user_id)->first();

            if (!$deviceData) {
                return $this->sendError(__('client_device_restrictions.not_found'));
            }

            return $this->sendSuccess(new ClientDeviceRestrictionResource($deviceData), __('client_device_restrictions.detail'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Update resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/device-restrictions/edit/",
     *     summary="Update device restriction",
     *     description="Update the attributes of a specific device restriction",
     *     operationId="updateDeviceRestriction",
     *     tags={"Client Device Restrictions (Advance Setting)"},
     *     security={{"Bearer": {}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User Id of the device restriction",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="number_of_device",
     *                 type="integer",
     *                 description="The number of devices allowed",
     *                 example=5
     *             ),
     *             @OA\Property(
     *                 property="os_id",
     *                 type="integer",
     *                 description="The ID of the operating system",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="api_duration",
     *                 type="integer",
     *                 description="The duration of API access in minutes",
     *                 example=60
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="The status of the device restriction",
     *                 enum={"active", "inactive"}
     *             ),
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 description="The ID of the user associated with the device restriction",
     *                 example=10
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Validation error message"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example="Validation errors"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *     )
     * )
     */
    public function update(Request $request, ClientDeviceRestriction $clientDeviceRestriction): JsonResponse
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
                $clientDeviceData = $clientDeviceRestriction->where('user_id', $user_id)->first();

                if ($clientDeviceData) {
                    // Update the existing clientSmtp
                    $clientDeviceData->fill($request->all());
                    $clientDeviceData->save();
                    // Return response
                    return $this->sendCreated([], __('client_device_restrictions.update_success'));
                } else {
                    // Create a new clientSmtp
                    $clientSmtpData = new ClientDeviceRestriction($request->all());
                    $clientSmtpData->save();
                    // Return response
                    return $this->sendCreated([], __('client_device_restrictions.update_success'));
                }
            } else {
                // Update the clientSmtp properties
                $clientDeviceRestriction->fill($request->all());
                $clientDeviceRestriction->save();
                // Return response
                return $this->sendCreated([], __('client_device_restrictions.success'));
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
            'number_of_device' => ['required'],
            'os_id' => ['required'],
            'api_duration' => ['required'],
            'status' => ['required'],
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