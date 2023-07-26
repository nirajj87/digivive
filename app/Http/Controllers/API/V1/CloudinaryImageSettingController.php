<?php
namespace App\Http\Controllers\API\V1;
use App\Models\CloudinaryImageSetting;
use Illuminate\Http\Request;
use App\Http\Resources\CloudinaryImageSettingResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;

class CloudinaryImageSettingController extends BaseController
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
            $cloudinaryImageSetting = CloudinaryImageSetting::where('user_id', $user_id)->first();

            if (!$cloudinaryImageSetting) {
                return $this->sendError(__('cloudinary_image_storage_settings.details_not_found'));
            }

            return $this->sendSuccess(new CloudinaryImageSettingResource($cloudinaryImageSetting), __('cloudinary_image_storage_settings.detail'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }
    public function update(Request $request, CloudinaryImageSetting $cloudinaryImageSetting): JsonResponse
    {
        try {
            // Validate Client SMTP Settings
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            // Find the specific wowzaStorageSetting by user_id if provided
            $user_id = $request->input('user_id');
            if ($user_id !== '') {
                $cloudinaryImageSettingData = $cloudinaryImageSetting->where('user_id', $user_id)->first();

                if ($cloudinaryImageSettingData) {
                    // Update the existing wowzaStorageSetting
                    $cloudinaryImageSettingData->fill($request->all());
                    $cloudinaryImageSettingData->save();
                    // Return response
                    return $this->sendCreated([], __('cloudinary_image_storage_settings.update_success'));
                } else {
                    // Create a new wowzaStorageSetting
                    $cloudinaryImageSettingData = new CloudinaryImageSetting($request->all());
                    $cloudinaryImageSettingData->save();
                    // Return response
                    return $this->sendCreated([], __('cloudinary_image_storage_settings.update_success'));
                }
            } else {
                // Update the wowzaStorageSetting properties
                $cloudinaryImageSetting->fill($request->all());
                $cloudinaryImageSetting->save();
                // Return response
                return $this->sendCreated([], __('cloudinary_image_storage_settings.success'));
            }
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
            'bucket' => ['required'],
            'user_id' => ['required', 'integer'],
            'status' => ['required'],
            'cdn_url' => ['required'],


        ];
    }
    private function getValiditionRuleDetails()
    {
        return [
            'user_id' => ['required', 'integer']
        ];
    }
}
