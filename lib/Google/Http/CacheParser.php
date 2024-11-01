<?php // @codingStandardsIgnoreLine.
/**
 * This file implements the caching directives.
 *
 * @author Tech Banker
 * @package wp-backup-bank/lib/Google/Http
 * @version 3.0.1
 */

/*
 * Copyright 2012 Google Inc.
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
 * Implement the caching directives specified in rfc2616. This
 * implementation is guided by the guidance offered in rfc2616-sec13.
 */
class Google_Http_CacheParser {
	public static $CACHEABLE_HTTP_METHODS = array( 'GET', 'HEAD' );// @codingStandardsIgnoreLine.
	public static $CACHEABLE_STATUS_CODES = array( '200', '203', '300', '301' );// @codingStandardsIgnoreLine.
	/**
	 * Check if an HTTP request can be cached by a private local cache.
	 *
	 * @static
	 * @param Google_Http_Request $resp .
	 * @return bool True if the request is cacheable.
	 * False if the request is uncacheable.
	 */
	public static function isRequestCacheable( Google_Http_Request $resp ) {// @codingStandardsIgnoreLine.
		$method = $resp->getRequestMethod();
		if ( ! in_array( $method, self::$CACHEABLE_HTTP_METHODS ) ) {// @codingStandardsIgnoreLine.
			return false;
		}

		// Don't cache authorized requests/responses.
		// [rfc2616-14.8] When a shared cache receives a request containing an
		// Authorization field, it MUST NOT return the corresponding response
		// as a reply to any other request...
		if ( $resp->getRequestHeader( 'authorization' ) ) {
			return false;
		}

		return true;
	}
	/**
	 * Check if an HTTP response can be cached by a private local cache.
	 *
	 * @static
	 * @param Google_Http_Request $resp .
	 * @return bool True if the response is cacheable.
	 * False if the response is un-cacheable.
	 */
	public static function isResponseCacheable( Google_Http_Request $resp ) {// @codingStandardsIgnoreLine.
		// First, check if the HTTP request was cacheable before inspecting the
		// HTTP response.
		if ( false == self::isRequestCacheable( $resp ) ) {// WPCS: Loose comparison ok.
			return false;
		}

		$code = $resp->getResponseHttpCode();
		if ( ! in_array( $code, self::$CACHEABLE_STATUS_CODES ) ) {// @codingStandardsIgnoreLine.
			return false;
		}

		// The resource is uncacheable if the resource is already expired and
		// the resource doesn't have an ETag for revalidation.
		$etag = $resp->getResponseHeader( 'etag' );
		if ( self::isExpired( $resp ) && false == $etag ) {// WPCS: Loose comparison ok.
			return false;
		}

		// [rfc2616-14.9.2]  If [no-store is] sent in a response, a cache MUST NOT
		// store any part of either this response or the request that elicited it.
		$cacheControl = $resp->getParsedCacheControl();// @codingStandardsIgnoreLine.
		if ( isset( $cacheControl['no-store'] ) ) {// @codingStandardsIgnoreLine.
			return false;
		}

		// Pragma: no-cache is an http request directive, but is occasionally
		// used as a response header incorrectly.
		$pragma = $resp->getResponseHeader( 'pragma' );
		if ( 'no-cache' == $pragma || strpos( $pragma, 'no-cache' ) !== false ) {// WPCS: Loose comparison ok.
			return false;
		}

		// [rfc2616-14.44] Vary: * is extremely difficult to cache. "It implies that
		// a cache cannot determine from the request headers of a subsequent request
		// whether this response is the appropriate representation."
		// Given this, we deem responses with the Vary header as uncacheable.
		$vary = $resp->getResponseHeader( 'vary' );
		if ( $vary ) {
			return false;
		}

		return true;
	}
	/**
	 * It checks token.
	 *
	 * @static
	 * @param Google_Http_Request $resp .
	 * @return bool True if the HTTP response is considered to be expired.
	 * False if it is considered to be fresh.
	 *
	 * @throws Google_Exception .
	 */
	public static function isExpired( Google_Http_Request $resp ) {// @codingStandardsIgnoreLine.
		// HTTP/1.1 clients and caches MUST treat other invalid date formats,
		// especially including the value “0”, as in the past.
		$parsedExpires   = false;// @codingStandardsIgnoreLine.
		$responseHeaders = $resp->getResponseHeaders();// @codingStandardsIgnoreLine.

		if ( isset( $responseHeaders['expires'] ) ) {// @codingStandardsIgnoreLine.
			$rawExpires = $responseHeaders['expires'];// @codingStandardsIgnoreLine.
			// Check for a malformed expires header first.
			if ( empty( $rawExpires ) || ( is_numeric( $rawExpires ) && $rawExpires <= 0 ) ) {// @codingStandardsIgnoreLine.
				return true;
			}

			// See if we can parse the expires header.
			$parsedExpires = strtotime( $rawExpires );// @codingStandardsIgnoreLine.
			if ( false == $parsedExpires || $parsedExpires <= 0 ) {// @codingStandardsIgnoreLine.
				return true;
			}
		}

		// Calculate the freshness of an http response.
		$freshnessLifetime = false;// @codingStandardsIgnoreLine.
		$cacheControl      = $resp->getParsedCacheControl();// @codingStandardsIgnoreLine.
		if ( isset( $cacheControl['max-age'] ) ) {// @codingStandardsIgnoreLine.
			$freshnessLifetime = $cacheControl['max-age'];// @codingStandardsIgnoreLine.
		}

		$rawDate    = $resp->getResponseHeader( 'date' );// @codingStandardsIgnoreLine.
		$parsedDate = strtotime( $rawDate );// @codingStandardsIgnoreLine.

		if ( empty( $rawDate ) || false == $parsedDate ) {// @codingStandardsIgnoreLine.
			// We can't default this to now, as that means future cache reads
			// will always pass with the logic below, so we will require a
			// date be injected if not supplied.
			throw new Google_Exception( 'All cacheable requests must have creation dates.' );
		}

		if ( false == $freshnessLifetime && isset( $responseHeaders['expires'] ) ) {// @codingStandardsIgnoreLine.
			$freshnessLifetime = $parsedExpires - $parsedDate;// @codingStandardsIgnoreLine.
		}

		if ( false == $freshnessLifetime ) {// @codingStandardsIgnoreLine.
			return true;
		}

		// Calculate the age of an http response.
		$age = max( 0, time() - $parsedDate );// @codingStandardsIgnoreLine.
		if ( isset( $responseHeaders['age'] ) ) {// @codingStandardsIgnoreLine.
			$age = max( $age, strtotime( $responseHeaders['age'] ) );// @codingStandardsIgnoreLine.
		}

		return $freshnessLifetime <= $age;// @codingStandardsIgnoreLine.
	}
	/**
	 * Determine if a cache entry should be revalidated with by the origin.
	 *
	 * @param Google_Http_Request $response .
	 * @return bool True if the entry is expired, else return false.
	 */
	public static function mustRevalidate( Google_Http_Request $response ) {// @codingStandardsIgnoreLine.
		// [13.3] When a cache has a stale entry that it would like to use as a
		// response to a client's request, it first has to check with the origin
		// server to see if its cached entry is still usable.
		return self::isExpired( $response );
	}
}
