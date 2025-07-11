<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponseTrait
{
    public function successResponse($data = null, $message = 'Success', $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function successWithPagination($data, $message = 'Success', $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'total' => $data->total()
            ]
        ], $code);
    }

    /**
     * Success response with pagination and additional summary data
     */
    public function successWithPaginationAndSummary($data, $summary = null, $message = 'Success', $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'total' => $data->total()
            ]
        ];

        // Add summary if provided
        if ($summary !== null) {
            $response['balance_summary'] = $summary;
        }

        return response()->json($response, $code);
    }

    public function errorResponse($message = 'Error occurred', $code = Response::HTTP_BAD_REQUEST, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    public function validationErrorResponse($errors, $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function notFoundResponse($message = 'Resource Not found'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], Response::HTTP_NOT_FOUND);
    }

    public function createdResponse($data, $message = 'Resource created successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], Response::HTTP_CREATED);
    }

    /**
     * Created response with additional summary data
     */
    public function createdResponseWithSummary($data, $summary = null, $message = 'Resource created successfully'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        if ($summary !== null) {
            $response['balance_summary'] = $summary;
        }

        return response()->json($response, Response::HTTP_CREATED);
    }

    public function updatedResponse($data, $message = 'Resource updated successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Updated response with additional summary data
     */
    public function updatedResponseWithSummary($data, $summary = null, $message = 'Resource updated successfully'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        if ($summary !== null) {
            $response['balance_summary'] = $summary;
        }

        return response()->json($response, Response::HTTP_OK);
    }

    public function deletedResponse($message = 'Resource deleted successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ], Response::HTTP_OK);
    }

    /**
     * Deleted response with additional summary data
     */
    public function deletedResponseWithSummary($summary = null, $message = 'Resource deleted successfully'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if ($summary !== null) {
            $response['balance_summary'] = $summary;
        }

        return response()->json($response, Response::HTTP_OK);
    }
}
