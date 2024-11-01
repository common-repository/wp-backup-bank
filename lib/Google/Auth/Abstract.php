<?php // @codingStandardsIgnoreLine.
/**
 * This file is used for connecting with google client.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/Google/Auth
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
 * Abstract class for the Authentication in the API client
 *
 * @author Chris Chabot <chabotc@google.com>
 */
abstract class Google_Auth_Abstract {
	/**
	 * An utility function that Used for when a request
	 * should be authenticated
	 *
	 * @param Google_Http_Request $request .
	 */
	abstract public function authenticatedRequest( Google_Http_Request $request);// @codingStandardsIgnoreLine.
	/**
	 * An utility function that first calls $this->auth->sign($request).
	 *
	 * @param Google_Http_Request $request .
	 */
	abstract public function sign( Google_Http_Request $request);
}
