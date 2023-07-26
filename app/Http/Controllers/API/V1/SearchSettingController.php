<?php

namespace App\Http\Controllers\API\V1;

use App\Models\AlgoliaSearchSettings;
use App\Models\TypesenseSearchSettings;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\SearchSettingResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Enums\SearchType;

class SearchSettingController extends BaseController
{

    public function details(Request $request, $user_id)
    {
        try {
            $searchType = SearchType::toArray();
            $algoliaSearchSettings = AlgoliaSearchSettings::where('user_id', $user_id)->first();
            $typesenseSearchSettings = TypesenseSearchSettings::where('user_id', $user_id)->first();
            return $this->sendSuccess(SearchSettingResource::SearchSettingsDetailArray($searchType,$algoliaSearchSettings,$typesenseSearchSettings), __('search_settings.detail'));

        } catch (\Exception $e) {
            print_r($e->getMessage());
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $user_id = $request->input('user_id');
            $search_type = json_decode($request->input('search_type'));

            // Save or Update Algolia Search Settings
            if ($search_type[0]->slug === 'algolia') {
                $algoliaData = json_decode($request->input('algolia'));
                $data = [
                    'app_id' => $algoliaData->app_id,
                    'key' => $algoliaData->key,
                    'collection_name' => $algoliaData->collection_name,
                    'host' => $algoliaData->host,
                    'port' => $algoliaData->port,
                    'protocol' => $algoliaData->protocol,
                    'is_selected' => $algoliaData->is_selected ?? 0,
                    'status' => $algoliaData->status ?? 0
                ];
               // print_r($data);die;
                AlgoliaSearchSettings::updateOrCreate(['user_id' => $user_id],$data);
            } 

            // Save or Update Typesense Search Settings
            if ($search_type[1]->slug === 'typesense') {
                $typesenseData = json_decode($request->input('typesense'));
                $data = [
                    'app_id' => $typesenseData->app_id,
                    'key' => $typesenseData->key,
                    'collection_name' => $typesenseData->collection_name,
                    'host' => $typesenseData->host,
                    'port' => $typesenseData->port,
                    'protocol' => $typesenseData->protocol,
                    'is_selected' => $typesenseData->is_selected ?? 0,
                    'status' => $typesenseData->status ?? 0
                ];
                TypesenseSearchSettings::updateOrCreate(['user_id' => $user_id],$data);
            }
            return $this->sendCreated([], __('search_settings.update_success'));
        } catch (Exception $e) {
            print_r($e->getMessage());die;
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    public function searchType(): JsonResponse
    {
        try {
            $searchTypes = SearchType::toArray();

            return $this->sendListWithSuccess(new SearchSettingResource($searchTypes), __('search.search_type'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'search_type' => ['required'],
            'algolia' => ['required'],
            'typesense' => ['required'],
            'user_id' => ['required']
        ];
    }
    private function getValiditionRuleUserId()
    {
        return [
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