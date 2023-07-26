<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Resources\CountriesResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Models\Countries;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CountriesController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/v1/countries",
     *     tags={"Countries (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of countries",
     *     description="Retrieve a list of countries with status = 1",
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="Field to sort the countries by",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sortDirection",
     *         in="query",
     *         description="Sorting direction (asc or desc)",
     *         @OA\Schema(
     *             type="string",
     *             enum={"asc", "desc"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for filtering countries",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of countries per page",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="Countries (Master)",
     *     description="Countries related operations"
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Countries::query();

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
            $query->orWhere('iso3', 'ILIKE', '%' . $search . '%');
            $query->orWhere('iso2', 'ILIKE', '%' . $search . '%');
            $query->orWhere('phone_code', 'ILIKE', '%' . $search . '%');
            $query->orWhere('capital', 'ILIKE', '%' . $search . '%');
            $query->orWhere('phone_code', 'ILIKE', '%' . $search . '%');
        }

        // Pagination
        $perPage = $request->input('perPage');

        if (isset($perPage) && $perPage !== '') {
            $per_page = intval($perPage);
        } else {
            //$per_page = self::DEFAULT_PER_PAGE;
            $per_page = -1;
        }

        $country_list = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(CountriesResource::collection($country_list), __('countries.list'));
    }
    /**
     * @OA\Post(
     *     path="/v1/countries",
     *     tags={"Countries (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Create a new country",
     *     description="Create a new country",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Country data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "iso2", "iso3", "phone_code", "capital", "currency", "currency_name", "currency_symbol", "timezones"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="India",
     *                     description="Title of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="iso2",
     *                     type="string",
     *                     example="IN",
     *                     description="ISO Code 2 of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="iso3",
     *                     type="string",
     *                     example="IND",
     *                     description="ISO Code 3 of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="phone_code",
     *                     type="string",
     *                     example="91",
     *                     description="Phone Code of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="capital",
     *                     type="string",
     *                     example="New Delhi",
     *                     description="Capital of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="currency",
     *                     type="string",
     *                     example="INR",
     *                     description="Currency of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="currency_name",
     *                     type="string",
     *                     example="Rupees",
     *                     description="Currency Name of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="currency_symbol",
     *                     type="string",
     *                     example="Rs.",
     *                     description="Currency symbol of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="timezones",
     *                     type="string",
     *                     example="Timezones in JSON format",
     *                     description="Timezones of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="latitude",
     *                     type="string",
     *                     example="-16.00000000",
     *                     description="Latitude of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="longitude",
     *                     type="string",
     *                     example="16.00000000",
     *                     description="Longitude of the Country"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     example="",
     *                     description="Image of the Country"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="Countries (Master)",
     *     description="Countries related operations"
     * )
     */
     public function store(Request $request): JsonResponse
    {
        try {

            #validate Language
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            //$slug = Countries::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the language
            #language data
            $data = [
                'title' => $request->input('title'),
                'iso3' => $request->input('iso3', null),
                'iso2' => $request->input('iso2', null),
                'phone_code' => $request->input('phone_code', null),
                'capital' => $request->input('capital', null),
                'currency' => $request->input('currency', null),
                'currency_name' => $request->input('currency_name', null),
                'currency_symbol' => $request->input('currency_symbol',null),
                'timezones' => json_encode($request->input('timezones',null)),
                'latitude' => $request->input('latitude', null),
                'longitude' => $request->input('longitude', null),
                'emoji' => $request->input('iso2', null),
                'image' => $request->input('image',null),
                'status' => $request->input('status',1),
            ];

            #create language
            $country = Countries::create($data);

            #return response
            return $this->sendCreated([], __('countries.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
 * @OA\Get(
 *     path="/countries/{id}",
 *     operationId="getCountriesById",
 *     tags={"Countries (Master)"},
 *     summary="Get country by ID",
 *     description="Retrieve country information based on the provided ID",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         description="Country ID",
 *         required=true,
 *         in="path",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Country not found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *     )
 * )
 */
    public function show($id): JsonResponse
{
    try {
        $country = Countries::findOrFail($id);
        return $this->sendSuccess(new CountriesResource($country), __('countries.detail'));
    } catch (Exception $e) {
        Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
        return $this->sendServerError(__('common.server_error'), $e->getMessage());
    }
}

    /**
     * @OA\Put(
     *     path="/v1/countries/edit/{id}",
     *     summary="Update Country",
     *     tags={"Countries (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Country",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Country data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="United States",
     *                 description="Title of the Country"
     *             ),
     *             @OA\Property(
     *                 property="iso3",
     *                 type="string",
     *                 example="USA",
     *                 description="ISO Code 3 of the Country"
     *             ),
     *             @OA\Property(
     *                 property="iso2",
     *                 type="string",
     *                 example="US",
     *                 description="ISO Code 2 of the Country"
     *             ),
     *             @OA\Property(
     *                 property="phone_code",
     *                 type="string",
     *                 example="+1",
     *                 description="Phone Code of the Country"
     *             ),
     *             @OA\Property(
     *                 property="capital",
     *                 type="string",
     *                 example="Washington, D.C.",
     *                 description="Capital of the Country"
     *             ),
     *             @OA\Property(
     *                 property="currency",
     *                 type="string",
     *                 example="USD",
     *                 description="Currency of the Country"
     *             ),
     *             @OA\Property(
     *                 property="currency_name",
     *                 type="string",
     *                 example="United States Dollar",
     *                 description="Currency Name of the Country"
     *             ),
     *             @OA\Property(
     *                 property="currency_symbol",
     *                 type="string",
     *                 example="$",
     *                 description="Currency Symbol of the Country"
     *             ),
     *             @OA\Property(
     *                 property="timezones",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"America/New_York", "America/Los_Angeles"},
     *                 description="Timezones of the Country"
     *             ),
     *             @OA\Property(
     *                 property="latitude",
     *                 type="number",
     *                 example=37.0902,
     *                 description="Latitude of the Country"
     *             ),
     *             @OA\Property(
     *                 property="longitude",
     *                 type="number",
     *                 example=-95.7129,
     *                 description="Longitude of the Country"
     *             ),
     *             @OA\Property(
     *                 property="emoji",
     *                 type="string",
     *                 example="🇺🇸",
     *                 description="Emoji of the Country"
     *             ),
     *             @OA\Property(
     *                 property="image",
     *                 type="string",
     *                 example="usa.jpg",
     *                 description="Image URL of the Country"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true,
     *                 description="Status of the Country"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Country updated",
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


    public function update(Request $request, $id): JsonResponse
    {
        try {
            #validate Language
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            
            $country = Countries::find($id);
            $country->title = $request->input('title', $country->title);
            $country->iso3 = $request->input('iso3', $country->iso3);
            $country->iso2 = $request->input('iso2', $country->iso2);
            $country->phone_code = $request->input('phone_code', $country->phone_code);
            $country->capital = $request->input('capital', $country->capital);
            $country->currency = $request->input('currency', $country->currency);
            $country->currency_name = $request->input('currency_name', $country->currency_name);
            $country->currency_symbol = $request->input('currency_symbol', $country->currency_symbol);
            $country->timezones = json_encode($request->input('timezones', $country->timezones));
            $country->latitude = $request->input('latitude', $country->latitude);
            $country->longitude = $request->input('longitude', $country->longitude);
            $country->emoji = $request->input('emoji', $country->iso2);
            $country->image = $request->input('image', $country->image);
            $country->status = $request->input('status', $country->status);

            // Save the updated language
            $country->save();

            // Return response
            return $this->sendCreated([], __('countries.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }
    
    public function destroy(Countries $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new CountriesResource($id), __('countries.delete_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'iso3' => ['required', 'min:3', 'max:3'],
            'iso2' => ['required', 'min:2', 'max:2'],
            'phone_code' => ['required'],
            'capital' => ['required'],
            'currency' => ['required'],
            'currency_name' => ['required'],
            'currency_symbol' => ['required'],
            'timezones' => ['required', 'json']
        ];
    }
}
