<?php // @codingStandardsIgnoreLine.
/**
 * This file is used for google services .
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/google
 * @version 3.0.1
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
} // Exit if accessed directly/**
/**
 * This class is used for google services
 */
class Google_Service {
	/**
	 * For root url.
	 *
	 * @var       string
	 * @access   public
	 */
	public $rootUrl; //@codingStandardsIgnoreLine
	/**
	 * Used for version.
	 *
	 * @var       string
	 * @access   public
	 */
	public $version;
	/**
	 * Used for service path.
	 *
	 * @var       string
	 * @access   public
	 */
	public $servicePath; //@codingStandardsIgnoreLine
	/**
	 * Used for available scope.
	 *
	 * @var       string
	 * @access   public
	 */
	public $availableScopes; //@codingStandardsIgnoreLine
	/**
	 * Used for resources.
	 *
	 * @var       string
	 * @access   public
	 */
	public $resource;
	/**
	 * Used for client.
	 *
	 * @var       string
	 * @access   private
	 */
	private $client;
	/**
	 * This function is used for construct.
	 *
	 * @param string $client .
	 */
	public function __construct( Google_Client $client ) { //@codingStandardsIgnoreLine
		$this->client = $client;
	}
	/**
	 * Return the associated Google_Client class.
	 *
	 * @return Google_Client
	 */
	public function getClient() {// @codingStandardsIgnoreLine.
		return $this->client;
	}
}
