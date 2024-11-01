<?php // @codingStandardsIgnoreLine.
/**
 * This file contains Null logger class.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/Google/Logger
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
}// Exit if accessed directly
if ( ! class_exists( 'Google_Client' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/autoload.php';
}
/**
 * Null logger based on the PSR-3 standard.
 *
 * This logger simply discards all messages.
 */
class Google_Logger_Null extends Google_Logger_Abstract {
	/**
	 * The level.
	 *
	 * {@inheritdoc}
	 *
	 * @param string $level .
	 */
	public function shouldHandle( $level ) {
		return false;
	}
	/**
	 * This funnction is used to write the message.
	 *
	 * {@inheritdoc}
	 *
	 * @param string $message .
	 * @param array  $context .
	 */
	protected function write( $message, array $context = array() ) {

	}
}
