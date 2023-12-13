<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		\League\OAuth2\Server\Exception\OAuthServerException::class
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Report or log an exception.
	 *
	 * @param  \Exception  $exception
	 * @return void
	 */
	public function report(Exception $exception) {
		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $exception
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception) {

		if ($request->wantsJson()) {
			//add Accept: application/json in request
			return $this->handleApiException($request, $exception);
		}

		return parent::render($request, $exception);
	}

	private function handleApiException($request, Exception $exception) {
		$exception = $this->prepareException($exception);

		if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
			$exception = $exception->getResponse();
		}

		if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
			$exception = $this->unauthenticated($request, $exception);
		}

		if ($exception instanceof \Illuminate\Validation\ValidationException) {
			$exception = $this->convertValidationExceptionToResponse($exception, $request);
		}

		return $this->customApiResponse($exception);
	}

	private function customApiResponse($exception) {
		if (method_exists($exception, 'getStatusCode')) {
			$statusCode = $exception->getStatusCode();
		} else {
			if ($exception instanceof TokenBlacklistedException) {
				return response()->json([
					'message' => 'The token has been blacklisted.',
					'code' => 999,
				], 200);

			}
			if ($exception instanceof TokenInvalidException) {
				return response()->json([
					'message' => 'Session Expired - Please login again.',
					'code' => 999,
				], 200);

			}
			if ($exception instanceof TokenExpiredException) {

				return response()->json([
					'message' => 'Session Expired - Please login again.',
					'code' => 999,
				], 200);
			}
			$statusCode = 500;
		}

		$response = [];

		switch ($statusCode) {
		case 401:
			$response['message'] = 'Unauthorized';
			break;
		case 403:
			$response['message'] = 'Forbidden';
			break;
		case 404:
			$response['message'] = 'Not Found';
			break;
		case 405:
			$response['message'] = 'Method Not Allowed';
			break;
		case 422:
			$response['message'] = $exception->original['message'];
			$response['errors'] = $exception->original['errors'];
			break;
		case 500:
			$response['message'] = ($statusCode == 500) ? 'Oops, something went wrong! Please contact info@lms.com to receive help.' : $exception->getMessage();
			break;
		default:
			break;
		}

		$response['code'] = $statusCode;

		return response()->json($response, $statusCode);
	}
}
