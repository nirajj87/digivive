<?php

namespace App\Http\Controllers\API\V1;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\DepartmentResource;
use App\Enums\Department;
use App\Http\Controllers\API\V1\BaseController as BaseController;


class DepartmentController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/department-list",
     *     operationId="getDepartmentList",
     *     summary="Get the list of departments.",
     *     tags={"Department"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $departmentList = Department::toArray();
        return $this->sendSuccessResponse(DepartmentResource::collection($departmentList), __('department.list'));
    }


}