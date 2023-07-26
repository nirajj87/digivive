<?php

namespace App\Http\Controllers\API\V1;

use App\Models\ClientSettings;
use App\Http\Resources\ClientSettingsResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\V1\BaseController as BaseController;

class ClientSettingsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = ClientSettings::all();
        return $this->sendListWithSuccess(ClientSettingsResource::collection($settings), __('client_settings.list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ClientSettings $clientSettings)
    {
        try{
            return $this->sendSuccess(new ClientSettingsResource($clientSettings), __('client_settings.detail'));
        } catch(\Exception $e){
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }
    
     
    /**
     * Store a newly created resource in storage.
     *   
     * @OA\Post(
     *   path="/v1/client-settings",
     *   summary="Create Client Settings",
     *   tags={"Client Settings (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"video_profile_settings", "audio_profile_setting", "status"},
     *         @OA\Property(
     *           property="video_profile_settings",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="audio_profile_setting",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="status",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="user_id",
     *           type="string"
     *         ),
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Client Settings created successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       
     *       @OA\Property(property="message", type="string")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Validation Error"),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="Server Error")
     * )
     */
    public function store(Request $request)
    {
        try {
            
            #validate
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());
            
            #check validation
            if ($validatedData->fails()){
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());  
            }

            #Settings data
            $data = [
                'video_profile_settings'       => $request->input('video_profile_settings'),
                'audio_profile_setting'        => $request->input('audio_profile_setting'),
                'status'                       => $request->input('status'),
            ];
            
            #create settings
            $settings = ClientSettings::updateOrCreate(['user_id' => $request->input('user_id')],$data);

            #return response
            return $this->sendCreated([], __('client_settings.successful'));

        } catch (\Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientSettings $clientSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClientSettings $clientSettings)
    {
        try {
            
            #validate client settings
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());
            
            #check validation
            if ($validatedData->fails()){
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());  
            }
           
            $clientSettings->user_id = $request->input('user_id');
            $clientSettings->video_profile_settings = $request->input('video_profile_settings');
            $clientSettings->audio_profile_setting = $request->input('audio_profile_setting');
            $clientSettings->status  = $request->input('status'); 
            
            #update client settings
            $clientSettings->update();

            #return response
            return $this->sendCreated([], __('client_settings.update_success'));

        } catch (\Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *   path="/v1/client-settings/{device_id}",
     *   summary="Delete Client Settings",
     *   tags={"Client Settings (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="device_id",
     *     in="path",
     *     required=true,
     *     description="ID of the client setting",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Client setting deleted successfully",
     *     @OA\JsonContent(
     *       type="object",
     *  
     *       @OA\Property(property="message", type="string")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="Server Error")
     * )
     */
    public function destroy(ClientSettings $clientSettings)
    {
        try{
            $clientSettings->delete();
            return $this->sendCreated(new ClientSettingsResource($clientSettings), __('client_settings.delete_success'));
        } catch(\Exception $e){
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }

    
    /**
     * Display the user settings resource.
     *
     * @OA\Get(
     *   path="/v1/client-settings/get-user-settings/{user_id}",
     *   summary="Get User Client Settings Details",
     *   tags={"Client Settings (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="user_id",
     *     in="path",
     *     required=true,
     *     description="ID of the user",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="User Client Settings retrieved successfully",
     *     @OA\JsonContent(
     *       type="object",
     *  
     *       @OA\Property(property="message", type="string")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="Server Error")
     * )
     */
    public function getUserSettings($user_id)
    {
        try{
            $clientSettings = ClientSettings::where('user_id', $user_id)->first();
            return $this->sendSuccess(new ClientSettingsResource($clientSettings), __('client_settings.detail'));
        } catch(\Exception $e){
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }

    /**
     * Return the validation rule for add/update record 
    */
    private function getValiditionRule(){
        return [
            'user_id'                   => ['required', 'integer'],
            'video_profile_settings'    => ['required'],
            'audio_profile_setting'     => ['required'],
            'status'                    => ['required'],
        ];
    }
}
