<?php

namespace App\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiResponses {
	
	public static function respondCollectionRetrieved(JsonResource $resource) {

		return $resource->additional([
				'meta' => [
                    'message' => (new \ReflectionClass($resource->resource))->getShortName() . ' listing has been retreived.',
                    'http_status' => Response::HTTP_OK,
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_OK);
	}	

	public static function respondCreated(JsonResource $resource) {

		return $resource->additional([
				'meta' => [
                    'message' => (new \ReflectionClass($resource->resource))->getShortName() . ' has been created.',
                    'http_status' => Response::HTTP_CREATED,
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_CREATED);
	}

	public static function respondRetrieved(JsonResource $resource) {

		return $resource->additional([
				'meta' => [
                    'message' => (new \ReflectionClass($resource->resource))->getShortName() . ' has been retreived.',
                    'http_status' => Response::HTTP_OK,
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_OK);
	}

	public static function respondUpdated(JsonResource $resource) {

		return $resource->additional([
				'meta' => [
                    'message' => (new \ReflectionClass($resource->resource))->getShortName() . ' has been updated.',
                    'http_status' => Response::HTTP_OK,
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_OK);
	}

	public static function respondDeleted(JsonResource $resource) {


		return $resource->additional([
				'meta' => [
                    'message' => (new \ReflectionClass($resource->resource))->getShortName() . ' has been deleted.',
                    'http_status' => Response::HTTP_OK,
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_OK);

    }

    public static function respondValidationFailed(Array $errors) {

    	return response()->json(
    		[
				'meta' => [
                    'message' => 'Validation failed',
                    'http_status' => Response::HTTP_BAD_REQUEST,
                    'error' => true,
                    'errors' => $errors
                ]
			])
        	->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public static function respondNotFound() {
    	return response()->json(
    		[
				'meta' => [
                    'message' => 'The endpoint you requested does not exist.',
                    'http_status' => Response::HTTP_NOT_FOUND,
                    'error' => true
                ]
			])
        	->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    public static function respondModelNotFound() {
    	return response()->json(
    		[
				'meta' => [
                    'message' => 'The record you are attempting to retrieve, edit, or delete does not exist.',
                    'http_status' => Response::HTTP_NOT_FOUND,
                    'error' => true
                ]
			])
        	->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    public static function respondMethodNotAllowed() {
    	return response()->json(
    		[
				'meta' => [
                    'message' => 'The HTTP method is not allowed.  Are you sure you used the correct HTTP verb?',
                    'http_status' => Response::HTTP_METHOD_NOT_ALLOWED,
                    'error' => true
                ]
			])
        	->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public static function respondInternalServerError($exception) {
    	return response()->json(
    		[
				'meta' => [
                    'message' => 'There was an unkown server error: ' . get_class($exception),
                    'http_status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'error' => true
                ]
			])
        	->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function respondException(Exception $exception) {

    	if ($exception instanceof ModelNotFoundException) {
          return ApiResponses::respondModelNotFound();
        }

        if ($exception instanceof NotFoundHttpException) {
          return ApiResponses::respondNotFound();
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
          return ApiResponses::respondMethodNotAllowed();
        }

        return ApiResponses::respondInternalServerError($exception);

    }

}