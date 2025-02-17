<?php // @codingStandardsIgnoreLine
/**
 * This file is used to PEAR, the PHP Extension and Application Repository
 * PEAR class and PEAR_Error class
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/pear-archieve-tar
 * @version 3.0.1
 *
 * @license     http://opensource.org/licenses/bsd-license.php New BSD License
 * @link             http://pear.php.net/package/PEAR
 * @since           File available since Release 0.1
 */

/*
 * #@+
 * ERROR constants
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
define( 'PEAR_ERROR_RETURN', 1 );
define( 'PEAR_ERROR_PRINT', 2 );
define( 'PEAR_ERROR_TRIGGER', 4 );
define( 'PEAR_ERROR_DIE', 8 );
define( 'PEAR_ERROR_CALLBACK', 16 );

/**
 * WARNING: obsolete
 *
 * @deprecated
 */
define( 'PEAR_ERROR_EXCEPTION', 32 );

/* * #@- */

if ( substr( PHP_OS, 0, 3 ) == 'WIN' ) { // WPCS:loose comparison ok.
	define( 'OS_WINDOWS', true );
	define( 'OS_UNIX', false );
	define( 'PEAR_OS', 'Windows' );
} else {
	define( 'OS_WINDOWS', false );
	define( 'OS_UNIX', true );
	define( 'PEAR_OS', 'Unix' ); // blatant assumption .
}

$GLOBALS['_PEAR_default_error_mode']     = PEAR_ERROR_RETURN;
$GLOBALS['_PEAR_default_error_options']  = E_USER_NOTICE;
$GLOBALS['_PEAR_destructor_object_list'] = array();
$GLOBALS['_PEAR_shutdown_funcs']         = array();
$GLOBALS['_PEAR_error_handler_stack']    = array();

@ini_set( 'track_errors', true ); // @codingStandardsIgnoreLine.
/**
 * Base class for other PEAR classes.   Provides rudimentary
 * emulation of destructors.
 *
 * If you want a destructor in your class, inherit PEAR and make a
 * destructor method called _yourclassname (same name as the
 * constructor, but with a "_" prefix). Also, in your constructor you
 * have to call the PEAR constructor: $this->PEAR();.
 * The destructor method will be called without parameters. Note that
 * at in some SAPI implementations (such as Apache), any output during
 * the request shutdown (in which destructors are called) seems to be
 * discarded.   If you need to get any debug information from your
 * destructor, use error_log(), syslog() or something similar.
 *
 * IMPORTANT! To use the emulated destructors you need to create the
 * objects by reference: $obj =& new PEAR_child;
 */
