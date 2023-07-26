<?php

namespace App\Http\Controllers\API\V1;

use App\Models\State;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\StateResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StateController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/v1/state/list",
     *     tags={"State (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of states",
     *     description="Retrieve a list of states with status = 1",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="State (Master)",
     *     description="State related operations"
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = State::query();

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
            $per_page = -1;
        }

        $stateList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(StateResource::collection($stateList), __('state.list'));
    }

    public function getStateListByCountryId(Request $request): JsonResponse
    {
        #validate Status
        $validatedData = Validator::make($request->all(), $this->getValiditionRuleCountryId());

        #check validation
        if ($validatedData->fails()) {
            return $this->sendError(__('common.validaton_error'), $validatedData->errors());
        }
        $country_id = json_decode($request->input('country_id'));
        $countryId = (array) $country_id;

        $query = State::query();

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

        if ((!empty($countryId) && count($countryId) > 0) && ($search != '')) {
            // Filter by country_id
            $query->whereIn('country_id', $countryId);
        }
        // Pagination
        $perPage = $request->input('perPage');

        if (isset($perPage) && $perPage !== '') {
            $per_page = intval($perPage);
        } else {
            $per_page = self::DEFAULT_PER_PAGE;
        }
        if (!empty($countryId) && count($countryId) > 0) {
            $stateList = $query->with('country')->where('status', 1)->whereIn('country_id', $countryId)->paginate($per_page);
        } else {

            $stateList = $query->with('country')->where('status', 1)->get();
        }
        $stateList->load('country');
        return $this->sendPaginationSuccessResponse(StateResource::collection($stateList), __('state.list'));
    }

    /**
     * @OA\Post(
     *     path="/v1/state/create",
     *     summary="Create State",
     *     tags={"State (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="State data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "state_code", "state_id", "country_id", "latitude", "longitude"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Delhi",
     *                     description="Title of the State"
     *                 ),
     *                 @OA\Property(
     *                     property="state_code",
     *                     type="string",
     *                     example="DL",
     *                     description="State code of the State"
     *                 ),
     *                 @OA\Property(
     *                     property="state_id",
     *                     type="integer",
     *                     example=1,
     *                     description="State ID of the State"
     *                 ),
     *                 @OA\Property(
     *                     property="country_id",
     *                     type="integer",
     *                     example=11,
     *                     description="Country ID of the State"
     *                 ),
     *                 @OA\Property(
     *                     property="latitude",
     *                     type="number",
     *                     example=28.6139,
     *                     description="Latitude of the State"
     *                 ),
     *                 @OA\Property(
     *                     property="longitude",
     *                     type="number",
     *                     example=77.2090,
     *                     description="Longitude of the State"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="1 or 0",
     *                     description="Status of the State"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="State created",
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


    public function store(Request $request): JsonResponse
    {
        try {

            #validate Status
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            $last_entry = State::latest()->first();
            $stateId = $last_entry->id + 1;
            $data = [
                'title' => $request->input('title'),
                'state_code' => $request->input('state_code'),
                'state_id' => $stateId,
                'country_id' => $request->input('country_id'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'status' => $request->input('status', 1)
            ];

            #create Status
            $Status = State::create($data);

            #return response
            return $this->sendCreated([], __('state.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/state/details/{id}",
     *     tags={"State (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get State details",
     *     description="Get the details of a specific State by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the State",
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
    public function details(State $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new StateResource($id), __('state.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/v1/states/edit/{id}",
     *     summary="Update State",
     *     tags={"State (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the State",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="State data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="California",
     *                 description="Title of the State"
     *             ),
     *             @OA\Property(
     *                 property="state_code",
     *                 type="string",
     *                 example="CA",
     *                 description="State Code of the State"
     *             ),
     *             @OA\Property(
     *                 property="state_id",
     *                 type="integer",
     *                 example=1,
     *                 description="State ID of the State"
     *             ),
     *             @OA\Property(
     *                 property="country_id",
     *                 type="integer",
     *                 example=1,
     *                 description="Country ID of the State"
     *             ),
     *             @OA\Property(
     *                 property="latitude",
     *                 type="number",
     *                 example=37.7749,
     *                 description="Latitude of the State"
     *             ),
     *             @OA\Property(
     *                 property="longitude",
     *                 type="number",
     *                 example=-122.4194,
     *                 description="Longitude of the State"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true,
     *                 description="Status of the State"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="State updated",
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


    public function update(Request $request, State $id): JsonResponse
    {
        try {
            #validate Status
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            // Update the Status attributes
            $id->title = $request->input('title', $id->title);
            $id->state_code = $request->input('state_code', $id->state_code);
            $id->state_id = $request->input('state_id', $id->state_id);
            $id->country_id = $request->input('country_id', $id->country_id);
            $id->latitude = $request->input('latitude', $id->latitude);
            $id->longitude = $request->input('longitude', $id->longitude);
            $id->status = $request->input('status', $id->status);

            // Save the updated Status
            $id->save();

            // Return response
            return $this->sendCreated([], __('state.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }



    public function destroy(State $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new StateResource($id), __('state.delete_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'state_code' => ['required', 'min:2', 'max:3'],
            'country_id' => ['required', 'integer']
        ];
    }
    private function getValiditionRuleCountryId()
    {
        return [
            'country_id' => ['required', 'json']
        ];
    }

}