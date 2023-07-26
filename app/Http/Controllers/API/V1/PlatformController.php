<?php
namespace App\Http\Controllers\API\V1;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PlatformResource;
use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/v1/platform/list",
     *     summary="Get a list of Plateform",
     *     tags={"Plateform (Master)"},
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
     *     name="Plateform (Master)",
     *     description="Platform related operations"
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Platform::query();

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

        $plateformList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(PlatformResource::collection($plateformList), __('platform.list'));
    }


    /**
     * @OA\Post(
     *     path="/v1/platform/create",
     *     summary="Create a new plateform",
     *     tags={"Plateform (Master)"}, 
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Create a new plateform",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title"
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

            #validate Plateform
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = Platform::getUniqueSlug(Str::slug($title, "-"));

            #Plateform data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'status' => $request->input('status', 1)
            ];

            #create Plateform
            Platform::create($data);

            #return response
            return $this->sendCreated([], __('platform.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/platform/details/{id}",
     *     summary="Get the details of a plateform",
     *     tags={"Plateform (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the plateform",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Platform not found"
     *     )
     * )
     */
    public function details(Platform $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new PlatformResource($id), __('platform.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Platform  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *     path="/v1/platform/edit/{id}",
     *     summary="Update a plateform",
     *     tags={"Plateform (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the plateform",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Plateform details to be updated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="integer"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plateform updated successfully",
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
     *         description="Plateform not found",
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
    public function update(Request $request, Platform $id): JsonResponse
    {
        try {
            #validate Plateform
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            // Update the Platform attributes
            $title = $request->input('title');
            $slug = Platform::getUniqueSlug(Str::slug($title, "-"));
            $id->title = $title;
            $id->slug = $slug;
            $id->status = $request->input('status', $id->status);
            // Save the updated Platform
            $id->save();

            // Return response
            return $this->sendCreated([], __('platform.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/v1/platform/delete/{id}",
     *     summary="Delete a plateform",
     *     tags={"Plateform (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the plateform",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plateform succeesfully deleted",
     *       
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plateform not found"
     *     )
     * )
     */
    public function destroy(Platform $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new PlatformResource($id), __('platform.delete_success'));
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