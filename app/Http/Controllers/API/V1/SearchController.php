<?php
namespace App\Http\Controllers\API\V1;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SearchResource;
use App\Models\Search;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/v1/search/list",
     *     summary="Get the list of searches",
     *     tags={"Search (Master)"},
     *     security={{"bearerAuth": {}}},
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
     * @OA\Tag(
     *     name="Search (Master)",
     *     description="Search related operations"
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Search::query();

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

        $searchList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(SearchResource::collection($searchList), __('search.list'));
    }

    /*
     * Store a newly created resource in storage.
     *
     */
    /**
     * @OA\Post(
     *   path="/v1/search/create",
     *   summary="Create a new search option",
     *   tags={"Search (Master)"},
     *
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Create Search Option in Master",
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"title", "configration"},
     *         @OA\Property(
     *           property="title",
     *           type="string",
     *           description="Title"
     *         ),
     *         @OA\Property(
     *           property="configration",
     *           type="json",
     *           description="configration"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Search created successfully",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Invalid request"
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

            #validate Search
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            #Search data
            $data = [
                'title' => $request->input('title'),
                'configration' => $request->input('configration'),
                'status' => $request->input('status', 1)
            ];

            #create Search
            Search::create($data);

            #return response
            return $this->sendCreated([], __('search.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/search/details/{id}",
     *     summary="Get details of a search",
     *     tags={"Search (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the search",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Search not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
    public function details(Search $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new SearchResource($id), __('search.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * @OA\Put(
     *     path="/v1/search/edit/{id}",
     *     summary="Update a search",
     *     tags={"Search (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the search",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Search details to be updated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="integer"
     *             ),
     *             @OA\Property(
     *                 property="configuration",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Search updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="object"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Search not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, Search $id): JsonResponse
    {
        try {
            #validate Search
            $validatedData = Validator::make($request->all(), $this->getUpdateValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            // Update the Search attributes
            $id->title = $request->input('title', $id->title);
            $id->status = $request->input('status', $id->status);
            $id->configration = $request->input('configration', $id->configration);
            // Save the updated Search
            $id->save();

            // Return response
            return $this->sendCreated([], __('search.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/v1/search/delete/{id}",
     *     summary="Delete a search",
     *     tags={"Search (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the search",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Search deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Search not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
    public function destroy(Search $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new SearchResource($id), __('search.delete_success'));
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
    private function getUpdateValiditionRule()
    {
        return [
            'title' => ['required']
        ];
    }

}