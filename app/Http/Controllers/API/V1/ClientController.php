<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\UserProfile;
use App\Models\Module;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\API\V1\BaseController as BaseController;


class ClientController extends BaseController
{
    /**
     * Retrieve a paginated list of clients.
     *
     * @OA\Get(
     *     path="/v1/client",
     *     tags={"Client (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of clients",
     *     description="Retrieve a paginated list of clients with optional search and sorting",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search query to filter clients by name or email",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Column to sort the results by",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         example="name",
     *         description="Sorting direction (asc or desc)",
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         example="10",
     *         description="Number of results per page",
     *         @OA\Schema(type="integer", format="int32")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     * @OA\Tag(
     *     name="Client (Master)",
     *     description="Clients related operations"
     * )
     */
    public function index(Request $request)
    {
        $query = Client::query()
            ->clients()
            ->select('id', 'company_name', 'email', 'status', 'role_id', 'country_id', 'profile_img', 'timezone', 'date_format')
            ->with(['role:id,title', 'clientTimezone:id,title,timezone,zone_name']);

        // To search for a value
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'ILIKE', "%{$search}%")
                    ->orWhere('company_name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        // Sorting based on a column
        if ($request->has('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortDirection = $request->input('sort_direction', 'asc');
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Exclude logged-in client
        $query->where('id', '!=', Auth::id());

        // Pagination
        $perPage = $request->input('perPage', self::DEFAULT_PER_PAGE);
        $clients = $query->paginate($perPage);

        return $this->sendPaginationSuccessResponse(ClientResource::collection($clients), __('client.list'));
    }

    /**
     * Retrieve a paginated list of clients for a specific client (master).
     *
     * @OA\Get(
     *     path="/get-client-user/{client_id}",
     *     tags={"Client (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of clients for a specific client (master)",
     *     description="Retrieve a paginated list of clients with optional search and sorting for a specific client (master).",
     *     @OA\Parameter(
     *         name="client_id",
     *         in="path",
     *         required=true,
     *         description="The ID of the master client for which to retrieve the list of clients",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search query to filter clients by first name, last name, company name, or email",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Column to sort the results by",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         example="asc",
     *         description="Sorting direction (asc or desc)",
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         example="10",
     *         description="Number of results per page",
     *         @OA\Schema(type="integer", format="int32")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=500, description="Server Error")
     * )
     * @OA\Tag(
     *     name="Client (Master)",
     *     description="Clients related operations"
     * )
     */
    public function getClinetUserList(Request $request, $client_id)
    {
        try {

            $query = Client::query()
                ->clients()
                ->select('id', 'first_name', 'last_name', 'company_name', 'email', 'status', 'role_id', 'country_id', 'profile_img', 'timezone', 'date_format')
                ->with(['role:id,title', 'clientTimezone:id,title,timezone,zone_name', 'user_profile']);

            // To search for a value
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'ILIKE', "%{$search}%")
                        ->orWhere('last_name', 'ILIKE', "%{$search}%")
                        ->orWhere('company_name', 'ILIKE', "%{$search}%")
                        ->orWhere('email', 'ILIKE', "%{$search}%");
                });
            }

