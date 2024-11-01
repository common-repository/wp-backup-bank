<?php // @codingStandardsIgnoreLine
/**
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
 * HTTP Request to be executed by IO classes. Upon execution, the
 * responseHttpCode, responseHeaders and responseBody will be filled in.
 *
 * @author Chris Chabot <chabotc@google.com>
 * @author Chirag Shah <chirags@google.com>
 */
class Google_Http_Request {
	const GZIP_UA = ' (gzip)';
	/**
	 * This variable for batch header
	 *
	 * @var       array
	 * @access   protected
	 */
	private $batchHeaders = array( // @codingStandardsIgnoreLine
		'Content-Type'              => 'application/http',
		'Content-Transfer-Encoding' => 'binary',
		'MIME-Version'              => '1.0',
	);
	/**
	 * This variable is queryparam
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $queryParams; // @codingStandardsIgnoreLine
	/**
	 * This variable for rquest method
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $requestMethod; // @codingStandardsIgnoreLine
	/**
	 * This variable for request header
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $requestHeaders; // @codingStandardsIgnoreLine
	/**
	 * This variable for base component
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $baseComponent = null; // @codingStandardsIgnoreLine
	/**
	 * This variable for path
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $path;
	/**
	 * This variable for post body
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $postBody; // @codingStandardsIgnoreLine
	/**
	 * This variable for iser agent
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $userAgent; // @codingStandardsIgnoreLine
	/**
	 * This variable for Gzip
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $canGzip = null; // @codingStandardsIgnoreLine
	/**
	 * This variable for response HTpp code
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $responseHttpCode; // @codingStandardsIgnoreLine
	/**
	 * This variable for response header
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $responseHeaders; // @codingStandardsIgnoreLine
	/**
	 * This variable for response body
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $responseBody; // @codingStandardsIgnoreLine
	/**
	 * This variable fr expected class
	 *
	 * @var       string
	 * @access   protected
	 */
	protected $expectedClass; // @codingStandardsIgnoreLine
	/**
	 * This variable for expected raw
	 *
	 * @var       boolean
	 * @access   protected
	 */
	protected $expectedRaw = false; // @codingStandardsIgnoreLine
	/**
	 * This variable for acceskey
	 *
	 * @var       string
	 * @access   protected
	 */
	public $accessKey; // @codingStandardsIgnoreLine
	/**
	 * This function is used for construct
	 *
	 * @param string $url .
	 * @param string $method .
	 * @param array  $headers .
	 * @param string $postBody .
	 */
	public function __construct(
	$url, $method = 'GET', $headers = array(), $postBody = null // @codingStandardsIgnoreLine
	) {
		$this->setUrl( $url );
		$this->setRequestMethod( $method );
		$this->setRequestHeaders( $headers );
		$this->setPostBody( $postBody ); // @codingStandardsIgnoreLine
	}
	/**
	 * Misc function that returns the base url component of the $url
	 * used by the OAuth signing class to calculate the base string
	 *
	 * @return string The base url component of the $url.
	 */
	public function getBaseComponent() {// @codingStandardsIgnoreLine
		return $this->baseComponent; // @codingStandardsIgnoreLine
	}
	/**
	 * Set the base URL that path and query parameters will be added to.
	 *
	 * @param string $baseComponent .
	 */
	public function setBaseComponent( $baseComponent ) {// @codingStandardsIgnoreLine
		$this->baseComponent = $baseComponent; // @codingStandardsIgnoreLine
	}
	/**
	 * Enable support for gzipped responses with this request.
	 */
	public function enableGzip() {// @codingStandardsIgnoreLine
		$this->setRequestHeaders( array( 'Accept-Encoding' => 'gzip' ) );
		$this->canGzip = true; // @codingStandardsIgnoreLine
		$this->setUserAgent( $this->userAgent ); // @codingStandardsIgnoreLine
	}
	/**
	 * Disable support for gzip responses with this request.
	 */
	public function disableGzip() {// @codingStandardsIgnoreLine
		if (
			isset( $this->requestHeaders['accept-encoding'] ) && // @codingStandardsIgnoreLine
			$this->requestHeaders['accept-encoding'] == 'gzip' // @codingStandardsIgnoreLine
		) {
			unset( $this->requestHeaders['accept-encoding'] ); // @codingStandardsIgnoreLine
		}
		$this->canGzip   = false; // @codingStandardsIgnoreLine
		$this->userAgent = str_replace( self::GZIP_UA, '', $this->userAgent ); // @codingStandardsIgnoreLine
	}
	/**
	 * Can this request accept a gzip response?
	 *
	 * @return bool
	 */
	public function canGzip() {// @codingStandardsIgnoreLine
		return $this->canGzip; // @codingStandardsIgnoreLine
	}
	/**
	 * Misc function that returns an array of the query parameters of the current
	 * url used by the OAuth signing class to calculate the signature
	 *
	 * @return array Query parameters in the query string.
	 */
	public function getQueryParams() {// @codingStandardsIgnoreLine
		return $this->queryParams; // @codingStandardsIgnoreLine
	}
	/**
	 * Set a new query parameter.
	 *
	 * @param string $key - string to set, does not need to be URL encoded .
	 * @param string $value - string to set, does not need to be URL encoded .
	 */
	public function setQueryParam( $key, $value ) {// @codingStandardsIgnoreLine
		$this->queryParams[ $key ] = $value; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get HTTP response code
	 *
	 * @return string HTTP Response Code.
	 */
	public function getResponseHttpCode() {// @codingStandardsIgnoreLine
		return (int) $this->responseHttpCode; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to  set response
	 *
	 * @param int $responseHttpCode HTTP Response Code.
	 */
	public function setResponseHttpCode( $responseHttpCode ) {// @codingStandardsIgnoreLine
		$this->responseHttpCode = $responseHttpCode; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get response header
	 *
	 * @return $responseHeaders (array) HTTP Response Headers.
	 */
	public function getResponseHeaders() {// @codingStandardsIgnoreLine
		return $this->responseHeaders; // @codingStandardsIgnoreLine
	}
	/**
	 * Thiis function is used to get response body
	 *
	 * @return string HTTP Response Body .
	 */
	public function getResponseBody() { // @codingStandardsIgnoreLine
		return $this->responseBody; // @codingStandardsIgnoreLine
	}
	/**
	 * Set the class the response to this request should expect.
	 *
	 * @param string $class the class name .
	 */
	public function setExpectedClass( $class ) { // @codingStandardsIgnoreLine
		$this->expectedClass = $class; // @codingStandardsIgnoreLine
	}
	/**
	 * Retrieve the expected class the response should expect.
	 *
	 * @return string class name
	 */
	public function getExpectedClass() { // @codingStandardsIgnoreLine
		return $this->expectedClass; // @codingStandardsIgnoreLine
	}
	/**
	 * Enable expected raw response
	 */
	public function enableExpectedRaw() { // @codingStandardsIgnoreLine
		$this->expectedRaw = true; // @codingStandardsIgnoreLine
	}
	/**
	 * Disable expected raw response
	 */
	public function disableExpectedRaw() { // @codingStandardsIgnoreLine
		$this->expectedRaw = false; // @codingStandardsIgnoreLine
	}
	/**
	 * Expected raw response or not.
	 *
	 * @return boolean expected raw response
	 */
	public function getExpectedRaw() { // @codingStandardsIgnoreLine
		return $this->expectedRaw; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used  HTTP response headers to be normalized.
	 *
	 * @param array $headers .
	 */
	public function setResponseHeaders( $headers ) { // @codingStandardsIgnoreLine
		$headers = Google_Utils::normalize( $headers );
		if ( $this->responseHeaders ) { // @codingStandardsIgnoreLine
			$headers = array_merge( $this->responseHeaders, $headers ); // @codingStandardsIgnoreLine
		}

		$this->responseHeaders = $headers; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to return request HTTP header
	 *
	 * @param string $key .
	 * @return array|boolean Returns the requested HTTP header or
	 * false if unavailable.
	 */
	public function getResponseHeader( $key ) { // @codingStandardsIgnoreLine
		return isset( $this->responseHeaders[ $key ] ) ? $this->responseHeaders[ $key ] : false; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set response body
	 *
	 * @param string $responseBody The HTTP response body.
	 */
	public function setResponseBody( $responseBody ) { // @codingStandardsIgnoreLine
		$this->responseBody = $responseBody; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get URL
	 *
	 * @return string $url The request URL.
	 */
	public function getUrl() { // @codingStandardsIgnoreLine
		return $this->baseComponent . $this->path . // @codingStandardsIgnoreLine
			( count( $this->queryParams ) ? // @codingStandardsIgnoreLine
			'?' . $this->buildQuery( $this->queryParams ) : // @codingStandardsIgnoreLine
			'' );
	}
	/**
	 * This function is used to get request method
	 *
	 * @return string $method HTTP Request Method .
	 */
	public function getRequestMethod() { // @codingStandardsIgnoreLine
		return $this->requestMethod; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used for HTTP request headers
	 *
	 * @return array $headers HTTP Request Headers.
	 */
	public function getRequestHeaders() { // @codingStandardsIgnoreLine
		return $this->requestHeaders; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to return the request headers
	 *
	 * @param string $key .
	 * @return array|boolean Returns the requested HTTP header or
	 * false if unavailable.
	 */
	public function getRequestHeader( $key ) { // @codingStandardsIgnoreLine
		return isset( $this->requestHeaders[ $key ] ) ? $this->requestHeaders[ $key ] : false; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get http request post body
	 *
	 * @return string $postBody HTTP Request Body.
	 */
	public function getPostBody() { // @codingStandardsIgnoreLine
		return $this->postBody; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set url
	 *
	 * @param string $url the url to set .
	 */
	public function setUrl( $url ) { // @codingStandardsIgnoreLine
		if ( substr( $url, 0, 4 ) != 'http' ) { // WPCS: loose comparison ok.
			// Force the path become relative.
			if ( substr( $url, 0, 1 ) !== '/' ) {
				$url = '/' . $url;
			}
		}
		$parts = parse_url( $url ); // @codingStandardsIgnoreLine
		if ( isset( $parts['host'] ) ) {
			$this->baseComponent = sprintf( // @codingStandardsIgnoreLine
				'%s%s%s', isset( $parts['scheme'] ) ? $parts['scheme'] . '://' : '', isset( $parts['host'] ) ? $parts['host'] : '', isset( $parts['port'] ) ? ':' . $parts['port'] : ''
			);
		}
		$this->path        = isset( $parts['path'] ) ? $parts['path'] : '';
		$this->queryParams = array(); // @codingStandardsIgnoreLine
		if ( isset( $parts['query'] ) ) {
			$this->queryParams = $this->parseQuery( $parts['query'] ); // @codingStandardsIgnoreLine
		}
	}
	/**
	 * This function is used to set request method
	 *
	 * @param string $method Set he HTTP Method and normalize .
	 * it to upper-case, as required by HTTP.
	 */
	public function setRequestMethod( $method ) { // @codingStandardsIgnoreLine
		$this->requestMethod = strtoupper( $method ); // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set header request
	 *
	 * @param array $headers The HTTP request headers .
	 * to be set and normalized.
	 */
	public function setRequestHeaders( $headers ) { // @codingStandardsIgnoreLine
		$headers = Google_Utils::normalize( $headers );
		if ( $this->requestHeaders ) { // @codingStandardsIgnoreLine
			$headers = array_merge( $this->requestHeaders, $headers ); // @codingStandardsIgnoreLine
		}
		$this->requestHeaders = $headers; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get post body
	 *
	 * @param string $postBody the postBody to set .
	 */
	public function setPostBody( $postBody ) { // @codingStandardsIgnoreLine
		$this->postBody = $postBody; // @codingStandardsIgnoreLine
	}
	/**
	 * Set the User-Agent Header.
	 *
	 * @param string $userAgent The User-Agent.
	 */
	public function setUserAgent( $userAgent ) { // @codingStandardsIgnoreLine
		$this->userAgent = $userAgent; // @codingStandardsIgnoreLine
		if ( $this->canGzip ) { // @codingStandardsIgnoreLine
			$this->userAgent = $userAgent . self::GZIP_UA; // @codingStandardsIgnoreLine
		}
	}
	/**
	 * This function is used to get user Agent
	 *
	 * @return string The User-Agent.
	 */
	public function getUserAgent() { // @codingStandardsIgnoreLine
		return $this->userAgent; // @codingStandardsIgnoreLine
	}
	/**
	 * Returns a cache key depending on if this was an OAuth signed request
	 * in which case it will use the non-signed url and access key to make this
	 * cache key unique per authenticated user, else use the plain request url
	 *
	 * @return string The md5 hash of the request cache key.
	 */
	public function getCacheKey() { // @codingStandardsIgnoreLine
		$key = $this->getUrl();

		if ( isset( $this->accessKey ) ) { // @codingStandardsIgnoreLine
			$key .= $this->accessKey; // @codingStandardsIgnoreLine
		}

		if ( isset( $this->requestHeaders['authorization'] ) ) { // @codingStandardsIgnoreLine
			$key .= $this->requestHeaders['authorization']; // @codingStandardsIgnoreLine
		}

		return md5( $key );
	}
	/**
	 * This function is used to get parse cache control
	 */
	public function getParsedCacheControl() { // @codingStandardsIgnoreLine
		$parsed          = array();
		$rawCacheControl = $this->getResponseHeader( 'cache-control' ); // @codingStandardsIgnoreLine
		if ( $rawCacheControl ) { // @codingStandardsIgnoreLine
			$rawCacheControl = str_replace( ', ', '&', $rawCacheControl ); // @codingStandardsIgnoreLine
			parse_str( $rawCacheControl, $parsed ); // @codingStandardsIgnoreLine
		}

		return $parsed;
	}
	/**
	 * Thus function is used to represent string of the HTTP request
	 *
	 * @param string $id .
	 * @return string A string representation of the HTTP Request.
	 */
	public function toBatchString( $id ) { // @codingStandardsIgnoreLine
		$str  = '';
		$path = parse_url( $this->getUrl(), PHP_URL_PATH ) . '?' . // @codingStandardsIgnoreLine
			http_build_query( $this->queryParams ); // @codingStandardsIgnoreLine
		$str .= $this->getRequestMethod() . ' ' . $path . " HTTP/1.1\n";

		foreach ( $this->getRequestHeaders() as $key => $val ) {
			$str .= $key . ': ' . $val . "\n";
		}

		if ( $this->getPostBody() ) {
			$str .= "\n";
			$str .= $this->getPostBody();
		}

		$headers = '';
		foreach ( $this->batchHeaders as $key => $val ) { // @codingStandardsIgnoreLine
			$headers .= $key . ': ' . $val . "\n";
		}

		$headers .= "Content-ID: $id\n";
		$str      = $headers . "\n" . $str;

		return $str;
	}
	/**
	 * Our own version of parse_str that allows for multiple variables
	 * with the same name.
	 *
	 * @param string $string - the query string to parse .
	 */
	private function parseQuery( $string ) { // @codingStandardsIgnoreLine
		$return = array();
		$parts  = explode( '&', $string );
		foreach ( $parts as $part ) {
			list($key, $value) = explode( '=', $part, 2 );
			$value             = urldecode( $value );
			if ( isset( $return[ $key ] ) ) {
				if ( ! is_array( $return[ $key ] ) ) {
					$return[ $key ] = array( $return[ $key ] );
				}
				$return[ $key ][] = $value;
			} else {
				$return[ $key ] = $value;
			}
		}
		return $return;
	}
	/**
	 * A version of build query that allows for multiple
	 * duplicate keys.
	 *
	 * @param array $parts of key value pairs .
	 */
	private function buildQuery( $parts ) { // @codingStandardsIgnoreLine
		$return = array();
		foreach ( $parts as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $v ) {
					$return[] = urlencode( $key ) . '=' . urlencode( $v ); // @codingStandardsIgnoreLine
				}
			} else {
				$return[] = urlencode( $key ) . '=' . urlencode( $value ); // @codingStandardsIgnoreLine
			}
		}
		return implode( '&', $return );
	}
	/**
	 * If we're POSTing and have no body to send, we can send the query
	 * parameters in there, which avoids length issues with longer query
	 * params.
	 */
	public function maybeMoveParametersToBody() { // @codingStandardsIgnoreLine
		if ( $this->getRequestMethod() == 'POST' && empty( $this->postBody ) ) { // @codingStandardsIgnoreLine
			$this->setRequestHeaders(
				array(
					'content-type' =>
					'application/x-www-form-urlencoded; charset=UTF-8',
				)
			);
			$this->setPostBody( $this->buildQuery( $this->queryParams ) ); // @codingStandardsIgnoreLine
			$this->queryParams = array(); // @codingStandardsIgnoreLine
		}
	}
}
