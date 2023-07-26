<?php

namespace App\Http\Controllers\API\V1;

use App\Models\AssetTypes;
use Illuminate\Http\Request;

use App\Http\Resources\AssetTypesResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AssetTypesController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/asset-type/list",
     *     operationId="indexAssetTypes",
     *     summary="Get the list of asset type",
     *     tags={"Assets Type (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of records per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=20
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     *  @OA\Tag(
     *     name="Assets Type (Master)",
     *     description="Assets related operations"
     * )
     */

    public function index(Request $request): JsonResponse
    {
        $query = AssetTypes::query();

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

        $assetTypesList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(AssetTypesResource::collection($assetTypesList), __('assets_type.list'));
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
     */
    /**
     * @OA\Post(
     *     path="/v1/asset-type/create",
     *     summary="Create asset type",
     *     tags={"Assets Type (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="asset type data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="example",
     *                     description="Title of the asset type"
     *                 ),
     *                 @OA\Property(
     *                     property="order",
     *                     type="integer",
     *                     example="1",
     *                     description="order of the asset type"
     *                 ),
     *                 @OA\Property(
     *                     property="additional_settings",
     *                     type="json",
     *                     example="['color':'red']",
     *                     description="Additional setting of the asset type"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="asset type Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */


    public function store(Request $request): JsonResponse
    {
        try {

            #validate asset type
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = AssetTypes::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the asset type
            #asset type data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'status' =>$request->input('status', 1)
            ];

            #create asset type
            AssetTypes::create($data);

            #return response
            return $this->sendCreated([], __('assets_type.successful'));

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
     *     path="/v1/asset-type/details/{id}",
     *     tags={"Assets Type (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get asset type details",
     *     description="Get the details of a specific asset type by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the asset type",
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
            $AssetTypes = AssetTypes::findOrFail($id);
            return $this->sendSuccess(new AssetTypesResource($AssetTypes), __('assets_type.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(AssetTypes $AssetTypes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/v1/asset-type/edit/{id}",
     *     summary="Update asset type",
     *     tags={"Assets Type (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the asset type",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="asset type data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Title",
     *                 description="Title of the asset type"
     *             ),
     *             @OA\Property(
     *                 property="order",
     *                 type="integer",
     *                 example="1",
     *                 description="Order of the asset type"
     *             ),
     *             @OA\Property(
     *                 property="additional_settings",
     *                 type="string",
     *                 example="['color':'red']",
     *                 description="Additional Settings of the asset type"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="asset type updated",
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


    public function update(Request $request, AssetTypes $id): JsonResponse
    {
        try {
            // Validate asset type
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validation_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = AssetTypes::getUniqueSlug(Str::slug($title, "-"));

            // Update the asset type attributes
            $fieldsToUpdate = [
                'title' => $title,
                'slug' => $slug,
                'status' => $request->input('status', $id->status),
            ];

            $id->update($fieldsToUpdate);

            // Return response
            return $this->sendSuccess([], __('assets_type.update_success'));
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
     *     path="/v1/asset-type/delete/{id}",
     *     tags={"Assets Type (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete asset type",
     *     description="Delete a specific asset type",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the asset type",
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
    public function destroy(AssetTypes $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new AssetTypesResource($id), __('assets_type.delete_success'));
        } catch (Exception $e) {
            // Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required']
        ];
    }
}
