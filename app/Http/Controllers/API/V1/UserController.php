<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Module;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\ModulePermissionsResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Exception;
use Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password as PasswordRules;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource with searching sorting and pagination.
     *
     * @OA\Get(
     *   path="/v1/user",
     *   summary="Get Users",
     *   tags={"Users"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="search",
     *     in="query",
     *     description="Search query",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="sortBy",
     *     in="query",
     *     description="Sort by column",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="sortDirection",
     *     in="query",
     *     description="Sort direction (asc or desc)",
     *     required=false,
     *     @OA\Schema(
     *       type="string",
     *       enum={"asc", "desc"}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="perPage",
     *     in="query",
     *     description="Items per page",
     *     required=false,
     *     @OA\Schema(
     *       type="integer",
     *       format="int32"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Bad Request"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error"
     *   )
     * )
     *  @OA\Tag(
     *     name="Users",
     *     description="User related operations"
     * )
     * 
     */
    public function index(Request $request)
    {
        $query = User::query();

        # To search for a value
        if ($request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'ILIKE', "%{$search}%")
                    ->orWhere('last_name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%")
                    ->orWhere('phone_number', 'ILIKE', "%{$search}%");
            });
        }

        # Sorting based on a column
        $query->Admin();
        //$query->Client();

        if ($request->has('sortBy')) {
            $sortBy = $request->query('sortBy');
            $sortDirection = $request->query('sortDirection', $sortBy);
            $query->orderBy('created_at', $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc'); // Sort by 'created_at' field in descending order
        }

        # Exclude logged-in user
        $query->where('id', '!=', Auth::id());

        # Pagination
        $perPage = $request->input('perPage', self::DEFAULT_PER_PAGE);
        $users = $query->paginate($perPage);

        return $this->sendPaginationSuccessResponse(UserResource::collection($users), __('user.list'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/v1/user/{id}",
     *     summary="Get User Details",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
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

    public function show(string $id): JsonResponse
    {
        try {
            $user = User::find($id);
            $manager_ids = [];

            if ($user->user_profile && $user->user_profile->managers != null) {
                $manager_ids = json_decode($user->user_profile->managers);
            }

            $manages = User::whereIn('id', $manager_ids)->with('user_profile')->get();

            if ($manages) {
                $user['manages'] = $manages;
            }
            return $this->sendSuccess(new UserProfileResource($user), __('user.detail'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/v1/user/{user}",
     *     summary="Update User",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="User data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="User's name"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 description="User's email"
     *             ),
     *             @OA\Property(
     *                 property="country_id",
     *                 type="integer",
     *                 description="Country ID"
     *             ),
     *             @OA\Property(
     *                 property="timezone_id",
     *                 type="integer",
     *                 description="Timezone ID"
     *             ),
     *             @OA\Property(
     *                 property="date_format_id",
     *                 type="integer",
     *                 description="Date format ID"
     *             ),
     *             @OA\Property(
     *                 property="permission",
     *                 type="string",
     *                 description="User's permission"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="User's status"
     *             ),
     *             @OA\Property(
     *                 property="role",
     *                 type="integer",
     *                 description="Role ID"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User updated",
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
    public function update(Request $request, User $user)
    {
        try {

            #validate module
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            $path = null;
            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $filename = $file->getClientOriginalName();
                $folder = "user/$client->id/profile_img";
                $path = $file->storeAs($folder, $filename, 'public');
            }

            $user->name = $request->input('name') ?? $user->name;
            $user->email = $request->input('email') ?? $user->email;
            $user->country_id = $request->input('country_id') ?? $user->country_id;
            $user->timezone_id = $request->input('timezone_id') ?? $user->timezone_id;
            $user->date_format_id = $request->input('date_format_id') ?? $user->date_format_id;
            $user->permission = $request->input('permission') ?? $user->permission;
            $user->status = $request->input('status') ?? $user->status;
            $user->role_id = $request->input('role') ?? $user->role_id;

            #update user
            $user->update();

            #return response
            return $this->sendCreated([], __('user.update_success'));

        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/v1/user/{user}",
     *     summary="Delete User",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User deleted",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */

    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            return $this->sendCreated(new UserResource($user), __('user.delete_success'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Get user permissions.
     *
     * @OA\Get(
     *   path="/v1/user/get-permissions/{id}",
     *   tags={"Users"},
     *   summary="Get User Permissions",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to retrieve",
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
    public function getUserPermissions(string $id): JsonResponse
    {
        $user = User::find($id);
        #If new user is created
        if ($user['permission'] == null || $user['permission'] == "null") {
            $permissions = Module::where('parent_id', 0)
                ->whereJsonContains('role_ids', $user['role_id'])
                ->orderBy('order', 'asc')
                ->get();

            #create object of default permissions structure for each parent module
            foreach ($permissions as $permission) {
                $privilegesData = [
                    $permission->title => [
                        "is_add" => 0,
                        "is_edit" => 0,
                        "is_view" => 0,
                        "is_delete" => 0
                    ]
                ];
                $permission->privileges = $privilegesData;

                #fetch child modules for each parent module
                $subModules = Module::select('id', 'title', 'slug', 'order', 'parent_id', 'status', 'created_by', 'created_at', 'updated_at')
                    ->where('parent_id', $permission->id)
                    ->whereJsonContains('role_ids', $user['role_id'])
                    ->orderBy('order', 'asc')
                    ->get();

                #create object of default permissions structure for each child module
                foreach ($subModules as $subModule) {
                    $subModulePrivilegesData = [
                        $subModule->title => [
                            "is_add" => 0,
                            "is_edit" => 0,
                            "is_view" => 0,
                            "is_delete" => 0
                        ]
                    ];
                    $subModule->privileges = $subModulePrivilegesData;
                }
                $permission->sub_modules = $subModules;
            }
            return $this->sendSuccess(ModulePermissionsResource::collection($permissions), __('module.list'));
        }

        #If an existing user
        else {
            $permissions = $user['permission'];
            return $this->sendSuccess(ModulePermissionsResource::userSavedPermissions($permissions), __('module.list'));
        }
    }

    /**
     * Save user permissions.
     *
     *  
     * @OA\Post(
     * path="/v1/user/save-permissions",
     *   tags={"Users"},
     *   security={{"bearerAuth": {}}},
     *   summary="Save User Permissions",
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="permission",
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
    public function saveUserPermissions(Request $request): JsonResponse
    {
        try {
            $user = User::find($request->input('id'));
            $user->permission = $request->input('permission');
            $user->update();
            return $this->sendCreated([], __('user.update_success'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }


    /**
     * Create Admin User
     *  
     * @OA\Post(
     * path="/v1/user/create-admin",
     *   tags={"Users"},
     *   security={{"bearerAuth": {}}},
     *   summary="Create Admin User",
     *
     *   @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="country_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="role_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="permission",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="status",
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
    public function createAdminUser(Request $request): JsonResponse
    {
        try {
            # Validate input data
            $validatedData = Validator::make($request->all(), $this->getValiditionRuleAdminUser());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            # Get admin data
            $getAdminData = User::where('id', Auth::id())->with('user_profile')->first();
            # User data
            $randomPassword = Str::random(10);
            $data = [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($randomPassword),
                'role_id' => $request->input('role_id') ?? 1,
                'phone_number' => $request->input('phone_number'),
                'country_id' => $getAdminData->country_id ?? null,
                'timezone' => $getAdminData->timezone ?? null,
                'date_format' => $getAdminData->date_format ?? null,
                'is_parent' => $getAdminData->is_parent ?? null,
                'created_by' => Auth::id() ?? null,
                'status' => $request->input('status') ?? 0,
                'company_name' => $getAdminData->user_profile->company_name ?? null,
            ];

            # Create user
            $user = User::create($data);
            $lastUserId = $user->id;

            # Handle profile image
            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $filename = $file->getClientOriginalName();
                $folder = "user/$lastUserId/profile_img";
                $path = $file->storeAs($folder, $filename, 'public');
                $user->profile_img = $path;
                $user->save();
            }

            # User profile data
            $profileData =
                [
                    'user_id' => $lastUserId,
                    'user_name' => $request->input('email'),
                    'designation' => $request->input('designation') ?? null,
                    'company_name' => $getAdminData->user_profile->company_name ?? null,
                    'company_logo' => $getAdminData->user_profile->company_logo ?? null,
                    'department' => $request->input('department') ?? null,
                    'address' => $getAdminData->user_profile->address ?? null,
                    'managers' => $request->input('manager') ?? null,
                ];

            # Create user profile
            UserProfile::create($profileData);

            # Return response
            return $this->sendCreated([], __('register.create'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendCreated(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Update User Profile
     *  
     * @OA\Post(
     * path="/v1/user/update-profile/{id}",
     *   tags={"Users"},
     *   security={{"bearerAuth": {}}},
     *   summary="Update User Profile",
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="address_line_1",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="address_line_2",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="country_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="state_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="city_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="landmark",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="zip_code",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="manager_ids",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="json"
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
    public function updateProfile(Request $request, $user_id)
    {

        try {
            $formData = $request->all();
            // print_r($formData);die;
            #validate module
            #$validatedData = Validator::make($request->all(), $this->getValiditionRuleprofile());

            #check validation
            /*if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }*/
            // Retrieve the form data

            // Retrieve the user profile data
            $userProfileData = $formData['user_profile'];
            $userProfileData = [
                'user_name' => $formData['email'],
                'company_name' => $formData['company_name'],
                'address' => $formData['user_profile']['address'],
                'communication_channel' => json_encode($formData['user_profile']['communication_channel']),

                'managers' => json_encode($formData['user_profile']['managers_id']) ?? '',

            ];

            // Update or create the user profile
            $userProfile = UserProfile::updateOrCreate(['user_id' => $user_id], $userProfileData);
            // Retrieve the user details from the form data
            $userDetails = [
                'first_name' => $formData['first_name'],
                'last_name' => $formData['last_name'],
                'email' => $formData['email'],
                'profile_img' => $formData['profile_img'],
                'company_name' => $formData['company_name'],
                'role_id' => $formData['role_id'],
                'country_id' => $formData['usercountry']['id'],
                'timezone' => $formData['timezone'],
                'date_format' => $formData['date_format'],
                'phone_number' => $formData['phone_number'],
                'updated_by' => Auth::id(),
            ];

            // Check if an image is attached

            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $filename = $file->getClientOriginalName();
                $folder = "user/$user_id/profile_img";
                $path = $file->storeAs($folder, $filename, 'public');
                $userDetails['profile_img'] = $path;
            }

            // Update the user details if the user exists
            if ($user = User::find($user_id)) {
                $user->fill($userDetails);
                $user->save();
            } else {
                // Handle the case where the user does not exist
                return $this->sendError(__('client.user_not_found'), [], 404);
            }

            // Return the response
            return $this->sendCreated([], __('client.update_success'));

        } catch (\Exception $e) {
            print_r($e->getMessage());
            die;
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    public function updateBilling(Request $request)
    {
        try {
            # Validate module
            $validatedData = Validator::make($request->all(), $this->getValiditionRuleBilling());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            // Retrieve the form data and decode it from JSON to an array
            $formData = json_decode($request->input('billing'), true);
            $user_id = $request->input('user_id');

            // Handle uploaded files
            if ($request->hasFile('cheque')) {
                $file = $request->file('cheque');
                $filename = $file->getClientOriginalName();
                $folder = "user/$user_id/cheque";
                $path = $file->storeAs($folder, $filename, 'public');
                $formData['cheque'] = $path;
            }
            if ($request->hasFile('gstin_image')) {
                $file = $request->file('gstin_image');
                $filename = $file->getClientOriginalName();
                $folder = "user/$user_id/gstin_image";
                $path = $file->storeAs($folder, $filename, 'public');
                $formData['gstin_image'] = $path;
            }

            if ($request->hasFile('cin_image')) {
                $file = $request->file('cin_image');
                $filename = $file->getClientOriginalName();
                $folder = "user/$user_id/cin_image";
                $path = $file->storeAs($folder, $filename, 'public');
                $formData['cin_image'] = $path;
            }

            if ($request->hasFile('pan_image')) {
                $file = $request->file('pan_image');
                $filename = $file->getClientOriginalName();
                $folder = "user/$user_id/pan_image";
                $path = $file->storeAs($folder, $filename, 'public');
                $formData['pan_image'] = $path;
            }

            $userProfileData = [
                'billing' => $formData,
                'cheque' => $request->input('cheque'),
                'gstin_image' => $request->input('gstin_image'),
                'cin_image' => $request->input('cin_image'),
                'pan_image' => $request->input('pan_image'),
            ];
            // Remove the separate keys for images from the root level

            // Update or create the user profile
            UserProfile::updateOrCreate(['user_id' => $user_id], $userProfileData);

            // Return the response
            return $this->sendCreated([], __('bank_details.successful'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }



    /**
     * Get role based user permissions.
     *
     * @OA\Get(
     *   path="/v1/user/get-role-permissions",
     *   tags={"Users"},
     *   summary="Get Role Based User Permissions",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *         name="role",
     *         in="path",
     *         description="ID of the role to retrieve",
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
    public function getRoleBasedPermissions(int $role_id)
    {
        $permissions = Module::where('parent_id', null)
            ->whereJsonContains('role_ids', $role_id)
            ->orderBy('order', 'asc')
            ->get();

        #create object of default permissions structure for each parent module
        foreach ($permissions as $permission) {
            $privilegesData = [
                $permission->title => [
                    "is_add" => 0,
                    "is_edit" => 0,
                    "is_view" => 0,
                    "is_delete" => 0
                ]
            ];
            $permission->privileges = $privilegesData;

            #fetch child modules for each parent module
            $subModules = Module::select('id', 'title', 'slug', 'order', 'parent_id', 'status', 'created_by', 'created_at', 'updated_at')
                ->where('parent_id', $permission->id)
                ->whereJsonContains('role_ids', $role_id)
                ->orderBy('order', 'asc')
                ->get();

            #create object of default permissions structure for each child module
            foreach ($subModules as $subModule) {
                $subModulePrivilegesData = [
                    $subModule->title => [
                        "is_add" => 0,
                        "is_edit" => 0,
                        "is_view" => 0,
                        "is_delete" => 0
                    ]
                ];
                $subModule->privileges = $subModulePrivilegesData;
            }
            $permission->sub_modules = $subModules;
        }
        return $this->sendSuccess(ModulePermissionsResource::collection($permissions), __('module.list'));
    }

    /**
     * Update user profile picture.
     *
     * @OA\Post(
     *   path="/v1/user/update-profile-picture/{id}",
     *   tags={"Users"},
     *   summary="Update user profile picture",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *    @OA\Parameter(
     *         name="profile_img",
     *         in="path",
     *         description="Profile picture",
     *         required=true,
     *         @OA\Schema(
     *             type="file"
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
    public function updateProfilePicture(Request $request, $user_id)
    {
        try {

            #validate module
            $validatedData = Validator::make($request->all(), $this->getUserProfilePictureValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            $user = User::find($user_id);
            $file = $request->file('profile_img');

            $filename = $file->getClientOriginalName();

            $folder = "user/$user->id/profile_img";
            $path = $file->storeAs($folder, $filename, 'public');
            $user->profile_img = $path;

            #update user profile picture
            $user->update();

            #return response
            return $this->sendCreated([], __('user.profile_pic_update_success'));

        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    public function getUserManagers(Request $request)
    {

        $userId = auth()->user()->id;
        $roleId = auth()->user()->role_id;
        $parentId = auth()->user()->parent_id;

        if ($parentId) {
            $user = User::where(function ($query) use ($userId, $parentId) {
                $query->where('id', $userId)
                    ->orWhere('created_by', $userId)
                    ->orWhere('created_by', $parentId);
            })->where("role_id", $roleId)->get();
        } else {
            $user = User::where(function ($query) use ($userId, $parentId) {
                $query->where('id', $userId)
                    ->orWhere('created_by', $userId);
            })->where("role_id", $roleId)->get();
        }

        return $this->sendSuccess(UserProfileResource::ManagerDetailArray($user ?? null), __('client.manager_list'));
    }

    public function getUserCommunicationManagers($id)
    {

        $objUser = User::where('id', $id)->get();

        if ($objUser->count()) {
            $userId = $id;
            $roleId = $objUser[0]->role_id;
            $parentId = $objUser[0]->parent_id;

            if ($parentId) {
                $user = User::where(function ($query) use ($userId, $parentId) {
                    $query->where('id', $userId)
                        ->orWhere('created_by', $userId)
                        ->orWhere('created_by', $parentId);
                })->where("role_id", $roleId)->get();
            } else {
                $user = $objUser;
            }

            return $this->sendSuccess(UserProfileResource::CommunicationManagerDetailArray($user ?? null), __('client.manager_list'));
        } else {

        }

    }
    private function getValiditionRuleprofile()
    {
        return [
            'company_name' => ['required'],
            'email' => ['required'],
            'first_name' => ['required'],
            'country_id' => ['required', 'integer'],
            'date_format' => ['required'],
            'timezone' => ['required'],
            'managers_id' => ['required'],
            'communication_channel' => ['required'],
        ];
    }
    private function getValiditionRuleAdminUser()
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'designation' => ['required'],
            'manager' => ['required'],
            'status' => ['required']
        ];
    }

    private function getValiditionRuleBilling()
    {
        return [
            'billing' => ['required']
        ];
    }
    private function getUserProfilePictureValiditionRule()
    {
        return [
            'profile_img' => ['required']
        ];
    }

}