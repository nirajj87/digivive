<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\TranscodingSettings;

use App\Http\Resources\TranscodingSettingsResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;

class TranscodingSettingsController extends BaseController
{
    public function show(Request $request, $user_id)
    {

        try {
            $transcoding = TranscodingSettings::where('user_id', $user_id)->first();

            return $this->sendSuccess(new TranscodingSettingsResource($transcoding), __('transcoding_settings.detail'));
        } catch (\Exception $e) {
            print_r($e->getMessage()); die;
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    public function update(Request $request): JsonResponse
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
            $data = [
                'video_presets_id' => $request->video_presets,
                'video_download_type_id' => $request->video_download_type,
                'audio_presets_id' => $request->audio_presets,
                'audio_download_type_id' => $request->audio_download_type,
                'status' => $request->status
            ];
            TranscodingSettings::updateOrCreate(['user_id' => $user_id],$data);
            return $this->sendCreated([], __('transcoding_settings.update_success'));
            
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
            'video_presets' => ['required'],
            'video_download_type' => ['required'],
            'audio_presets' => ['required'],
            'audio_download_type' => ['required'],
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
