<?php

namespace App\Http\Controllers\API\V1;

use App\Models\AnalyticsManagement;
use Illuminate\Http\Request;
use App\Http\Resources\AnalyticsManagementResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
 
class AnalyticsManagementController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/v1/analytics-management/list",
     *     operationId="indexAnalyticsManagement",
     *     summary="Get the list of analytics management",
     *     tags={"Analytics Management (Master)"},
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
     *     name="Analytics Management (Master)",
     *     description="Analytics Management related operations"
     * )
     */

    public function index(Request $request): JsonResponse
    {
        $query = AnalyticsManagement::query();

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

        $analyticsManagementList = $query->paginate($per_page);
        return $this->sendPaginationSuccessResponse(AnalyticsManagementResource::collection($analyticsManagementList), __('analytics_management.list'));
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
     *     path="/v1/analytics-management/create",
     *     summary="Create analytics Management",
     *     tags={"Analytics Management (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="analytics Management data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="example",
     *                     description="Title of the analytics Management"
     *                 ),
     *                 @OA\Property(
     *                     property="order",
     *                     type="integer",
     *                     example="1",
     *                     description="order of the analytics management"
     *                 ),
     *                 @OA\Property(
     *                     property="additional_settings",
     *                     type="json",
     *                     example="['color':'red']",
     *                     description="Additional setting of the analytics Management"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="analytics Management Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */


    public function store(Request $request): JsonResponse
    {
        try {
       
            #validate analytics Management
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = AnalyticsManagement::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the analytics Management
            #analytics Management data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'tracking_id' => $request->input('tracking_id', null),
                'additional_settings' => $request->input('additional_settings', null),
                'status' => $request->input('status', 1)
            ];

            #create analytics Management
            AnalyticsManagement::create($data);

            #return response
            return $this->sendCreated([], __('analytic_management.successful'));

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
     *     path="/v1/analytics-management/details/{id}",
     *     tags={"Analytics Management (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get analytics Management details",
     *     description="Get the details of a specific analytics Management by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the analytics Management",
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
            $analyticsManagement = AnalyticsManagement::findOrFail($id);
            return $this->sendSuccess(new AnalyticsManagementResource($analyticsManagement), __('analytic_management.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(AnalyticsManagement $analyticsManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/v1/analytics-management/edit/{id}",
     *     summary="Update analytics Management",
     *     tags={"Analytics Management (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the analytics Management",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="analytics Management data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Title",
     *                 description="Title of the analytics Management"
     *             ),
     *             @OA\Property(
     *                 property="order",
     *                 type="integer",
     *                 example="1",
     *                 description="Order of the analytics Management"
     *             ),
     *             @OA\Property(
     *                 property="additional_settings",
     *                 type="string",
     *                 example="['color':'red']",
     *                 description="Additional Settings of the analytics Management"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="analytics Management updated",
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


    public function update(Request $request, AnalyticsManagement $id): JsonResponse
    {
        try {
            // Validate analytics Management
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validation_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = AnalyticsManagement::getUniqueSlug(Str::slug($title, "-"));

            // Update the analytics Management attributes
            $fieldsToUpdate = [
                'title' => $title,
                'slug' => $slug,
                'tracking_id' => $request->input('tracking_id',$id->tracking_id),
                'additional_settings' => $request->input('additional_settings', $id->additional_settings),
                'status' => $request->input('status', $id->status),
            ];

            $id->update($fieldsToUpdate);

            // Return response
            return $this->sendSuccess([], __('analytic_management.update_success'));
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
     *     path="/v1/analytics-management/delete/{id}",
     *     tags={"Analytics Management (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete analytics Management",
     *     description="Delete a specific analytics Management",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the analytics Management",
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
    public function destroy(AnalyticsManagement $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new AnalyticsManagementResource($id), __('analytic_management.delete_success'));
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
