<?php // @codingStandardsIgnoreLine
/**
 * This file is used for google services .
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/google
 * @version 3.0.1
 * Copyright 2011 Google Inc.
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
 * This class defines attributes, valid values, and usage which is generated
 * from a given json schema.
 * http://tools.ietf.org/html/draft-zyp-json-schema-03#section-5
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly\
/**
 * This class is used to converting to JSON with a real null
 */
class Google_Model implements ArrayAccess {
	/**
	* If you need to specify a NULL JSON value, use Google_Model::NULL_VALUE
	* instead - it will be replaced when converting to JSON with a real null.
	*/
	const NULL_VALUE = '{}gapi-php-null';
	/**
	 * Used for internal gap mapping.
	 *
	 * @var       array
	 * @access   protected
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Used for data model.
	 *
	 * @var       array
	 * @access   protected
	 */
	protected $modelData = array(); // @codingStandardsIgnoreLine
	/**
	 * Used for processed.
	 *
	 * @var       array
	 * @access   protected
	 */
	protected $processed = array();
	/**
	* Polymorphic - accepts a variable number of arguments dependent
	* on the type of the model subclass.
	*/
	final public function __construct() {
		if ( func_num_args() == 1 && is_array( func_get_arg( 0 ) ) ) { // WPCS:loose comparison ok.
			// Initialize the model with the array's contents.
			$array = func_get_arg( 0 );
			$this->mapTypes( $array );
		}
		$this->gapiInit();
	}
	/**
	 * Getter that handles passthrough access to the data array, and lazy object creation.
	 *
	 * @param string $key Property name.
	 * @return mixed The value if any, or null.
	 */
	public function __get( $key ) {
		$keyTypeName = $this->keyType( $key ); // @codingStandardsIgnoreLine
		$keyDataType = $this->dataType( $key ); // @codingStandardsIgnoreLine
		if ( isset( $this->$keyTypeName ) && ! isset( $this->processed[ $key ] ) ) { // @codingStandardsIgnoreLine
			if ( isset( $this->modelData[ $key ] ) ) { // @codingStandardsIgnoreLine
				$val = $this->modelData[ $key ]; // @codingStandardsIgnoreLine
			} elseif ( isset( $this->$keyDataType ) && ( $this->$keyDataType == 'array' || $this->$keyDataType == 'map' ) ) { // @codingStandardsIgnoreLine
				$val = array();
			} else {
				$val = null;
			}

			if ( $this->isAssociativeArray( $val ) ) {
				if ( isset( $this->$keyDataType ) && 'map' == $this->$keyDataType ) { // @codingStandardsIgnoreLine
					foreach ( $val as $arrayKey => $arrayItem ) { // @codingStandardsIgnoreLine
						$this->modelData[ $key ][ $arrayKey ] = $this->createObjectFromName( $keyTypeName, $arrayItem ); // @codingStandardsIgnoreLine
					}
				} else {
					$this->modelData[ $key ] = $this->createObjectFromName( $keyTypeName, $val ); // @codingStandardsIgnoreLine
				}
			} elseif ( is_array( $val ) ) {
				$arrayObject = array(); // @codingStandardsIgnoreLine
				foreach ( $val as $arrayIndex => $arrayItem ) { // @codingStandardsIgnoreLine
					$arrayObject[ $arrayIndex ] = $this->createObjectFromName( $keyTypeName, $arrayItem ); // @codingStandardsIgnoreLine
				}
				$this->modelData[ $key ] = $arrayObject; // @codingStandardsIgnoreLine
			}
			$this->processed[ $key ] = true;
		}

		return isset( $this->modelData[ $key ] ) ? $this->modelData[ $key ] : null; // @codingStandardsIgnoreLine
	}
	/**
	 * Initialize this object's properties from an array.
	 *
	 * @param array $array Used to seed this object's properties.
	 * @return void
	 */
	protected function mapTypes( $array ) {
		// Hard initialise simple types, lazy load more complex ones.
		foreach ( $array as $key => $val ) {
			if ( ! property_exists( $this, $this->keyType( $key ) ) &&
			property_exists( $this, $key ) ) {
				$this->$key = $val;
				unset( $array[ $key ] );
			} elseif ( property_exists( $this, $camelKey = Google_Utils::camelCase( $key ) ) ) { // @codingStandardsIgnoreLine
				// This checks if property exists as camelCase, leaving it in array as snake_case
				// in case of backwards compatibility issues.
				$this->$camelKey = $val; // @codingStandardsIgnoreLine
			}
		}
		$this->modelData = $array; // @codingStandardsIgnoreLine
	}
	/**
	 * Blank initialiser to be used in subclasses to do  post-construction initialisation - this
	 * avoids the need for subclasses to have to implement the variadics handling in their
	 * constructors.
	 */
	protected function gapiInit() {
		return; // @codingStandardsIgnoreLine
	}
	/**
	 * Create a simplified object suitable for straightforward
	 * conversion to JSON. This is relatively expensive
	 * due to the usage of reflection, but shouldn't be called
	 * a whole lot, and is the most straightforward way to filter.
	 */
	public function toSimpleObject() {
		$object = new stdClass();

		// Process all other data.
		foreach ( $this->modelData as $key => $val ) { // @codingStandardsIgnoreLine
			$result = $this->getSimpleValue( $val );
			if ( null !== $result ) {
				$object->$key = $this->nullPlaceholderCheck( $result );
			}
		}

		// Process all public properties.
		$reflect = new ReflectionObject( $this );
		$props   = $reflect->getProperties( ReflectionProperty::IS_PUBLIC );
		foreach ( $props as $member ) {
			$name   = $member->getName();
			$result = $this->getSimpleValue( $this->$name );
			if ( null !== $result ) {
				$name          = $this->getMappedName( $name );
				$object->$name = $this->nullPlaceholderCheck( $result );
			}
		}

		return $object;
	}
	/**
	 * Handle different types of values, primarily
	 * other objects and map and array data types.
	 *
	 * @param string $value .
	 */
	private function getSimpleValue( $value ) {
		if ( $value instanceof Google_Model ) {
			return $value->toSimpleObject();
		} elseif ( is_array( $value ) ) {
			$return = array();
			foreach ( $value as $key => $a_value ) {
				$a_value = $this->getSimpleValue( $a_value );
				if ( null !== $a_value ) {
					$key            = $this->getMappedName( $key );
					$return[ $key ] = $this->nullPlaceholderCheck( $a_value );
				}
			}
			return $return;
		}
		return $value;
	}
	/**
	 * Check whether the value is the null placeholder and return true null.
	 *
	 * @param string $value .
	 */
	private function nullPlaceholderCheck( $value ) {
		if ( $value === self::NULL_VALUE ) { //@codingStandardsIgnoreLine
			return null;
		}
		return $value;
	}
	/**
	 * If there is an internal name mapping, use that.
	 *
	 * @param string $key .
	 */
	private function getMappedName( $key ) {
		if ( isset( $this->internal_gapi_mappings ) &&
			isset( $this->internal_gapi_mappings[ $key ] ) ) {
			$key = $this->internal_gapi_mappings[ $key ];
		}
		return $key;
	}
	/**
	 * Returns true only if the array is associative.
	 *
	 * @param array $array .
	 * @return bool True if the array is associative.
	 */
	protected function isAssociativeArray( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$keys = array_keys( $array );
		foreach ( $keys as $key ) {
			if ( is_string( $key ) ) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Given a variable name, discover its type.
	 *
	 * @param string $name .
	 * @param string $item .
	 * @return object The object from the item.
	 */
	private function createObjectFromName( $name, $item ) {
		$type = $this->$name;
		return new $type( $item );
	}
	/**
	 * Verify if $obj is an array.
	 *
	 * @throws Google_Exception Thrown if $obj isn't an array.
	 * @param array  $obj Items that should be validated.
	 * @param string $method Method expecting an array as an argument.
	 */
	public function assertIsArray( $obj, $method ) {
		if ( $obj && ! is_array( $obj ) ) {
			throw new Google_Exception(
				"Incorrect parameter type passed to $method(). Expected an array."
			);
		}
	}
	/**
	 * This function is used to check offset exist.
	 *
	 * @param string $offset .
	 */
	public function offsetExists( $offset ) {
		return isset( $this->$offset ) || isset( $this->modelData[ $offset ] ); // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get offset .
	 *
	 * @param string $offset .
	 */
	public function offsetGet( $offset ) {
		return isset( $this->$offset ) ?
			$this->$offset :
			$this->__get( $offset );
	}
	/**
	 * This function is used to set offset .
	 *
	 * @param string $offset .
	 * @param string $value .
	 */
	public function offsetSet( $offset, $value ) {
		if ( property_exists( $this, $offset ) ) {
			$this->$offset = $value;
		} else {
			$this->modelData[ $offset ] = $value; //@codingStandardsIgnoreLine
			$this->processed[ $offset ] = true;
		}
	}
	/**
	 * This function is used to unset offset .
	 *
	 * @param string $offset .
	 */
	public function offsetUnset( $offset ) {
		unset( $this->modelData[ $offset ] ); //@codingStandardsIgnoreLine
	}
	/**
	 * This function is used to give key type .
	 *
	 * @param string $key .
	 */
	protected function keyType( $key ) {
		return $key . 'Type';
	}
	/**
	 * This function is used to give key data type .
	 *
	 * @param string $key .
	 */
	protected function dataType( $key ) {
		return $key . 'DataType';
	}
	/**
	 * This function is used set key .
	 *
	 * @param string $key .
	 */
	public function __isset( $key ) {
		return isset( $this->modelData[ $key ] ); //@codingStandardsIgnoreLine
	}
	/**
	 * This function is used unset key .
	 *
	 * @param string $key .
	 */
	public function __unset( $key ) {
		unset( $this->modelData[ $key ] ); //@codingStandardsIgnoreLine
	}
}