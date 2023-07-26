<?php

namespace App\Http\Controllers\API\V1;

use App\Models\ContentAvailability;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ContentAvailabilityResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContentAvailabilityController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/v1/content-availability/list",
     *     tags={"Content Availability (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of ContentAvailabilitys",
     *     description="Retrieve a list of ContentAvailabilitys with status = 1",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *       
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     )
     *    @OA\Tag(
     *     name="Content Availability (Master)",
     *     description="Content Availability related operations"
     *     )
     */

    public function index(Request $request): JsonResponse
    {
        $query = ContentAvailability::query();

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

        $contentAvailabilityList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(ContentAvailabilityResource::collection($contentAvailabilityList), __('content_advisory.list'));
    }

    /**
     * @OA\Post(
     *   path="/v1/content-availability/create",
     *   summary="Create Content Availability",
     *   tags={"Content Availability (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Content Availability data",
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"title"},
     *         @OA\Property(
     *           property="title",
     *           type="string",
     *           description="Title of the Content Availability"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Content Availability created",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Bad Request"
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error"
     *   )
     * )
     */

    public function store(Request $request): JsonResponse
    {
        try {

            #validate ContentAvailability
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = ContentAvailability::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the contentAvailability
            #ContentAvailability data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'status' => $request->input('status', 1)
            ];

            #create ContentAvailability
            $ContentAvailability = ContentAvailability::create($data);

            #return response
            return $this->sendCreated([], __('content_availability.successful'));

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
     *     path="/v1/content-availability/details/{id}",
     *     tags={"Content Availability (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get Content Availability details",
     *     description="Get the details of a specific Content Availability by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Content Availability",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *    
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
            $contentAvailability = ContentAvailability::findOrFail($id);
            return $this->sendSuccess(new ContentAvailabilityResource($contentAvailability), __('content_availability.detail'));
        } catch (ModelNotFoundException $e) {
            return $this->sendNotFound(__('content_availability.not_found'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/v1/content-availability/edit/{id}",
     *     tags={"Content Availability (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Update ContentAvailability",
     *     description="Update the details of a specific Content Availability",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Content Availability",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="Title of the Content Availability"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=500, description="Server Error")
     * )
     */
    public function update(Request $request, ContentAvailability $id): JsonResponse
    {
        try {
            #validate ContentAvailability
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            // Update the ContentAvailability attributes
            $title = $request->input('title');
            $slug = ContentAvailability::getUniqueSlug(Str::slug($title, "-"));
            $id->title = $title;
            $id->slug = $slug;
            $id->status = $request->input('status', $id->status);
            // Save the updated ContentAvailability
            $id->save();

            // Return response
            return $this->sendCreated([], __('content_availability.update_success'));
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
     *     path="/v1/content-availability/delete/{id}",
     *     tags={"Content Availability (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete ContentAvailability",
     *     description="Delete a specific ContentAvailability",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Content Availability",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=500, description="Server Error")
     * )
     */
    public function destroy(ContentAvailability $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new ContentAvailabilityResource($id), __('content_availability.delete_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
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