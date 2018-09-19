<?php

namespace App\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;

class ApiResponses {
	
	public static function respondCollectionRetrieved(JsonResource $resource) {

		return $resource->additional([
				'status' => [
                    'message' => (new \ReflectionClass($resource))->getShortName() . ' has been retrieved.',
                    'http_status' => Response::HTTP_OK,
                    'http_status_text' => 'HTTP_OK',
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_OK);
	}	

	public static function respondCreated(JsonResource $resource) {

		return $resource->additional([
				'status' => [
                    'message' => (new \ReflectionClass($resource->resource))->getShortName() . ' has been created.',
                    'http_status' => Response::HTTP_CREATED,
                    'http_status_text' => 'HTTP_CREATED',
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_CREATED);
	}

	public static function respondRetrieved(JsonResource $resource) {

		return $resource->additional([
				'status' => [
                    'message' => (new \ReflectionClass($resource->resource))->getShortName() . ' has been retrieved.',
                    'http_status' => Response::HTTP_OK,
                    'http_status_text' => 'HTTP_OK',
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_OK);
	}

	public static function respondUpdated(JsonResource $resource) {

		return $resource->additional([
				'status' => [
                    'message' => (new \ReflectionClass($resource->resource))->getShortName() . ' has been updated.',
                    'http_status' => Response::HTTP_OK,
                    'http_status_text' => 'HTTP_OK',
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_OK);
	}

	public static function respondDeleted(JsonResource $resource) {


		return $resource->additional([
				'status' => [
                    'message' => (new \ReflectionClass($resource->resource))->getShortName() . ' has been deleted.',
                    'http_status' => Response::HTTP_OK,
                    'http_status_text' => 'HTTP_OK',
                    'error' => false
                ]
			])
            ->response()
        	->setStatusCode(Response::HTTP_OK);

    }

    public static function respondValidationFailed(Array $errors) {

    	return response()->json(
    		[
				'status' => [
                    'message' => 'Validation failed',
                    'http_status' => Response::HTTP_BAD_REQUEST,
                    'http_status_text' => 'HTTP_BAD_REQUEST',
                    'error' => true,
                    'errors' => $errors
                ]
			])
        	->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public static function respondNotFound() {
    	return response()->json(
    		[
				'status' => [
                    'message' => 'The endpoint you requested does not exist.',
                    'http_status' => Response::HTTP_NOT_FOUND,
                    'http_status_text' => 'HTTP_NOT_FOUND',
                    'error' => true
                ]
			])
        	->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    public static function respondModelNotFound() {
    	return response()->json(
    		[
				'status' => [
                    'message' => 'The record you are attempting to retrieve, edit, or delete does not exist.',
                    'http_status' => Response::HTTP_NOT_FOUND,
                    'http_status_text' => 'HTTP_NOT_FOUND',
                    'error' => true
                ]
			])
        	->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    public static function respondMethodNotAllowed() {
    	return response()->json(
    		[
				'status' => [
                    'message' => 'The HTTP method is not allowed.  Are you sure you used the correct HTTP verb?',
                    'http_status' => Response::HTTP_METHOD_NOT_ALLOWED,
                    'http_status_text' => 'HTTP_METHOD_NOT_ALLOWED',
                    'error' => true
                ]
			])
        	->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public static function respondInternalServerError(Exception $exception=null) {

    	return response()->json(
    		[
				'status' => [
                    'message' => 'There was an unkown server error' . $exception->getMessage(),
                    'http_status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'http_status_text' => 'HTTP_INTERNAL_SERVER_ERROR',
                    'error' => true
                ]
			])
        	->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function respondNotAuthenticated() {
        return response()->json(
            [
                'status' => [
                    'message' => 'You must supply a valid token to access this resource',
                    'http_status' => Response::HTTP_FORBIDDEN,
                    'http_status_text' => 'HTTP_FORBIDDEN',
                    'error' => true
                ]
            ])
            ->setStatusCode(Response::HTTP_FORBIDDEN);
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

        if ($exception instanceof AuthenticationException) {
          return ApiResponses::respondNotAuthenticated();
        }


        return ApiResponses::respondInternalServerError($exception);

    }

}