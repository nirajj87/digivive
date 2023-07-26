<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Rest API Starter Explorer",
 *     description="A proposed openAPI explorer for Rest Api Starter Template"
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api/",
 *     description="Local server"
 * )
 *
 * @OA\Server(
 *     url="https://stage-api-cms/api/",
 *     description="Staging server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
}
