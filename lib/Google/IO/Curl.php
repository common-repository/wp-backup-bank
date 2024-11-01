<?php // @codingStandardsIgnoreLine.
/**
 * This file contains Abstract logging class.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/Google/IO
 * @version 3.0.1
 */

/*
 * Copyright 2014 Google Inc.
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
 * Curl based implementation of Google_IO.
 *
 * @author Stuart Langley <slangley@google.com>
 */
class Google_IO_Curl extends Google_IO_Abstract {
	// CURL hex representation of version 7.30.0.
	const NO_QUIRK_VERSION = 0x071E00;
	/**
	 * The array of options.
	 *
	 * @var array $options .
	 */
	private $options = array();
	/**
	 * The current Google api client.
	 *
	 * @param Google_Client $client .
	 *
	 * @throws Google_IO_Exception .
	 */
	public function __construct( Google_Client $client ) {
		if ( ! extension_loaded( 'curl' ) ) {
			$error = 'The cURL IO handler requires the cURL extension to be enabled';
			$client->getLogger()->critical( $error );
			throw new Google_IO_Exception( $error );
		}

		parent::__construct( $client );
	}
	/**
	 * Execute an HTTP Request
	 *
	 * @param Google_Http_Request $request the http request to be executed.
	 * @return array containing response headers, body, and http code
	 * @throws Google_IO_Exception On curl or IO error.
	 */
	public function executeRequest( Google_Http_Request $request ) {
		$curl = curl_init();// @codingStandardsIgnoreLine.

		if ( $request->getPostBody() ) {
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $request->getPostBody() );// @codingStandardsIgnoreLine.
		}

		$requestHeaders = $request->getRequestHeaders();// @codingStandardsIgnoreLine.
		if ( $requestHeaders && is_array( $requestHeaders ) ) {// @codingStandardsIgnoreLine.
			$curlHeaders = array();// @codingStandardsIgnoreLine.
			foreach ( $requestHeaders as $k => $v ) {// @codingStandardsIgnoreLine.
				$curlHeaders[] = "$k: $v";// @codingStandardsIgnoreLine.
			}
			curl_setopt( $curl, CURLOPT_HTTPHEADER, $curlHeaders );// @codingStandardsIgnoreLine.
		}
		curl_setopt( $curl, CURLOPT_URL, $request->getUrl() );// @codingStandardsIgnoreLine.

		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $request->getRequestMethod() );// @codingStandardsIgnoreLine.
		curl_setopt( $curl, CURLOPT_USERAGENT, $request->getUserAgent() );// @codingStandardsIgnoreLine.

		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, false );// @codingStandardsIgnoreLine.
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, true );// @codingStandardsIgnoreLine.
		// 1 is CURL_SSLVERSION_TLSv1, which is not always defined in PHP.
		// The SDK leaves this on the default setting in later releases
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );// @codingStandardsIgnoreLine.
		curl_setopt( $curl, CURLOPT_HEADER, true );// @codingStandardsIgnoreLine.

		if ( $request->canGzip() ) {
			curl_setopt( $curl, CURLOPT_ENCODING, 'gzip,deflate' );// @codingStandardsIgnoreLine.
		}

		$options = $this->client->getClassConfig( 'Google_IO_Curl', 'options' );
		if ( is_array( $options ) ) {
			$this->setOptions( $options );
		}

		foreach ( $this->options as $key => $var ) {
			curl_setopt( $curl, $key, $var );// @codingStandardsIgnoreLine.
		}

		if ( ! isset( $this->options[ CURLOPT_CAINFO ] ) ) {
			curl_setopt( $curl, CURLOPT_CAINFO, BACKUP_BANK_DIR_PATH . 'lib/cacert/cacerts.pem' );// @codingStandardsIgnoreLine.
		}

		$this->client->getLogger()->debug(
			'cURL request', array(
				'url'     => $request->getUrl(),
				'method'  => $request->getRequestMethod(),
				'headers' => $requestHeaders,// @codingStandardsIgnoreLine.
				'body'    => $request->getPostBody(),
			)
		);

		$response = curl_exec( $curl );// @codingStandardsIgnoreLine.
		if ( false === $response ) {
			$error = curl_error( $curl );// @codingStandardsIgnoreLine.
			$code  = curl_errno( $curl );// @codingStandardsIgnoreLine.
			$map   = $this->client->getClassConfig( 'Google_IO_Exception', 'retry_map' );

			$this->client->getLogger()->error( 'cURL ' . $error );
		}
		$headerSize = curl_getinfo( $curl, CURLINFO_HEADER_SIZE );// @codingStandardsIgnoreLine.

		list($responseHeaders, $responseBody) = $this->parseHttpResponse( $response, $headerSize );// @codingStandardsIgnoreLine.
		$responseCode                         = curl_getinfo( $curl, CURLINFO_HTTP_CODE );// @codingStandardsIgnoreLine.

		$this->client->getLogger()->debug(
			'cURL response', array(
				'code'    => $responseCode,// @codingStandardsIgnoreLine.
				'headers' => $responseHeaders,// @codingStandardsIgnoreLine.
				'body'    => $responseBody,// @codingStandardsIgnoreLine.
			)
		);

		return array( $responseBody, $responseHeaders, $responseCode );// @codingStandardsIgnoreLine.
	}
	/**
	 * Set options that update the transport implementation's behavior.
	 *
	 * @param array $options .
	 */
	public function setOptions( $options ) {
		$this->options = $options + $this->options;
	}
	/**
	 * Set the maximum request time in seconds.
	 *
	 * @param string $timeout in seconds.
	 */
	public function setTimeout( $timeout ) {
		// Since this timeout is really for putting a bound on the time
		// we'll set them both to the same. If you need to specify a longer
		// CURLOPT_TIMEOUT, or a higher CONNECTTIMEOUT, the best thing to
		// do is use the setOptions method for the values individually.
		$this->options[ CURLOPT_CONNECTTIMEOUT ] = $timeout;
		$this->options[ CURLOPT_TIMEOUT ]        = $timeout;
	}
	/**
	 * Get the maximum request time in seconds.
	 *
	 * @return timeout in seconds
	 */
	public function getTimeout() {
		return $this->options[ CURLOPT_TIMEOUT ];
	}
	/**
	 * Test for the presence of a cURL header processing bug
	 *
	 * {@inheritDoc}
	 *
	 * @return boolean
	 */
	protected function needsQuirk() {
		$ver        = curl_version();// @codingStandardsIgnoreLine.
		$versionNum = $ver['version_number'];// @codingStandardsIgnoreLine.
		return $versionNum < Google_IO_Curl::NO_QUIRK_VERSION;// @codingStandardsIgnoreLine.
	}
}
