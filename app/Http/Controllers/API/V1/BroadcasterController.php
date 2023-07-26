<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BroadcasterResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Models\Broadcaster;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BroadcasterController extends BaseController
{
    /**
     * @OA\Get(
     *   path="/v1/broadcaster/list",
     *   summary="Get Broadcaster List",
     *   tags={"Broadcasters (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
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
     *  @OA\Tag(
     *     name="Broadcasters (Master)",
     *     description="Broadcasters related operation"
     * )
     */

    public function index(Request $request): JsonResponse
    {
        $query = Broadcaster::query();

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

        $broadcasterList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(BroadcasterResource::collection($broadcasterList), __('broadcaster.list'));
    }


    /**
     * @OA\Post(
     *   path="/v1/broadcaster/create",
     *   summary="Create a Broadcaster",
     *   tags={"Broadcasters (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="title",
     *           type="string",
     *           example="Broadcaster Title"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Broadcaster successfully created",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Validation error"
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

            #validate broadcaster
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = Broadcaster::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the broadcaster
            #broadcaster data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'status' => $request->input('status', 1)
            ];

            #create broadcaster
            $broadcaster = Broadcaster::create($data);

            #return response
            return $this->sendCreated([], __('broadcaster.successful'));

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
     *   path="/v1/broadcaster/details/{id}",
     *   summary="Get Broadcaster Details",
     *   tags={"Broadcasters (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID of the Broadcaster",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error"
     *   )
     * )
     */
    public function details(Broadcaster $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new BroadcasterResource($id), __('broadcaster.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *   path="/v1/broadcaster/edit/{id}",
     *   summary="Update Broadcaster",
     *   tags={"Broadcasters (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID of the Broadcaster",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="title",
     *         type="string"
     *       ),
     *       @OA\Property(
     *         property="status",
     *         type="integer",
     *         format="int32"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Broadcaster successfully updated",
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
     *     response=403,
     *     description="Forbidden"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error"
     *   )
     * )
     */
    public function update(Request $request, Broadcaster $id): JsonResponse
    {
        try {

            // Update the broadcaster attributes
            $title = $request->input('title');
            $slug = Broadcaster::getUniqueSlug(Str::slug($title, "-"));
            $id->title = $title;
            $id->slug = $slug;
            $id->status = $request->input('status',1);
            // Save the updated broadcaster
            $id->save();

            // Return response
            return $this->sendCreated([], __('broadcaster.update_success'));
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
     *   path="/v1/broadcaster/delete/{id}",
     *   summary="Delete Broadcaster",
     *   tags={"Broadcasters (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID of the Broadcaster",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Broadcaster successfully deleted",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
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
    public function destroy(Broadcaster $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new BroadcasterResource($id), __('broadcaster.delete_success'));
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