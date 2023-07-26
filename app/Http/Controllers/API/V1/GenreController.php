<?php
namespace App\Http\Controllers\API\V1;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/v1/genre/list",
     *     summary="Get the list of genres",
     *     tags={"Genre (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     *
     * @OA\Tag(
     *     name="Genre (Master)",
     *     description="Genre related operations"
     * )
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Genre::query();

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

        $genreResourceList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(GenreResource::collection($genreResourceList), __('genre.list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/v1/genre/create",
     *     summary="Create Genre",
     *     tags={"Genre (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Genre data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title of the Genre"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Genre created",
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
     *
     * @return JsonResponse
     */

    public function store(Request $request): JsonResponse
    {
        try {

            #validate Genre
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = Genre::getUniqueSlug(Str::slug($title, "-"));

            #Genre data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'user_id' => $request->input('user_id'),
                'status' => $request->input('status', 1)
            ];

            #create Genre
            Genre::create($data);

            #return response
            return $this->sendCreated([], __('genre.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/v1/genre/details/{id}",
     *     summary="Get details of a genre",
     *     tags={"Genre (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the genre",
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
     *         description="Genre not found",
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
    public function details(genre $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new GenreResource($id), __('genre.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/v1/genre/edit/{id}",
     *     summary="Update Genre",
     *     tags={"Genre (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Genre",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Genre data",
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="Title of the Genre"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 description="Status of the Genre (optional)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Genre updated",
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

    public function update(Request $request, Genre $id): JsonResponse
    {
        try {
            #validate Genre
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            // Update the Genre attributes
            $title = $request->input('title');
            $slug = Genre::getUniqueSlug(Str::slug($title, "-"));
            $id->title = $title;
            $id->slug = $slug;
             $id->user_id = $request->input('user_id', $id->user_id);
            $id->status = $request->input('status', $id->status);
            // Save the updated Genre
            $id->save();

            // Return response
            return $this->sendCreated([], __('genre.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     
     * @OA\Delete(
     *     path="/v1/genre/delete/{id}",
     *     summary="Delete a genre",
     *     tags={"Genre (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the genre",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Genre deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *               
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Genre not found",
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
    public function destroy(genre $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new GenreResource($id), __('genre.delete_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'user_id' => ['required']
        ];
    }
}