<?php // @codingStandardsIgnoreLine.
/**
 * This file is used to execute a Google_Http_Request.
 *
 * @author Tech Banker
 * @package wp-backup-bank/lib/Google/Http
 * @version 3.0.1
 */

/*
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! class_exists( 'Google_Client' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/autoload.php';
}
/**
 * This class implements the RESTful transport of apiServiceRequest()'s
 */
class Google_Http_REST {
	/**
	 * Executes a Google_Http_Request and (if applicable) automatically retries
	 * when errors occur.
	 *
	 * @param Google_Client       $client .
	 * @param Google_Http_Request $req .
	 * @return array decoded result
	 * @Throws Google_Service_Exception on server side error (ie: not authenticated,
	 *  invalid or malformed post body, invalid url).
	 */
	public static function execute( Google_Client $client, Google_Http_Request $req ) {
		$runner = new Google_Task_Runner(
			$client, sprintf( '%s %s', $req->getRequestMethod(), $req->getUrl() ), array( get_class(), 'doExecute' ), array( $client, $req )
		);
		return $runner->run();
	}
	/**
	 * Executes a Google_Http_Request
	 *
	 * @param Google_Client       $client .
	 * @param Google_Http_Request $req .
	 * @return array decoded result
	 * @Throws Google_Service_Exception on server side error (ie: not authenticated,
	 *  invalid or malformed post body, invalid url).
	 */
	public static function doExecute( Google_Client $client, Google_Http_Request $req ) {// @codingStandardsIgnoreLine.
		$http_request = $client->getIo()->makeRequest( $req );
		$http_request->setExpectedClass( $req->getExpectedClass() );
		return self::decodeHttpResponse( $http_request, $client );
	}
	/**
	 * Decode an HTTP Response.
	 *
	 * @static
	 * @throws Google_Service_Exception .
	 * @param Google_Http_Request $response The http response to be decoded.
	 * @param Google_Client       $client .
	 * @return mixed|null
	 */
	public static function decodeHttpResponse( $response, Google_Client $client = null ) {// @codingStandardsIgnoreLine.
		$code    = $response->getResponseHttpCode();
		$body    = $response->getResponseBody();
		$decoded = null;

		if ( ( intVal( $code ) ) >= 300 ) {
			$decoded = json_decode( $body, true );
			$err     = 'Error calling ' . $response->getRequestMethod() . ' ' . $response->getUrl();
			if ( isset( $decoded['error'] ) &&
			isset( $decoded['error']['message'] ) &&
			isset( $decoded['error']['code'] ) ) {
				// if we're getting a json encoded error definition, use that instead of the raw response
				// body for improved readability.
				$err .= ": ({$decoded['error']['code']}) {$decoded['error']['message']}";
			} else {
				$err .= ": ($code) $body";
			}

			$errors = null;
			// Specific check for APIs which don't return error details, such as Blogger.
			if ( isset( $decoded['error'] ) && isset( $decoded['error']['errors'] ) ) {
				$errors = $decoded['error']['errors'];
			}

			$map = null;
			if ( $client ) {
				$client->getLogger()->error(
					$err, array(
						'code'   => $code,
						'errors' => $errors,
					)
				);

				$map = $client->getClassConfig(
					'Google_Service_Exception', 'retry_map'
				);
			}
			return '601';
		}

		// Only attempt to decode the response, if the response code wasn't (204) 'no content'.
		if ( '204' != $code ) {// WPCS: Loose comparison ok.
			if ( $response->getExpectedRaw() ) {
				return $body;
			}

			$decoded = json_decode( $body, true );
			if ( null === $decoded || '' === $decoded ) {
				// UpdraftPlus patch.
				$error = "Invalid json in service response ($code): $body";
				if ( $client ) {
					$client->getLogger()->error( $error );
				}
				throw new Google_Service_Exception( $error );
			}

			if ( $response->getExpectedClass() ) {
				$class   = $response->getExpectedClass();
				$decoded = new $class( $decoded );
			}
		}
		return $decoded;
	}
	/**
	 * Parse/expand request parameters and create a fully qualified
	 * request uri.
	 *
	 * @static
	 * @param string $servicePath .
	 * @param string $restPath .
	 * @param array  $params .
	 * @return string $requestUrl .
	 */
	public static function createRequestUri( $servicePath, $restPath, $params ) {// @codingStandardsIgnoreLine.
		$requestUrl      = $servicePath . $restPath;// @codingStandardsIgnoreLine.
		$uriTemplateVars = array();// @codingStandardsIgnoreLine.
		$queryVars       = array();// @codingStandardsIgnoreLine.
		foreach ( $params as $paramName => $paramSpec ) {// @codingStandardsIgnoreLine.
			if ( $paramSpec['type'] == 'boolean' ) {// @codingStandardsIgnoreLine.
				$paramSpec['value'] = ( $paramSpec['value'] ) ? 'true' : 'false';// @codingStandardsIgnoreLine.
			}
			if ( $paramSpec['location'] == 'path' ) {// @codingStandardsIgnoreLine.
				$uriTemplateVars[ $paramName ] = $paramSpec['value'];// @codingStandardsIgnoreLine.
			} elseif ( $paramSpec['location'] == 'query' ) {// @codingStandardsIgnoreLine.
				if ( isset( $paramSpec['repeated'] ) && is_array( $paramSpec['value'] ) ) {// @codingStandardsIgnoreLine.
					foreach ( $paramSpec['value'] as $value ) {// @codingStandardsIgnoreLine.
						$queryVars[] = $paramName . '=' . rawurlencode( rawurldecode( $value ) );// @codingStandardsIgnoreLine.
					}
				} else {
					$queryVars[] = $paramName . '=' . rawurlencode( rawurldecode( $paramSpec['value'] ) );// @codingStandardsIgnoreLine.
				}
			}
		}

		if ( count( $uriTemplateVars ) ) {// @codingStandardsIgnoreLine.
			$uriTemplateParser = new Google_Utils_URITemplate();// @codingStandardsIgnoreLine.
			$requestUrl        = $uriTemplateParser->parse( $requestUrl, $uriTemplateVars );// @codingStandardsIgnoreLine.
		}

		if ( count( $queryVars ) ) {// @codingStandardsIgnoreLine.
			$requestUrl .= '?' . implode( $queryVars, '&' );// @codingStandardsIgnoreLine.
		}

		return $requestUrl;// @codingStandardsIgnoreLine.
	}
}