            // Sorting based on a column
            if ($request->has('sort_by')) {
                $sortBy = $request->input('sort_by');
                $sortDirection = $request->input('sort_direction', 'asc');
                $query->orderBy($sortBy, $sortDirection);
            } else {
                $query->orderBy('id', 'desc');
            }
            $query->where('is_parent', $client_id ? $client_id : Auth::id());
            // Pagination
            $perPage = $request->input('perPage', self::DEFAULT_PER_PAGE);
            $clients = $query->paginate($perPage);
            return $this->sendPaginationSuccessResponse(ClientResource::collection($clients), __('client.list'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified client details.
     *
     * @OA\Get(
     *     path="/v1/client/{id}",
     *     tags={"Client (Master)"},
     *     summary="Get Client Details",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the client to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="Client (Master)",
     *     description="Clients related operations"
     * )
     */
    public function show(string $id)
    {
        try {
            $client = User::client()->find($id);
            return $this->sendSuccess(new UserProfileResource($client), __('client.detail'));
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
     * Update the specified client in storage.
     *
     * @OA\Put(
     *     path="/v1/client/{client}",
     *     summary="Update Client",
     *     tags={"Client (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="client",
     *         in="path",
     *         required=true,
     *         description="ID of the client to update",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client updated successfully",
     *     ),
     *     @OA\Response(response=400, description="Validation Error"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     * @OA\Tag(
     *     name="Client (Master)",
     *     description="Clients related operations"
     * )
     */

    public function update(Request $request, Client $client)
    {
        try {

            #validate module
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $filename = $file->getClientOriginalName();
                $folder = "user/$client->id/profile_img";
                $path = $file->storeAs($folder, $filename, 'public');
            }

            $client->first_name = $request->input('company_name') ?? $client->first_name;
            $client->email = $request->input('email') ?? $client->email;
            $client->profile_img = $path ?? $request->input('profile_img');
            $client->company_name = $request->input('company_name') ?? $client->company_name;

            $client->country_id = $request->input('country_id') ?? $client->country_id;
            $client->timezone = $request->input('timezone') ?? $client->timezone;
            $client->date_format = $request->input('date_format') ?? $client->date_format;
            $client->status = $request->input('status') ?? $client->status;
            $client->updated_by = Auth::id();

            #update client
            $client->update();

            #return response
            return $this->sendCreated([], __('client.update_success'));

        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @OA\Delete(
     *   path="/v1/client/{id}",
     *   tags={"Client (Master)"},
     *   summary="Delete Client",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the client to be deleted",
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

    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return $this->sendCreated([], __('client.delete_success'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Create Client User.
     *
     * @OA\Post(
     *     path="/v1/client/create-client",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     summary="Create Client User",
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="company_name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="country_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="timezone",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="date_format",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="role_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="profile_img", type="string", format="binary"),
     *             @OA\Property(property="department", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client created successfully",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    //Create Client
    public function createClient(Request $request)
    {
        try {
            // Retrieve the authenticated user's ID
            $loginUserId = Auth::id();

            # Validate input data
            $validatedData = Validator::make($request->all(), $this->getValiditionRuleClient());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $randomPassword = Str::random(10);

            #user data
            $data = [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name') ?? "",
                'email' => $request->input('email'),
                'password' => Hash::make($randomPassword),
                'company_name' => $request->input('company_name'),
                'role_id' => 2,
                'country_id' => $request->input('country_id'),
                'timezone' => $request->input('timezone'),
                'date_format' => $request->input('date_format'),
                'status' => 0,
                'created_by' => $loginUserId,
                'updated_by' => $loginUserId,
            ];

            #invoke register event of laravel
            event(new Registered($client = Client::create($data)));

            #upload image if available
            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $filename = $file->getClientOriginalName();
                $folder = "user/$client->id/profile_img";
                $path = $file->storeAs($folder, $filename, 'public');
                $client->profile_img = $path;
                $client->save();
            }

            $profileData = [
                'user_id' => $client->id,
                'user_name' => $request->input('email'),
                'designation' => 'Owner',
                'company_name' => $request->input('company_name') ?? null,
                'department' => $request->input('department') ?? null,
                'company_logo' => $path ?? null
            ];

            # Create user profile
            UserProfile::create($profileData);

            #send verification email and random password notification
            // $client->sendEmailVerificationNotification();
            // $client->sendRandomPasswordNotification($randomPassword);

            #return response
            return $this->sendCreated([], __('client.successful'));

        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendCreated(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * Create Client User.
     *
     * @OA\Post(
     *     path="/v1/client/create-client-user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     summary="Create Client User",
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="client_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="role_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="phone_number",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="profile_img", type="string", format="binary"),
     *             @OA\Property(property="designation", type="string"),
     *             @OA\Property(property="department", type="string"),
     *             @OA\Property(property="manager", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client user created successfully",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function createClientUser(Request $request): JsonResponse
    {
        try {
            # Validate input data
            $validatedData = Validator::make($request->all(), $this->getValiditionRuleClientUser());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            # Get admin data
            $clientId = $request->input('client_id');
            $getClientData = User::where('id', $clientId ?: Auth::id())->with('user_profile')->first();

            # User data
            $randomPassword = Str::random(10);
            $data = [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($randomPassword),
                'role_id' => $request->input('role_id') ?? 2,
                'phone_number' => $request->input('phone_number'),
                'country_id' => $getClientData->country_id ?? null,
                'timezone' => $getClientData->timezone ?? null,
                'date_format' => $getClientData->date_format ?? null,
                'is_parent' => $request->input('client_id') ?? $getClientData->is_parent,
                'created_by' => $request->input('client_id') ?: Auth::id(),
                'status' => $request->input('status') ?? 0,
                'company_name' => $getClientData->user_profile->company_name ?? null,
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
            $profileData = [
                'user_id' => $lastUserId,
                'user_name' => $request->input('email'),
                'designation' => $request->input('designation') ?? null,
                'company_name' => $getClientData->user_profile->company_name ?? null,
                'company_logo' => $getClientData->user_profile->company_logo ?? null,
                'department' => $request->input('department') ?? null,
                'address' => $getClientData->user_profile->address ?? null,
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
     * @OA\Put(
     *   path="/v1/user/update-profile/{user_id}",
     *   summary="Update Client Profile",
     *   tags={"Client (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="user_id",
     *     in="path",
     *     required=true,
     *     description="ID of the User",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="first_name", type="string"),
     *       @OA\Property(property="last_name", type="string"),
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="profile_img", type="string", format="binary"),
     *       @OA\Property(property="company_name", type="string"),
     *       @OA\Property(property="role_id", type="integer", format="int64"),
     *       @OA\Property(property="country_id", type="integer", format="int64"),
     *       @OA\Property(property="timezone", type="string"),
     *       @OA\Property(property="dob", type="string"),
     *       @OA\Property(property="phone_number", type="string"),
     *       @OA\Property(property="created_by", type="integer", format="int64"),
     *       @OA\Property(property="updated_by", type="integer", format="int64"),
     *       @OA\Property(property="address", type="object",
     *          @OA\Property(property="address_line_1", type="string"),
     *          @OA\Property(property="address_line_2", type="string"),
     *          @OA\Property(property="country_id", type="integer", format="int64"),
     *          @OA\Property(property="state_id", type="integer", format="int64"),
     *          @OA\Property(property="city_id", type="integer", format="int64"),
     *          @OA\Property(property="landmark", type="string"),
     *          @OA\Property(property="zip_code", type="string"),
     *       ),
     *       @OA\Property(property="comunication_chanel", type="string"),
     *       @OA\Property(property="managers", type="array",
     *          @OA\Items(type="integer", format="int64"),
     *       ),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Client Profile successfully updated",
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
     *     response=404,
     *     description="User not found"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error"
     *   )
     * )
     */
    public function updateProfile(Request $request, $user_id)
    {
        try {

            #validate module
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            $data = [
                "user_name" => $request->input('user_name'),
                "desination" => $request->input('desination'),
                "company_name" => $request->input('company_name'),
                "company_logo" => $request->input('company_logo'),
                "address" => json_encode($request->input('address')),
                "comunication_chanel" => $request->input('comunication_chanel'),
                "managers" => json_encode($request->input('managers')),
                "landmark" => $request->input('landmark'),
                "country_id" => $request->input('country_id'),
                "state_id" => $request->input('state_id'),
                "city_id" => $request->input('city_id'),
                "zip_code" => $request->input('zip_code'),
            ];

            // Update or create the user profile
            UserProfile::updateOrCreate(['user_id' => $user_id], $data);

            // Check if an image is attached
            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $filename = $file->getClientOriginalName();
                $folder = "user/$user_id/profile_img";
                $path = $file->storeAs($folder, $filename, 'public');
            }

            // Update the user details if the user exists
            if ($user = Client::find($user_id)) {
                $user->first_name = $request->input('first_name') ?? $user->first_name;
                $user->last_name = $request->input('last_name') ?? $user->last_name;
                $user->email = $request->input('email') ?? $user->email;
                $user->profile_img = $path ?? $user->profile_img;
                $user->company_name = $request->input('company_name') ?? $user->company_name;
                $user->role_id = $request->input('role_id') ?? $user->role_id;
                $user->country_id = $request->input('country_id') ?? $user->country_id;
                $user->timezone = $request->input('timezone') ?? $user->timezone;
                $user->dob = $request->input('dob') ?? $user->dob;
                $user->phone_number = $request->input('phone_number') ?? $user->phone_number;
                $user->created_by = $request->input('created_by') ?? $user->created_by;
                $user->updated_by = $request->input('updated_by') ?? $user->updated_by;

                $user->update();
            } else {
                // Handle the case where the user does not exist
                return $this->sendError(__('client.user_not_found'), [], 404);
            }

            // Return the response
            return $this->sendCreated([], __('client.update_success'));

        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }





    /**
     * Update Client profile picture.
     *
     * @OA\Post(
     *   path="/v1/client/update-profile-picture/{id}",
     *   tags={"Client"},
     *   summary="Update Client profile picture",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Client to retrieve",
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
    public function updateProfilePicture(Request $request, $client_id)
    {
        try {

            #validate module
            $validatedData = Validator::make($request->all(), $this->getUserProfilePictureValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            $client = Client::clients()->find($client_id);
            $file = $request->file('profile_img');
            $filename = $file->getClientOriginalName();
            $folder = "user/$client->id/profile_img";
            $path = $file->storeAs($folder, $filename, 'public');
            $client->profile_img = $path;

            #update client profile picture
            $client->update();

            #return response
            return $this->sendCreated([], __('client.profile_pic_update_success'));

        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }


    /**
     * Delete client profile picture.
     *
     * @OA\Delete(
     *   path="/v1/client/delete-profile-picture/{id}",
     *   tags={"Client"},
     *   summary="Delete client profile picture",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the client to delete profile picture",
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
    public function deleteProfilePicture($client_id)
    {
        try {
            $client = Client::clients()->find($client_id);
            if ($client->profile_img) {
                // Delete the picture from storage
                Storage::delete($client->profile_img);
            }
            $client->profile_img = null;
            $client->save();
            return $this->sendCreated([], __('client.profile_pic_delete_success'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }

    /**
     * Display manager details of resource.
     * 
     * @OA\Get(
     *   path="/v1/client/get-managers/{id}",
     *   tags={"Client (Master)"},
     *   summary="Get Managers",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the client to retrieve managers",
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
    public function getClientManagerList(Request $request, $userId = null)
    {
        // If the $userId is not provided in the URL, fallback to the authenticated user's ID
        $userId = $userId ? $userId : auth()->user()->id;
        // Fetch the user details based on the provided or authenticated user_id
        $user = User::where('id', $userId)->first();
//print_r( $user);die;
        // If the user with the provided ID does not exist or the user's role_id or parent_id is not available,
        // fallback to the authenticated user's role_id and parent_id
        if (!$user || !$user->role_id) {
            $roleId = auth()->user()->role_id;
            $parentId = auth()->user()->parent_id;
        } else {
            $roleId = $user->role_id;
            $parentId = $user->is_parent;
        }

        // Fetch the user based on the provided or authenticated user_id and role_id
        $user = User::where(function ($query) use ($userId, $parentId) {
            $query->where('created_by', $userId)
                ->orWhere('created_by', $parentId);
        })->whereNotIn("id", [$userId])->where("role_id", $roleId)->get();

        // Return the user details using UserProfileResource
        return $this->sendSuccess(UserProfileResource::ManagerDetailArray($user ?? null), __('client.manager_list'));
    }



    private function getValiditionRuleClient()
    {
        return [
            'company_name' => ['required'],
            'first_name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'country_id' => ['required', 'integer'],
            'timezone' => ['required'],
            'date_format' => ['required']
        ];
    }
    private function getValiditionRuleClientUser()
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'designation' => ['required'],
            'manager' => ['required'],
            'department' => ['required'],
            'status' => ['required'],
            'client_id' => ['required']
        ];
    }
    private function getValiditionRule()
    {
        return [
            'company_name' => ['required'],
            'email' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone_number' => ['required'],
            'timezone' => ['required'],
            'comunication_chanel' => ['required', 'json'],
            'managers' => ['json'],
            'address' => ['json'],
            'country_id' => ['required', 'integer']
        ];
    }

    private function getUserProfilePictureValiditionRule()
    {
        return [
            'profile_img' => ['required']
        ];
    }

    private function getUserProfileValiditionRule()
    {
        return [
            'address_line_1' => ['required'],
            'country_id' => ['required'],
            'landmark' => ['required'],
            'zip_code' => ['required']
        ];
    }
}