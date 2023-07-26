<?php

namespace App\Http\Controllers\API\V1;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ContentAdvisoryResource;
use App\Models\ContentAdvisory;
use Illuminate\Http\Request;

class ContentAdvisoryController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/v1/content-advisory/list",
     *     summary="Get Content Advisory List",
     *     tags={"Content Advisory (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
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
     * @OA\Tag(
     *     name="Content Advisory (Master)",
     *     description="Content advisory related operations"
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContentAdvisory::query();

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

        $contentAdvisoryList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(ContentAdvisoryResource::collection($contentAdvisoryList), __('content_advisory.list'));
    }

    /**
     * @OA\Post(
     *     path="/v1/content-advisory/create",
     *     summary="Create Content Advisory",
     *     tags={"Content Advisory (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Content Advisory data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Sample Content Advisory"
     *                 ),
     *                 @OA\Property(
     *                     property="view_order",
     *                     type="integer",
     *                     example=0
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Content Advisory created",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
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

    public function store(Request $request): JsonResponse
    {
        try {

            #validate ContentAdvisory
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = ContentAdvisory::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the ContentAdvisory
            #ContentAdvisory data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'view_order' => $request->input('view_order', 0),
                'status' => $request->input('status', 1)
            ];

            #create ContentAdvisory
            $ContentAdvisory = ContentAdvisory::create($data);

            #return response
            return $this->sendCreated([], __('content_advisory.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/content-advisory/details/{id}",
     *     summary="Get Content Advisory Details",
     *     tags={"Content Advisory (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Content Advisory",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function details(ContentAdvisory $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new ContentAdvisoryResource($id), __('content_advisory.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/v1/content-advisory/edit/{id}",
     *     tags={"Content Advisory (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Update Content Advisory",
     *     description="Update the details of a specific Content Advisory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Content Advisory",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="Title of the Content Advisory"
     *             ),
     *             @OA\Property(
     *                 property="view_order",
     *                 type="integer",
     *                 description="View order of the Content Advisory"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 description="Status of the Content Advisory"
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
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=500, description="Server Error")
     * )
     */

    public function update(Request $request, $id): JsonResponse
    {
        try {
            #validate ContentAdvisory
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());
            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            // Retrieve the ContentAdvisory model
            $contentAdvisory = ContentAdvisory::findOrFail($id);

            // Update the ContentAdvisory attributes
            $title = $request->input('title');
            $slug = ContentAdvisory::getUniqueSlug(Str::slug($title, "-"));
            $contentAdvisory->title = $title;
            $contentAdvisory->slug = $slug;
            $contentAdvisory->view_order = $request->input('view_order', $contentAdvisory->view_order);
            $contentAdvisory->status = $request->input('status', $contentAdvisory->status);
            // Save the updated ContentAdvisory
            $contentAdvisory->save();
            // Return response
            return $this->sendCreated([], __('content_advisory.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/v1/content-advisory/delete/{id}",
     *     summary="Delete Content Advisory",
     *     tags={"Content Advisory (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Content Advisory",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Content Advisory successfully deleted",
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
    public function destroy(ContentAdvisory $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new ContentAdvisoryResource($id), __('content_advisory.delete_success'));
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