<?php // @codingStandardsIgnoreLine.
/**
 * This file is used to create Zip File of Backup.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( class_exists( 'ZipArchive' ) ) :
	/**
	 * This is used to last_error variable for comaptibility with our Backup_bank_PclZip object.
	 */
	class Backup_bank_ZipArchive extends ZipArchive { // @codingStandardsIgnoreLine.
		/**
		 * This class is used to add last_error variable for comaptibility with our Backup_bank_PclZip object
		 *
		 * @access   public
		 * @var string $last_error .
		 */
		public $last_error = 'Unknown: ZipArchive does not return error messages';
	}
	endif;

/**
 * This class is used to ZipArchive compatibility layer, with behaviour sufficient for our usage of ZipArchive.
 */
class Backup_bank_PclZip { // @codingStandardsIgnoreLine.
	/**
	 * Used to zip.
	 *
	 * @access   public
	 * @var      string    $pclzip.
	 */
	public $pclzip;
	/**
	 * Gives path.
	 *
	 * @access   public
	 * @var      string    $path.
	 */
	public $path;
	/**
	 * To ad files.
	 *
	 * @access   public
	 * @var      string    $addfiles.
	 */
	public $addfiles;
	/**
	 * The version of this plugin.
	 *
	 * @access   public
	 * @var      string    $adddirs.
	 */
	public $adddirs;
	/**
	 * To show Error message.
	 *
	 * @access   public
	 * @var      string    $last_error.
	 */
	public $last_error;
	/**
	 * Public constructor.
	 */
	public function __construct() {
		$this->addfiles = array();
		$this->adddirs  = array();
	}
	/**
	 * This function is used to open .
	 *
	 * @param integer $path .
	 * @param string  $flags .
	 */
	public function open( $path, $flags = 0 ) {
		if ( ! defined( 'PCLZIP_TEMPORARY_DIR' ) ) {
			define( 'PCLZIP_TEMPORARY_DIR', trailingslashit( dirname( $path ) ) );
		}
		if ( ! class_exists( 'PclZip' ) ) {
			include_once ABSPATH . '/wp-admin/includes/class-pclzip.php';
		}
		if ( ! class_exists( 'PclZip' ) ) {
			$this->last_error = 'No PclZip class was found';
			return false;
		}

		// Route around PHP bug (exact version with the problem not known).
		$ziparchive_create_match = ( version_compare( PHP_VERSION, '5.2.12', '>' ) && defined( 'ZIPARCHIVE::CREATE' ) ) ? ZIPARCHIVE::CREATE : 1;

		if ( $flags == $ziparchive_create_match && file_exists( $path ) ) { // WPCS:loose comparison ok.
			@unlink( $path ); // @codingStandardsIgnoreLine.
		}

		$this->pclzip = new PclZip( $path );
		if ( empty( $this->pclzip ) ) {
			$this->last_error = 'Could not get a PclZip object';
			return false;
		}
		$this->path = $path;
		return true;
	}
	/**
	 * This function is used to close .
	 */
	public function close() {
		if ( empty( $this->pclzip ) ) {
			$this->last_error = 'Zip file was not opened';
			return false;
		}
		$activity = false;
		if ( isset( $this->addfiles ) && count( $this->addfiles ) > 0 ) {
			foreach ( $this->addfiles as $rdirname => $adirnames ) {
				foreach ( $adirnames as $adirname => $files ) {
					if ( false == $this->pclzip->add( $files, PCLZIP_OPT_REMOVE_PATH, $rdirname, PCLZIP_OPT_ADD_PATH, $adirname ) ) { // WPCS:loose comparison ok.
						$this->last_error = $this->pclzip->errorInfo( true );
						return false;
					}
					$activity = true;
				}
			}
		}
		$this->pclzip   = false;
		$this->addfiles = array();
		$this->adddirs  = array();

		clearstatcache();
		if ( $activity && filesize( $this->path ) < 50 ) {
			$this->last_error = 'Write failed - unknown cause (check your file permissions)';
			return false;
		}
		return true;
	}
	/**
	 * This function is used to unset files.
	 */
	public function addFiles_unset() { // @codingStandardsIgnoreLine.
		if ( count( $this->addfiles ) > 0 ) {
			foreach ( $this->addfiles as $rdirname => $adirnames ) {
				foreach ( $adirnames as $adirname => $files ) {
					if ( false == $this->pclzip->add( $files, PCLZIP_OPT_REMOVE_PATH, $rdirname, PCLZIP_OPT_ADD_PATH, $adirname ) ) { // WPCS:loose comparison ok .
						$this->last_error = $this->pclzip->errorInfo( true );
						return false;
					}
					$activity = true;
				}
			}
		}

		$this->addfiles = array();
	}
	/**
	 * This function is used to Add the files.
	 * PclZip appears to do the whole (copy zip to
	 * temporary file, add file, move file) cycle for each file -
	 * so batch them as much as possible. We have to batch by dirname().
	 * basename($add_as) is irrelevant,it is actually basename($file) that will be used
	 *
	 * @param string $file .
	 * @param string $add_as .
	 */
	public function addFile( $file, $add_as ) { // @codingStandardsIgnoreLine.
		$rdirname                                   = dirname( $file );
		$adirname                                   = dirname( $add_as );
		$this->addfiles[ $rdirname ][ $adirname ][] = $file;
	}
	/**
	 * This function is used when PclZip doesn't have a direct.
	 *
	 * @param string $dir .
	 */
	public function addEmptyDir( $dir ) { // @codingStandardsIgnoreLine.
		$this->adddirs[] = $dir;
	}
	/**
	 * This function is used to extract files.
	 *
	 * @param string $backup_file .
	 * @param string $restore_directory .
	 */
	public function extract( $backup_file, $restore_directory ) {
		if ( ! class_exists( 'PclZip' ) ) {
			include_once ABSPATH . '/wp-admin/includes/class-pclzip.php';
		}
		$zip = new PclZip( $backup_file );
		$zip->extract( PCLZIP_OPT_PATH, $restore_directory );
	}
}
