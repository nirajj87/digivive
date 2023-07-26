<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Resources\AdsManagementResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Models\AdsManagement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdsManagementController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/v1/adsmanagement/list",
     *     tags={"Ads Management (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of ads management",
     *     description="Retrieve a list of ads management with status = 1",
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=20
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="Ads Management (Master)",
     *     description="Ads management related operations"
     * )
     */

     public function index(Request $request): JsonResponse
     {
        $query = AdsManagement::query();

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

        $adsManagementList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(AdsManagementResource::collection($adsManagementList), __('ads_management.list'));
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
     *     path="/v1/adsmanagement/create",
     *     summary="Create Ads Management",
     *     tags={"Ads Management (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Ads Management data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="example",
     *                     description="Title of the Ads Management"
     *                 ),
     *                 @OA\Property(
     *                     property="order",
     *                     type="integer",
     *                     example=1,
     *                     description="Order of the ads management"
     *                 ),
     *                 @OA\Property(
     *                     property="additional_settings",
     *                     type="json",
     *                     example="['color':'red']",
     *                     description="Additional settings of the Ads Management"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Ads Management Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */


    public function store(Request $request): JsonResponse
    {
        try {

            #validate Ads Management
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = AdsManagement::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the Ads Management
            #Ads Management data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'order' => $request->input('order', 0),
                'additional_settings' => $request->input('additional_settings', null),
                'status' => $request->input('status', 1),
            ];

            #create Ads Management
            AdsManagement::create($data);

            #return response
            return $this->sendCreated([], __('ads_management.successful'));

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
     *     path="/v1/adsmanagement/details/{id}",
     *     tags={"Ads Management (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get Ads Management details",
     *     description="Get the details of a specific Ads Management by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Ads Management",
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
            $adsManagement = AdsManagement::findOrFail($id);
            return $this->sendSuccess(new AdsManagementResource($adsManagement), __('ads_management.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(AdsManagement $adsManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/v1/adsmanagement/edit/{id}",
     *     summary="Update Ads Management",
     *     tags={"Ads Management (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Ads Management",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Ads Management data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Title",
     *                 description="Title of the Ads Management"
     *             ),
     *             @OA\Property(
     *                 property="order",
     *                 type="integer",
     *                 example="1",
     *                 description="Order of the Ads Management"
     *             ),
     *             @OA\Property(
     *                 property="additional_settings",
     *                 type="string",
     *                 example="['color':'red']",
     *                 description="Additional Settings of the Ads Management"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ads Management updated",
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


    public function update(Request $request, AdsManagement $id): JsonResponse
    {
        try {
            # Validate Ads Management
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = AdsManagement::getUniqueSlug(Str::slug($title, "-"));

            // Update the Ads Management attributes
            $fieldsToUpdate = [
                'title' => $title,
                'slug' => $slug,
                'additional_settings' => $request->input('additional_settings', $id->additional_settings),
                'order' => $request->input('order', $id->order),
                'status' => $request->input('status', $id->status),
            ];

            $id->update($fieldsToUpdate);

            // Return response
            return $this->sendCreated([], __('ads_management.update_success'));
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
     *     path="/v1/adsmanagement/delete/{id}",
     *     tags={"Ads Management (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete Ads Management",
     *     description="Delete a specific Ads Management",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Ads Management",
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
    public function destroy(AdsManagement $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new AdsManagementResource($id), __('ads_management.delete_success'));
        } catch (Exception $e) {
           // Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'order' => ['integer'],
            'additional_settings' => ['json']
        ];
    }
}