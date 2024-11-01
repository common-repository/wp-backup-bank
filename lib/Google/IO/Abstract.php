<?php // @codingStandardsIgnoreLine.
/**
 * This file contains Abstract logging class.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/Google/IO
 * @version 3.0.1
 */

/*
 * Copyright 2013 Google Inc.
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

/**
 * Abstract IO base class
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! class_exists( 'Google_Client' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/autoload.php';
}
/**
 * Google IO Abstract Class.
 */
abstract class Google_IO_Abstract {
	const UNKNOWN_CODE                             = 0;
	const FORM_URLENCODED                          = 'application/x-www-form-urlencoded';
	private static $CONNECTION_ESTABLISHED_HEADERS = array(// @codingStandardsIgnoreLine.
		"HTTP/1.0 200 Connection established\r\n\r\n",
		"HTTP/1.1 200 Connection established\r\n\r\n",
	);
	private static $ENTITY_HTTP_METHODS            = array(// @codingStandardsIgnoreLine.
		'POST' => null,
		'PUT'  => null,
	);
	private static $HOP_BY_HOP                     = array(// @codingStandardsIgnoreLine.
		'connection'          => true,
		'keep-alive'          => true,
		'proxy-authenticate'  => true,
		'proxy-authorization' => true,
		'te'                  => true,
		'trailers'            => true,
		'transfer-encoding'   => true,
		'upgrade'             => true,
	);
	/**
	 * The current Google Client.
	 *
	 * @var Google_Client .
	 */
	protected $client;
	/**
	 * Public Constructor
	 *
	 * @param Google_Client $client .
	 */
	public function __construct( Google_Client $client ) {
		$this->client = $client;
		$timeout      = $client->getClassConfig( 'Google_IO_Abstract', 'request_timeout_seconds' );
		if ( $timeout > 0 ) {
			$this->setTimeout( $timeout );
		}
	}
	/**
	 * Executes a Google_Http_Request
	 *
	 * @param Google_Http_Request $request the http request to be executed.
	 * @return array containing response headers, body, and http code
	 * @throws Google_IO_Exception On curl or IO error.
	 */
	abstract public function executeRequest( Google_Http_Request $request);// @codingStandardsIgnoreLine.
	/**
	 * Set options that update the transport implementation's behavior.
	 *
	 * @param array $options .
	 */
	abstract public function setOptions( $options);// @codingStandardsIgnoreLine.
	/**
	 * Set the maximum request time in seconds.
	 *
	 * @param string $timeout in seconds.
	 */
	abstract public function setTimeout( $timeout);// @codingStandardsIgnoreLine.
	/**
	 * Get the maximum request time in seconds.
	 *
	 * @return timeout in seconds
	 */
	abstract public function getTimeout();// @codingStandardsIgnoreLine.
	/**
	 * Test for the presence of a cURL header processing bug
	 *
	 * The cURL bug was present in versions prior to 7.30.0 and caused the header
	 * length to be miscalculated when a "Connection established" header added by
	 * some proxies was present.
	 *
	 * @return boolean
	 */
	abstract protected function needsQuirk();// @codingStandardsIgnoreLine.
	/**
	 * Visible for testing.
	 * Cache the response to an HTTP request if it is cacheable.
	 *
	 * @param Google_Http_Request $request .
	 * @return bool Returns true if the insertion was successful.
	 * Otherwise, return false.
	 */
	public function setCachedRequest( Google_Http_Request $request ) {// @codingStandardsIgnoreLine.
		// Determine if the request is cacheable.
		if ( Google_Http_CacheParser::isResponseCacheable( $request ) ) {
			$this->client->getCache()->set( $request->getCacheKey(), $request );
			return true;
		}

		return false;
	}
	/**
	 * Execute an HTTP Request
	 *
	 * @param Google_Http_Request $request the http request to be executed.
	 * @return Google_Http_Request http request with the response http code,
	 * response headers and response body filled in
	 * @throws Google_IO_Exception On curl or IO error.
	 */
	public function makeRequest( Google_Http_Request $request ) {// @codingStandardsIgnoreLine.
		// First, check to see if we have a valid cached version.
		$cached = $this->getCachedRequest( $request );
		if ( false !== $cached && $cached instanceof Google_Http_Request ) {
			if ( ! $this->checkMustRevalidateCachedRequest( $cached, $request ) ) {
				return $cached;
			}
		}

		if ( array_key_exists( $request->getRequestMethod(), self::$ENTITY_HTTP_METHODS ) ) {// @codingStandardsIgnoreLine.
			$request = $this->processEntityRequest( $request );
		}

		list($responseData, $responseHeaders, $respHttpCode) = $this->executeRequest( $request );// @codingStandardsIgnoreLine.

		if ( $respHttpCode == 304 && $cached ) {// @codingStandardsIgnoreLine.
			// If the server responded NOT_MODIFIED, return the cached request.
			$this->updateCachedRequest( $cached, $responseHeaders );// @codingStandardsIgnoreLine.
			return $cached;
		}

		if ( ! isset( $responseHeaders['Date'] ) && ! isset( $responseHeaders['date'] ) ) {// @codingStandardsIgnoreLine.
			$responseHeaders['date'] = date( 'r' );// @codingStandardsIgnoreLine.
		}

		$request->setResponseHttpCode( $respHttpCode );// @codingStandardsIgnoreLine.
		$request->setResponseHeaders( $responseHeaders );// @codingStandardsIgnoreLine.
		$request->setResponseBody( $responseData );// @codingStandardsIgnoreLine.
		// Store the request in cache (the function checks to see if the request
		// can actually be cached)
		$this->setCachedRequest( $request );
		return $request;
	}
	/**
	 * Visible for testing.
	 *
	 * @param Google_Http_Request $request .
	 * @return Google_Http_Request|bool Returns the cached object or
	 * false if the operation was unsuccessful.
	 */
	public function getCachedRequest( Google_Http_Request $request ) {// @codingStandardsIgnoreLine.
		if ( false === Google_Http_CacheParser::isRequestCacheable( $request ) ) {
			return false;
		}

		return $this->client->getCache()->get( $request->getCacheKey() );
	}
	/**
	 * Visible for testing
	 * Process an http request that contains an enclosed entity.
	 *
	 * @param Google_Http_Request $request .
	 * @return Google_Http_Request Processed request with the enclosed entity.
	 */
	public function processEntityRequest( Google_Http_Request $request ) {// @codingStandardsIgnoreLine.
		$postBody    = $request->getPostBody();// @codingStandardsIgnoreLine.
		$contentType = $request->getRequestHeader( 'content-type' );// @codingStandardsIgnoreLine.

		// Set the default content-type as application/x-www-form-urlencoded.
		if ( false == $contentType ) {// @codingStandardsIgnoreLine.
			$contentType = self::FORM_URLENCODED;// @codingStandardsIgnoreLine.
			$request->setRequestHeaders( array( 'content-type' => $contentType ) );// @codingStandardsIgnoreLine.
		}

		// Force the payload to match the content-type asserted in the header.
		if ( $contentType == self::FORM_URLENCODED && is_array( $postBody ) ) {// @codingStandardsIgnoreLine.
			$postBody = http_build_query( $postBody, '', '&' );// @codingStandardsIgnoreLine.
			$request->setPostBody( $postBody );// @codingStandardsIgnoreLine.
		}

		// Make sure the content-length header is set.
		if ( ! $postBody || is_string( $postBody ) ) {// @codingStandardsIgnoreLine.
			$postsLength = strlen( $postBody );// @codingStandardsIgnoreLine.
			$request->setRequestHeaders( array( 'content-length' => $postsLength ) );// @codingStandardsIgnoreLine.
		}

		return $request;
	}
	/**
	 * Check if an already cached request must be revalidated, and if so update
	 * the request with the correct ETag headers.
	 *
	 * @param Google_Http_Request $cached A previously cached response.
	 * @param Google_Http_Request $request The outbound request.
	 * return bool If the cached object needs to be revalidated, false if it is
	 * still current and can be re-used.
	 */
	protected function checkMustRevalidateCachedRequest( $cached, $request ) {// @codingStandardsIgnoreLine.
		if ( Google_Http_CacheParser::mustRevalidate( $cached ) ) {
			$addHeaders = array();// @codingStandardsIgnoreLine.
			if ( $cached->getResponseHeader( 'etag' ) ) {
				// [13.3.4] If an entity tag has been provided by the origin server,
				// we must use that entity tag in any cache-conditional request.
				$addHeaders['If-None-Match'] = $cached->getResponseHeader( 'etag' );// @codingStandardsIgnoreLine.
			} elseif ( $cached->getResponseHeader( 'date' ) ) {
				$addHeaders['If-Modified-Since'] = $cached->getResponseHeader( 'date' );// @codingStandardsIgnoreLine.
			}

			$request->setRequestHeaders( $addHeaders );// @codingStandardsIgnoreLine.
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Update a cached request, using the headers from the last response.
	 *
	 * @param Google_Http_Request $cached A previously cached response.
	 * @param mixed               $responseHeaders Associative array of response headers from the last request.
	 */
	protected function updateCachedRequest( $cached, $responseHeaders ) {// @codingStandardsIgnoreLine.
		$hopByHop = self::$HOP_BY_HOP;// @codingStandardsIgnoreLine.
		if ( ! empty( $responseHeaders['connection'] ) ) {// @codingStandardsIgnoreLine.
			$connectionHeaders = array_map(// @codingStandardsIgnoreLine.
				'strtolower', array_filter(
					array_map( 'trim', explode( ',', $responseHeaders['connection'] ) )// @codingStandardsIgnoreLine.
				)
			);
			$hopByHop         += array_fill_keys( $connectionHeaders, true );// @codingStandardsIgnoreLine.
		}

		$endToEnd = array_diff_key( $responseHeaders, $hopByHop );// @codingStandardsIgnoreLine.
		$cached->setResponseHeaders( $endToEnd );// @codingStandardsIgnoreLine.
	}
	/**
	 * Used by the IO lib and also the batch processing.
	 *
	 * @param string $respData .
	 * @param int    $headerSize .
	 * @return array
	 */
	public function parseHttpResponse( $respData, $headerSize ) {// @codingStandardsIgnoreLine.
		// check proxy header.
		foreach ( self::$CONNECTION_ESTABLISHED_HEADERS as $established_header ) {// @codingStandardsIgnoreLine.
			if ( stripos( $respData, $established_header ) !== false ) {// @codingStandardsIgnoreLine.
				// existed, remove it.
				$respData = str_ireplace( $established_header, '', $respData );// @codingStandardsIgnoreLine.
				// Subtract the proxy header size unless the cURL bug prior to 7.30.0
				// is present which prevented the proxy header size from being taken into
				// account.
				if ( ! $this->needsQuirk() ) {
					$headerSize -= strlen( $established_header );// @codingStandardsIgnoreLine.
				}
				break;
			}
		}

		if ( $headerSize ) {// @codingStandardsIgnoreLine.
			$responseBody    = substr( $respData, $headerSize );// @codingStandardsIgnoreLine.
			$responseHeaders = substr( $respData, 0, $headerSize );// @codingStandardsIgnoreLine.
		} else {
			$responseSegments = explode( "\r\n\r\n", $respData, 2 );// @codingStandardsIgnoreLine.
			$responseHeaders  = $responseSegments[0];// @codingStandardsIgnoreLine.
			$responseBody     = isset( $responseSegments[1] ) ? $responseSegments[1] :// @codingStandardsIgnoreLine.
			null;
		}

		$responseHeaders = $this->getHttpResponseHeaders( $responseHeaders );// @codingStandardsIgnoreLine.
		return array( $responseHeaders, $responseBody );// @codingStandardsIgnoreLine.
	}
	/**
	 * Parse out headers from raw headers
	 *
	 * @param array/string $rawHeaders .
	 * @return array
	 */
	public function getHttpResponseHeaders( $rawHeaders ) {// @codingStandardsIgnoreLine.
		if ( is_array( $rawHeaders ) ) {// @codingStandardsIgnoreLine.
			return $this->parseArrayHeaders( $rawHeaders );// @codingStandardsIgnoreLine.
		} else {
			return $this->parseStringHeaders( $rawHeaders );// @codingStandardsIgnoreLine.
		}
	}
	private function parseStringHeaders( $rawHeaders ) {// @codingStandardsIgnoreLine.
		$headers             = array();
		$responseHeaderLines = explode( "\r\n", $rawHeaders );// @codingStandardsIgnoreLine.
		foreach ( $responseHeaderLines as $headerLine ) {// @codingStandardsIgnoreLine.
			if ( $headerLine && strpos( $headerLine, ':' ) !== false ) {// @codingStandardsIgnoreLine.
				list($header, $value) = explode( ': ', $headerLine, 2 );// @codingStandardsIgnoreLine.
				$header               = strtolower( $header );
				if ( isset( $headers[ $header ] ) ) {
					$headers[ $header ] .= "\n" . $value;
				} else {
					$headers[ $header ] = $value;
				}
			}
		}
		return $headers;
	}
	private function parseArrayHeaders( $rawHeaders ) {// @codingStandardsIgnoreLine.
		$header_count = count( $rawHeaders );// @codingStandardsIgnoreLine.
		$headers      = array();

		for ( $i = 0; $i < $header_count; $i++ ) {
			$header = $rawHeaders[ $i ];// @codingStandardsIgnoreLine.
			// Times will have colons in - so we just want the first match.
			$header_parts = explode( ': ', $header, 2 );
			if ( 2 == count( $header_parts ) ) {// WPCS: Loose comparison ok.
				$headers[ strtolower( $header_parts[0] ) ] = $header_parts[1];
			}
		}

		return $headers;
	}
}
