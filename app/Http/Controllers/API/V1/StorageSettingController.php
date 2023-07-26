<?php

namespace App\Http\Controllers\API\v1;

use App\Models\AwsStorageSetting;
use App\Models\WowzaStorageSetting;
use Illuminate\Http\Request;
use App\Http\Resources\StorageSettingResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use App\Enums\StorageType;
use App\Http\Controllers\Controller;



class StorageSettingController extends BaseController
{

    public function details(Request $request, $user_id)
    {
        try {
            $storageType = StorageType::toArray();
            $wowzaStorageSetting = WowzaStorageSetting::where('user_id', $user_id)->first();
            $awsStorageSetting = AwsStorageSetting::where('user_id', $user_id)->first();
            return $this->sendSuccess(StorageSettingResource::StorageSettingsDetailArray($storageType,$awsStorageSetting,$wowzaStorageSetting), __('storage_settings.detail'));

        } catch (\Exception $e) {
            print_r($e->getMessage());
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    public function update(Request $request, AwsStorageSetting $awsStorageSetting): JsonResponse
    {
        try {
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $user_id = $request->input('user_id');
            $storage_type = json_decode($request->input('storage_type'));
            // Save or Update AWS Storage Settings
            if ($storage_type[0]->slug === 'aws') {
                $awsData = json_decode($request->input('aws'));
                $data = [
                    'storage_type' => $awsData->storage_type,
                    'key' => $awsData->key,
                    'secret' => $awsData->secret,
                    'region' => $awsData->region,
                    'default_acl' => $awsData->default_acl,
                    'bucket' => $awsData->bucket,
                    'row_folder' => $awsData->row_folder,
                    'content_folder' => $awsData->content_folder,
                    'backup_folder' => $awsData->backup_folder,
                    'user_id' => $user_id,
                    'cdn_url' => $awsData->cdn_url,
                    'is_selected' => $awsData->is_selected,
                    'status' => $awsData->status,
                ];
                AwsStorageSetting::updateOrCreate(['user_id' => $user_id],$data);
            } 

            // Save or Update Wowza Storage Settings
            if ($storage_type[1]->slug === 'wowza') {
                $wowzaData = json_decode($request->input('wowza'));
                $data = [
                    'storage_type' => $wowzaData->storage_type,
                    'host' => $wowzaData->host,
                    'user_name' => $wowzaData->user_name,
                    'password' => $wowzaData->password,
                    'content_path' => $wowzaData->content_path,
                    'wowza_path_format' => $wowzaData->wowza_path_format,
                    'user_id' => $user_id,
                    'cdn_url' => $wowzaData->cdn_url,
                    'status' =>$wowzaData->status,
                    'is_selected' =>$wowzaData->is_selected
                ];
               WowzaStorageSetting::updateOrCreate(['user_id' => $user_id],$data);
            }
            return $this->sendCreated([], __('storage_settings.update_success'));
        } catch (Exception $e) {
            print_r($e->getMessage());die;
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'storage_type' => ['required'],
            'aws' => ['required'],
            'wowza' => ['required'],
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