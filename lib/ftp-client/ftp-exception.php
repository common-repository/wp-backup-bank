<?php // @codingStandardsIgnoreLine.
/**
 * This used for the ftp exception class.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/ftp-client
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
/**
 * The FtpException class.
 * Exception thrown if an error on runtime of the FTP client occurs.
 */
class FtpException extends \Exception {

}
