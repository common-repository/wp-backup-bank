<?php // @codingStandardsIgnoreLine.
/**
 * This file is used for HTTP error response.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/Google/Service
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
 * This class is used for HTTP error response.
 */
class Google_Service_Exception extends Google_Exception implements Google_Task_Retryable {
	/**
	 * Optional list of errors returned in a JSON body of an HTTP error response.
	 *
	 * @var array $errors .
	 */
	protected $errors = array();
	/**
	 * Map of errors.
	 *
	 * @var array $retryMap Map of errors with retry counts.
	 */
	private $retryMap = array();// @codingStandardsIgnoreLine.
	/**
	 * Override default constructor to add the ability to set $errors and a retry
	 * map.
	 *
	 * @param string             $message .
	 * @param int                $code .
	 * @param Exception|null     $previous .
	 * @param [{string, string}] $errors List of errors returned in an HTTP
	 * response.  Defaults to [].
	 * @param array|null         $retryMap Map of errors with retry counts.
	 */
	public function __construct(
	$message, $code = 0, Exception $previous = null, $errors = array(), array $retryMap = null// @codingStandardsIgnoreLine.
	) {
		if ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) {
			parent::__construct( $message, $code, $previous );
		} else {
			parent::__construct( $message, $code );
		}

		$this->errors = $errors;

		if ( is_array( $retryMap ) ) {// @codingStandardsIgnoreLine.
			$this->retryMap = $retryMap;// @codingStandardsIgnoreLine.
		}
	}
	/**
	 * An example of the possible errors returned.
	 *
	 * {
	 *   "domain": "global",
	 *   "reason": "authError",
	 *   "message": "Invalid Credentials",
	 *   "locationType": "header",
	 *   "location": "Authorization",
	 * }
	 *
	 * @return [{string, string}] List of errors return in an HTTP response or [].
	 */
	public function getErrors() {
		return $this->errors;
	}
	/**
	 * Gets the number of times the associated task can be retried.
	 *
	 * NOTE: -1 is returned if the task can be retried indefinitely
	 *
	 * @return integer
	 */
	public function allowedRetries() {
		if ( isset( $this->retryMap[ $this->code ] ) ) {// @codingStandardsIgnoreLine.
			return $this->retryMap[ $this->code ];// @codingStandardsIgnoreLine.
		}

		$errors = $this->getErrors();

		if ( ! empty( $errors ) && isset( $errors[0]['reason'] ) &&
		isset( $this->retryMap[ $errors[0]['reason'] ] ) ) {// @codingStandardsIgnoreLine.
			return $this->retryMap[ $errors[0]['reason'] ];// @codingStandardsIgnoreLine.
		}

		return 0;
	}
}
