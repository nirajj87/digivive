<?php
namespace App\Http\Controllers\API\V1;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ViewerRatingResource;
use App\Models\ViewerRating;
use Illuminate\Http\Request;

class ViewerRatingController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/v1/viewer-rating/list",
     *     summary="Get the list of viewer ratings",
     *     tags={"Viewer Rating (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Viewer Rating List",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
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
     *     name="Viewer Rating (Master)",
     *     description="Viewer rating related operations"
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = ViewerRating::query();

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

        $viewerRatingList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(ViewerRatingResource::collection($viewerRatingList), __('viewer_rating.list'));
    }

    /**
     * @OA\Post(
     *     path="/v1/viewer-rating/create",
     *     summary="Create a new viewer rating",
     *     tags={"Viewer Rating (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Viewer rating created successfully",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "view_order"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title of the viewer rating"
     *                 ),
     *                 @OA\Property(
     *                     property="view_order",
     *                     type="integer",
     *                     description="View order of the viewer rating"
     *                 )
     *             )
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
     *         response=400,
     *         description="Invalid request"
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

            #validate Genere
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = ViewerRating::getUniqueSlug(Str::slug($title, "-"));

            #Genere data
            $data = [
                'title'     => $title,
                'slug'      => $slug,
                'view_order' => $request->input('view_order', 0),
                'status'    => $request->input('status',1),
            ];
            #create Genere
            ViewerRating::create($data);

            #return response
            return $this->sendCreated([], __('viewer_rating.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/viewer-rating/details/{id}",
     *     summary="Get details of a viewer rating",
     *     tags={"Viewer Rating (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the viewer rating",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Viewer rating details",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
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

    public function details(ViewerRating $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new ViewerRatingResource($id), __('viewer_rating.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *     path="/v1/viewer-rating/edit/{id}",
     *     summary="Update viewer rating",
     *     tags={"Viewer Rating (Master)"},
     *     description="Update a viewer rating by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Viewer Rating",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Viewer Rating details to be updated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Updated Title"
     *             ),
     *             @OA\Property(
     *                 property="view_order",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=1
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Viewer Rating updated successfully",
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
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $viewerRating = ViewerRating::findOrFail($id);

            // Update the Viewer Rating attributes
            $title = $request->input('title');
            $slug = ViewerRating::getUniqueSlug(Str::slug($title, "-"));
            $viewerRating->title = $title;
            $viewerRating->slug = $slug;

            $viewerRating->view_order = $request->input('view_order', $viewerRating->view_order);
            $viewerRating->status = $request->input('status', $viewerRating->status);
            // Save the updated Viewer Rating
            $viewerRating->save();

            // Return response
            return response()->json(['success' => true, 'message' => __('viewer_rating.update_success')], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/v1/viewer-rating/delete/{id}",
     *     summary="Delete a viewer rating",
     *     tags={"Viewer Rating (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the viewer rating",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Viewer rating deleted successfully",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
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
    public function destroy($id): JsonResponse
    {
        try {
            $viewerRating = ViewerRating::findOrFail($id);
            $viewerRating->delete();
            return $this->sendSuccess(__('viewer_rating.delete_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title'      => ['required'],
            'view_order' => ['required']
        ];
    }
}
