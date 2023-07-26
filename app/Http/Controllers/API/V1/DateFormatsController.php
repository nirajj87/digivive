<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Resources\DateFormatsResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Models\DateFormats;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DateFormatsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Retrieve a list of date formats.
     *
     * @OA\Get(
     *   path="/v1/date-formats",
     *   summary="Get Date Formats",
     *   tags={"Date Formats (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="data", type="array", @OA\Items()),
     *       @OA\Property(property="message", type="string")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="Server Error")
     * )
     *  @OA\Tag(
     *     name="Date Formats (Master)",
     *     description="Date formats related operation"
     * )
     */

    /*public function index(): JsonResponse
    {
        $dates = DateFormats::where(['flag'=> '0'])
                    ->orderBy('order', 'ASC')
                    ->get();

        $times = DateFormats::where(['flag'=> '1'])
                    ->orderBy('order', 'ASC')
                    ->get();
                    
        return $this->sendListWithSuccess(DateFormatsResource::toDateTimeArray($dates, $times), __('date_formats.list'));
        //
    }*/

    public function index(Request $request): JsonResponse
    {
        $query = DateFormats::query();

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
            $query->orWhere('format_key', 'ILIKE', '%' . $search . '%');
        }

        // Pagination
        $perPage = $request->input('perPage');

        if (isset($perPage) && $perPage !== '') {
            $per_page = intval($perPage);
        } else {
            //$per_page = self::DEFAULT_PER_PAGE;
            $per_page = -1;
        }

        $dateTimeList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(DateFormatsResource::collection($dateTimeList), __('date_formats.list'));
    }

    /**
     * @OA\Post(
     *     path="/v1/date-formats/",
     *     summary="Create date formate",
     *     tags={"Date Formate (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="date formate data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "format_key"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="10:59 AM",
     *                     description="Title of the date formate"
     *                 ),
     *                 @OA\Property(
     *                     property="format_key",
     *                     type="string",
     *                     example="h:i A",
     *                     description="Format key of the date formate"
     *                 ),
     *                 @OA\Property(
     *                     property="order",
     *                     type="string",
     *                     example="0",
     *                     description="Order of the date formate"
     *                 ),
     *                 @OA\Property(
     *                     property="flag",
     *                     type="integer",
     *                     example="1-Time 0-Date",
     *                     description="Flag of the date formate"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="1",
     *                     description="Status of the date formate"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="date formate created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */


    public function store(Request $request): JsonResponse
    {
        try {

            #validate date formate
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            
            #date formate data
            $data = [
                'title'      => $request->input('title'),
                'format_key' => $request->input('format_key'),
                'flag'       => $request->input('flag', 0),
                'order'      => $request->input('order',0),
                'status'     => $request->input('status', 1),
            ];

            #create date formate
            DateFormats::create($data);

            #return response
            return $this->sendCreated([], __('date_formats.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/date-formats/{id}",
     *     tags={"Date Formate (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get date formate details",
     *     description="Get the details of a specific date formate by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the date formate",
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
    
    public function show(DateFormats $date_format): JsonResponse
    {
        try {
            return $this->sendSuccess(new DateFormatsResource($date_format), __('date_formats.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/v1/date_formats/{id}",
     *     summary="Update date formate",
     *     tags={"Date Formate (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the date formate",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="date formate data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="10:59 AM",
     *                 description="Title of the date formate"
     *             ),
     *             @OA\Property(
     *                 property="format_key",
     *                 type="string",
     *                 example="h:i A",
     *                 description="Format Key of the date formate"
     *             ),
     *             @OA\Property(
     *                 property="order",
     *                 type="string",
     *                 example="1",
     *                 description="Order of the date formate"
     *             ),
     *             @OA\Property(
     *                 property="flag",
     *                 type="string",
     *                 example="1-Time/0-Date",
     *                 description="Flag of the date formate"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="1",
     *                 description="Status of the date formate"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="date formate updated",
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


    public function update(Request $request, DateFormats $date_format): JsonResponse
    {
        try {
            #validate date format
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());
            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            // Update the date format attributes
            $date_format->title = $request->input('title', $date_format->title);
            $date_format->format_key = $request->input('format_key', $date_format->format_key);
            $date_format->flag = $request->input('flag', $date_format->flag);
            $date_format->order = $request->input('order', $date_format->order);
            $date_format->status = $request->input('status', $date_format->status);

            // Save the updated date format
            $date_format->save();

            // Return response
            return $this->sendCreated([], __('date_formats.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }



    /**
     * @OA\Delete(
     *     path="/v1/date_formats/{id}",
     *     tags={"Date Formate (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete date formate",
     *     description="Delete a specific date formate",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the date formate",
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
    public function destroy(DateFormats $date_format): JsonResponse
    {
        try {
            $date_format->delete();
            return $this->sendCreated(new DateFormatsResource($date_format), __('date_formats.delete_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'format_key' => ['required']
        ];
    }

}
