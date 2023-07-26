<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\LanguageResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LanguageController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/v1/language/list",
     *     tags={"Language (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get a list of languages",
     *     description="Retrieve a list of languages with status = 1",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     * @OA\Tag(
     *     name="Language (Master)",
     *     description="Language related operations"
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Language::query();

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
            $query->orWhere('iso_code2', 'ILIKE', '%' . $search . '%');
            $query->orWhere('iso_code3', 'ILIKE', '%' . $search . '%');
        }

        // Pagination
        $perPage = $request->input('perPage');

        if (isset($perPage) && $perPage !== '') {
            $per_page = intval($perPage);
        } else {
            $per_page = self::DEFAULT_PER_PAGE;
        }

        $languageList = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(LanguageResource::collection($languageList), __('language.list'));
    }

    /**
     * @OA\Post(
     *     path="/v1/language/create",
     *     summary="Create Language",
     *     tags={"Language (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Language data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "iso_code2", "iso_code3", "country_code"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="English",
     *                     description="Title of the Language"
     *                 ),
     *                 @OA\Property(
     *                     property="iso_code2",
     *                     type="string",
     *                     example="en",
     *                     description="ISO Code 2 of the Language"
     *                 ),
     *                 @OA\Property(
     *                     property="iso_code3",
     *                     type="string",
     *                     example="eng",
     *                     description="ISO Code 3 of the Language"
     *                 ),
     *                 @OA\Property(
     *                     property="country_code",
     *                     type="string",
     *                     example="US",
     *                     description="Country Code of the Language"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Language created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Internal Server Error")
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

            $title = $request->input('title');
            $slug = Language::getUniqueSlug(Str::slug($title, "-")); #create unique slug for the language
            #language data
            $data = [
                'title' => $title,
                'slug' => $slug,
                'iso_code2' => $request->input('iso_code2'),
                'iso_code3' => $request->input('iso_code3'),
                'status' => $request->input('status',1),
                'country_code' => $request->input('country_code')
            ];

            #create language
            $language = Language::create($data);

            #return response
            return $this->sendCreated([], __('language.successful'));

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/language/details/{id}",
     *     tags={"Language (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get language details",
     *     description="Get the details of a specific language by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the language",
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
    public function details(Language $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new LanguageResource($id), __('language.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/v1/language/edit/{id}",
     *     summary="Update Language",
     *     tags={"Language (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Language",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Language data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="English",
     *                 description="Title of the Language"
     *             ),
     *             @OA\Property(
     *                 property="iso_code2",
     *                 type="string",
     *                 example="en",
     *                 description="ISO Code 2 of the Language"
     *             ),
     *             @OA\Property(
     *                 property="iso_code3",
     *                 type="string",
     *                 example="eng",
     *                 description="ISO Code 3 of the Language"
     *             ),
     *             @OA\Property(
     *                 property="country_code",
     *                 type="string",
     *                 example="US",
     *                 description="Country Code of the Language"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Language updated",
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


    public function update(Request $request, Language $id): JsonResponse
    {
        try {
            #validate Language
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            // Update the language attributes
            $title = $request->input('title');
            $slug = Language::getUniqueSlug(Str::slug($title, "-"));
            $id->title = $title;
            $id->slug = $slug;
            $id->iso_code2 = $request->input('iso_code2', $id->iso_code2);
            $id->iso_code3 = $request->input('iso_code3', $id->iso_code3);
            $id->status = $request->input('status', $id->status);
            $id->country_code = $request->input('country_code', $id->country_code);
            // Save the updated language
            $id->save();

            // Return response
            return $this->sendCreated([], __('language.update_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }


    /**
     * @OA\Delete(
     *     path="/v1/language/delete/{id}",
     *     tags={"Language (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Delete language",
     *     description="Delete a specific language",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the language",
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
    public function destroy(Language $id): JsonResponse
    {
        try {
            $id->delete();
            return $this->sendCreated(new LanguageResource($id), __('language.delete_success'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'iso_code2' => ['required', 'min:2', 'max:2'],
            'country_code' => ['required', 'min:2', 'max:3']
        ];
    }

}