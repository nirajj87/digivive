<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BitframeResource;

use App\Models\Bitframe;
use Illuminate\Http\Request;
use App\Enums\BitFrameType;

class BitframeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/v1/bitframe/list",
     *     tags={"Bitframe (Master)"},
     *     summary="Get bitframe list",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     * @OA\Tag(
     *     name="Bitframe (Master)",
     *     description="Transcoding related operation"
     * )
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Bitframe::query();

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
            $query->where('type', 'ILIKE', '%' . $search . '%');
        }

        // Pagination
        $perPage = $request->input('perPage');

        if (isset($perPage) && $perPage !== '') {
            $per_page = intval($perPage);
        } else {
            $per_page = self::DEFAULT_PER_PAGE;
        }

        $bitframeList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(BitframeResource::collection($bitframeList), __('bitframe.list'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/v1/bitframe/create",
     *     summary="Create a Bitfram",
     *     tags={"Bitframe (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"type", "presets", "is_downloadable", "download_type", "status"},
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     description="Enter Type",
     *                     example="Audio/Video"
     *                 ),
     *                  @OA\Property(
     *                     property="presets",
     *                     type="string",
     *                     description="Enter presets",
     *                     example="144/360/480/720/1080/audio"
     *                 ),
     *                 @OA\Property(
     *                     property="is_downloadable",
     *                     type="integer",
     *                     description="Enter this field downloadable or not",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="download_type",
     *                     type="string",
     *                     description="Enter download type",
     *                     example="HD/SD/HQ"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     description="Enter bitfram status",
     *                     example=1
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            #validate Bitfram
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            #Bitfram data
            $data = [
                'type' => $request->input('type'),
                'presets' => $request->input('presets'),
                'is_downloadable' => $request->input('is_downloadable',0),
                'download_type' => $request->input('download_type'),
                'status' => $request->input('status', 1),
            ];

            #create Bitfram
            $bitfram = Bitframe::create($data);

            #return response
            return $this->sendCreated([], __('bitframe.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/v1/bitframe/details/{id}",
     *     summary="Get details of a Bitfram",
     *     tags={"Bitframe (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Enter ID to get Bitfram details",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     *
     * @param int $id
     * @return JsonResponse
     */

    public function details(Bitframe $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new BitframeResource($id), __('bitframe.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Update a Bitfram resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bitfram  $id
     * @return \Illuminate\Http\JsonResponse

     * @OA\Put(
     *     path="/v1/bitframe/edit/{id}",
     *     summary="Update a Bitfram",
     *     tags={"Bitframe (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Bitfram",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Bitfram details to be updated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="type",
     *                 type="string",
     *                 example="Video/Audio"
     *             ),
     *             @OA\Property(
     *                 property="presets",
     *                 type="string",
     *                 example="144/360/480/720/1080/audio"
     *             ),
     *             @OA\Property(
     *                 property="is_downloadable",
     *                 type="integer",
     *                 format="int64",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="download_type",
     *                 type="string",
     *                 example="HD/SD/HQ"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 format="int32",
     *                 example="1/0"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bitfram updated successfully",
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
     *         description="Server error",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function update(Request $request, Bitframe $id): JsonResponse
    {
        try {
            #validate Bitfram
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            // Update the bitfram attributes
            $id->type = $request->input('type', $id->type);
            $id->presets = $request->input('presets', $id->presets);
            $id->is_downloadable = $request->input('is_downloadable', $id->is_downloadable);
            $id->download_type = $request->input('download_type', $id->download_type);
            $id->status = $request->input('status', $id->status);
            // Save the updated bitfram
            $id->save();

            // Return response
            return $this->sendCreated([], __('bitframe.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/v1/bitframe/delete/{id}",
     *     summary="Delete a Bitfram",
     *     tags={"Bitframe (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Bitfram",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bitfram deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Bitfram deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bitfram not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Bitfram not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Server error"
     *             )
     *         )
     *     )
     * )
     */
    public function destroy(Bitframe $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new BitframeResource($id), __('bitframe.delete_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    public function BitFrameType(): JsonResponse
    {
        try {
            $bitFrameType = BitFrameType::toArray();

            return $this->sendListWithSuccess(new BitframeResource($bitFrameType), __('bitframe.search_type'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'type' => ['required'],
            'presets' => ['required'],
            'is_downloadable' => ['required'],
            'download_type' => ['required'],
            'status' => ['required']
        ];
    }
}