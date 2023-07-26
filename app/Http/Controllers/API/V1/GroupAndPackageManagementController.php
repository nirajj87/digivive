<?php

namespace App\Http\Controllers\API\V1;

use App\Models\GroupAndPackageManagement;
use Illuminate\Http\Request;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Http\Resources\GroupAndPackageManagementResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GroupAndPackageManagementController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/v1/group-packege/list",
     *     tags={"Group Package (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of group packege",
     *     description="Retrieve a list of group packege with status = 1",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="Group Package (Master)",
     *     description="Group Packege related operations"
     * )
     */

    public function index(Request $request): JsonResponse
    {
       $query = GroupAndPackageManagement::query();

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

        $groupPackegeList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(GroupAndPackageManagementResource::collection($groupPackegeList), __('group_pack.list'));
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
     *     path="/v1/group-packege/create",
     *     summary="Create group-packege",
     *     tags={"Group Package (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Group Packege Data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="example",
     *                     description="Title of the group packege"
     *                 ),
     *                 @OA\Property(
     *                     property="order",
     *                     type="integer",
     *                     example="1",
     *                     description="order of the group-packege"
     *                 ),
     *                 @OA\Property(
     *                     property="additional_settings",
     *                     type="json",
     *                     example="['color':'red']",
     *                     description="Additional setting of the group-packege"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="group-packege Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */


    public function store(Request $request): JsonResponse
    {
        try {

            #validate group-packege
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = GroupAndPackageManagement::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the group-packege
            #group-packege data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'additional_settings' => $request->input('additional_settings') ?: null,
                'order' => $request->input('order', 0),
                'status' => $request->input('status', 1)
            ];

            #create group-packege
            GroupAndPackageManagement::create($data);

            #return response
            return $this->sendCreated([], __('group_pack.successful'));

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
     *     path="/v1/group-packege/details/{id}",
     *     tags={"Group Package (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get group-packege details",
     *     description="Get the details of a specific group-packege by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the group-packege",
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
            $groupPack = GroupAndPackageManagement::findOrFail($id);
            return $this->sendSuccess(new GroupAndPackageManagementResource($groupPack), __('group_pack.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(GroupAndPackageManagement $GroupAndPackageManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/v1/group-packege/edit/{id}",
     *     summary="Update group-packege",
     *     tags={"Group Package (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the group-packege",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="group-packege data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Title",
     *                 description="Title of the group-packege"
     *             ),
     *             @OA\Property(
     *                 property="order",
     *                 type="integer",
     *                 example="1",
     *                 description="Order of the group-packege"
     *             ),
     *             @OA\Property(
     *                 property="additional_settings",
     *                 type="string",
     *                 example="['color':'red']",
     *                 description="Additional Settings of the group-packege"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="group-packege updated",
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


    public function update(Request $request, GroupAndPackageManagement $id): JsonResponse
    {
        try {
            # Validate group-packege
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = GroupAndPackageManagement::getUniqueSlug(Str::slug($title, "-"));

            // Update the group-packege attributes
            $fieldsToUpdate = [
                'title' => $title,
                'slug' => $slug,
                'additional_settings' => $request->input('additional_settings', $id->additional_settings),
                'order' => $request->input('order', $id->order),
                'status' => $request->input('status', $id->status),
            ];

            $id->update($fieldsToUpdate);

            // Return response
            return $this->sendCreated([], __('group_pack.update_success'));
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
     *     path="/v1/group-packege/delete/{id}",
     *     tags={"Group Package (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete group-packege",
     *     description="Delete a specific group-packege",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the group-packege",
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
    public function destroy(GroupAndPackageManagement $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new GroupAndPackageManagementResource($id), __('group_pack.delete_success'));
        } catch (Exception $e) {
            // Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'additional_settings' => ['json']
        ];
    }
}
