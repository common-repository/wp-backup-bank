<?php // @codingStandardsIgnoreLine.
/**
 * This file is used to include the files .
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/google
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Google_Client' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/autoload.php';
}
/**
 * Extension to the regular Google_Model that automatically
 * exposes the items array for iteration, so you can just
 * iterate over the object rather than a reference inside.
 */
class Google_Collection extends Google_Model implements Iterator, Countable {
	/**
	 * This function is used
	 *
	 * @var $collection_key
	 */
	protected $collection_key = 'items';
	/**
	 * This function is used to rewind
	 */
	public function rewind() {
		if ( isset( $this->modelData[ $this->collection_key ] ) && is_array( $this->modelData[ $this->collection_key ] ) ) { // @codingStandardsIgnoreLine.
			reset( $this->modelData[ $this->collection_key ] );// @codingStandardsIgnoreLine.
		}
	}
	/**
	 * This function is used for current type
	 */
	public function current() {
		$this->coerceType( $this->key() );
		if ( is_array( $this->modelData[ $this->collection_key ] ) ) { // @codingStandardsIgnoreLine.
			return current( $this->modelData[ $this->collection_key ] ); // @codingStandardsIgnoreLine.
		}
	}
	/**
	 * This function is used for the key
	 */
	public function key() {
		if ( isset( $this->modelData[ $this->collection_key ] ) && is_array( $this->modelData[ $this->collection_key ] ) ) { // @codingStandardsIgnoreLine.
			return key( $this->modelData[ $this->collection_key ] ); // @codingStandardsIgnoreLine.
		}
	}
	/**
	 * This function is used for next
	 */
	public function next() {
		return next( $this->modelData[ $this->collection_key ] ); // @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used valid key
	 */
	public function valid() {
		$key = $this->key();
		return null !== $key && false !== $key;
	}
	/**
	 * This function is used for count
	 */
	public function count() {
		if ( ! isset( $this->modelData[ $this->collection_key ] ) ) { // @codingStandardsIgnoreLine.
			return 0;
		}
		return count( $this->modelData[ $this->collection_key ] ); // @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to check offset exist .
	 *
	 * @param int $offset .
	 */
	public function offsetExists( $offset ) {
		if ( ! is_numeric( $offset ) ) {
			return parent::offsetExists( $offset );
		}
		return isset( $this->modelData[ $this->collection_key ][ $offset ] ); // @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get offset .
	 *
	 * @param int $offset .
	 */
	public function offsetGet( $offset ) {
		if ( ! is_numeric( $offset ) ) {
			return parent::offsetGet( $offset );
		}
		$this->coerceType( $offset );
		return $this->modelData[ $this->collection_key ][ $offset ]; // @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used set offset
	 *
	 * @param int $offset .
	 * @param int $value .
	 */
	public function offsetSet( $offset, $value ) {
		if ( ! is_numeric( $offset ) ) {
			return parent::offsetSet( $offset, $value );
		}
		$this->modelData[ $this->collection_key ][ $offset ] = $value; // @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used unset the offset
	 *
	 * @param int $offset .
	 */
	public function offsetUnset( $offset ) {
		if ( ! is_numeric( $offset ) ) {
			return parent::offsetUnset( $offset );
		}
		unset( $this->modelData[ $this->collection_key ][ $offset ] ); // @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used key type
	 *
	 * @param int $offset .
	 */
	private function coerceType( $offset ) {
		$typeKey = $this->keyType( $this->collection_key ); // @codingStandardsIgnoreLine.
		if ( isset( $this->$typeKey ) && ! is_object( $this->modelData[ $this->collection_key ][ $offset ] ) ) { // @codingStandardsIgnoreLine.
			$type = $this->$typeKey; // @codingStandardsIgnoreLine.
			$this->modelData[ $this->collection_key ][ $offset ] = new $type( $this->modelData[ $this->collection_key ][ $offset ] ); // @codingStandardsIgnoreLine.
		}
	}
}
