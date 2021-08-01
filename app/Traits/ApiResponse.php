<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;
use mysql_xdevapi\DatabaseObject;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Prepare response.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @return array
     */
    public function prepareApiResponse(string $message = '', int $statusCode = Response::HTTP_OK): array
    {
        if (empty($message)) {
            $message = Response::$statusTexts[$statusCode];
        }

        return [
            'message' => $message,
            'code' => $statusCode,
        ];
    }

    /**
     * Success Response
     *
     * @param  array  $data
     * @param  int  $statusCode
     * @param  string  $message
     * @return JsonResponse
     */
    public function success(array$data, int $statusCode = Response::HTTP_OK, string $message = ''): JsonResponse
    {
        $response = $this->prepareApiResponse($message, $statusCode);
        $response['data'] = $data;
        $response['status'] = 'OK';
        return response()->json($response, $statusCode);
    }

    /**
     * Error Response
     *
     * @param  array  $errors
     * @param  int  $statusCode
     * @param  string  $message
     * @return JsonResponse
     */
    public function error(array $errors=[], int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, string $message = ''): JsonResponse
    {
        $response = $this->prepareApiResponse($message, $statusCode);
        $response['errors'] = $errors;
        $response['status'] = 'NOK';
        return response()->json($response, $statusCode);
    }
}
