<?php
/**
 * This file is used for managing dropbox.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/dropbox
 * @version 3.0.1
 */

/**
 * DropPHP v2 - A simple Dropbox client that works without cURL.
 *
 * Link: http://fabi.me/en/php-projects/dropphp-dropbox-api-client/
 *
 * @author     Fabian Schlieper <fabian@fabi.me>
 * @copyright  Fabian Schlieper 2017
 * @version    2.0.0
 * MIT License

 * Copyright (c) 2017 Fabian Schlieper

 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * b IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class DropboxClient {
	const API_URL         = 'https://api.dropboxapi.com/';
	const API_CONTENT_URL = 'https://content.dropboxapi.com/';

	const BUFFER_SIZE           = 4096;
	const MAX_UPLOAD_CHUNK_SIZE = 150 * 1024 * 1024;
	const UPLOAD_CHUNK_SIZE     = 2 * 1024 * 1024;
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $app_params.
	 */
	private $app_params;
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $consumer_token.
	 */
	private $consumer_token;
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $request_token.
	 */
	private $request_token;
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $access_token.
	 */
	private $access_token;
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $root_path.
	 */
	private $root_path;
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $use_curl.
	 */
	private $use_curl;
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $curl_options.
	 */
	private $curl_options;
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $_redirect_uri.
	 */
	private $redirect_uri;

	/**
	 * DropboxClient constructor.
	 *
	 * @param array  $app_params ['app_key' => ..., 'app_secret' => ..., 'app_full_access' => ...] .
	 *
	 * @param string $deprecated_locale Deprecated.
	 *
	 * @throws DropboxException .
	 */
	public function __construct( $app_params, $deprecated_locale = 'en' ) {
		if ( count( func_get_args() ) > 1 ) {
			trigger_error( 'locale is deprecated with Dropbox v2 API.', E_USER_DEPRECATED ); // @codingStandardsIgnoreLine
		}

		$this->appParams = $app_params; // @codingStandardsIgnoreLine
		if ( empty( $app_params['app_key'] ) ) {
			throw new DropboxException( 'App Key is empty!' );
		}

		$this->consumerToken = array(// @codingStandardsIgnoreLine
			't' => $this->appParams['app_key'],// @codingStandardsIgnoreLine
			's' => $this->appParams['app_secret'],// @codingStandardsIgnoreLine
		);
		$this->rootPath      = empty( $app_params['app_full_access'] ) ? 'sandbox' : 'dropbox';// @codingStandardsIgnoreLine

		$this->requestToken = null;// @codingStandardsIgnoreLine
		$this->accessToken  = null;// @codingStandardsIgnoreLine

		$this->useCurl = function_exists( 'curl_init' );// @codingStandardsIgnoreLine
	}
	/**
	 * This Function is used to access token on deserialization.
	 */
	public function __wakeup() {
		// delete v1 access token on deserialization.
		if ( ! empty( $this->accessToken['s'] ) ) { // @codingStandardsIgnoreLine
			$this->accessToken = null;// @codingStandardsIgnoreLine
		}
		$this->useCurl = $this->useCurl && function_exists( 'curl_init' );// @codingStandardsIgnoreLine
	}

	/**
	 * Sets whether to use cURL if its available or PHP HTTP wrappers otherwise
	 *
	 * @param boolean $use_it whether to use it or not .
	 * @param array   $curl_options .
	 *
	 * @return bool Whether to actually use cURL (always false if not installed)
	 * @throws DropboxException .
	 */
	public function set_use_curl( $use_it, $curl_options = array() ) {
		if ( ! $use_it && ! empty( $curl_options ) ) {
			throw new DropboxException( 'not using cURL but specified cURL options' );
		}
		$this->curlOptions = $curl_options; // @codingStandardsIgnoreLine

		return ( $this->useCurl = ( $use_it && function_exists( 'curl_init' ) ) );// @codingStandardsIgnoreLine
	}

	// ##################################################
	// Authorization
	/**
	 * Step 1. Returns a URL the user must be redirected to in order to connect the app to their Dropbox account
	 *
	 * @param string $redirect_uri URL users are redirected after authorization .
	 * @param string $state Up to 500 bytes of arbitrary data passed back to $redirect_uri .
	 *
	 * @return string URL
	 */
	public function build_authorize_url( $redirect_uri, $state = '' ) {
		$this->_redirectUri = $redirect_uri;// @codingStandardsIgnoreLine

		return "https://www.dropbox.com/oauth2/authorize?response_type=code&client_id={$this->appParams['app_key']}&redirect_uri=" . urlencode( $redirect_uri ) . '&state=' . urlencode( $state );// @codingStandardsIgnoreLine.
	}


	/**
	 * Step 2.
	 *
	 * @param string $code The code GET param Dropbox generates when HTTP-redirecting the client .
	 * @param string $redirect_uri The same redirect_uri as passed to build_authorize_url() before, used for validation .
	 *
	 * @return array
	 * @throws DropboxException .
	 */
	public function get_bearer_token( $code = '', $redirect_uri = '' ) {
		if ( ! empty( $this->accessToken ) ) { // @codingStandardsIgnoreLine
			return $this->accessToken; // @codingStandardsIgnoreLine
		}

		if ( empty( $code ) ) {
			$code = filter_input( INPUT_GET, 'code', FILTER_SANITIZE_STRING );
			if ( empty( $code ) ) {
				throw new DropboxException( 'Missing OAuth2 code parameter!' );
			}
		}

		if ( ! empty( $redirect_uri ) ) {
			$this->_redirectUri = $redirect_uri; // @codingStandardsIgnoreLine
		}

		if ( empty( $this->_redirectUri ) ) { // @codingStandardsIgnoreLine
			throw new DropboxException( 'Redirect URI unknown, please specify or call build_authorize_url() before!' );
		}

		$res = $this->api_call(
			'oauth2/token', array(
				'code'          => $code,
				'grant_type'    => 'authorization_code',
				'client_id'     => $this->appParams['app_key'],// @codingStandardsIgnoreLine
				'client_secret' => $this->appParams['app_secret'],// @codingStandardsIgnoreLine
				'redirect_uri'  => $this->_redirectUri, // @codingStandardsIgnoreLine
			)
		);

		if ( empty( $res ) || empty( $res->access_token ) ) {
			throw new DropboxException( sprintf( 'Could not get bearer token! (code: %s)', $code ) );
		}

		return ( $this->accessToken = array( // @codingStandardsIgnoreLine
			't'          => $res->access_token,
			'account_id' => $res->account_id,
		) );
	}

	/**
	 * Sets a previously retrieved (and stored) bearer token.
	 *
	 * @param array|object $token .
	 *
	 * @throws DropboxException .
	 */
	public function set_bearer_token( $token ) {
		$token = (array) $token;
		if ( empty( $token['t'] ) ) {
			throw new DropboxException( 'Passed invalid bearer token.' );
		}
		$this->accessToken = $token; // @codingStandardsIgnoreLine
	}

	/**
	 * Checks if an access token has been set.
	 *
	 * @access public
	 * @return boolean Authorized or not
	 */
	public function is_authorized() {
		// v1 was: Array ( [t] => '...' [s] => '...' )
		// v2 is:  Array ( [t] => '...' [account_id] => '...' ) .
		return ! empty( $this->accessToken ) && ! empty( $this->accessToken['account_id'] );// @codingStandardsIgnoreLine
	}

	// ##################################################
	// API Functions
	/**
	 * Retrieves information about the user's account.
	 *
	 * @access public
	 * @return object Account info object. See https://www.dropbox.com/developers/documentation/http/documentation#users-get_current_account
	 */
	public function get_account_info() {
		$info               = $this->api_call( '2/users/get_current_account' );
		$info->uid          = $info->account_id;
		$info->name_details = $info->name;
		$info->display_name = $info->name->display_name;

		return $info;
	}
	/**
	 * This Function is used to get files.
	 *
	 * @param string $path .
	 * @param bool   $recursive .
	 * @param bool   $include_deleted .
	 *
	 * @return mixed.
	 * @throws DropboxException .
	 */
	public function get_files( $path = '', $recursive = false, $include_deleted = false ) {
		if ( is_object( $path ) && ! empty( $path->path ) ) {
			$path = $path->path;
		}
		if ( '/' === $path ) {
			$path = '';
		}

		$res     = $this->api_call( '2/files/list_folder', compact( 'path', 'recursive', 'include_deleted' ) );
		$entries = $res->entries;

		while ( $res->has_more ) {
			$res     = $this->api_call( '2/files/list_folder/continue', array( 'cursor' => $res->cursor ) );
			$entries = array_merge( $entries, $res->entries );
		}

		$entries_assoc = array();
		foreach ( $entries as $entry ) {
			$entries_assoc[ trim( $entry->path_display, '/' ) ] = $entry;
		}

		return array_map( array( __CLASS__, 'compat_meta' ), $entries_assoc );
	}
	/**
	 * See https://www.dropbox.com/developers/documentation/http/documentation#files-get_metadata.
	 *
	 * This Function is used to get meta data.
	 *
	 * @param string $path .
	 * @param bool   $include_deleted .
	 * @param null   $rev .
	 *
	 * @return mixed .
	 * @throws DropboxException .
	 */
	public function get_meta_data( $path, $include_deleted = false, $rev = null ) {
		if ( is_object( $path ) && ! empty( $path->path ) ) {
			$path = $path->path;
		}
		if ( ! empty( $rev ) ) {
			$path = "rev:$rev";
		}

		return self::compat_meta( $this->api_call( '2/files/get_metadata', compact( 'path', 'include_deleted' ) ) );
	}

	/**
	 * Download a file from user's Dropbox to the web server
	 *
	 * @param string|object $path Dropbox path or metadata object of the file to download.
	 * @param string        $dest_path Local path for destination .
	 * @param string        $rev Optional. The revision of the file to retrieve. This defaults to the most recent revision.
	 * @param callback      $progress_changed_callback Optional. Callback that will be called during download with 2 args: 1. bytes loaded, 2. file size .
	 *
	 * @return object Dropbox file metadata
	 * @throws DropboxException .
	 */
	public function download_file( $path, $dest_path = '', $rev = null, $progress_changed_callback = null ) {
		if ( is_object( $path ) && ! empty( $path->path ) ) {
			$path = $path->path;
		}

		if ( empty( $dest_path ) ) {
			$dest_path = basename( $path );
		}

		$url = self::clean_url( self::API_CONTENT_URL . '2/files/download' );
		if ( ! empty( $rev ) ) {
			$path = "rev:$rev";
		}
		$context = $this->create_request_context( $url, compact( 'path' ) );

		$fh = @fopen( $dest_path, 'wb' ); // @codingStandardsIgnoreLine .
		if ( false === $fh ) {
			throw new DropboxException( "Could not create file $dest_path !" );
		}

		if ( $this->useCurl ) { // @codingStandardsIgnoreLine .
			curl_setopt( $context, CURLOPT_BINARYTRANSFER, true );// @codingStandardsIgnoreLine .
			curl_setopt( $context, CURLOPT_RETURNTRANSFER, true );// @codingStandardsIgnoreLine .
			curl_setopt( $context, CURLOPT_FILE, $fh );// @codingStandardsIgnoreLine .
			$response_headers = array();
			self::execCurlAndClose( $context, $response_headers );
			fclose( $fh );// @codingStandardsIgnoreLine .
			$meta         = self::get_meta_from_headers( $response_headers, true );
			$bytes_loaded = filesize( $dest_path );
		} else {
			$rh = @fopen( $url, 'rb', false, $context ); // @codingStandardsIgnoreLine .
			if ( false === $rh ) {
				throw new DropboxException( "HTTP request to $url failed!" );
			}

			// get file meta from HTTP header .
			$s_meta       = stream_get_meta_data( $rh );
			$meta         = self::get_meta_from_headers( $s_meta['wrapper_data'], true );
			$bytes_loaded = 0;
			while ( ! feof( $rh ) ) {
				if ( ( $s = fwrite( $fh, fread( $rh, self::BUFFER_SIZE ) ) ) === false ) {// @codingStandardsIgnoreLine .
					@fclose( $rh );// @codingStandardsIgnoreLine .
					@fclose( $fh );// @codingStandardsIgnoreLine .
					throw new DropboxException( "Writing to file $dest_path failed!'" );
				}
				$bytes_loaded += $s;
				if ( ! empty( $progress_changed_callback ) ) {
					call_user_func( $progress_changed_callback, $bytes_loaded, $meta->bytes );
				}
			}

			fclose( $rh );// @codingStandardsIgnoreLine .
			fclose( $fh );// @codingStandardsIgnoreLine .
		}

		if ( $meta->size !== $bytes_loaded ) {
			throw new DropboxException( "Download size mismatch! (header:{$meta->size} vs actual:{$bytes_loaded}; curl:{$this->useCurl})" );
		}

		return $meta;
	}
	/**
	 * This function is used to get compat data .
	 *
	 * @param string $meta passes .
	 */
	public static function compat_meta( $meta ) {
		$meta->is_dir = $meta->{'.tag'} == 'folder';// @codingStandardsIgnoreLine .
		$meta->path   = $meta->path_display;
		$meta->bytes  = isset( $meta->size ) ? $meta->size : 0;

		return $meta;
	}
	/**
	 * Upload a file to dropbox
	 *
	 * @access public
	 *
	 * @param string $src_file .
	 * @param string $path .
	 * @param string $file_name .
	 * @param string $logfile_name .
	 * @param string $backup_bank_data .
	 * @param string $backup_type .
	 * @param bool   $overwrite .
	 *
	 * @return object Dropbox file metadata
	 * @throws DropboxException .
	 */
	public function upload_file( $src_file, $path = '', $file_name, $logfile_name, $backup_bank_data, $backup_type = '', $overwrite = true ) {
				$upload_path   = untrailingslashit( $backup_bank_data['folder_location'] );
				$archive_name  = implode( '', maybe_unserialize( $backup_bank_data['archive_name'] ) );
				$log_file_path = $upload_path . '/' . $archive_name . '.txt';
				$start_time    = microtime( true );
		if ( empty( $path ) ) {
			$path = basename( $src_file );
		}
		$path = self::to_path( $path );

		// make sure the dropbox_path is not a dir. if it is, append basename of $src_file .
		$dropbox_bn = basename( $path );
		if ( strpos( $dropbox_bn, '.' ) === false ) { // check if ext. is missing -> could be a directory!
			try {
				$meta = $this->get_meta_data( $path );
				if ( $meta && $meta->is_dir ) {
					$path = self::to_path( $path . '/' . basename( $src_file ) );
				}
			} catch ( Exception $e ) { // @codingStandardsIgnoreLine .
			}
		}

		$file_size = filesize( $src_file );

		$commit_params = array(
			'path'       => $path,
			'mode'       => $overwrite ? 'overwrite' : 'add',
			'autorename' => true,
		);

		if ( $file_size > self::UPLOAD_CHUNK_SIZE ) {
			$fh = fopen( $src_file, 'rb' );// @codingStandardsIgnoreLine .
			if ( false === $fh ) {
				throw new DropboxException( "Cannot open $src_file for reading!" );
			}

			$offset = 0;

			$res                       = $this->api_call( '2/files/upload_session/start', array(), true );
			$session_id                = $res->session_id;
						$offset        = 0;
						$cloud         = 2;
						$backup_status = 'completed';
			while ( ! feof( $fh ) ) {
				$content = fread( $fh, self::UPLOAD_CHUNK_SIZE );// @codingStandardsIgnoreLine
				$this->api_call( '2/files/upload_session/append_v2', array( 'cursor' => compact( 'session_id', 'offset' ) ), true, $content );
				$offset                += strlen( $content );
								$result = ceil( $offset / $file_size * 100 );
								$rtime  = microtime( true ) - $start_time;

				if ( $src_file != $logfile_name ) {// WPCS: loose comparison ok.
					$log = 'Uploading to <b>Dropbox</b> (<b>' . round( ( $offset / 1048576 ), 1 ) . 'MB</b> out of <b>' . round( ( $file_size / 1048576 ), 1 ) . 'MB</b>).';
					if ( 'schedule' == $backup_type ) {// WPCS: loose comparison ok.
						@file_put_contents( $logfile_name, strip_tags( sprintf( '%08.03f', round( $rtime, 3 ) ) . ' ' . $log . "\r\n" ), FILE_APPEND );// @codingStandardsIgnoreLine.
					}
					if ( '' != $file_name ) {// WPCS: loose comparison ok.
						$message  = '{' . "\r\n";
						$message .= '"log": "' . $log . '",\r\n"';
						$message .= '"perc": ' . $result . ',' . "\r\n";
						$message .= '"status": "' . $backup_status . '",\r\n"';
						$message .= '"cloud": ' . $cloud . "\r\n";
						$message .= '}';
						file_put_contents( $file_name, $message ); // @codingStandardsIgnoreLine.
						@file_put_contents( $log_file_path, strip_tags( sprintf( '%08.03f', round( $rtime, 3 ) ) . ' ' . $log . "\r\n" ), FILE_APPEND );// @codingStandardsIgnoreLine.
					}
				}
				unset( $content );
				if ( $offset >= $file_size ) {
					break;
				}
			}

			@fclose( $fh );// @codingStandardsIgnoreLine.

			return $this->api_call(
				'2/files/upload_session/finish', array(
					'cursor' => compact( 'session_id', 'offset' ),
					'commit' => $commit_params,
				), true
			);
		} else {
			$content = file_get_contents( $src_file );// @codingStandardsIgnoreLine.

			return $this->api_call( '2/files/upload', $commit_params, true, $content );
		}
	}

	/**
	 * Get thumbnail for a specified image
	 *
	 * @access public
	 *
	 * @param string $dropbox_file string Path to the image.
	 * @param string $size string Thumbnail size (xs, s, m, l, xl).
	 * @param string $format string Image format of the thumbnail (jpeg or png).
	 * @param bool   $echo .
	 *
	 * @return string Returns the thumbnail as binary image data
	 */
	public function get_thumbnail( $dropbox_file, $size = 's', $format = 'jpeg', $echo = false ) {
		$path = self::to_path( $dropbox_file );

		$size_transform = array(
			'xs' => 'w32h32',
			's'  => 'w64h64',
			'm'  => 'w128h128',
			'l'  => 'w640h480',
			'xl' => 'w1024h768',
		);
		if ( isset( $size_transform[ $size ] ) ) {
			$size = $size_transform[ $size ];
		}

		$url     = self::API_CONTENT_URL . '2/files/get_thumbnail';
		$context = $this->create_request_context( $url, compact( 'path', 'size', 'format' ) );
		$thumb   = $this->useCurl ? self::execCurlAndClose( $context ) : file_get_contents( $url, false, $context ); // @codingStandardsIgnoreLine.

		if ( $echo ) {
			header( 'Content-type: image/' . $format );
			echo $thumb; // WPCS: XSS ok.
			unset( $thumb );

			return '';
		}

		return $thumb;
	}
	/**
	 * The function is get_link.
	 *
	 * @param string $path .
	 * @param string $preview .
	 * @param string $_short .
	 * @param string $expires .
	 *
	 * @throws DropboxException .
	 */
	public function get_link(
		$path, $preview = true,
		$_short = true, &$expires = null
	) {
		$path = self::to_path( $path );

		if ( ! $preview ) {
			$data    = $this->api_call(
				'2/files/get_temporary_link', array(
					'path' => $path,
				)
			);
			$expires = time() + ( 4 * 3600 ) - 60; // expires in 4h.

			return $data->link;
		} else {
			try {
				$url = $this->api_call(
					'2/sharing/create_shared_link_with_settings', array(
						'path'     => $path,
						'settings' => array(
							'requested_visibility' => 'public',
						),
					)
				);
			} catch ( DropboxException $ex ) {
				if ( 'shared_link_already_exists' == $ex->getTag() ) { // WPCS: loose comparison ok.
					$public_links = array_filter(
						$this->api_call( '2/sharing/list_shared_links', array( 'path' => $path ) )->links, function ( $link ) {
							return 'file' == $link->{'.tag'} && 'public' == $link->link_permissions->resolved_visibility->{'.tag'};// WPCS: loose comparison ok.
						}
					);
					$url          = reset( $public_links );
				} else {
					throw $ex;
				}
			}

			// we leave $expires unset, Dropbox does not mention expiry of links from `get_temporary_link`.
			return $url->url;
		}
	}
	/**
	 * The function is delta.
	 *
	 * @param string $cursor .
	 */
	public function delta( $cursor ) {
		return $this->api_call(
			'2/files/list_folder/continue', array_merge(
				compact( 'cursor' ), array()
			)
		);
	}
	/**
	 * The function is latest_cursor.
	 *
	 * @param string $path .
	 * @param string $include_media_info .
	 */
	public function latest_cursor( $path = '', $include_media_info = false ) {
		$res = $this->api_call( '2/files/list_folder/get_latest_cursor', compact( 'path', 'include_media_info' ) );

		return $res->cursor;
	}
	/**
	 * The function is get_revisions.
	 *
	 * @param string $path .
	 * @param string $limit .
	 */
	public function get_revisions( $path, $limit = 10 ) {
		$path = self::to_path( $path );

		return $this->api_call( '2/files/list_revisions', compact( 'path', 'limit' ) )->entries;
	}
	/**
	 * The function is restore.
	 *
	 * @param string $dropbox_file .
	 * @param string $rev .
	 */
	public function restore( $dropbox_file, $rev ) {
		if ( is_object( $dropbox_file ) && ! empty( $dropbox_file->path ) ) {
			$dropbox_file = $dropbox_file->path;
		}

		return $this->api_call( "restore/$this->rootPath/$dropbox_file", compact( 'rev' ) );
	}
	/**
	 * The function is search.
	 *
	 * @param string $path .
	 * @param string $query .
	 * @param string $max_results .
	 * @param string $include_deleted .
	 */
	public function search( $path, $query, $max_results = 1000, $include_deleted = false ) {
		$path = self::to_path( $path );
		$mode = $include_deleted ? 'deleted_filename' : 'filename';

		$meta = array();
		foreach ( $this->api_call( '2/files/search', compact( 'path', 'query', 'max_results', 'mode' ) )->matches as $match ) {
			$meta[] = self::compat_meta( $match->metadata );
		}

		return $meta;
	}
	/**
	 * The function is get_copy_ref.
	 *
	 * @param string $dropbox_file .
	 * @param string $expires .
	 */
	public function get_copy_ref( $dropbox_file, &$expires = null ) {
		if ( is_object( $dropbox_file ) && ! empty( $dropbox_file->path ) ) {
			$dropbox_file = $dropbox_file->path;
		}
		$ref     = $this->api_call( "copy_ref/$this->rootPath/$dropbox_file", 'GET' );
		$expires = strtotime( $ref->expires );

		return $ref->copy_ref;
	}
	/**
	 * The function is copy.
	 *
	 * @param string $from_path .
	 * @param string $to_path .
	 * @param string $copy_ref .
	 */
	public function copy( $from_path, $to_path, $copy_ref = false ) {
		if ( is_object( $from_path ) && ! empty( $from_path->path ) ) {
			$from_path = $from_path->path;
		}

		return $this->api_call(
			'fileops/copy', array(
				'root'    => $this->rootPath, //@codingStandardsIgnoreLine.
				( $copy_ref ? 'from_copy_ref' : 'from_path' ) => $from_path,
				'to_path' => $to_path,
			)
		);
	}

	/**
	 * Creates a new folder in the DropBox
	 *
	 * @access public
	 *
	 * @param string $path string The path to the new folder to create.
	 * @param bool   $autorename .
	 *
	 * @return object Dropbox folder metadata
	 */
	public function create_folder( $path, $autorename = false ) {
		$res                     = $this->api_call(
			'2/files/create_folder_v2',
			array(
				'path'       => $path,
				'autorename' => $autorename,
			)
		);
		$res->metadata->{'.tag'} = 'folder';
		return self::compat_meta( $res->metadata );
	}

	/**
	 * Delete file or folder.
	 *
	 * @param string $path mixed The path or metadata of the file/folder to be deleted.
	 *
	 * @return object Dropbox metadata of deleted file or folder.
	 */
	public function delete( $path ) {
		if ( is_object( $path ) && ! empty( $path->path ) ) {
			$path = $path->path;
		}

		$res = $this->api_call( '2/files/delete_v2', array( 'path' => $path ) );
		return self::compat_meta( $res->metadata );
	}
	/**
	 * Move file or folder.
	 *
	 * @param string $from_path .
	 * @param string $to_path .
	 */
	public function move( $from_path, $to_path ) {
		if ( is_object( $from_path ) && ! empty( $from_path->path ) ) {
			$from_path = $from_path->path;
		}

		return $this->api_call(
			'fileops/move', array(
				'root'      => $this->rootPath, //@codingStandardsIgnoreLine.
				'from_path' => $from_path,
				'to_path'   => $to_path,
			)
		);
	}

	/**
	 * The function is createCurl.
	 *
	 * @param string $url .
	 * @param string $http_context .
	 */
	private function createCurl( $url, $http_context ) {// @codingStandardsIgnoreLine.
		$ch = curl_init( $url );// @codingStandardsIgnoreLine.

		$curl_opts = array(
			CURLOPT_HEADER         => false, // exclude header from output //CURLOPT_MUTE => true, // no output!.
			CURLOPT_RETURNTRANSFER => true, // but return!.
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_BINARYTRANSFER => true,
		);

		$curl_opts[ CURLOPT_CUSTOMREQUEST ] = $http_context['method'];

		if ( ! empty( $http_context['content'] ) ) {
			$curl_opts[ CURLOPT_POSTFIELDS ] =& $http_context['content'];
			if ( defined( 'CURLOPT_POSTFIELDSIZE' ) ) {
				$curl_opts[ CURLOPT_POSTFIELDSIZE ] = strlen( $http_context['content'] );
			}
		}

		$curl_opts[ CURLOPT_HTTPHEADER ] = array_map( 'trim', explode( "\n", $http_context['header'] ) );

		if ( ! empty( $this->curlOptions ) && is_array( $this->curlOptions ) ) {// @codingStandardsIgnoreLine.
			$curl_opts = array_merge( $curl_opts, $this->curlOptions );// @codingStandardsIgnoreLine.
		}

		curl_setopt_array( $ch, $curl_opts );// @codingStandardsIgnoreLine.

		return $ch;
	}

	static private $_curlHeadersRef;// @codingStandardsIgnoreLine.
	/**
	 * The function is _curl_header_callback.
	 *
	 * @param string $ch .
	 * @param string $header .
	 */
	private static function _curlHeaderCallback(// @codingStandardsIgnoreLine.
		$ch, $header
	) {
		self::$_curlHeadersRef[] = trim( $header );// @codingStandardsIgnoreLine.

		return strlen( $header );
	}
	/**
	 * The function is &execCurlAndClose.
	 *
	 * @param string $ch .
	 * @param string $out_response_headers .
	 *
	 * @throws DropboxException .
	 */
	private static function &execCurlAndClose( $ch, &$out_response_headers = null ) {// @codingStandardsIgnoreLine.
		if ( is_array( $out_response_headers ) ) {
			self::$_curlHeadersRef =& $out_response_headers;// @codingStandardsIgnoreLine.
			curl_setopt( $ch, CURLOPT_HEADERFUNCTION, array( __CLASS__, '_curlHeaderCallback' ) );// @codingStandardsIgnoreLine.
		}
		$res     = curl_exec( $ch );// @codingStandardsIgnoreLine.
		$err_no  = curl_errno( $ch );// @codingStandardsIgnoreLine.
		$err_str = curl_error( $ch );// @codingStandardsIgnoreLine.
		curl_close( $ch );// @codingStandardsIgnoreLine.
		if ( $err_no || false === $res ) {
			throw new DropboxException( "cURL-Error ($err_no): $err_str" );
		}

		return $res;
	}
	/**
	 * This function is create_request_context.
	 *
	 * @param string $url .
	 * @param string $params .
	 * @param string $content .
	 * @param int    $bearer_token .
	 *
	 * @return resource
	 */
	private function create_request_context( $url, $params, &$content = '', $bearer_token = - 1 ) {
		if ( - 1 === $bearer_token ) {
			$bearer_token = $this->accessToken['t']; // @codingStandardsIgnoreLine.
		}

		$http_context = array(
			'method'  => 'POST',
			'header'  => '',
			'content' => '',
		);

		if ( strpos( $url, '/oauth2/token' ) !== false ) {
			$http_context['header'] .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$http_context['content'] = http_build_query( $params );
		} else {

			if ( ! empty( $bearer_token ) ) {
				$http_context['header'] .= "Authorization: Bearer $bearer_token\r\n";
			}

			if ( empty( $content ) && strpos( $url, self::API_CONTENT_URL ) === false ) {
				if ( ! empty( $params ) ) {
					$http_context['header'] .= "Content-Type: application/json\r\n";
					$http_context['content'] = wp_json_encode( $params );
				}
			} else {
				$http_context['header'] .= 'Dropbox-API-Arg: ' . str_replace( '"', '"', wp_json_encode( (object) $params ) ) . "\r\n";
				$http_context['header'] .= "Content-Type: application/octet-stream\r\n";
				if ( ! empty( $content ) ) {
					$http_context['content'] = $content;
				}
			}
		}

		if ( strpos( $url, self::API_CONTENT_URL ) === false ) {
			$http_context['header'] .= 'Content-Length: ' . strlen( $http_context['content'] );
		}

		$http_context['header'] = trim( $http_context['header'] );

		// be able to retrieve error response body.
		$http_context['ignore_errors'] = true;

		return $this->useCurl ? $this->createCurl( $url, $http_context ) : stream_context_create( array( 'http' => $http_context ) );// @codingStandardsIgnoreLine.
	}
		/**
		 * This function is check_for_error .
		 *
		 * @param object $resp .
		 * @param string $context .
		 *
		 * @return object
		 * @throws DropboxException .
		 */
	private static function check_for_error(
		$resp, $context = null
	) {
		if ( ! empty( $resp->error ) ) {
			throw new DropboxException( $resp, $context );
		}

		return $resp;
	}

	/**
	 * This function is do_single_call .
	 *
	 * @param string $path .
	 * @param array  $params .
	 * @param bool   $content_call .
	 * @param string $content .
	 *
	 * @return object
	 * @throws DropboxException .
	 */
	private function do_single_call( $path, $params = array(), $content_call = false, &$content = null ) {
		$url     = self::clean_url( ( $content_call ? self::API_CONTENT_URL : self::API_URL ) . $path );
		$context = $this->create_request_context( $url, $params, $content );

		$json = $this->useCurl ? self::execCurlAndClose( $context ) : file_get_contents( $url, false, $context );// @codingStandardsIgnoreLine.
		$resp = json_decode( $json );

		if ( is_null( $resp ) && $content_call ) {
			return null;
		}

		if ( is_null( $resp ) && ! empty( $json ) ) {
			throw new DropboxException( "api_call($path) failed: $json (URL was $url)" );
		}
		if ( ( false === $resp || is_null( $resp ) ) && ! empty( $json ) && ! $content_call ) {
			throw new DropboxException( "Error api_call($path): $json" );
		}

		return self::check_for_error( $resp, "api_call($path)" );
	}

	/**
	 * This function is merge_continue .
	 *
	 * @param object $target .
	 * @param object $part .
	 */
	private static function merge_continue( &$target, $part ) {
		$keys = array_keys( get_object_vars( $target ) );
		foreach ( $keys as $k ) {
			if ( is_array( $target->$k ) && ! empty( $part->$k ) && is_array( $part->$k ) ) {
				$target->$k = array_merge( $target->$k, $part->$k );
			}
		}
		$target->has_more = $part->has_more;
		$target->cursor   = $part->cursor;
	}

	/**
	 * This function is api_call .
	 *
	 * @param string $path .
	 * @param array  $params .
	 * @param object $content_call .
	 * @param object $content .
	 *
	 * @throws DropboxException .
	 */
	private function api_call(
		$path, $params = array(), $content_call = false, &$content = null
	) {
		$resp = $this->do_single_call( $path, $params, $content_call, $content );

		// check for 'has_more' and run /continue requests.
		if ( ! empty( $resp->has_more ) && strpos( $path, '/continue' ) === false ) {
			$path .= '/continue';
		}

		while ( ! $content_call && ! empty( $resp->has_more ) ) {
			if ( empty( $resp->cursor ) ) {
				throw new DropboxException( "Unexpected response from $path: has_more without cursor!" );
			}
			$params['cursor'] = is_string( $resp->cursor ) ? $resp->cursor : $resp->cursor->value;
			self::merge_continue( $resp, $this->do_single_call( $path, $params, $content_call, $content ) );
		}

		return $resp;
	}
	/**
	 * This function is get_meta_from_headers .
	 *
	 * @param string $header_array .
	 * @param string $throw_on_error .
	 *
	 * @return object
	 * @throws DropboxException .
	 */
	private static function get_meta_from_headers(
		&$header_array, $throw_on_error = false
	) {
		$obj = json_decode(
			substr(
				@array_shift( // @codingStandardsIgnoreLine.
					array_filter(
						$header_array, function ( $s ) {
							return stripos( $s, 'dropbox-api-result:' ) === 0;
						}
					)
				), 20
			)
		);
		if ( $throw_on_error && ( empty( $obj ) || ! is_object( $obj ) ) ) {
			throw new DropboxException( 'Could not retrieve meta data from header data: ' . print_r( $header_array, true ) );// @codingStandardsIgnoreLine.
		}
		if ( $throw_on_error ) {
			self::check_for_error( $obj, __FUNCTION__ );
		}

		return self::compat_meta( $obj );
	}
	/**
	 * This function is to_path .
	 *
	 * @param string $file_or_path .
	 */
	private static function to_path( $file_or_path ) {
		if ( is_object( $file_or_path ) ) {
			$file_or_path = $file_or_path->path;
		}
		$file_or_path = '/' . trim( $file_or_path, '/' );
		if ( '/' == $file_or_path ) {// WPCS: loose comparison ok.
			$file_or_path = '';
		}

		return $file_or_path;
	}
	/**
	 * This function is clean_url .
	 *
	 * @param string $url .
	 */
	private static function clean_url( $url ) {
		$p   = substr( $url, 0, 8 );
		$url = str_replace( '//', '/', str_replace( '\\', '/', substr( $url, 8 ) ) );
		$url = rawurlencode( $url );
		$url = str_replace( '%2F', '/', $url );

		return $p . $url;
	}
	/**
	 * This function is content_hash_stream .
	 *
	 * @param string $stream .
	 * @param string $chunksize .
	 */
	public static function content_hash_stream( $stream, $chunksize = 1024 * 8 ) {
		static $block_size = 4 * 1024 * 1024;
		$streamhasher      = hash_init( 'sha256' );
		$blockhasher       = hash_init( 'sha256' );
		$current_block     = 1;
		$current_blocksize = 0;
		while ( ! feof( $stream ) ) {
			$max_bytes_to_read = min( $chunksize, $block_size - $current_blocksize );
			$chunk             = fread( $stream, $max_bytes_to_read );// @codingStandardsIgnoreLine.
			if ( strlen( $chunk ) == 0 ) { // WPCS: loose comparison ok.
				break;
			}
			hash_update( $blockhasher, $chunk );
			$current_blocksize += $max_bytes_to_read;
			if ( $current_blocksize == $block_size ) {// WPCS: loose comparison ok.
				$blockhash = hash_final( $blockhasher, true );
				hash_update( $streamhasher, $blockhash );
				$blockhasher = hash_init( 'sha256' );
				++ $current_block;
				$current_blocksize = 0;
			}
		}

		if ( $current_blocksize > 0 ) {
			$blockhash = hash_final( $blockhasher, true );
			hash_update( $streamhasher, $blockhash );
		}

		return hash_final( $streamhasher );
	}
	/**
	 * This function is content_hash_file .
	 *
	 * @param string $local_file_name .
	 */
	public static function content_hash_file( $local_file_name ) {
		$handle = fopen( $local_file_name, 'r' );// @codingStandardsIgnoreLine.
		$hash   = self::content_hash_stream( $handle );
		fclose( $handle );// @codingStandardsIgnoreLine.

		return $hash;
	}

	/**
	 * This function is used to get acces token.
	 *
	 * @deprecated
	 * @throws DropboxException .
	 */
	public function get_request_token() {
		throw new DropboxException( 'get_request_token() has been removed with v2 API. Request tokens do not exist in OAuth2 anymore.' );
	}
	/**
	 * This function is used to get acces token.
	 *
	 * @deprecated
	 * @throws DropboxException .
	 */
	public function get_access_token() {
		throw new DropboxException( 'get_access_token() has been removed with v2 API. Use get_bearer_token() instead!' );
	}

}
/**
 * This is exception handling.
 *
 * @deprecated
 * @throws DropboxException .
 */
class DropboxException extends Exception { // @codingStandardsIgnoreLine.
	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $tag.
	 */
	private $tag;
	/**
	 * This is __construct function.
	 *
	 * @param string $resp .
	 * @param string $context .
	 */
	public function __construct( $resp = null, $context = null ) {
		if ( is_null( $resp ) ) {
			$el            = error_get_last();
			$this->message = $el['message'];
			$this->file    = $el['file'];
			$this->line    = $el['line'];
		} elseif ( is_object( $resp ) && isset( $resp->error ) ) {
			$this->message = empty( $resp->error_description ) ? ( wp_json_encode( $resp ) . ( $context ? ", in $context" : '' ) ) : $resp->error_description;

			$this->tag = is_object( $resp->error ) ? $resp->error->{'.tag'} : $resp->error;
		} else {
			$this->message = $resp . ( $context ? ", in $context" : '' );
		}
	}
	/**
	 * This is get_tag function.
	 */
	public function get_tag() {
		return $this->tag;
	}
}