class PEAR {
	/**
	 * Whether to enable internal debug messages.
	 *
	 * @var       bool
	 * @access   private
	 */
	var $_debug = false; // @codingStandardsIgnoreLine
	/**
	 * Default error mode for this object.
	 *
	 * @var       int
	 * @access   private
	 */
	var $_default_error_mode = null; // @codingStandardsIgnoreLine
	/**
	 * Default error options used for this object when error mode is PEAR_ERROR_TRIGGER.
	 *
	 * @var       int
	 * @access   private
	 */
	var $_default_error_options = null; // @codingStandardsIgnoreLine
	/**
	 * Default error handler (callback) for this object, if error mode is PEAR_ERROR_CALLBACK.
	 *
	 * @var       string
	 * @access   private
	 */
	var $_default_error_handler = ''; // @codingStandardsIgnoreLine
	/**
	 * Which class to use for error objects.
	 *
	 * @var       string
	 * @access   private
	 */
	var $_error_class = 'PEAR_Error'; // @codingStandardsIgnoreLine
	/**
	 * An array of expected errors.
	 *
	 * @access   private
	 * @var       array
	 */
	var $_expected_errors = array(); // @codingStandardsIgnoreLine
	/**
	 * List of methods that can be called both statically and non-statically.
	 *
	 * @var array
	 */
	protected static $bivalentMethods = array( // @codingStandardsIgnoreLine.
		'setErrorHandling'  => true,
		'raiseError'        => true,
		'throwError'        => true,
		'pushErrorHandling' => true,
		'popErrorHandling'  => true,
	);
	/**
	 * Constructor.  Registers this object in
	 * $_PEAR_destructor_object_list for destructor emulation if a
	 * destructor object exists.
	 *
	 * @param string $error_class    (optional) which class to use for
	 *               error objects, defaults to PEAR_Error.
	 * @access public
	 * @return void
	 */
	public function __construct( $error_class = null ) {
		$classname = strtolower( get_class( $this ) );
		if ( $this->_debug ) {
			print "PEAR constructor called, class=$classname\n"; // @codingStandardsIgnoreLine.
		}

		if ( null !== $error_class ) {
			$this->_error_class = $error_class;
		}

		while ( $classname && strcasecmp( $classname, 'pear' ) ) {
			$destructor = "_$classname";
			if ( method_exists( $this, $destructor ) ) {
				global $_PEAR_destructor_object_list; // @codingStandardsIgnoreLine.
				$_PEAR_destructor_object_list[] = &$this; // @codingStandardsIgnoreLine.
				if ( ! isset( $GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] ) ) {
					register_shutdown_function( '_PEAR_call_destructors' );
					$GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] = true;
				}
				break;
			} else {
				$classname = get_parent_class( $classname );
			}
		}
	}
	/**
	 * Only here for backwards compatibility.
	 *
	 * @param string $error_class Which class to use for error objects .
	 */
	public function PEAR( $error_class = null ) {
		self::__construct( $error_class );
	}
	/**
	 * Destructor (the emulated type of...). Does nothing right now,
	 * but is included for forward compatibility, so subclass
	 * destructors should always call it.
	 *
	 * @access public
	 * @return void
	 */
	public function _PEAR() { // @codingStandardsIgnoreLine.
		if ( $this->_debug ) {
			printf( "PEAR destructor called, class=%s\n", strtolower( get_class( $this ) ) ); // @codingStandardsIgnoreLine.
		}
	}
	/**
	 * Call undefined method .
	 *
	 * @param string $method .
	 * @param string $arguments .
	 * @access public
	 */
	public function __call( $method, $arguments ) {
		if ( ! isset( self::$bivalentMethods[ $method ] ) ) { // @codingStandardsIgnoreLine.
			trigger_error( 'Call to undefined method PEAR::' . $method . '()', E_USER_ERROR ); // @codingStandardsIgnoreLine.
		}
		return call_user_func_array(
			array( get_class(), '_' . $method ), array_merge( array( $this ), $arguments )
		);
	}
	/**
	 * Cal the static undefined method .
	 *
	 * @param string $method .
	 * @param string $arguments .
	 * @access public
	 */
	public static function __callStatic( $method, $arguments ) {
		if ( ! isset( self::$bivalentMethods[ $method ] ) ) { // @codingStandardsIgnoreLine.
			trigger_error( 'Call to undefined method PEAR::' . $method . '()', E_USER_ERROR ); // @codingStandardsIgnoreLine.
		}
		return call_user_func_array(
			array( get_class(), '_' . $method ), array_merge( array( null ), $arguments )
		);
	}
	/**
	 * If you have a class that's mostly/entirely static, and you need static
	 * properties, you can use this method to simulate them.
	 * You MUST use a reference, or they will not persist!
	 *
	 * @param    string $class   The calling classname, to prevent clashes .
	 * @param    string $var     The variable to retrieve.
	 * @return mixed  A reference to the variable. If not set it will be auto initialised to NULL.
	 */
	public static function &getStaticProperty( $class, $var ) { // @codingStandardsIgnoreLine.
		static $properties;
		if ( ! isset( $properties[ $class ] ) ) {
			$properties[ $class ] = array();
		}

		if ( ! array_key_exists( $var, $properties[ $class ] ) ) {
			$properties[ $class ][ $var ] = null;
		}
		return $properties[ $class ][ $var ];
	}
	/**
	 * Use this function to register a shutdown method for static classes.
	 *
	 * @param    mixed $func The function name (or array of class/method) to call .
	 * @param    mixed $args The arguments to pass to the function .
	 *
	 * @return void
	 */
	public static function registerShutdownFunc( $func, $args = array() ) { // @codingStandardsIgnoreLine.
		// if we are called statically, there is a potential
		// that no shutdown func is registered.
		if ( ! isset( $GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] ) ) {
			register_shutdown_function( '_PEAR_call_destructors' );
			$GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] = true;
		}
		$GLOBALS['_PEAR_shutdown_funcs'][] = array( $func, $args );
	}
	/**
	 * Tell whether a value is a PEAR error.
	 *
	 * @param     mixed $data     the value to test .
	 * @param     int   $code   if $data is an error object, return true
	 *                                             only if $code is a string and
	 *                                             $obj->getMessage() == $code or
	 *                                             $code is an integer and $obj->getCode() == $code .
	 * @return   bool        true if parameter is an error
	 */
	public static function isError( $data, $code = null ) { // @codingStandardsIgnoreLine.
		if ( ! is_a( $data, 'PEAR_Error' ) ) {
			return false;
		}
		if ( is_null( $code ) ) {
			return true;
		} elseif ( is_string( $code ) ) {
			return $data->getMessage() == $code; // WPCS:loose comparison ok.
		}

		return $data->getCode() == $code; // WPSC:loose comparison ok.
	}
	/**
	 * Sets how errors generated by this object should be handled.
	 * Can be invoked both in objects and statically.    If called
	 * statically, setErrorHandling sets the default behaviour for all
	 * PEAR objects. If called in an object, setErrorHandling sets
	 * the default behaviour for that object.
	 *
	 * @param object $object Object the method was called on (non-static mode) .
	 *
	 * @param int    $mode .
	 *
	 * @param mixed  $options .
	 *               When $mode is PEAR_ERROR_TRIGGER, this is the error level (one
	 *               of E_USER_NOTICE, E_USER_WARNING or E_USER_ERROR).
	 *
	 *               When $mode is PEAR_ERROR_CALLBACK, this parameter is expected
	 *               to be the callback function or method.  A callback
	 *               function is a string with the name of the function, a
	 *               callback method is an array of two elements: the element
	 *               at index 0 is the object, and the element at index 1 is
	 *               the name of the method to call in the object.
	 *
	 *               When $mode is PEAR_ERROR_PRINT or PEAR_ERROR_DIE, this is
	 *               a printf format string used when printing the error
	 *               message.
	 *
	 * @access public
	 * @return void
	 * @see PEAR_ERROR_RETURN
	 * @see PEAR_ERROR_PRINT
	 * @see PEAR_ERROR_TRIGGER
	 * @see PEAR_ERROR_DIE
	 * @see PEAR_ERROR_CALLBACK
	 * @see PEAR_ERROR_EXCEPTION
	 *
	 * @since PHP 4.0.5
	 */
	protected static function _setErrorHandling( // @codingStandardsIgnoreLine
	$object, $mode = null, $options = null
	) {
		if ( null !== $object ) {
			$setmode    = &$object->_default_error_mode;
			$setoptions = &$object->_default_error_options;
		} else {
			$setmode    = &$GLOBALS['_PEAR_default_error_mode'];
			$setoptions = &$GLOBALS['_PEAR_default_error_options'];
		}

		switch ( $mode ) {
			case PEAR_ERROR_EXCEPTION:
			case PEAR_ERROR_RETURN:
			case PEAR_ERROR_PRINT:
			case PEAR_ERROR_TRIGGER:
			case PEAR_ERROR_DIE:
			case null:
				$setmode    = $mode;
				$setoptions = $options;
				break;

			case PEAR_ERROR_CALLBACK:
				$setmode = $mode;
				// class/object method callback .
				if ( is_callable( $options ) ) {
					$setoptions = $options;
				} else {
					trigger_error( 'invalid error callback', E_USER_WARNING ); // @codingStandardsIgnoreLine
				}
				break;

			default:
				trigger_error( 'invalid error mode', E_USER_WARNING ); // @codingStandardsIgnoreLine
				break;
		}
	}
	/**
	 * This method is used to tell which errors you expect to get.
	 * Expected errors are always returned with error mode
	 * PEAR_ERROR_RETURN.    Expected error codes are stored in a stack,
	 * and this method pushes a new element onto it. The list of
	 * expected errors are in effect until they are popped off the
	 * stack with the popExpect() method.
	 *
	 * Note that this method can not be called statically
	 *
	 * @param mixed $code a single error code or an array of error codes to expect .
	 *
	 * @return int        the new depth of the "expected errors" stack
	 * @access public
	 */
	function expectError( $code = '*' ) { // @codingStandardsIgnoreLine
		if ( is_array( $code ) ) {
			array_push( $this->_expected_errors, $code );
		} else {
			array_push( $this->_expected_errors, array( $code ) );
		}
		return count( $this->_expected_errors );
	}
	/**
	 * This method pops one element off the expected error codes
	 * stack.
	 *
	 * @return array  the list of error codes that were popped
	 */
	function popExpect() { // @codingStandardsIgnoreLine
		return array_pop( $this->_expected_errors );
	}
	/**
	 * This method checks unsets an error code if available
	 *
	 * @param mixed $error_code .
	 * @return bool true if the error code was unset, false otherwise
	 * @access private
	 * @since PHP 4.3.0
	 */
	function _checkDelExpect( $error_code ) { // @codingStandardsIgnoreLine
		$deleted = false;
		foreach ( $this->_expected_errors as $key => $error_array ) {
			if ( in_array( $error_code, $error_array ) ) { // @codingStandardsIgnoreLine
				unset( $this->_expected_errors[ $key ][ array_search( $error_code, $error_array ) ] ); // @codingStandardsIgnoreLine
				$deleted = true;
			}

			// clean up empty arrays .
			if ( 0 == count( $this->_expected_errors[ $key ] ) ) { // WPCS:loose comparison ok.
				unset( $this->_expected_errors[ $key ] );
			}
		}

		return $deleted;
	}
	/**
	 * This method deletes all occurences of the specified element from
	 * the expected error codes stack.
	 *
	 * @param    mixed $error_code error code that should be deleted .
	 * @return mixed list of error codes that were deleted or error
	 * @access public
	 * @since PHP 4.3.0
	 */
	function delExpect( $error_code ) { // @codingStandardsIgnoreLine
		$deleted = false;
		if ( ( is_array( $error_code ) && ( 0 != count( $error_code ) ) ) ) { // WPCS:loose comparison ok
			// $error_code is a non-empty array here; we walk through it trying
			// to unset all values .
			foreach ( $error_code as $key => $error ) {
				$deleted = $this->_checkDelExpect( $error ) ? true : false;
			}
			return $deleted ? true : PEAR::raiseError( 'The expected error you submitted does not exist' ); // IMPROVE ME .
		} elseif ( ! empty( $error_code ) ) {
			// $error_code comes alone, trying to unset it
			if ( $this->_checkDelExpect( $error_code ) ) {
				return true;
			}
			return PEAR::raiseError( 'The expected error you submitted does not exist' ); // IMPROVE ME .
		}
		// $error_code is empty
		return PEAR::raiseError( 'The expected error you submitted is empty' ); // IMPROVE ME .
	}
	/**
	 * This method is a wrapper that returns an instance of the
	 * configured error class with this object's default error
	 * handling applied. If the $mode and $options parameters are not
	 * specified, the object's defaults are used.
	 *
	 * @param string $object .
	 * @param mixed  $message a text error message or a PEAR error object .
	 *
	 * @param int    $code          a numeric error code (it is up to your class
	 *                                      to define these if you want to use codes) .
	 *
	 * @param int    $mode  One of PEAR_ERROR_RETURN, PEAR_ERROR_PRINT,
	 *                                      PEAR_ERROR_TRIGGER, PEAR_ERROR_DIE,
	 *                                      PEAR_ERROR_CALLBACK, PEAR_ERROR_EXCEPTION.
	 *
	 * @param mixed  $options If $mode is PEAR_ERROR_TRIGGER, this parameter
	 *                                    specifies the PHP-internal error level (one of
	 *                                    E_USER_NOTICE, E_USER_WARNING or E_USER_ERROR).
	 *                                    If $mode is PEAR_ERROR_CALLBACK, this
	 *                                    parameter specifies the callback function or
	 *                                    method. In other error modes this parameter
	 *                                    is ignored.
	 *
	 * @param string $userinfo If you need to pass along for example debug
	 *                                   information, this parameter is meant for that.
	 *
	 * @param string $error_class The returned error object will be
	 *                                   instantiated from this class, if specified.
	 *
	 * @param bool   $skipmsg If true, raiseError will only pass error codes,
	 *                                     the error message parameter will be dropped.
	 *
	 * @return object     a PEAR error object
	 * @see PEAR::setErrorHandling
	 * @since PHP 4.0.5
	 */
	protected static function _raiseError( $object, $message = null, $code = null, $mode = null, $options = null, $userinfo = null, $error_class = null, $skipmsg = false ) { // @codingStandardsIgnoreLine
		// The error is yet a PEAR error object
		if ( is_object( $message ) ) {
			$code                          = $message->getCode();
			$userinfo                      = $message->getUserInfo();
			$error_class                   = $message->getType();
			$message->error_message_prefix = '';
			$message                       = $message->getMessage();
		}

		if (
			null !== $object &&
			isset( $object->_expected_errors ) &&
			count( $object->_expected_errors ) > 0 &&
			count( $exp = end( $object->_expected_errors ) ) // @codingStandardsIgnoreLine
		) {
			if ( '*' == $exp[0] || ( is_int( reset( $exp ) ) && in_array( $code, $exp ) ) || ( is_string( reset( $exp ) ) && in_array( $message, $exp ) ) // @codingStandardsIgnoreLine
			) {
				$mode = PEAR_ERROR_RETURN;
			}
		}

		// No mode given, try global ones .
		if ( null === $mode ) {
			// Class error handler .
			if ( null !== $object && isset( $object->_default_error_mode ) ) {
				$mode    = $object->_default_error_mode;
				$options = $object->_default_error_options;
				// Global error handler .
			} elseif ( isset( $GLOBALS['_PEAR_default_error_mode'] ) ) {
				$mode    = $GLOBALS['_PEAR_default_error_mode'];
				$options = $GLOBALS['_PEAR_default_error_options'];
			}
		}

		if ( null !== $error_class ) {
			$ec = $error_class;
		} elseif ( null !== $object && isset( $object->_error_class ) ) {
			$ec = $object->_error_class;
		} else {
			$ec = 'PEAR_Error';
		}

		if ( $skipmsg ) {
			$a = new $ec( $code, $mode, $options, $userinfo );
		} else {
			$a = new $ec( $message, $code, $mode, $options, $userinfo );
		}
		return $a;
	}
	/**
	 * Simpler form of raiseError with fewer options.    In most cases
	 * message, code and userinfo are enough.
	 *
	 * @param string $object .
	 * @param mixed  $message a text error message or a PEAR error object .
	 *
	 * @param int    $code          a numeric error code (it is up to your class
	 *                                      to define these if you want to use codes) .
	 *
	 * @param string $userinfo If you need to pass along for example debug
	 *                                   information, this parameter is meant for that.
	 *
	 * @return object     a PEAR error object
	 * @see PEAR::raiseError
	 */
	protected static function _throwError( $object, $message = null, $code = null, $userinfo = null ) { // @codingStandardsIgnoreLine
		if ( null !== $object ) {
			$a = &$object->raiseError( $message, $code, null, null, $userinfo );
			return $a;
		}
		$a = &PEAR::raiseError( $message, $code, null, null, $userinfo );
		return $a;
	}
	public static function staticPushErrorHandling( $mode, $options = null ) { // @codingStandardsIgnoreLine
		$stack       = &$GLOBALS['_PEAR_error_handler_stack'];
		$def_mode    = &$GLOBALS['_PEAR_default_error_mode'];
		$def_options = &$GLOBALS['_PEAR_default_error_options'];
		$stack[]     = array( $def_mode, $def_options );

		switch ( $mode ) {
			case PEAR_ERROR_EXCEPTION:
			case PEAR_ERROR_RETURN:
			case PEAR_ERROR_PRINT:
			case PEAR_ERROR_TRIGGER:
			case PEAR_ERROR_DIE:
			case null:
				$def_mode    = $mode;
				$def_options = $options;
				break;

			case PEAR_ERROR_CALLBACK:
				$def_mode = $mode;
				// class/object method callback .
				if ( is_callable( $options ) ) {
					$def_options = $options;
				} else {
					trigger_error( 'invalid error callback', E_USER_WARNING ); // @codingStandardsIgnoreLine
				}
				break;

			default:
				trigger_error( 'invalid error mode', E_USER_WARNING ); // @codingStandardsIgnoreLine
				break;
		}
		$stack[] = array( $mode, $options );
		return true;
	}
	public static function staticPopErrorHandling() { // @codingStandardsIgnoreLine
		$stack      = &$GLOBALS['_PEAR_error_handler_stack'];
		$setmode    = &$GLOBALS['_PEAR_default_error_mode'];
		$setoptions = &$GLOBALS['_PEAR_default_error_options'];
		array_pop( $stack );
		list($mode, $options) = $stack[ sizeof( $stack ) - 1 ]; // @codingStandardsIgnoreLine
		array_pop( $stack );

		switch ( $mode ) {
			case PEAR_ERROR_EXCEPTION:
			case PEAR_ERROR_RETURN:
			case PEAR_ERROR_PRINT:
			case PEAR_ERROR_TRIGGER:
			case PEAR_ERROR_DIE:
			case null:
				$setmode    = $mode;
				$setoptions = $options;
				break;

			case PEAR_ERROR_CALLBACK:
				$setmode = $mode;
				// class/object method callback .
				if ( is_callable( $options ) ) {
					$setoptions = $options;
				} else {
					trigger_error( 'invalid error callback', E_USER_WARNING ); // @codingStandardsIgnoreLine
				}
				break;

			default:
				trigger_error( 'invalid error mode', E_USER_WARNING ); // @codingStandardsIgnoreLine
				break;
		}
		return true;
	}
	/**
	 * Push a new error handler on top of the error handler options stack. With this
	 * you can easily override the actual error handler for some code and restore
	 * it later with popErrorHandling.
	 *
	 * @param string $object .
	 * @param mixed  $mode (same as setErrorHandling) .
	 * @param mixed  $options (same as setErrorHandling) .
	 *
	 * @return bool Always true
	 *
	 * @see PEAR::setErrorHandling
	 */
	protected static function _pushErrorHandling( $object, $mode, $options = null ) { // @codingStandardsIgnoreLine
		$stack = &$GLOBALS['_PEAR_error_handler_stack'];
		if ( null !== $object ) {
			$def_mode    = &$object->_default_error_mode;
			$def_options = &$object->_default_error_options;
		} else {
			$def_mode    = &$GLOBALS['_PEAR_default_error_mode'];
			$def_options = &$GLOBALS['_PEAR_default_error_options'];
		}
		$stack[] = array( $def_mode, $def_options );

		if ( null !== $object ) {
			$object->setErrorHandling( $mode, $options );
		} else {
			PEAR::setErrorHandling( $mode, $options );
		}
		$stack[] = array( $mode, $options );
		return true;
	}
	/**
	 * Pop the last error handler used
	 *
	 * @param string $object .
	 * @return bool Always true
	 *
	 * @see PEAR::pushErrorHandling
	 */
	protected static function _popErrorHandling( $object ) { // @codingStandardsIgnoreLine
		$stack = &$GLOBALS['_PEAR_error_handler_stack'];
		array_pop( $stack );
		list($mode, $options) = $stack[ sizeof( $stack ) - 1 ]; // @codingStandardsIgnoreLine
		array_pop( $stack );
		if ( null !== $object ) {
			$object->setErrorHandling( $mode, $options );
		} else {
			PEAR::setErrorHandling( $mode, $options );
		}
		return true;
	}
	/**
	 * OS independent PHP extension load. Remember to take care
	 * on the correct extension name for case sensitive OSes.
	 *
	 * @param string $ext The extension name .
	 * @return bool Success or not on the dl() call
	 */
	public static function loadExtension( $ext ) { // @codingStandardsIgnoreLine
		if ( extension_loaded( $ext ) ) {
			return true;
		}

		// if either returns true dl() will produce a FATAL error, stop that .
		if (
			function_exists( 'dl' ) === false ||
			ini_get( 'enable_dl' ) != 1 // WPCS: Loose comparison ok.
		) {
			return false;
		}

		if ( OS_WINDOWS ) {
			$suffix = '.dll';
		} elseif ( PHP_OS == 'HP-UX' ) {
			$suffix = '.sl';// WPCS: Loose comparison ok.
		} elseif ( PHP_OS == 'AIX' ) {
			$suffix = '.a';// WPCS: Loose comparison ok.
		} elseif ( PHP_OS == 'OSX' ) {
			$suffix = '.bundle';// WPCS: Loose comparison ok.
		} else {
			$suffix = '.so';
		}
		return @dl( 'php_' . $ext . $suffix ) || @dl( $ext . $suffix ); // @codingStandardsIgnoreLine.
	}
}
function _PEAR_call_destructors() { // @codingStandardsIgnoreLine.
	global $_PEAR_destructor_object_list; // @codingStandardsIgnoreLine.
	if ( is_array( $_PEAR_destructor_object_list ) && // @codingStandardsIgnoreLine.
		sizeof( $_PEAR_destructor_object_list ) ) { // @codingStandardsIgnoreLine.
		reset( $_PEAR_destructor_object_list ); // @codingStandardsIgnoreLine.

		$destructLifoExists = PEAR::getStaticProperty( 'PEAR', 'destructlifo' ); // @codingStandardsIgnoreLine.

		if ( $destructLifoExists ) { // @codingStandardsIgnoreLine.
			$_PEAR_destructor_object_list = array_reverse( $_PEAR_destructor_object_list ); // @codingStandardsIgnoreLine.
		}

		while ( list($k, $objref) = each( $_PEAR_destructor_object_list ) ) { // @codingStandardsIgnoreLine.
			$classname = get_class( $objref );
			while ( $classname ) {
				$destructor = "_$classname";
				if ( method_exists( $objref, $destructor ) ) {
					$objref->$destructor();
					break;
				} else {
					$classname = get_parent_class( $classname );
				}
			}
		}
		// Empty the object list to ensure that destructors are
		// not called more than once.
		$_PEAR_destructor_object_list = array(); // @codingStandardsIgnoreLine.
	}

	// Now call the shutdown functions.
	if (
		isset( $GLOBALS['_PEAR_shutdown_funcs'] ) &&
		is_array( $GLOBALS['_PEAR_shutdown_funcs'] ) &&
		! empty( $GLOBALS['_PEAR_shutdown_funcs'] )
	) {
		foreach ( $GLOBALS['_PEAR_shutdown_funcs'] as $value ) {
			call_user_func_array( $value[0], $value[1] );
		}
	}
}
/**
 * Standard PEAR error class for PHP 4
 *
 * This class is supserseded by {@link PEAR_Exception} in PHP 5
 *
 * @license     http://opensource.org/licenses/bsd-license.php New BSD License
 * @version     Release: 1.10.1
 * @link             http://pear.php.net/manual/en/core.pear.pear-error.php
 * @see             PEAR::raiseError(), PEAR::throwError()
 * @since           Class available since PHP 4.0.2
 */
