<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Resources\TimezonesResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Models\Timezones;

class TimezonesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Retrieve a list of timezones.
     *
     * @OA\Get(
     *   path="/v1/timezones",
     *   summary="Get Timezones",
     *   tags={"Timezones (Master)"},
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
     * @OA\Tag(
     *     name="Timezones (Master)",
     *     description="Timezones list "
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Timezones::query();

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
            //$per_page = self::DEFAULT_PER_PAGE;
            $per_page = -1;
        }

        $timezones = $query->paginate($per_page);

        return $this->sendPaginationSuccessResponse(TimezonesResource::collection($timezones), __('timezones.list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        //
    }
}
