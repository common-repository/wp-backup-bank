<?php // @codingStandardsIgnoreLine.
/**
 * This file implements implements a basic on disk storage.
 *
 * @author Tech Banker
 * @package wp-backup-bank/lib/Google/Cache
 * @version 3.0.1
 */

/*
 * Copyright 2008 Google Inc.
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
 * This class implements a basic on disk storage. While that does
 * work quite well it's not the most elegant and scalable solution.
 * It will also get you into a heap of trouble when you try to run
 * this in a clustered environment.
 *
 * @author Chris Chabot <chabotc@google.com>
 */
class Google_Cache_File extends Google_Cache_Abstract {
	const MAX_LOCK_RETRIES = 10;
	/**
	 * This variable conmtains the path.
	 *
	 * @var string $path .
	 */
	private $path;
	/**
	 * This variable.
	 *
	 * @var string $fh .
	 */
	private $fh;
	/**
	 * The current Google Client.
	 *
	 * @var Google_Client the current client.
	 */
	private $client;
	/**
	 * Public Constructor.
	 *
	 * @param Google_Client $client .
	 */
	public function __construct( Google_Client $client ) {
		$this->client = $client;
		$this->path   = $this->client->getClassConfig( $this, 'directory' );
	}
	/**
	 * Get Cache file.
	 *
	 * @param string $key .
	 * @param bool   $expiration .
	 */
	public function get( $key, $expiration = false ) {
		$storageFile = $this->getCacheFile( $key );// @codingStandardsIgnoreLine.
		$data        = false;

		if ( ! file_exists( $storageFile ) ) {// @codingStandardsIgnoreLine.
			$this->client->getLogger()->debug(
				'File cache miss', array(
					'key'  => $key,
					'file' => $storageFile,// @codingStandardsIgnoreLine.
				)
			);
			return false;
		}

		if ( $expiration ) {
			$mtime = filemtime( $storageFile );// @codingStandardsIgnoreLine.
			if ( ( time() - $mtime ) >= $expiration ) {
				$this->client->getLogger()->debug(
					'File cache miss (expired)', array(
						'key'  => $key,
						'file' => $storageFile,// @codingStandardsIgnoreLine.
					)
				);
				$this->delete( $key );
				return false;
			}
		}

		if ( $this->acquireReadLock( $storageFile ) ) {// @codingStandardsIgnoreLine.
			if ( filesize( $storageFile ) > 0 ) {// @codingStandardsIgnoreLine.
				$data = fread( $this->fh, filesize( $storageFile ) );// @codingStandardsIgnoreLine.
				$data = maybe_unserialize( $data );
			} else {
				$this->client->getLogger()->debug(
					'Cache file was empty', array( 'file' => $storageFile )// @codingStandardsIgnoreLine.
				);
			}
			$this->unlock( $storageFile );// @codingStandardsIgnoreLine.
		}

		$this->client->getLogger()->debug(
			'File cache hit', array(
				'key'  => $key,
				'file' => $storageFile,// @codingStandardsIgnoreLine.
				'var'  => $data,
			)
		);

		return $data;
	}
	/**
	 * This functions sets the cache file.
	 *
	 * @param string $key .
	 * @param string $value .
	 */
	public function set( $key, $value ) {
		$storageFile = $this->getWriteableCacheFile( $key );// @codingStandardsIgnoreLine.
		if ( $this->acquireWriteLock( $storageFile ) ) {// @codingStandardsIgnoreLine.
			// We serialize the whole request object, since we don't only want the
			// responseContent but also the postBody used, headers, size, etc.
			$data   = maybe_serialize( $value );
			$result = fwrite( $this->fh, $data );// @codingStandardsIgnoreLine.
			$this->unlock( $storageFile );// @codingStandardsIgnoreLine.

			$this->client->getLogger()->debug(
				'File cache set', array(
					'key'  => $key,
					'file' => $storageFile, // @codingStandardsIgnoreLine.
					'var'  => $value,
				)
			);
		} else {
			$this->client->getLogger()->notice(
				'File cache set failed', array(
					'key'  => $key,
					'file' => $storageFile,// @codingStandardsIgnoreLine.
				)
			);
		}
	}
	/**
	 * This function is used delete the cache file.
	 *
	 * @param string $key .
	 *
	 * @throws Google_Cache_Exception .
	 */
	public function delete( $key ) {
		$file = $this->getCacheFile( $key );
		if ( file_exists( $file ) && ! unlink( $file ) ) {// @codingStandardsIgnoreLine.
			$this->client->getLogger()->error(
				'File cache delete failed', array(
					'key'  => $key,
					'file' => $file,
				)
			);
			throw new Google_Cache_Exception( 'Cache file could not be deleted' );
		}

		$this->client->getLogger()->debug(
			'File cache delete', array(
				'key'  => $key,
				'file' => $file,
			)
		);
	}
	/**
	 * This function is used to get writable cache file.
	 *
	 * @param string $file .
	 */
	private function getWriteableCacheFile( $file ) {
		return $this->getCacheFile( $file, true );
	}
	/**
	 * This function is used to get cache file.
	 *
	 * @param string $file .
	 * @param bool   $forWrite .
	 */
	private function getCacheFile( $file, $forWrite = false ) {// @codingStandardsIgnoreLine.
		return $this->getCacheDir( $file, $forWrite ) . '/' . md5( $file );// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get cache directory.
	 *
	 * @param string $file .
	 * @param string $forWrite .
	 *
	 * @throws Google_Cache_Exception .
	 */
	private function getCacheDir( $file, $forWrite ) {// @codingStandardsIgnoreLine.
		// use the first 2 characters of the hash as a directory prefix
		// this should prevent slowdowns due to huge directory listings
		// and thus give some basic amount of scalability.
		$storageDir = $this->path . '/' . substr( md5( $file ), 0, 2 );// @codingStandardsIgnoreLine.
		if ( $forWrite && ! is_dir( $storageDir ) ) {// @codingStandardsIgnoreLine.
			if ( ! mkdir( $storageDir, 0755, true ) ) {// @codingStandardsIgnoreLine.
				$this->client->getLogger()->error(
					'File cache creation failed', array( 'dir' => $storageDir )// @codingStandardsIgnoreLine.
				);
				throw new Google_Cache_Exception( "Could not create storage directory: $storageDir" );// @codingStandardsIgnoreLine.
			}
		}
		return $storageDir;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to aquire lock.
	 *
	 * @param string $storageFile .
	 */
	private function acquireReadLock( $storageFile ) {// @codingStandardsIgnoreLine.
		return $this->acquireLock( LOCK_SH, $storageFile );// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to aquire write lock.
	 *
	 * @param string $storageFile .
	 */
	private function acquireWriteLock( $storageFile ) {// @codingStandardsIgnoreLine.
		$rc = $this->acquireLock( LOCK_EX, $storageFile );// @codingStandardsIgnoreLine.
		if ( ! $rc ) {
			$this->client->getLogger()->notice(
				'File cache write lock failed', array( 'file' => $storageFile )// @codingStandardsIgnoreLine.
			);
			$this->delete( $storageFile );// @codingStandardsIgnoreLine.
		}
		return $rc;
	}
	/**
	 * This function is used to aquire lock.
	 *
	 * @param string $type .
	 * @param string $storageFile .
	 */
	private function acquireLock( $type, $storageFile ) {// @codingStandardsIgnoreLine.
		$mode     = $type == LOCK_EX ? 'w' : 'r';// @codingStandardsIgnoreLine.
		$this->fh = fopen( $storageFile, $mode );// @codingStandardsIgnoreLine.
		if ( ! $this->fh ) {
			$this->client->getLogger()->error(
				'Failed to open file during lock acquisition', array( 'file' => $storageFile )// @codingStandardsIgnoreLine.
			);
			return false;
		}
		$count = 0;
		while ( ! flock( $this->fh, $type | LOCK_NB ) ) {// @codingStandardsIgnoreLine.
			// Sleep for 10ms.
			usleep( 10000 );
			if ( ++$count < self::MAX_LOCK_RETRIES ) {
				return false;
			}
		}
		return true;
	}
	/**
	 * This function is used to unlock.
	 *
	 * @param string $storageFile .
	 */
	public function unlock( $storageFile ) {// @codingStandardsIgnoreLine.
		if ( $this->fh ) {
			flock( $this->fh, LOCK_UN );// @codingStandardsIgnoreLine.
		}
	}
}
