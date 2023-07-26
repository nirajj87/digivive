<?php

namespace App\Http\Controllers\API\V1;

use App\Models\PaymentModes;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Http\Resources\PaymentModesResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentModesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/v1/PaymentModes/list",
     *     tags={"Payment Modes (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of payment mode",
     *     description="Retrieve a list of payment mode with status = 1",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="payment modes (Master)",
     *     description="payment mode related operations(Master)"
     * )
     */


    /**
     * @OA\Get(
     *     path="/payment-modes",
     *     summary="Get payment modes",
     *     tags={"Payment Modes (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="Sort by column name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sortDirection",
     *         in="query",
     *         description="Sort direction (asc or desc)",
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by title",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         @OA\Schema(type="integer", format="int32", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     * @OA\Tag(
     *     name="Payment Modes (Master)",
     *     description="Payment Modes related operations"
     * )
     */

    public function index(Request $request): JsonResponse
    {
        $query = PaymentModes::query();

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
        // Filtering by type
         $type = $request->input('type');
    
        if ($type) {
            $query->where('type', $type);
        }

        // Pagination
        $perPage = intval($request->input('perPage', self::DEFAULT_PER_PAGE));

        $paymentModesList = $query->paginate($perPage);

        return $this->sendPaginationSuccessResponse(PaymentModesResource::collection($paymentModesList), __('payment_mode.list'));
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
     *
     * @OA\Post(
     *     path="/v1/PaymentModes/create",
     *     summary="Create payment mode",
     *     tags={"Payment Modes (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Payment mode data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="example",
     *                     description="Title of the payment mode"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     example="type",
     *                     description="Type of the payment mode"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     example="Image",
     *                     description="Image of the payment mode"
     *                 ),
     *                 @OA\Property(
     *                     property="is_recurring",
     *                     type="string",
     *                     example="0 Or 1",
     *                     description="Is recurring"
     *                 ),
     *                 @OA\Property(
     *                     property="additional_settings",
     *                     type="json",
     *                     example="{'color':'red'}",
     *                     description="Additional settings of the payment mode"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Payment mode created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */


    public function store(Request $request): JsonResponse
    {
        try {

            #validate payment mode
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = PaymentModes::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the payment mode
            #payment mode data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'type' => $request->input('type', null),
                'is_recurring' => $request->input('is_recurring', null),
                'image' => $request->input('image',null),
                'additional_settings' => $request->input('additional_settings', null),
                'status' => $request->input('status', 1)
            ];

            #create payment mode
            PaymentModes::create($data);

            #return response
            return $this->sendCreated([], __('payment_mode.successful'));

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
     *     path="/v1/PaymentModes/details/{id}",
     *     tags={"Payment Modes (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get payment mode details",
     *     description="Get the details of a specific payment mode by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the payment mode",
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
            $payment_mode = PaymentModes::findOrFail($id);
            return $this->sendSuccess(new PaymentModesResource($payment_mode), __('payment_mode.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(PaymentModes $payment_mode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/v1/PaymentModes/edit/{id}",
     *     summary="Update payment mode",
     *     tags={"Payment Modes (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the payment mode",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="payment mode data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Title",
     *                 description="Title of the payment mode"
     *             ),
     *             @OA\Property(
     *                 property="type",
     *                 type="string",
     *                 example="Type",
     *                 description="Type of the payment mode"
     *             ),
     *             @OA\Property(
     *                 property="image",
     *                 type="file",
     *                 example="upload file",
     *                 description="Image of the payment mode"
     *             ),
     *             @OA\Property(
     *                 property="is_recurring",
     *                 type="string",
     *                 example="0 Or 1",
     *                 description="Is recuring "
     *             ),
     *             @OA\Property(
     *                 property="additional_settings",
     *                 type="string",
     *                 example="['color':'red']",
     *                 description="Additional Settings of the payment mode"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="payment mode updated",
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


    public function update(Request $request, PaymentModes $id): JsonResponse
    {
        try {
            # Validate payment mode
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = PaymentModes::getUniqueSlug(Str::slug($title, "-"));

            // Update the payment mode attributes
            $fieldsToUpdate = [
                'title' => $title,
                'slug' => $slug,
                'type' => $request->input('type', $id->type),
                'is_recurring' => $request->input('is_recurring', $id->is_recurring),
                'image' => $request->input('image', $id->image),
                'additional_settings' => $request->input('additional_settings', $id->additional_settings),
                'status' => $request->input('status',$id->status),
            ];

            $id->update($fieldsToUpdate);

            // Return response
            return $this->sendCreated([], __('payment_mode.update_success'));
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
     *     path="/v1/PaymentModes/delete/{id}",
     *     tags={"Payment Modes (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete payment mode",
     *     description="Delete a specific payment mode",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the payment mode",
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
    public function destroy(PaymentModes $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new PaymentModesResource($id), __('payment_mode.delete_success'));
        } catch (Exception $e) {
            // Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'type' => ['required'],
            'is_recurring' => ['required','integer'],
            'additional_settings' => ['json']
        ];
    }
}
