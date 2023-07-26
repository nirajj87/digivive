<?php
namespace App\Http\Controllers\API\V1;
use App\Models\AwsImageSetting;
use Illuminate\Http\Request;
use App\Http\Resources\AwsImageSettingResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use App\Enums\ImageStorageType;

class AwsImageSettingController extends BaseController
{
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
            $awsimagesetting = AwsImageSetting::where('user_id', $user_id)->get();

            if (!$awsimagesetting) {
                return $this->sendError(__('aws_image_settings.details_not_found'));
            }

            return $this->sendSuccess(new AwsImageSettingResource($awsimagesetting), __('aws_image_settings.detail'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }
    public function update(Request $request, AwsImageSetting $awsImageSetting): JsonResponse
    {
        try {
            // Validate Client SMTP Settings
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            // Find the specific awsImageSetting by user_id if provided
            $user_id = $request->input('user_id');
            if ($user_id !== '') {
                $awsImageSettingData = $awsImageSetting->where('user_id', $user_id)->first();

                if ($awsImageSettingData) {
                    // Update the existing awsImageSetting
                    $awsImageSettingData->fill($request->all());
                    $awsImageSettingData->save();
                    // Return response
                    return $this->sendCreated([], __('aws_image_settings.update_success'));
                } else {
                    // Create a new awsImageSetting
                    $awsImageSettingData = new AwsImageSetting($request->all());
                    $awsImageSettingData->save();
                    // Return response
                    return $this->sendCreated([], __('aws_image_settings.update_success'));
                }
            } else {
                // Update the awsImageSetting properties
                $awsImageSetting->fill($request->all());
                $awsImageSetting->save();
                // Return response
                return $this->sendCreated([], __('aws_image_settings.success'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    public function ImageStorageType(): JsonResponse
    {
        try {
            $imageStorageType = ImageStorageType::toArray();

            return $this->sendListWithSuccess(new AwsImageSettingResource($imageStorageType), __('aws_image_settings.search_type'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }
    private function getValiditionRule()
    {
        return [
            'storage_type' => ['required'],
            'key' => ['required'],
            'secret' => ['required'],
            'region' => ['required'],
            'default_acl' => ['required'],
            'bucket' => ['required'],
            'row' => ['required'],
            'content' => ['required'],
            'backup' => ['required'],
            'user_id' => ['required', 'integer'],
            'cdn_url' => ['required'],
            'status' => ['required'],
        ];
    }
    private function getValiditionRuleDetails()
    {
        return [
            'user_id' => ['required', 'integer']
        ];
    }
}
