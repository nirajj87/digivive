<?php
namespace App\Http\Controllers\API\V1;

use App\Models\City;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CityResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CityController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/v1/city/list",
     *     tags={"City (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of city",
     *     description="Retrieve a list of cities with status = 1",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="City (Master)",
     *     description="State related operations"
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = City::query();

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

        return $this->sendPaginationSuccessResponse(CityResource::collection($stateList), __('city.list'));
    }

    /**
     * @OA\Get(
     *     path="/v1/city/list-by-state-id",
     *     tags={"City (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of cities by state ID",
     *     description="Retrieve a list of cities with status = 1 filtered by state ID",
     *     @OA\Parameter(
     *         name="state_id",
     *         in="query",
     *         required=true,
     *         description="ID of the state",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="City (Master)",
     *     description="Operations related to cities"
     * )
     */
  
    public function getCityListByStateId(Request $request): JsonResponse
    {
        #validate Status
        $validatedData = Validator::make($request->all(), $this->getValiditionRuleStateId());

        #check validation
        if ($validatedData->fails()) {
            return $this->sendError(__('common.validaton_error'), $validatedData->errors());
        }
        $state_id = json_decode($request->input('state_id'));
        $stateId = (array) $state_id;

        $query = City::query();
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

        if ((!empty($stateId) && count($stateId) > 0) && ($search != '')) {
            // Filter by country_id
            $query->whereIn('state_id', $stateId);
        }
        // Pagination
        $perPage = $request->input('perPage');

        if (isset($perPage) && $perPage !== '') {
            $per_page = intval($perPage);
        } else {
            $per_page = self::DEFAULT_PER_PAGE;
        }
        if (!empty($stateId) && count($stateId) > 0) {
            $cityList = $query->with('state')->where('status', 1)->whereIn('state_id', $stateId)->paginate($per_page);
        } else {

            $cityList = $query->with('state')->where('status', 1)->get();
        }
        return $this->sendPaginationSuccessResponse(CityResource::collection($cityList), __('city.list'));
    }

    /**
     * @OA\Post(
     *     path="/v1/city/create",
     *     summary="Create City",
     *     tags={"City (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="City data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "state_id"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="City Name",
     *                     description="Title of the City"
     *                 ),
     *                 @OA\Property(
     *                     property="state_id",
     *                     type="integer",
     *                     example=1,
     *                     description="State ID of the City"
     *                 ),
     *                 @OA\Property(
     *                     property="latitude",
     *                     type="number",
     *                     example=28.6139,
     *                     description="Latitude of the City"
     *                 ),
     *                 @OA\Property(
     *                     property="longitude",
     *                     type="number",
     *                     example=77.2090,
     *                     description="Longitude of the City"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="1",
     *                     description="Status of the City"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="City created",
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
            #Status data
            $data = [
                'title' => $request->input('title'),
                'state_id' => $request->input('state_id'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'status' => $request->input('status', 1),
            ];

            #create Status
            $Status = City::create($data);

            #return response
            return $this->sendCreated([], __('city.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/city/details/{id}",
     *     tags={"City (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get city details",
     *     description="Get the details of a specific city by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the city",
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
    public function details(City $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new CityResource($id), __('city.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }



    /**
     * @OA\Put(
     *     path="/v1/city/edit/{id}",
     *     summary="Update Status",
     *     tags={"City (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the City",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="City data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="East Delhi",
     *                 description="Title of the City"
     *             ),
     *             @OA\Property(
     *                 property="state_id",
     *                 type="integer",
     *                 example="1",
     *                 description="State id  of the City"
     *             ),
     *              @OA\Property(
     *                 property="latitude",
     *                 type="string",
     *                 example="-8.960455",
     *                 description="Latitude  of the City"
     *             ),
     *              @OA\Property(
     *                 property="longitude",
     *                 type="string",
     *                 example="16.4586",
     *                 description="Longitude  of the City"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example="1 or 0",
     *                 description="Status of the City"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="City updated",
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


    public function update(Request $request, City $id): JsonResponse
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
            $id->state_id = $request->input('state_id', $id->state_id);
            $id->latitude = $request->input('latitude', $id->latitude);
            $id->longitude = $request->input('longitude', $id->longitude);
            $id->status = $request->input('status', $id->status);

            // Save the updated Status
            $id->save();

            // Return response
            return $this->sendCreated([], __('city.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * @OA\Delete(
     *     path="/v1/city/delete/{id}",
     *     tags={"City (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete City",
     *     description="Delete a specific City",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the City",
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
    public function destroy(City $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new CityResource($id), __('city.delete_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'state_id' => ['required', 'min:2']
        ];
    }

    private function getValiditionRuleStateId()
    {
        return [
            'state_id' => ['required']
        ];
    }
}