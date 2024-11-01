<?php  // @codingStandardsIgnoreLine.
/**
 * This file is used for OAuth 2.0 Signed JWT assertion grants.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/Google/Auth
 * @version 3.0.1
 */

/**
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
 * Credentials object used for OAuth 2.0 Signed JWT assertion grants.
 */
class Google_Auth_AssertionCredentials {
	const MAX_TOKEN_LIFETIME_SECS = 3600;
	/**
	 * The service account name.
	 *
	 * @access   public
	 * @var      string    $serviceAccountName.
	 */
	public $serviceAccountName;// @codingStandardsIgnoreLine.
	/**
	 * It contains the scope.
	 *
	 * @access   public
	 * @var      string    $scopes.
	 */
	public $scopes;
	/**
	 * The version of this plugin.
	 *
	 * @access   public
	 * @var      string    $privateKey.
	 */
	public $privateKey;// @codingStandardsIgnoreLine.
	/**
	 * The version of this plugin.
	 *
	 * @access   public
	 * @var      string    $privateKeyPassword.
	 */
	public $privateKeyPassword;// @codingStandardsIgnoreLine.
	/**
	 * The version of this plugin.
	 *
	 * @access   public
	 * @var      string    $assertionType.
	 */
	public $assertionType;// @codingStandardsIgnoreLine.
	/**
	 * The version of this plugin.
	 *
	 * @access   public
	 * @var      string    $sub.
	 */
	public $sub;
	/**
	 * The variable
	 *
	 * @deprecated
	 * @link http://tools.ietf.org/html/draft-ietf-oauth-json-web-token-06
	 * @var string
	 */
	public $prn;
	/**
	 * The variable.
	 *
	 * @access   public
	 * @var      string    $useCache.
	 */
	private $useCache;// @codingStandardsIgnoreLine.
	/**
	 * Public Constructor
	 *
	 * @param string      $serviceAccountName .
	 * @param array       $scopes array List of scopes.
	 * @param string      $privateKey .
	 * @param string      $privateKeyPassword .
	 * @param string      $assertionType .
	 * @param bool|string $sub The email address of the user for which the
	 *                                                          application is requesting delegated access.
	 * @param bool        $useCache Whether to generate a cache key and allow .
	 *              automatic caching of the generated token.
	 */
	public function __construct(
	$serviceAccountName, $scopes, $privateKey, $privateKeyPassword = 'notasecret', $assertionType = 'http://oauth.net/grant_type/jwt/1.0/bearer', $sub = false, $useCache = true// @codingStandardsIgnoreLine.
	) {
		$this->serviceAccountName = $serviceAccountName;// @codingStandardsIgnoreLine.
		$this->scopes             = is_string( $scopes ) ? $scopes : implode( ' ', $scopes );
		$this->privateKey         = $privateKey;// @codingStandardsIgnoreLine.
		$this->privateKeyPassword = $privateKeyPassword;// @codingStandardsIgnoreLine.
		$this->assertionType      = $assertionType;// @codingStandardsIgnoreLine.
		$this->sub                = $sub;
		$this->prn                = $sub;
		$this->useCache           = $useCache;// @codingStandardsIgnoreLine.
	}
	/**
	 * Generate a unique key to represent this credential.
	 *
	 * @return string
	 */
	public function getCacheKey() {// @codingStandardsIgnoreLine.
		if ( ! $this->useCache ) {// @codingStandardsIgnoreLine.
			return false;
		}
		$h  = $this->sub;
		$h .= $this->assertionType;// @codingStandardsIgnoreLine.
		$h .= $this->privateKey;// @codingStandardsIgnoreLine.
		$h .= $this->scopes;
		$h .= $this->serviceAccountName;// @codingStandardsIgnoreLine.
		return md5( $h );
	}
	/**
	 * Generate an assertion.
	 */
	public function generateAssertion() {// @codingStandardsIgnoreLine.
		$now = time();

		$jwtParams = array(// @codingStandardsIgnoreLine.
			'aud'   => Google_Auth_OAuth2::OAUTH2_TOKEN_URI,
			'scope' => $this->scopes,
			'iat'   => $now,
			'exp'   => $now + self::MAX_TOKEN_LIFETIME_SECS,
			'iss'   => $this->serviceAccountName,// @codingStandardsIgnoreLine.
		);

		if ( false !== $this->sub ) {
			$jwtParams['sub'] = $this->sub;// @codingStandardsIgnoreLine.
		} elseif ( false !== $this->prn ) {
			$jwtParams['prn'] = $this->prn;// @codingStandardsIgnoreLine.
		}

		return $this->makeSignedJwt( $jwtParams );// @codingStandardsIgnoreLine.
	}
	/**
	 * Creates a signed JWT.
	 *
	 * @param array $payload .
	 * @return string The signed JWT.
	 */
	private function makeSignedJwt( $payload ) {// @codingStandardsIgnoreLine.
		$header = array(
			'typ' => 'JWT',
			'alg' => 'RS256',
		);

		$payload = wp_json_encode( $payload );
		// Handle some overzealous escaping in PHP json that seemed to cause some errors
		// with claimsets.
		$payload = str_replace( '\/', '/', $payload );

		$segments = array(
			Google_Utils::urlSafeB64Encode( wp_json_encode( $header ) ),
			Google_Utils::urlSafeB64Encode( $payload ),
		);

		$signingInput = implode( '.', $segments );// @codingStandardsIgnoreLine.
		$signer       = new Google_Signer_P12( $this->privateKey, $this->privateKeyPassword );// @codingStandardsIgnoreLine.
		$signature    = $signer->sign( $signingInput );// @codingStandardsIgnoreLine.
		$segments[]   = Google_Utils::urlSafeB64Encode( $signature );

		return implode( '.', $segments );
	}
}
