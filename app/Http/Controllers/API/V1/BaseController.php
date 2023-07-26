<?php

namespace App\Http\Controllers\API\V1;


use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{   
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
   public const DEFAULT_PER_PAGE = 10;
    public function sendSuccessResponse($data, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }


    /**
     * success response method with pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendPaginationSuccessResponse($data, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $data,
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
            'message' => $message,
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendErrorResponse($error, $errorMessages = [], $code = Response::HTTP_BAD_REQUEST)
    {
    	$response = [
            'success' => false,
            'message' => $error,
            'data'    => $errorMessages
        ];

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendServerErrorResponse($error, $errorMessages = [], $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
    	$response = [
            'success' => false,
            'message' => $error,
            'data'    => $errorMessages
        ];

        return response()->json($response, $code);
    }

    /**
     * return unauthorized response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendUnauthorizedResponse($error, $data = [], $code = Response::HTTP_UNAUTHORIZED)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'data'    => $data
        ];

        return response()->json($response, $code);
    }

    /**
     * Send a success response with HTTP status code 200 List(OK).
     *
     * @param  mixed  $result
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    public function sendListWithSuccess($result = null, $message = 'Success', $code = Response::HTTP_OK)
    { 
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    /**
     * Send a success response with HTTP status code 200 (OK).
     *
     * @param  mixed  $result
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    public function sendSuccess($result = null, $message = 'Success', $code = Response::HTTP_OK)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    /**
     * Send a created response with HTTP status code 201 (Created).
     *
     * @param  mixed  $result
     * @param  string  $message
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function sendCreated($result = null, $message = 'Created', $code = Response::HTTP_CREATED)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    /**
     * Send a success response with HTTP status code 204 (No Content).
     *
     * @return \Illuminate\Http\Response
     */
    public function sendNoContent()
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Send an error response with a customizable HTTP status code.
     *
     * @param  string  $error
     * @param  array  $errorMessages
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = Response::HTTP_BAD_REQUEST)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Send a server error response with a customizable HTTP status code (default: 500 - Internal Server Error).
     *
     * @param  string  $error
     * @param  array  $errorMessages
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function sendServerError($error, $errorMessages = [], $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return $this->sendError($error, $errorMessages, $code);
    }

    /**
     * Send an unauthorized response with a customizable HTTP status code (default: 401 - Unauthorized).
     *
     * @param  string  $error
     * @param  mixed  $data
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function sendUnauthorized($error = 'Unauthorized', $data = null, $code = Response::HTTP_UNAUTHORIZED)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }

    /**
     * Send a forbidden response with a customizable HTTP status code (default: 403 - Forbidden).
     *
     * @param  string  $error
     * @param  mixed  $data
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function sendForbidden($error = 'Forbidden', $data = null, $code = Response::HTTP_FORBIDDEN)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }
}