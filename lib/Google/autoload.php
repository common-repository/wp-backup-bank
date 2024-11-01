<?php
/**
 * This file is used for autoload .
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/google
 * @version 3.0.1
 *
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
/**
 * This function is used to autoload the google api client
 *
 * @param string $className .
 */
function google_api_php_client_autoload( $className ) { // @codingStandardsIgnoreLine
	$classPath = explode( '_', $className ); // @codingStandardsIgnoreLine
	if ( $classPath[0] != 'Google' ) { // @codingStandardsIgnoreLine
		return;
	}
	// Drop 'Google', and maximum class file path depth in this project is 3.
	$classPath = array_slice( $classPath, 1, 2 ); // @codingStandardsIgnoreLine

	$filePath = dirname( __FILE__ ) . '/' . implode( '/', $classPath ) . '.php'; // @codingStandardsIgnoreLine
	if ( file_exists( $filePath ) ) { // @codingStandardsIgnoreLine
		require_once $filePath; // @codingStandardsIgnoreLine
	}
}
spl_autoload_register( 'google_api_php_client_autoload' );
