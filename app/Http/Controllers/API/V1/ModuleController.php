<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Http\Resources\ModuleResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ModuleController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @OA\Get(
     *   path="/v1/module",
     *   tags={"Module (Master)"},
     *   summary="Get Modules",
     *   security={{"bearerAuth": {}}},
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden"
     *   )
     *)
     * @OA\Tag(
     *     name="Module (Master)",
     *     description="Module related operations"
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Module::query();

        // Sorting
        $sortBy = $request->input('sortBy');
        $sortDirection = $request->input('sortDirection');

        if ($sortBy && $sortDirection) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Filtering by title
        $search = $request->input('search');
        if ($search) {
            $query->where('title', 'ILIKE', '%' . $search . '%');
        }
        // Pagination
        $perPage = intval($request->input('perPage',self::DEFAULT_PER_PAGE));

        $modules = $query->with(['parent:id,title'])
            ->paginate($perPage);
        return $this->sendPaginationSuccessResponse(ModuleResource::collection($modules), __('module.list'));
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
     ** path="/v1/module",
     *   tags={"Module (Master)"},
     *   security={{"bearerAuth": {}}},
     *   summary="Create Module",
     *
     *   @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="parent_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="child_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="icon",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="role",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden"
     *   )
     *)
     */
    public function store(Request $request) : JsonResponse
    {
        try {

            #validate module
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());
            
            
            #check validation
            if ($validatedData->fails()){
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());  
            }
            
            $arrModules = [];
            $sequence = '';
            $parentId = '';
            $newSequence = 1;
            $data = $request->input();

            if ($data['is_parent'] == 0) {  # If parent module
                $result = Module::select('order')->where('id', $data['parent_id'])->where('parent_id', 0)->get();
                if ($result->count() == 0) { 
                    # If first module
                    $sequence = 0;
                } else {
                    $sequence = $result[0]->order;
                    $arrModules = Module::select('id','order')->where('order', '>', $sequence)->where('parent_id', 0)->get();
                }
                $parentId = 0;
            } 
            else{  # If child module
                $result = Module::where('id', $data['child_id'])->select('order')->get();
                if ($result->count() == 0) {
                    $sequence = 0;
                } else {
                    $sequence = $result[0]->order;
                    // get current sequence to max sequence id
                    $arrModules = Module::select('id', 'order')->where('order', '>', $sequence)->where('parent_id', $data['parent_id'])->get();
                }
                $parentId = $data['parent_id'];
            }
            $newSequence = ($sequence + 1) ?: 1;
            $title = $request->input('title');
            $slug = Module::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the role
            
            //check if slug exist but module softdeleted
             $originalSlug = Str::slug($title, "-");
            // $checkSlug = Module::withTrashed()->where('slug', 'LIKE', '%' . $originalSlug . '%')->first();

            // if ($checkSlug && $checkSlug->exists) {
            //     return $this->sendError('Validation Error',__('module.softdelete'));
            // }
        
            #module data
            $data = [
                'title'         => $request->input('title'),
                'slug'          => $slug,
                'order'         => $newSequence,
                'parent_id'     => $parentId,
                'icon'          => $request->input('icon') ? $request->input('icon') : '',
                // 'active_icon'   => $request->input('active_icon'),
                'role'          => $request->input('role'),
                'status'        => $request->input('status'),
                'created_by'    => Auth::id()
            ]; 
            Module::create($data);
            
            #update order data for all modules
            foreach ($arrModules as $module) {
                Module::where('id', $module->id)->update([
                'order'=>$module->order + 1,
                ]);
            }

            #return response
            return $this->sendCreated([], __('module.successful'));

        } catch (\Exception $e) {
            print_r($e->getMessage()); die;
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }

    /**
     * Display the specified resource.
     * 
     * @OA\Get(
     **   path="/v1/module/{id}",
     *   tags={"Module (Master)"},
     *   summary="Get Module Details",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of the module to retrieve",
    *         required=true,
    *         @OA\Schema(
    *             type="integer"
    *         )
    *     ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden"
     *   )
     *)
     */
    public function show(Module $module) : JsonResponse
    {
        try{
            return $this->sendSuccess(ModuleResource::toModuleDetailArray($module), __('module.detail'));
        } catch(\Exception $e){
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }


    /**
     * Update the specified resource in storage.
     *    
     * @OA\Post(
     ** path="/v1/module/{id}",
     *   tags={"Module (Master)"},
     *   security={{"bearerAuth": {}}},
     *   summary="Update Module",
     *   @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of the module to update",
    *         required=true,
    *         @OA\Schema(
    *             type="integer"
    *         )
    *     ),
     *   @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="parent_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="child_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="icon",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     
     *   @OA\Parameter(
     *      name="role",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden"
     *   )
     *)
     */
    public function update(Request $request, Module $module) : JsonResponse
    {
        try {
            
            #validate module
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());
            
            #check validation
            if ($validatedData->fails()){
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());  
            }
            
            # ORDER UPDATION LOGIC
            $arrModules = [];
            $sequence = '';
            $parentId = '';
            $newSequence = 1;
            $data = $request->input();
            if ($data['is_parent'] == 0) {  # If parent module
                $result = Module::select('order')->where('id', $data['parent_id'])->where('parent_id', 0)->get();
                if ($result->count() == 0) { 
                    # If first module
                    $sequence = 0;
                } else {
                    $sequence = $result[0]->order;
                    $arrModules = Module::select('id','order')->where('order', '>', $sequence)->where('parent_id', 0)->orderBy('order', 'asc')->get();
                }
                $parentId = 0;
            } 
            else{  # If child module
                $result = Module::where('id', $data['child_id'])->select('order')->get();
                if ($result->count() == 0) {
                    $sequence = 0;
                } else {
                    $sequence = $result[0]->order;
                    # get current sequence to max sequence id
                    $arrModules = Module::select('id', 'order')->where('order', '>', $sequence)->where('parent_id', $data['parent_id'])->orderBy('order', 'asc')->get();
                }
                $parentId = $data['parent_id'];
            }
            $newSequence = ($sequence + 1) ?: 1;

            $title = $request->input('title');
            $slug = Module::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the module

            $module->title = $title ?: $module->title;
            $module->slug = $slug ?: $module->slug;
            $module->order = $newSequence;
            $module->parent_id = $parentId;
            $module->icon = $request->input('icon') ? $request->input('icon') : '';
            // $module->active_icon = $request->input('active_icon') ?: $module->active_icon;
            $module->role = $request->input('role') ?: $module->role;
            $module->status = $request->input('status') ?: $module->status;
            #update module
            $module->update();            

            if($arrModules){
                $increment = $newSequence;
                foreach ($arrModules as $newModule) {
                    if($module->id != $newModule->id){
                        $increment++;
                        Module::where('id', $newModule->id)->update(['order'=>$increment,]);
                    }
                }
            }

            #return response
            return $this->sendCreated([], __('module.update_success'));

        } catch (\Exception $e) {
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }

    /**
     * Remove the specified resource from storage.
     * *
     * @OA\Delete(
     **   path="/v1/module/{id}",
     *   tags={"Module (Master)"},
     *   summary="Delete Module",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of the module to be deleted",
    *         required=true,
    *         @OA\Schema(
    *             type="integer"
    *         )
    *     ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden"
     *   )
     *)
     */
    public function destroy(Module $module) : JsonResponse
    {
        try{
            $module->delete();
            return $this->sendCreated(new ModuleResource($module), __('module.delete_success'));
        } catch(\Exception $e){
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }

    /**
     * To get the list of Parent Modules.
     * 
     * @OA\Get(
     **   path="/v1/modules/get-parents",
     *   tags={"Module (Master)"},
     *   summary="Get Parent Modules",
     *   security={{"bearerAuth": {}}},
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden"
     *   )
     *)
     */
    public function parentModules() : JsonResponse
    {
        $modules = Module::where(function ($query) {
            $query->where('parent_id', null)
                ->orWhere('parent_id', 0);
        })
            ->orderBy('order', 'asc')
            ->get();
        return $this->sendSuccess(ModuleResource::collection($modules), __('module.list'));
    }

    /**
     * To get the list of Child Modules of a specific Parent.
     * 
     * @OA\Get(
     **   path="/v1/modules/get-child/{id}",
     *   tags={"Module (Master)"},
     *   summary="Get Child Modules",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of the module to be deleted",
    *         required=true,
    *         @OA\Schema(
    *             type="integer"
    *         )
    *     ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden"
     *   )
     *)
     */
    public function childModules(Request $request,$id = NULL) : JsonResponse
    {   
        $modules = Module::with(['parent:id,title'])
                    ->where('parent_id', $id)                    
                    ->orderBy('order', 'asc')
                    ->get();
        return $this->sendSuccess(ModuleResource::collection($modules), __('module.list'));
    }

    /**
     * Return the validation rule for add/update record 
    */
    private function getValiditionRule(){
        return [
            'title'              => ['required'],
            'status'             => ['required'],
            'role'               => ['required'],
            'is_parent'          => ['required', 'integer'],
        ];
    }
}
