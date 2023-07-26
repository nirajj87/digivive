<?php

namespace App\Http\Controllers\API\V1;

use App\Models\S3Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Http\Resources\S3SettingsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class S3SettingsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/v1/s3settings/list",
     *     tags={"S3 Settings (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of s3 setting",
     *     description="Retrieve a list of s3 setting with status = 1",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="S3 Settings (Master)",
     *     description="S3 Setting related operations(Master)"
     * )
     */

    public function index(Request $request): JsonResponse
    {
        $query = S3Settings::query();

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

        $s3SettingsList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(S3SettingsResource::collection($s3SettingsList), __('s3setting.list'));
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
     *     path="/v1/s3settings/create",
     *     summary="Create s3 setting",
     *     tags={"S3 Settings (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="s3 setting data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="example",
     *                     description="Title of the s3 setting"
     *                 ),
     *                 @OA\Property(
     *                     property="order",
     *                     type="integer",
     *                     example="1",
     *                     description="order of the s3 setting"
     *                 ),
     *                 @OA\Property(
     *                     property="additional_settings",
     *                     type="json",
     *                     example="['color':'red']",
     *                     description="Additional setting of the s3 setting"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="s3 setting Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */


    public function store(Request $request): JsonResponse
    {
        try {

            #validate s3 setting
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = S3Settings::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the s3 setting
            #s3 setting data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'additional_settings' => $request->input('additional_settings') ?: null,
                'status' => 1
            ];

            #create s3 setting
            S3Settings::create($data);

            #return response
            return $this->sendCreated([], __('s3setting.successful'));

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
     *     path="/v1/s3settings/details/{id}",
     *     tags={"S3 Settings (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get s3 setting details",
     *     description="Get the details of a specific s3 setting by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the s3 setting",
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
            $s3Setting = S3Settings::findOrFail($id);
            return $this->sendSuccess(new S3SettingsResource($s3Setting), __('s3setting.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(S3Settings $s3Setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/v1/s3settings/edit/{id}",
     *     summary="Update s3 setting",
     *     tags={"S3 Settings (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the s3 setting",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="s3 setting data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Title",
     *                 description="Title of the s3 setting"
     *             ),
     *             @OA\Property(
     *                 property="order",
     *                 type="integer",
     *                 example="1",
     *                 description="Order of the s3 setting"
     *             ),
     *             @OA\Property(
     *                 property="additional_settings",
     *                 type="string",
     *                 example="['color':'red']",
     *                 description="Additional Settings of the s3 setting"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="s3 setting updated",
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


    public function update(Request $request, S3Settings $id): JsonResponse
    {
        try {
            # Validate s3 setting
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            # Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            $title = $request->input('title');
            $slug = S3Settings::getUniqueSlug(Str::slug($title, "-"));

            // Update the s3 setting attributes
            $fieldsToUpdate = [
                'title' => $title,
                'slug' => $slug,
                'additional_settings' => $request->input('additional_settings') ?? $id->additional_settings,
                'status' => $request->input('status') ?? $id->status,
            ];

            $id->update($fieldsToUpdate);

            // Return response
            return $this->sendCreated([], __('s3setting.update_success'));
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
     *     path="/v1/s3settings/delete/{id}",
     *     tags={"S3 Settings (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete s3 setting",
     *     description="Delete a specific s3 setting",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the s3 setting",
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
    public function destroy(S3Settings $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new S3SettingsResource($id), __('s3setting.delete_success'));
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