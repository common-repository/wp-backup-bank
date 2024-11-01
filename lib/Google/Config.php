<?php // @codingStandardsIgnoreLine
/**
 * The file used to config google.
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

/**
 * A class to contain the library configuration for the Google API client.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
/**
 * This class is used to config google
 */
class Google_Config {
	const GZIP_DISABLED         = true;
	const GZIP_ENABLED          = false;
	const GZIP_UPLOADS_ENABLED  = true;
	const GZIP_UPLOADS_DISABLED = false;
	const USE_AUTO_IO_SELECTION = 'auto';
	const TASK_RETRY_NEVER      = 0;
	const TASK_RETRY_ONCE       = 1;
	const TASK_RETRY_ALWAYS     = -1;
	/**
	 * This class is used to config google
	 *
	 * @var $configuration
	 */
	protected $configuration;
	/**
	 * Create a new Google_Config. Can accept an ini file location with the
	 * local configuration. For example:
	 *     application_name="My App"
	 *
	 * @param string $ini_file_location - optional - The location of the ini file to load .
	 */
	public function __construct( $ini_file_location = null ) {
		$this->configuration = array(
			// The application_name is included in the User-Agent HTTP header.
			'application_name' => '',
			// Which Authentication, Storage and HTTP IO classes to use.
			'auth_class'       => 'Google_Auth_OAuth2',
			'io_class'         => self::USE_AUTO_IO_SELECTION,
			'cache_class'      => 'Google_Cache_File',
			'logger_class'     => 'Google_Logger_Null',
			// Don't change these unless you're working against a special development
			// or testing environment.
			'base_path'        => 'https://www.googleapis.com',
			// Definition of class specific values, like file paths and so on.
			'classes'          => array(
				'Google_IO_Abstract'       => array(
					'request_timeout_seconds' => 100,
				),
				'Google_Logger_Abstract'   => array(
					'level'          => 'debug',
					'log_format'     => "[%datetime%] %level%: %message% %context%\n",
					'date_format'    => 'd/M/Y:H:i:s O',
					'allow_newlines' => true,
				),
				'Google_Logger_File'       => array(
					'file' => 'php://stdout',
					'mode' => 0640,
					'lock' => false,
				),
				'Google_Http_Request'      => array(
					// Disable the use of gzip on calls if set to true. Defaults to false.
					'disable_gzip'            => self::GZIP_ENABLED,
					// We default gzip to disabled on uploads even if gzip is otherwise
					// enabled, due to some issues seen with small packet sizes for uploads.
					// Please test with this option before enabling gzip for uploads in
					// a production environment.
					'enable_gzip_for_uploads' => self::GZIP_UPLOADS_DISABLED,
				),
				// If you want to pass in OAuth 2.0 settings, they will need to be
				// structured like this.
				'Google_Auth_OAuth2'       => array(
					// Keys for OAuth 2.0 access, see the API console at
					// https://developers.google.com/console .
					'client_id'                  => '',
					'client_secret'              => '',
					'redirect_uri'               => '',
					// Simple API access key, also from the API console. Ensure you get
					// a Server key, and not a Browser key.
					'developer_key'              => '',
					// Other parameters.
					'hd'                         => '',
					'prompt'                     => '',
					'openid.realm'               => '',
					'include_granted_scopes'     => '',
					'login_hint'                 => '',
					'request_visible_actions'    => '',
					'access_type'                => 'online',
					'approval_prompt'            => 'auto',
					'federated_signon_certs_url' =>
					'https://www.googleapis.com/oauth2/v1/certs',
				),
				'Google_Task_Runner'       => array(
					// Delays are specified in seconds .
					'initial_delay' => 1,
					'max_delay'     => 60,
					// Base number for exponential backoff .
					'factor'        => 2,
					// A random number between -jitter and jitter will be added to the
					// factor on each iteration to allow for better distribution of
					// retries.
					'jitter'        => .5,
					// Maximum number of retries allowed .
					'retries'       => 0,
				),
				'Google_Service_Exception' => array(
					'retry_map' => array(
						'500'                   => self::TASK_RETRY_ALWAYS,
						'503'                   => self::TASK_RETRY_ALWAYS,
						'rateLimitExceeded'     => self::TASK_RETRY_ALWAYS,
						'userRateLimitExceeded' => self::TASK_RETRY_ALWAYS,
					),
				),
				'Google_IO_Exception'      => array(
					'retry_map' => ! extension_loaded( 'curl' ) ? array() : array(
						CURLE_COULDNT_RESOLVE_HOST => self::TASK_RETRY_ALWAYS,
						CURLE_COULDNT_CONNECT      => self::TASK_RETRY_ALWAYS,
						CURLE_OPERATION_TIMEOUTED  => self::TASK_RETRY_ALWAYS,
						CURLE_SSL_CONNECT_ERROR    => self::TASK_RETRY_ALWAYS,
						CURLE_GOT_NOTHING          => self::TASK_RETRY_ALWAYS,
					),
				),
				// Set a default directory for the file cache.
				'Google_Cache_File'        => array(
					'directory' => sys_get_temp_dir() . '/Google_Client',
				),
			),
		);
		if ( $ini_file_location ) {
			$ini = parse_ini_file( $ini_file_location, true );
			if ( is_array( $ini ) && count( $ini ) ) {
				$merged_configuration = $ini + $this->configuration;
				if ( isset( $ini['classes'] ) && isset( $this->configuration['classes'] ) ) {
					$merged_configuration['classes'] = $ini['classes'] + $this->configuration['classes'];
				}
				$this->configuration = $merged_configuration;
			}
		}
	}
	/**
	 * Set configuration specific to a given class.
	 * $config->setClassConfig('Google_Cache_File',
	 *   array('directory' => '/tmp/cache'));
	 *
	 * @param  string $class  The class name for the configuration .
	 * @param string $config  key or an array of configuration values .
	 * @param string $value optional - if $config is a key, the value .
	 */
	public function setClassConfig( $class, $config, $value = null ) { // @codingStandardsIgnoreLine
		if ( ! is_array( $config ) ) {
			if ( ! isset( $this->configuration['classes'][ $class ] ) ) {
				$this->configuration['classes'][ $class ] = array();
			}
			$this->configuration['classes'][ $class ][ $config ] = $value;
		} else {
			$this->configuration['classes'][ $class ] = $config;
		}
	}
	public function getClassConfig( $class, $key = null ) { // @codingStandardsIgnoreLine
		if ( ! isset( $this->configuration['classes'][ $class ] ) ) {
			return null;
		}
		if ( null === $key ) {
			return $this->configuration['classes'][ $class ];
		} else {
			return $this->configuration['classes'][ $class ][ $key ];
		}
	}
	/**
	 * Return the configured cache class.
	 *
	 * @return string
	 */
	public function getCacheClass() { // @codingStandardsIgnoreLine
		return $this->configuration['cache_class'];
	}
	/**
	 * Return the configured logger class.
	 *
	 * @return string
	 */
	public function getLoggerClass() { // @codingStandardsIgnoreLine
		return $this->configuration['logger_class'];
	}
	/**
	 * Return the configured Auth class.
	 *
	 * @return string
	 */
	public function getAuthClass() { // @codingStandardsIgnoreLine
		return $this->configuration['auth_class'];
	}
	/**
	 * Set the auth class.
	 *
	 * @param string $class the class name to set .
	 */
	public function setAuthClass( $class ) { // @codingStandardsIgnoreLine
		$prev = $this->configuration['auth_class'];
		if ( ! isset( $this->configuration['classes'][ $class ] ) &&
			isset( $this->configuration['classes'][ $prev ] ) ) {
			$this->configuration['classes'][ $class ] = $this->configuration['classes'][ $prev ];
		}
		$this->configuration['auth_class'] = $class;
	}
	/**
	 * Set the IO class.
	 *
	 * @param string $class the class name to set .
	 */
	public function setIoClass( $class ) { // @codingStandardsIgnoreLine
		$prev = $this->configuration['io_class'];
		if ( ! isset( $this->configuration['classes'][ $class ] ) &&
			isset( $this->configuration['classes'][ $prev ] ) ) {
			$this->configuration['classes'][ $class ] = $this->configuration['classes'][ $prev ];
		}
		$this->configuration['io_class'] = $class;
	}
	/**
	 * Set the cache class.
	 *
	 * @param string $class the class name to set .
	 */
	public function setCacheClass( $class ) { // @codingStandardsIgnoreLine
		$prev = $this->configuration['cache_class'];
		if ( ! isset( $this->configuration['classes'][ $class ] ) &&
			isset( $this->configuration['classes'][ $prev ] ) ) {
			$this->configuration['classes'][ $class ] = $this->configuration['classes'][ $prev ];
		}
		$this->configuration['cache_class'] = $class;
	}
	/**
	 * Set the logger class.
	 *
	 * @param string $class the class name to set .
	 */
	public function setLoggerClass( $class ) { // @codingStandardsIgnoreLine
		$prev = $this->configuration['logger_class'];
		if ( ! isset( $this->configuration['classes'][ $class ] ) &&
			isset( $this->configuration['classes'][ $prev ] ) ) {
			$this->configuration['classes'][ $class ] = $this->configuration['classes'][ $prev ];
		}
		$this->configuration['logger_class'] = $class;
	}
	/**
	 * Return the configured IO class.
	 *
	 * @return string
	 */
	public function getIoClass() { // @codingStandardsIgnoreLine
		return $this->configuration['io_class'];
	}
	/**
	 * Set the application name, this is included in the User-Agent HTTP header.
	 *
	 * @param string $name .
	 */
	public function setApplicationName( $name ) { // @codingStandardsIgnoreLine
		$this->configuration['application_name'] = $name;
	}
	/**
	 * This function is used to get application name
	 *
	 * @return string the name of the application
	 */
	public function getApplicationName() { // @codingStandardsIgnoreLine
		return $this->configuration['application_name'];
	}
	/**
	 * Set the client ID for the auth class.
	 *
	 * @param string $clientId - the API console client ID .
	 */
	public function setClientId( $clientId ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'client_id', $clientId ); // @codingStandardsIgnoreLine
	}
	/**
	 * Set the client secret for the auth class.
	 *
	 * @param string $secret - the API console client secret .
	 */
	public function setClientSecret( $secret ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'client_secret', $secret );
	}
	/**
	 * Set the redirect uri for the auth class. Note that if using the
	 * Javascript based sign in flow, this should be the string 'postmessage'.
	 *
	 * @param string $uri - the URI that users should be redirected to .
	 */
	public function setRedirectUri( $uri ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'redirect_uri', $uri );
	}
	/**
	 * Set the app activities for the auth class.
	 *
	 * @param string $rva a space separated list of app activity types .
	 */
	public function setRequestVisibleActions( $rva ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'request_visible_actions', $rva );
	}
	/**
	 * Set the the access type requested (offline or online.)
	 *
	 * @param string $access - the access type .
	 */
	public function setAccessType( $access ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'access_type', $access );
	}
	/**
	 * Set when to show the approval prompt (auto or force)
	 *
	 * @param string $approval - the approval request .
	 */
	public function setApprovalPrompt( $approval ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'approval_prompt', $approval );
	}
	/**
	 * Set the login hint (email address or sub identifier)
	 *
	 * @param string $hint .
	 */
	public function setLoginHint( $hint ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'login_hint', $hint );
	}
	/**
	 * Set the developer key for the auth class. Note that this is separate value
	 * from the client ID - if it looks like a URL, its a client ID!
	 *
	 * @param string $key - the API console developer key .
	 */
	public function setDeveloperKey( $key ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'developer_key', $key );
	}
	/**
	 * Set the hd (hosted domain) parameter streamlines the login process for
	 * Google Apps hosted accounts. By including the domain of the user, you
	 * restrict sign-in to accounts at that domain.
	 *
	 * This should not be used to ensure security on your application - check
	 * the hd values within an id token (@see Google_Auth_LoginTicket) after sign
	 * in to ensure that the user is from the domain you were expecting.
	 *
	 * @param string $hd - the domain to use.
	 */
	public function setHostedDomain( $hd ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'hd', $hd );
	}
	/**
	 * Set the prompt hint. Valid values are none, consent and select_account.
	 * If no value is specified and the user has not previously authorized
	 * access, then the user is shown a consent screen.
	 *
	 * @param string $prompt .
	 */
	public function setPrompt( $prompt ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'prompt', $prompt );
	}
	/**
	 * Openid.realm is a parameter from the OpenID 2.0 protocol, not from OAuth
	 * 2.0. It is used in OpenID 2.0 requests to signify the URL-space for which
	 * an authentication request is valid.
	 *
	 * @param string $realm - the URL-space to use.
	 */
	public function setOpenidRealm( $realm ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig( 'openid.realm', $realm );
	}
	/**
	 * If this is provided with the value true, and the authorization request is
	 * granted, the authorization will include any previous authorizations
	 * granted to this user/application combination for other scopes.
	 *
	 * @param boolean $include the URL-space to use.
	 */
	public function setIncludeGrantedScopes( $include ) { // @codingStandardsIgnoreLine
		$this->setAuthConfig(
			'include_granted_scopes', $include ? 'true' : 'false'
		);
	}
	/**
	 * This function is used to get base path
	 *
	 * @return string the base URL to use for API calls
	 */
	public function getBasePath() { // @codingStandardsIgnoreLine
		return $this->configuration['base_path'];
	}
	/**
	 * Set the auth configuration for the current auth class.
	 *
	 * @param int    $key - the key to set .
	 * @param string $value - the parameter value .
	 */
	private function setAuthConfig( $key, $value ) { // @codingStandardsIgnoreLine
		if ( ! isset( $this->configuration['classes'][ $this->getAuthClass() ] ) ) {
			$this->configuration['classes'][ $this->getAuthClass() ] = array();
		}
		$this->configuration['classes'][ $this->getAuthClass() ][ $key ] = $value;
	}
}
