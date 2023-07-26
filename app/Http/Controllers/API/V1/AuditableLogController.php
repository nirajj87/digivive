<?php
namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\AuditLogResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Support\Facades\Log;
use App\Models\MongoAudit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuditableLogController extends BaseController
{
    /**
     * @OA\Get(
     *   path="/v1/audit/log",
     *   summary="Get Auditable Log",
     *   tags={"Auditable Log"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="search",
     *     in="query",
     *     description="Search query to filter the log entries",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="event",
     *     in="query",
     *     description="Filter by event",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="Filter by user ID",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_name",
     *     in="query",
     *     description="Filter by user name",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="user_email",
     *     in="query",
     *     description="Filter by user email",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="date_from",
     *     in="query",
     *     description="Filter by start date (YYYY-MM-DD)",
     *     @OA\Schema(
     *       type="string",
     *       format="date"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="date_to",
     *     in="query",
     *     description="Filter by end date (YYYY-MM-DD)",
     *     @OA\Schema(
     *       type="string",
     *       format="date"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="sortBy",
     *     in="query",
     *     description="Sort the log entries by a column",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="sortDirection",
     *     in="query",
     *     description="Sort direction (asc or desc)",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     description="Number of log entries per page",
     *     @OA\Schema(
     *       type="integer",
     *       format="int32"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error"
     *   ),
     *  
     * )
     *  @OA\Tag(
     *     name="Auditable Log",
     *     description="User related activity logs"
     * )
     
     */
    public function getAuditableLog(Request $request): JsonResponse
    { 
        try {
            $query = MongoAudit::query();
            # To search for a value
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($query) use ($search) {
                    $query->where('event', 'like', "%{$search}%")
                        ->orWhere('user_id', 'like', "%{$search}%")
                        ->orWhere('created_at', 'like', "%{$search}%")
                        ->orWhere('new_values', 'like', "%{$search}%")
                        ->orWhere('old_values', 'like', "%{$search}%")
                        ->orWhere('user_data', 'like', "%{$search}%");
                });
            }

            # Role based filter
            if ($request->has('event')) {
                $events = $request->query('event');
                $query->where('event', $events);
            }
            if ($request->has('user_id')) {
                $userIds = $request->query('user_id');
                $query->where('user_id', (int)$userIds);
            }
            if ($request->has('user_name')) {
                $userNames = $request->query('user_name');
                $query->where('user_name', $userNames);
            }
            if ($request->has('user_email')) {
                $userEmails = $request->query('user_email');
                $query->where('user_email', $userEmails);
            }
            if ($request->has('date_from')) {
                $date_from = Carbon::parse($request->query('date_from'))->startOfDay();
            }

            if ($request->has('date_to')) {
                $date_to = Carbon::parse($request->query('date_to'))->endOfDay();
            }

            if ((isset($date_from) && ($date_from != '')) && ($date_to != '')) {
                $query->whereBetween('created_at', [$date_from, $date_to]);
            }

            // Sorting based on a column
            if ($request->has('sortBy')) {
                $sortBy = $request->query('sortBy');
                $sortDirection = $request->query('sortDirection', $sortBy);
                $query->orderBy('created_at', $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc'); // Sort by 'created_at' field in descending order
            }

            # Pagination
            $per_page = self::DEFAULT_PER_PAGE;
            $perPage = $request->query('per_page', $per_page);
            $logData = $query->paginate($perPage);

            return $this->sendPaginationSuccessResponse(AuditLogResource::collection($logData), __('auditlog.list'));
       
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *   path="/v1/audit/logs/details/{id}",
     *   summary="Get Auditable Log Details",
     *   tags={"Auditable Log"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID of the Auditable Log",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Invalid request"
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error"
     *   )
     * )
     */

    public function details(MongoAudit $id): JsonResponse
    {
        try {
            return $this->sendSuccess(new AuditLogResource($id), __('auditlog.detail'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }
    
}
