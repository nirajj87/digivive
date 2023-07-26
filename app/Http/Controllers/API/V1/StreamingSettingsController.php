<?php

namespace App\Http\Controllers\API\V1;

use App\Models\StreamingSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Http\Resources\StreamingSettingsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
 
class StreamingSettingsController extends BaseController
{

    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/v1/streaming-setting/list",
     *     tags={"Streaming Setting (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of streaming setting",
     *     description="Retrieve a list of streaming setting with status = 1",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="Streaming Setting (Master)",
     *     description="Streaming Setting related operations"
     * )
     */

    public function index(Request $request): JsonResponse
    {
        $query = StreamingSettings::query();

        // Sorting
        $sortBy = $request->input('sortBy');
        $sortDirection = $request->input('sortDirection');

        if (isset($sortBy) && isset($sortDirection)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Filtering by title
        $search = $request->input('search');

        if (isset($search)) {
            $query->where('title', 'ILIKE', '%' . $search . '%');
        }

        // Pagination
        $perPage = $request->input('perPage');

        if (isset($perPage) && $perPage !== '') {
            $per_page = intval($perPage);
        } else {
            $per_page = self::DEFAULT_PER_PAGE;
        }

        $streamSettingList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(StreamingSettingsResource::collection($streamSettingList), __('streaming_setting.list'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/v1/streaming-setting/create",
     *     summary="Create streaming setting",
     *     tags={"Streaming Setting (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Streaming setting data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="example",
     *                     description="Title of the streaming setting"
     *                 ),
     *                 @OA\Property(
     *                     property="order",
     *                     type="integer",
     *                     example=1,
     *                     description="Order of the streaming setting"
     *                 ),
     *                 @OA\Property(
     *                     property="additional_settings",
     *                     type="json",
     *                     example="['color':'red']",
     *                     description="Additional setting of the streaming setting"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Streaming setting Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */


    public function store(Request $request): JsonResponse
    {
        try {

            #validate streaming setting
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = StreamingSettings::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the streaming setting
            #streaming setting data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'additional_settings' => $request->input('additional_settings', null),
                'status' => $request->input('status', 1)
            ];

            #create streaming setting
            StreamingSettings::create($data);

            #return response
            return $this->sendCreated([], __('streaming_setting.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/v1/streaming-setting/details/{id}",
     *     tags={"Streaming Setting (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get streaming setting details",
     *     description="Get the details of a specific streaming setting by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the streaming setting",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function details($id): JsonResponse
    {
        try {
            $streamingSetting = StreamingSettings::findOrFail($id);
            return $this->sendSuccess(new StreamingSettingsResource($streamingSetting), __('streaming_setting.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(StreamingSettings $streamingSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/v1/streaming-setting/edit/{id}",
     *     summary="Update streaming setting",
     *     tags={"Streaming Setting (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the streaming setting",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="streaming setting data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Title",
     *                 description="Title of the streaming setting"
     *             ),
     *             @OA\Property(
     *                 property="order",
     *                 type="integer",
     *                 example="1",
     *                 description="Order of the streaming setting"
     *             ),
     *             @OA\Property(
     *                 property="additional_settings",
     *                 type="string",
     *                 example="['color':'red']",
     *                 description="Additional Settings of the streaming setting"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="streaming setting updated",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */


    public function update(Request $request, StreamingSettings $id): JsonResponse
    {
        try {
            # Validate streaming setting
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = StreamingSettings::getUniqueSlug(Str::slug($title, "-"));

            // Update the streaming setting attributes
            $fieldsToUpdate = [
                'title' => $title,
                'slug' => $slug,
                'additional_settings' => $request->input('additional_settings', $id->additional_settings),
                'status' => $request->input('status', $id->status),
            ];

            $id->update($fieldsToUpdate);

            // Return response
            return $this->sendCreated([], __('streaming_setting.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/v1/streaming-setting/delete/{id}",
     *     tags={"Streaming Setting (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete streaming setting",
     *     description="Delete a specific streaming setting",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the streaming setting",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=500, description="Server Error")
     * )
     */
    public function destroy(StreamingSettings $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new StreamingSettingsResource($id), __('streaming_setting.delete_success'));
        } catch (Exception $e) {
            // Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'additional_settings' => ['json']
        ];
    }
}