class PEAR_Error { // @codingStandardsIgnoreLine
	/**
	 * Get the prefix of error message.
	 *
	 * @var $error_message_prefix .
	 */
	var $error_message_prefix = ''; // @codingStandardsIgnoreLine
	/**
	 * Get the error mode.
	 *
	 * @var $mode .
	 */
	var $mode = PEAR_ERROR_RETURN; // @codingStandardsIgnoreLine
	/**
	 * Get the level.
	 *
	 * @var $level .
	 */
	var $level = E_USER_NOTICE; // @codingStandardsIgnoreLine
	/**
	 * Get the code.
	 *
	 * @var $mode .
	 */
	var $code = -1; // @codingStandardsIgnoreLine
	/**
	 * Get the message.
	 *
	 * @var $mode .
	 */
	var $message = ''; // @codingStandardsIgnoreLine
	/**
	 * Get the user info.
	 *
	 * @var $userinfo .
	 */
	var $userinfo = ''; // @codingStandardsIgnoreLine
	/**
	 * Get Backtrace.
	 *
	 * @var $backtrace .
	 */
	var $backtrace = null; // @codingStandardsIgnoreLine
	/**
	 * PEAR_Error constructor
	 *
	 * @param string $message    message .
	 *
	 * @param int    $code       (optional) error code .
	 *
	 * @param int    $mode       (optional) error mode, one of: PEAR_ERROR_RETURN,
	 *    PEAR_ERROR_PRINT, PEAR_ERROR_DIE, PEAR_ERROR_TRIGGER,
	 *    PEAR_ERROR_CALLBACK or PEAR_ERROR_EXCEPTION .
	 *
	 * @param mixed  $options  (optional) error level, _OR_ in the case of
	 *  PEAR_ERROR_CALLBACK, the callback function or object/method
	 *  tuple.
	 *
	 * @param string $userinfo (optional) additional user/debug info .
	 *
	 * @access public
	 */
	function __construct( $message = 'unknown error', $code = null, $mode = null, $options = null, $userinfo = null ) { // @codingStandardsIgnoreLine.
		if ( null === $mode ) {
			$mode = PEAR_ERROR_RETURN;
		}
		$this->message  = $message;
		$this->code     = $code;
		$this->mode     = $mode;
		$this->userinfo = $userinfo;

		$skiptrace = PEAR::getStaticProperty( 'PEAR_Error', 'skiptrace' );

		if ( ! $skiptrace ) {
			$this->backtrace = debug_backtrace(); // @codingStandardsIgnoreLine.
			if ( isset( $this->backtrace[0] ) && isset( $this->backtrace[0]['object'] ) ) {
				unset( $this->backtrace[0]['object'] );
			}
		}

		if ( $mode & PEAR_ERROR_CALLBACK ) {
			$this->level    = E_USER_NOTICE;
			$this->callback = $options;
		} else {
			if ( null === $options ) {
				$options = E_USER_NOTICE;
			}

			$this->level    = $options;
			$this->callback = null;
		}

		if ( $this->mode & PEAR_ERROR_PRINT ) {
			if ( is_null( $options ) || is_int( $options ) ) {
				$format = '%s';
			} else {
				$format = $options;
			}
			printf( $format, $this->getMessage() ); // @codingStandardsIgnoreLine.
		}

		if ( $this->mode & PEAR_ERROR_TRIGGER ) {
			trigger_error( $this->getMessage(), $this->level ); // @codingStandardsIgnoreLine.
		}

		if ( $this->mode & PEAR_ERROR_DIE ) {
			$msg = $this->getMessage();
			if ( is_null( $options ) || is_int( $options ) ) {
				$format = '%s';
				if ( substr( $msg, -1 ) != "\n" ) { // WPCS:loose comparison ok.
					$msg .= "\n";
				}
			} else {
				$format = $options;
			}
			die( sprintf( $format, $msg ) ); // @codingStandardsIgnoreLine.
		}

		if ( $this->mode & PEAR_ERROR_CALLBACK && is_callable( $this->callback ) ) {
			call_user_func( $this->callback, $this );
		}

		if ( $this->mode & PEAR_ERROR_EXCEPTION ) {
			trigger_error( 'PEAR_ERROR_EXCEPTION is obsolete, use class PEAR_Exception for exceptions', E_USER_WARNING ); // @codingStandardsIgnoreLine.
			eval( '$e = new Exception($this->message, $this->code);throw($e);' ); // @codingStandardsIgnoreLine.
		}
	}
	/**
	 * Only here for backwards compatibility.
	 *
	 * Class "Cache_Error" still uses it, among others.
	 *
	 * @param string $message    Message .
	 * @param int    $code        Error code .
	 * @param int    $mode        Error mode .
	 * @param mixed  $options    See __construct() .
	 * @param string $userinfo Additional user/debug info .
	 */
	public function PEAR_Error(
	$message = 'unknown error', $code = null, $mode = null, $options = null, $userinfo = null
	) {
		self::__construct( $message, $code, $mode, $options, $userinfo );
	}
	/**
	 * Get the error mode from an error object.
	 *
	 * @return int error mode
	 * @access public
	 */
	public function getMode() { // @codingStandardsIgnoreLine.
		return $this->mode;
	}
	/**
	 * Get the callback function/method from an error object.
	 *
	 * @return mixed callback function or object/method array
	 * @access public
	 */
	public function getCallback() { // @codingStandardsIgnoreLine.
		return $this->callback;
	}
	/**
	 * Get the error message from an error object.
	 *
	 * @return   string  full error message
	 * @access public
	 */
	public function getMessage() { // @codingStandardsIgnoreLine.
		return ( $this->error_message_prefix . $this->message );
	}
	/**
	 * Get error code from an error object
	 *
	 * @return int error code
	 * @access public
	 */
	public function getCode() { // @codingStandardsIgnoreLine.
		return $this->code;
	}
	/**
	 * Get the name of this error/exception.
	 *
	 * @return string error/exception name (type)
	 * @access public
	 */
	public function getType() { // @codingStandardsIgnoreLine.
		return get_class( $this );
	}
	/**
	 * Get additional user-supplied information.
	 *
	 * @return string user-supplied information
	 * @access public
	 */
	public function getUserInfo() { // @codingStandardsIgnoreLine.
		return $this->userinfo;
	}
	/**
	 * Get additional debug information supplied by the application.
	 *
	 * @return string debug information
	 * @access public
	 */
	public function getDebugInfo() { // @codingStandardsIgnoreLine.
		return $this->getUserInfo();
	}
	/**
	 * Get the call backtrace from where the error was generated.
	 * Supported with PHP 4.3.0 or newer.
	 *
	 * @param int $frame (optional) what frame to fetch .
	 * @return array Backtrace, or NULL if not available.
	 * @access public
	 */
	public function getBacktrace( $frame = null ) { // @codingStandardsIgnoreLine.
		if ( defined( 'PEAR_IGNORE_BACKTRACE' ) ) {
			return null;
		}
		if ( null === $frame ) {
			return $this->backtrace;
		}
		return $this->backtrace[ $frame ];
	}
	/**
	 * Add user Info .
	 *
	 * @param string $info .
	 *
	 * @access public
	 */
	public function addUserInfo( $info ) { // @codingStandardsIgnoreLine
		if ( empty( $this->userinfo ) ) {
			$this->userinfo = $info;
		} else {
			$this->userinfo .= " ** $info";
		}
	}
	/**
	 * Make a string representation of this message.
	 *
	 * @access public
	 */
	public function __toString() {
		return $this->getMessage();
	}
	/**
	 * Make a string representation of this object.
	 *
	 * @return string a string with an object summary
	 * @access public
	 */
	public function toString() { // @codingStandardsIgnoreLine.
		$modes  = array();
		$levels = array(
			E_USER_NOTICE  => 'notice',
			E_USER_WARNING => 'warning',
			E_USER_ERROR   => 'error',
		);

		if ( $this->mode & PEAR_ERROR_CALLBACK ) {
			if ( is_array( $this->callback ) ) {
				$callback = ( is_object( $this->callback[0] ) ?
				strtolower( get_class( $this->callback[0] ) ) :
				$this->callback[0] ) . '::' .
				$this->callback[1];
			} else {
				$callback = $this->callback;
			}
			return sprintf(
				'[%s: message="%s" code=%d mode=callback ' .
				'callback=%s prefix="%s" info="%s"]', strtolower( get_class( $this ) ), $this->message, $this->code, $callback, $this->error_message_prefix, $this->userinfo
			);
		}

		if ( $this->mode & PEAR_ERROR_PRINT ) {
			$modes[] = 'print';
		}
		if ( $this->mode & PEAR_ERROR_TRIGGER ) {
			$modes[] = 'trigger';
		}
		if ( $this->mode & PEAR_ERROR_DIE ) {
			$modes[] = 'die';
		}
		if ( $this->mode & PEAR_ERROR_RETURN ) {
			$modes[] = 'return';
		}
		return sprintf(
			'[%s: message="%s" code=%d mode=%s level=%s ' .
			'prefix="%s" info="%s"]', strtolower( get_class( $this ) ), $this->message, $this->code, implode( '|', $modes ), $levels[ $this->level ], $this->error_message_prefix, $this->userinfo
		);
	}
}
