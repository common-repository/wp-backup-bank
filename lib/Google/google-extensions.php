<?php // @codingStandardsIgnoreLine.
/**
 * This file is used to include the files .
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/google
 * @version 3.0.1
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
 * Manage large file uploads, which may be media but can be any type
 * of sizable data.
 */
class backup_bank_Google_Http_MediaFileUpload { // @codingStandardsIgnoreLine
	/**
	 * This function is used to set resume uri .
	 *
	 * @param string $resumeUri .
	 */
	public function backup_bank_setResumeUri( $resumeUri ) { // @codingStandardsIgnoreLine.
		$this->resumeUri = $resumeUri; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set progress .
	 *
	 * @param string $progress .
	 */
	public function backup_bank_setProgress( $progress ) { // @codingStandardsIgnoreLine
		$this->progress = $progress;
	}
	/**
	 * A public method getResumeUri already exists - and does something completely different
	 */
	public function backup_bank_getResumeUri() { // @codingStandardsIgnoreLine.
		return $this->resumeUri; // @codingStandardsIgnoreLine
	}
	const UPLOAD_MEDIA_TYPE     = 'media';
	const UPLOAD_MULTIPART_TYPE = 'multipart';
	const UPLOAD_RESUMABLE_TYPE = 'resumable';
	/**
	 * Mime type .
	 *
	 * @access private
	 * @var string $mimeType .
	 */
	private $mimeType; // @codingStandardsIgnoreLine
	/**
	 * This is for data
	 *
	 * @access private
	 * @var string $data .
	 */
	private $data;
	/**
	 * It checks whether resumable or not
	 *
	 * @var bool $resumable .
	 */
	private $resumable;
	/**
	 * The chunksize
	 *
	 * @var int $chunkSize .
	 */
	private $chunkSize; // @codingStandardsIgnoreLine
	/**
	 * It used for size
	 *
	 * @var int $size .
	 */
	private $size;
	/**
	 * Its used for resume uri
	 *
	 * @var string $resumeUri .
	 */
	private $resumeUri; // @codingStandardsIgnoreLine
	/**
	 * Its used for progress
	 *
	 * @var int $progress .
	 */
	private $progress;
	/**
	 * Used for google client
	 *
	 *  @var Google_Client .
	 */
	private $client;
	/**
	 * To used for http request .
	 *
	 * @var Google_Http_Request .
	 */
	private $request;
	/**
	 * This used to sety boundary
	 *
	 * @var string $boundary .
	 */
	private $boundary;
	/**
	 * Result code from last HTTP call
	 *
	 * @var int
	 */
	private $httpResultCode; // @codingStandardsIgnoreLine
	/**
	 * This function is used to construct .
	 *
	 * @param Google_Client       $client .
	 * @param Google_Http_Request $request .
	 * @param string              $mimeType string .
	 * @param string              $data string The bytes you want to upload .
	 * @param bool                $resumable bool .
	 * @param bool                $chunkSize File will be uploaded in chunks of this many bytes.
	 *                                     only used if resumable=True .
	 * @param bool                $boundary .
	 */
	public function __construct(
	Google_Client $client, Google_Http_Request $request, $mimeType, $data, $resumable = false, $chunkSize = false, $boundary = false // @codingStandardsIgnoreLine
	) {
		$this->client    = $client;
		$this->request   = $request;
		$this->mimeType  = $mimeType; // @codingStandardsIgnoreLine
		$this->data      = $data;
		$this->size      = strlen( $this->data );
		$this->resumable = $resumable;
		if ( ! $chunkSize ) { // @codingStandardsIgnoreLine
			$chunkSize = 256 * 1024; // @codingStandardsIgnoreLine
		}
		$this->chunkSize = $chunkSize; // @codingStandardsIgnoreLine
		$this->progress  = 0;
		$this->boundary  = $boundary;

		// Process Media Request .
		$this->process();
	}
	/**
	 * Set the size of the file that is being uploaded.
	 *
	 * @param string $size - int file size in bytes .
	 */
	public function setFileSize( $size ) { // @codingStandardsIgnoreLine
		$this->size = $size;
	}
	/**
	 * Return the progress on the upload
	 *
	 * @return int progress in bytes uploaded.
	 */
	public function getProgress() { // @codingStandardsIgnoreLine
		return $this->progress;
	}
	/**
	 * Return the HTTP result code from the last call made.
	 *
	 * @return int code
	 */
	public function getHttpResultCode() { // @codingStandardsIgnoreLine
		return $this->httpResultCode; // @codingStandardsIgnoreLine
	}
	/**
	 * Send the next part of the file to upload.
	 *
	 * @param string $chunk the next set of bytes to send. If false will used $data passed
	 *  at construct time.
	 */
	public function nextChunk( $chunk = false ) { // @codingStandardsIgnoreLine
		if ( false == $this->resumeUri ) { // @codingStandardsIgnoreLine
			$this->resumeUri = $this->getResumeUri(); // @codingStandardsIgnoreLine
		}

		if ( false == $chunk ) { // WPCS:Loose comparison ok.
			$chunk = substr( $this->data, $this->progress, $this->chunkSize );// @codingStandardsIgnoreLine
		}

		$lastBytePos = $this->progress + strlen( $chunk ) - 1; // @codingStandardsIgnoreLine
		$headers     = array(
			'content-range'  => "bytes $this->progress-$lastBytePos/$this->size", // @codingStandardsIgnoreLine
			'content-type'   => $this->request->getRequestHeader( 'content-type' ),
			'content-length' => $this->chunkSize, // @codingStandardsIgnoreLine
			'expect'         => '',
		);

		$http_request = new Google_Http_Request(
			$this->resumeUri, 'PUT', $headers, $chunk // @codingStandardsIgnoreLine
		);

		if ( $this->client->getClassConfig( 'Google_Http_Request', 'enable_gzip_for_uploads' ) ) {
			$http_request->enableGzip();
		} else {
			$http_request->disableGzip();
		}

		$response = $this->client->getIo()->makeRequest( $http_request );
		$response->setExpectedClass( $this->request->getExpectedClass() );
		$code                 = $response->getResponseHttpCode();
		$this->httpResultCode = $code; // @codingStandardsIgnoreLine

		if ( 308 == $code ) { // WPCS:Loose comparison ok.
			// Track the amount uploaded.
			$range          = explode( '-', $response->getResponseHeader( 'range' ) );
			$this->progress = $range[1] + 1;

			// Allow for changing upload URLs.
			$location = $response->getResponseHeader( 'location' );
			if ( $location ) {
				$this->resumeUri = $location; // @codingStandardsIgnoreLine
			}

			// No problems, but upload not complete.
			return false;
		} else {
			return Google_Http_REST::decodeHttpResponse( $response, $this->client );
		}
	}
	/**
	 * This function is used to process body .
	 *
	 * @visible for testing
	 */
	private function process() {
		$postBody    = false; // @codingStandardsIgnoreLine
		$contentType = false; // @codingStandardsIgnoreLine

		$meta = $this->request->getPostBody();
		$meta = is_string( $meta ) ? json_decode( $meta, true ) : $meta;

		$uploadType = $this->getUploadType( $meta ); // @codingStandardsIgnoreLine
		$this->request->setQueryParam( 'uploadType', $uploadType ); // @codingStandardsIgnoreLine
		$this->transformToUploadUrl();
		$mimeType = $this->mimeType ? // @codingStandardsIgnoreLine
			$this->mimeType : // @codingStandardsIgnoreLine
			$this->request->getRequestHeader( 'content-type' );

		if ( self::UPLOAD_RESUMABLE_TYPE == $uploadType ) { // @codingStandardsIgnoreLine
			$contentType = $mimeType; // @codingStandardsIgnoreLine
			$postBody    = is_string( $meta ) ? $meta : json_encode( $meta ); // @codingStandardsIgnoreLine
		} elseif ( self::UPLOAD_MEDIA_TYPE == $uploadType ) { // @codingStandardsIgnoreLine
			$contentType = $mimeType; // @codingStandardsIgnoreLine
			$postBody    = $this->data; // @codingStandardsIgnoreLine
		} elseif ( self::UPLOAD_MULTIPART_TYPE == $uploadType ) { // @codingStandardsIgnoreLine
			// This is a multipart/related upload.
			$boundary    = $this->boundary ? $this->boundary : mt_rand();
			$boundary    = str_replace( '"', '', $boundary );
			$contentType = 'multipart/related; boundary=' . $boundary; // @codingStandardsIgnoreLine
			$related     = "--$boundary\r\n";
			$related    .= "Content-Type: application/json; charset=UTF-8\r\n";
			$related    .= "\r\n" . wp_json_encode( $meta ) . "\r\n";
			$related    .= "--$boundary\r\n";
			$related    .= "Content-Type: $mimeType\r\n"; // @codingStandardsIgnoreLine
			$related    .= "Content-Transfer-Encoding: base64\r\n";
			$related    .= "\r\n" . base64_encode( $this->data ) . "\r\n";
			$related    .= "--$boundary--";
			$postBody    = $related; // @codingStandardsIgnoreLine
		}

		$this->request->setPostBody( $postBody ); // @codingStandardsIgnoreLine

		if ( isset( $contentType ) && $contentType ) { // @codingStandardsIgnoreLine
			$contentTypeHeader['content-type'] = $contentType; // @codingStandardsIgnoreLine
			$this->request->setRequestHeaders( $contentTypeHeader ); // @codingStandardsIgnoreLine
		}
	}
	/**
	 * This function is used to transform to upload url .
	 */
	private function transformToUploadUrl() { // @codingStandardsIgnoreLine
		$base = $this->request->getBaseComponent();
		$this->request->setBaseComponent( $base . '/upload' );
	}
	/**
	 * Valid upload types:
	 * - resumable (UPLOAD_RESUMABLE_TYPE)
	 * - media (UPLOAD_MEDIA_TYPE)
	 * - multipart (UPLOAD_MULTIPART_TYPE)
	 *
	 * @param string $meta .
	 * @return string
	 * @visible for testing
	 */
	public function getUploadType( $meta ) { // @codingStandardsIgnoreLine
		if ( $this->resumable ) {
			return self::UPLOAD_RESUMABLE_TYPE;
		}

		if ( false == $meta && $this->data ) { // WPCS:Loose comparison ok.
			return self::UPLOAD_MEDIA_TYPE;
		}

		return self::UPLOAD_MULTIPART_TYPE;
	}
	private function getResumeUri() { // @codingStandardsIgnoreLine
		$result = null;
		$body   = $this->request->getPostBody();
		if ( $body ) {
			$headers = array(
				'content-type'            => 'application/json; charset=UTF-8',
				'content-length'          => Google_Utils::getStrLen( $body ),
				'x-upload-content-type'   => $this->mimeType, // @codingStandardsIgnoreLine
				'x-upload-content-length' => $this->size,
				'expect'                  => '',
			);
			$this->request->setRequestHeaders( $headers );
		}

		$response = $this->client->getIo()->makeRequest( $this->request );
		$location = $response->getResponseHeader( 'location' );
		$code     = $response->getResponseHttpCode();

		if ( 200 == $code && true == $location ) { // WPCS:Loose comparison ok.
			return $location;
		}
		$message = $code;
		$body    = @json_decode( $response->getResponseBody() ); // @codingStandardsIgnoreLine
		if ( ! empty( $body->error->errors ) ) {
			$message .= ': ';
			foreach ( $body->error->errors as $error ) {
				$message .= "{$error->domain}, {$error->message};";
			}
			$message = rtrim( $message, ';' );
		}

		$error = "Failed to start the resumable upload (HTTP {$message})";
		$this->client->getLogger()->error( $error );
		throw new Google_Exception( $error );
	}
}
