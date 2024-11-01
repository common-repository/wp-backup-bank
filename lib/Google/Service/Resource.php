<?php // @codingStandardsIgnoreLine.
/**
 * This file is used for service.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/Google/Service
 * @version 3.0.1
 */

/**
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
 * Implements the actual methods/resources of the discovered Google API using magic function
 * calling overloading (__call()), which on call will see if the method name (plus.activities.list)
 * is available in this service, and if so construct an apiHttpRequest representing it.
 */
class Google_Service_Resource {
	// Valid query parameters that work, but don't appear in discovery.
	private $stackParameters = array(// @codingStandardsIgnoreLine.
		'alt'         => array(
			'type'     => 'string',
			'location' => 'query',
		),
		'fields'      => array(
			'type'     => 'string',
			'location' => 'query',
		),
		'trace'       => array(
			'type'     => 'string',
			'location' => 'query',
		),
		'userIp'      => array(
			'type'     => 'string',
			'location' => 'query',
		),
		'quotaUser'   => array(
			'type'     => 'string',
			'location' => 'query',
		),
		'data'        => array(
			'type'     => 'string',
			'location' => 'body',
		),
		'mimeType'    => array(
			'type'     => 'string',
			'location' => 'header',
		),
		'uploadType'  => array(
			'type'     => 'string',
			'location' => 'query',
		),
		'mediaUpload' => array(
			'type'     => 'complex',
			'location' => 'query',
		),
		'prettyPrint' => array(
			'type'     => 'string',
			'location' => 'query',
		),
	);
	/**
	 * The root url.
	 *
	 * @var string $rootUrl .
	 */
	private $rootUrl;// @codingStandardsIgnoreLine.
	/**
	 * The current API client.
	 *
	 * @var Google_Client $client .
	 */
	private $client;
	/**
	 * The name of service.
	 *
	 * @var string $serviceName .
	 */
	private $serviceName;// @codingStandardsIgnoreLine.
	/**
	 * The path for service.
	 *
	 * @var string $servicePath .
	 */
	private $servicePath;// @codingStandardsIgnoreLine.
	/**
	 * The resource name.
	 *
	 * @var string $resourceName .
	 */
	private $resourceName;// @codingStandardsIgnoreLine.
	/**
	 * The methods.
	 *
	 * @var array $methods .
	 */
	private $methods;
	public function __construct( $service, $serviceName, $resourceName, $resource ) {// @codingStandardsIgnoreLine.
		$this->rootUrl      = $service->rootUrl;// @codingStandardsIgnoreLine.
		$this->client       = $service->getClient();
		$this->servicePath  = $service->servicePath;// @codingStandardsIgnoreLine.
		$this->serviceName  = $serviceName;// @codingStandardsIgnoreLine.
		$this->resourceName = $resourceName;// @codingStandardsIgnoreLine.
		$this->methods      = is_array( $resource ) && isset( $resource['methods'] ) ?
		$resource['methods'] :
		array( $resourceName => $resource );// @codingStandardsIgnoreLine.
	}
	/**
	 * TODO(ianbarber): This function needs simplifying.
	 *
	 * @param string $name .
	 * @param array  $arguments .
	 * @param bool   $expected_class optional, the expected class name.
	 * @return Google_Http_Request|expected_class
	 * @throws Google_Exception .
	 */
	public function call( $name, $arguments, $expected_class = null ) {
		if ( ! isset( $this->methods[ $name ] ) ) {
			$this->client->getLogger()->error(
				'Service method unknown', array(
					'service'  => $this->serviceName,// @codingStandardsIgnoreLine.
					'resource' => $this->resourceName,// @codingStandardsIgnoreLine.
					'method'   => $name,
				)
			);

			throw new Google_Exception(
				'Unknown function: ' .
				"{$this->serviceName}->{$this->resourceName}->{$name}()"
			);
		}
		$method     = $this->methods[ $name ];
		$parameters = $arguments[0];

		// postBody is a special case since it's not defined in the discovery
		// document as parameter, but we abuse the param entry for storing it.
		$postBody = null;// @codingStandardsIgnoreLine.
		if ( isset( $parameters['postBody'] ) ) {
			if ( $parameters['postBody'] instanceof Google_Model ) {
				// In the cases the post body is an existing object, we want
				// to use the smart method to create a simple object for
				// for JSONification.
				$parameters['postBody'] = $parameters['postBody']->toSimpleObject();
			} elseif ( is_object( $parameters['postBody'] ) ) {
				// If the post body is another kind of object, we will try and
				// wrangle it into a sensible format.
				$parameters['postBody'] = $this->convertToArrayAndStripNulls( $parameters['postBody'] );
			}
			$postBody = wp_json_encode( $parameters['postBody'] );// @codingStandardsIgnoreLine.
			unset( $parameters['postBody'] );
		}

		// TODO(ianbarber): optParams here probably should have been
		// handled already - this may well be redundant code.
		if ( isset( $parameters['optParams'] ) ) {
			$optParams = $parameters['optParams'];// @codingStandardsIgnoreLine.
			unset( $parameters['optParams'] );
			$parameters = array_merge( $parameters, $optParams );// @codingStandardsIgnoreLine.
		}

		if ( ! isset( $method['parameters'] ) ) {
			$method['parameters'] = array();
		}

		$method['parameters'] = array_merge(
			$method['parameters'], $this->stackParameters// @codingStandardsIgnoreLine.
		);
		foreach ( $parameters as $key => $val ) {
			if ( 'postBody' != $key && ! isset( $method['parameters'][ $key ] ) ) {// WPCS: Loose comparison ok.
				$this->client->getLogger()->error(
					'Service parameter unknown', array(
						'service'   => $this->serviceName,// @codingStandardsIgnoreLine.
						'resource'  => $this->resourceName,// @codingStandardsIgnoreLine.
						'method'    => $name,
						'parameter' => $key,
					)
				);
				throw new Google_Exception( "($name) unknown parameter: '$key'" );
			}
		}

		foreach ( $method['parameters'] as $paramName => $paramSpec ) {// @codingStandardsIgnoreLine.
			if ( isset( $paramSpec['required'] ) &&// @codingStandardsIgnoreLine.
			 $paramSpec['required'] &&// @codingStandardsIgnoreLine.
			 ! isset( $parameters[ $paramName ] )// @codingStandardsIgnoreLine.
			) {
				$this->client->getLogger()->error(
					'Service parameter missing', array(
						'service'   => $this->serviceName,// @codingStandardsIgnoreLine.
						'resource'  => $this->resourceName,// @codingStandardsIgnoreLine.
						'method'    => $name,
						'parameter' => $paramName,// @codingStandardsIgnoreLine.
					)
				);
				throw new Google_Exception( "($name) missing required param: '$paramName'" );// @codingStandardsIgnoreLine.
			}
			if ( isset( $parameters[ $paramName ] ) ) {// @codingStandardsIgnoreLine.
				$value                             = $parameters[ $paramName ];// @codingStandardsIgnoreLine.
				$parameters[ $paramName ]          = $paramSpec;// @codingStandardsIgnoreLine.
				$parameters[ $paramName ]['value'] = $value;// @codingStandardsIgnoreLine.
				unset( $parameters[ $paramName ]['required'] );// @codingStandardsIgnoreLine.
			} else {
				// Ensure we don't pass nulls.
				unset( $parameters[ $paramName ] );// @codingStandardsIgnoreLine.
			}
		}

		$this->client->getLogger()->info(
			'Service Call', array(
				'service'   => $this->serviceName,// @codingStandardsIgnoreLine.
				'resource'  => $this->resourceName,// @codingStandardsIgnoreLine.
				'method'    => $name,
				'arguments' => $parameters,
			)
		);

		$url = Google_Http_REST::createRequestUri(// @codingStandardsIgnoreLine.
			$this->servicePath, $method['path'], $parameters// @codingStandardsIgnoreLine.
		);
		$http_request = new Google_Http_Request(
			$url, $method['httpMethod'], null, $postBody// @codingStandardsIgnoreLine.
		);

		if ( $this->rootUrl ) {// @codingStandardsIgnoreLine.
			$http_request->setBaseComponent( $this->rootUrl );// @codingStandardsIgnoreLine.
		} else {
			$http_request->setBaseComponent( $this->client->getBasePath() );
		}

		if ( $postBody ) { // @codingStandardsIgnoreLine.
			$contentTypeHeader                 = array();// @codingStandardsIgnoreLine.
			$contentTypeHeader['content-type'] = 'application/json; charset=UTF-8';// @codingStandardsIgnoreLine.
			$http_request->setRequestHeaders( $contentTypeHeader );// @codingStandardsIgnoreLine.
			$http_request->setPostBody( $postBody );// @codingStandardsIgnoreLine.
		}

		$http_request = $this->client->getAuth()->sign( $http_request );
		$http_request->setExpectedClass( $expected_class );

		if ( isset( $parameters['data'] ) &&
		( 'media' == $parameters['uploadType']['value'] || 'multipart' == $parameters['uploadType']['value'] ) ) {// WPCS: Loose comparison ok.
			// If we are doing a simple media upload, trigger that as a convenience.
			$mfu = new Google_Http_MediaFileUpload(
				$this->client, $http_request, isset( $parameters['mimeType'] ) ? $parameters['mimeType']['value'] : 'application/octet-stream', $parameters['data']['value']
			);
		}

		if ( isset( $parameters['alt'] ) && 'media' == $parameters['alt']['value'] ) {// WPCS: loose comparison ok.
			$http_request->enableExpectedRaw();
		}

		if ( $this->client->shouldDefer() ) {
			// If we are in batch or upload mode, return the raw request.
			return $http_request;
		}

		return $this->client->execute( $http_request );
	}
	protected function convertToArrayAndStripNulls( $o ) {// @codingStandardsIgnoreLine.
		$o = (array) $o;
		foreach ( $o as $k => $v ) {
			if ( null === $v ) {
				unset( $o[ $k ] );
			} elseif ( is_object( $v ) || is_array( $v ) ) {
				$o[ $k ] = $this->convertToArrayAndStripNulls( $o[ $k ] );
			}
		}
		return $o;
	}
}
