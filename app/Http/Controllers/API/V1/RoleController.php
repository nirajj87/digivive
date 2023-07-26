<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

use App\Http\Resources\RoleResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;


class RoleController extends BaseController
{
    /**

    * Display a listing of the resource.
    * 
    * @OA\Get(
    *   path="/v1/role",
    *   summary="Get Roles",
    *   tags={"Roles (Master)"},
    *   security={{"bearerAuth": {}}},
    *   @OA\Response(
    *     response=200,
    *     description="Success",
    *     @OA\JsonContent(
    *       type="object",
    *       @OA\Property(property="message", type="string")
    *     )
    *   ),
    *   @OA\Response(response=401, description="Unauthenticated"),
    *   @OA\Response(response=500, description="Server Error")
    * )
    * @OA\Tag(
    *     name="Roles (Master)",
    *     description="Manage  roles and permissions "
    * )
    */
    public function index(Request $request): JsonResponse
    {
        $query = Role::query();

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

        $roles = $query->paginate($per_page);


        return $this->sendPaginationSuccessResponse(RoleResource::collection($roles), __('role.list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *   
     * @OA\Post(
     *   path="/v1/role",
     *   summary="Create Role",
     *   tags={"Roles (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"title", "is_parent"},
     *         @OA\Property(
     *           property="title",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="is_parent",
     *           type="integer"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Role created successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       
     *       @OA\Property(property="message", type="string")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Validation Error"),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="Server Error")
     * )
     */
    public function store(Request $request) : JsonResponse
    {
        try {
            
            #validate role
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());
            
            #check validation
            if ($validatedData->fails()){
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());  
            }
           
            $title = $request->input('title');

            //check if slug exist but module softdeleted
            $originalSlug = Str::slug($title, "-");
            $checkSlug = Role::withTrashed()->where('slug', 'LIKE', '%' . $originalSlug . '%')->first();

            if ($checkSlug && $checkSlug->exists) {
                return $this->sendError('Validation Error',__('module.softdelete'));
            }

            #role data
            $data = [
                'title'         => $title,
                'slug'          => $originalSlug,
                'is_parent'     => $request->input('is_parent'),
                'created_by'    => Auth::id()
            ];
            
            #create role
            $role = Role::create($data);

            #return response
            return $this->sendCreated([], __('role.successful'));

        } catch (\Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *   path="/v1/role/{role}",
     *   summary="Get Role Details",
     *   tags={"Roles (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     description="ID of the role",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Role details retrieved successfully",
     *     @OA\JsonContent(
     *       type="object",
     *  
     *       @OA\Property(property="message", type="string")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="Server Error")
     * )
     */
    public function show(Role $role) : JsonResponse
    {
        try{
            return $this->sendSuccess(new RoleResource($role), __('role.detail'));
        } catch(\Exception $e){
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role) : JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @OA\Put(
     *   path="/v1/role/{role}",
     *   summary="Update Role",
     *   tags={"Roles (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     description="ID of the role",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="title",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="is_parent",
     *           type="integer"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Role updated successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="message", type="string")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Validation Error"),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="Server Error")
     * )
     */
    public function update(Request $request, Role $role) : JsonResponse
    {
        try {
            
            #validate role
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());
            
            #check validation
            if ($validatedData->fails()){
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());  
            }
           
            $role->title = $request->input('title');
            $role->is_parent = $request->input('is_parent');
            $role->created_by = Auth::id();
            
            #update role
            $role->update();

            #return response
            return $this->sendCreated([], __('role.update_success'));

        } catch (\Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *     path="/v1/role/{id}",
     *     summary="Delete a role",
     *     tags={"Roles (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the role to be deleted",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role deleted successfully",
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
    public function destroy(Role $role): JsonResponse
    {
        try{
            $role->delete();
            return $this->sendCreated([], __('role.delete_success'));
        } catch(\Exception $e){
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage()); 
        }
    }

    /**
     * Return the validation rule for add/update record 
    */
    private function getValiditionRule(){
        return [
            'title'         => ['required'],
            'is_parent'     => ['required', 'integer']
        ];
    }
}
