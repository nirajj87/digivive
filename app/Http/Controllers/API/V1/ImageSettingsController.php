<?php

namespace App\Http\Controllers\API\V1;

use App\Models\AwsImageSettings;
use App\Models\CloudinaryImageSettings;
use Illuminate\Http\Request;
use App\Http\Resources\ImageSettingsResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Enums\ImageStorageType;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\V1\BaseController as BaseController;

class ImageSettingsController extends BaseController
{

    public function show(Request $request, $user_id)
    {
        try {
            $storageType = ImageStorageType::toArray();
            $awsImageSettings = AwsImageSettings::where('user_id', $user_id)->first();
            $cloudinaryImageSettings = CloudinaryImageSettings::where('user_id', $user_id)->first();
            return $this->sendSuccess(ImageSettingsResource::ImageSettingsDetailArray($storageType,$awsImageSettings,$cloudinaryImageSettings), __('image_settings.detail'));

        } catch (\Exception $e) {
            print_r($e->getMessage()); die;
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            //print_r($request->all());die;
            // Validate Image Settings
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $user_id = $request->input('user_id');
            $storage_type = json_decode($request->input('storage_type'));

            // Save or Update AWS Image Settings
            if ($storage_type[0]->slug === 'aws') {
                $awsData = json_decode($request->input('aws'));
                $data = [
                    'storage_type'                  => $awsData->storage_type,
                    'copy_credential_same_as_aws'   => $awsData->copy_credential_same_as_aws,
                    'key'                           => $awsData->key,
                    'secret'                        => $awsData->secret,
                    'region'                        => $awsData->region,
                    'default_acl'                   => $awsData->default_acl,
                    'bucket'                        => $awsData->bucket,
                    'row_folder'                    => $awsData->row_folder,
                    'content_folder'                => $awsData->content_folder,
                    'backup_folder'                 => $awsData->backup_folder,
                    'user_id'                       => $user_id,
                    'path_format'                   => $awsData->path_format,
                    'cdn_url'                       => $awsData->cdn_url,
                    'is_selected'                   => $awsData->is_selected ?? 0,
                    'status'                        => $awsData->status ?? 0,
                ];
                AwsImageSettings::updateOrCreate(['user_id' => $user_id],$data);
                //AwsImageSettings::where('user_id', $user_id)->update($data);
            } 

            // Save or Update Cloudinary Image Settings
            if ($storage_type[1]->slug === 'cloudinary') {
                $cloudinaryData = json_decode($request->input('cloudinary'));
                $data = [
                    'storage_type'                  => $cloudinaryData->storage_type,
                    'key'                           => $cloudinaryData->key,
                    'secret'                        => $cloudinaryData->secret,
                    'bucket'                        => $cloudinaryData->bucket,
                    'status'                        => $cloudinaryData->status ?? 0,
                    'cdn_url'                       => $cloudinaryData->cdn_url,
                    'is_selected'                   => $cloudinaryData->is_selected ?? 0,
                    'user_id'                       => $user_id,
                ];
                CloudinaryImageSettings::updateOrCreate(['user_id' => $user_id],$data);
            }
            return $this->sendCreated([], __('image_settings.update_success'));
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
            'storage_type' => ['required'],
            'aws' => ['required'],
            'cloudinary' => ['required'],
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
