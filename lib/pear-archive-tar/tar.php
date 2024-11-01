<?php // @codingStandardsIgnoreLine.
/**
 *
 * This file is used to archieve the tar.
 * File::CSV
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/pear-archieve-tar
 * @version 3.0.1
 *
 * Copyright (c) 1997-2008,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *      * Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *      * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category    File_Formats
 * @package Archive_Tar
 * @copyright 1997-2010 The Authors
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version CVS: $Id$
 * @link        http://pear.php.net/package/Archive_Tar
 */
// If the PEAR class cannot be loaded via the autoloader,
// then try to require_once it from the PHP include path.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( file_exists( BACKUP_BANK_DIR_PATH . '/lib/pear-archive-tar/pear.php' ) ) {
	require_once BACKUP_BANK_DIR_PATH . '/lib/pear-archive-tar/pear.php';
}

define( 'ARCHIVE_TAR_ATT_SEPARATOR', 90001 );
define( 'ARCHIVE_TAR_END_BLOCK', pack( 'a512', '' ) );

if ( ! function_exists( 'gzopen' ) && function_exists( 'gzopen64' ) ) {
	/**
	 * This function is used to open gz.
	 *
	 * @param string $filename .
	 * @param string $mode .
	 * @param string $use_include_path .
	 */
	function gzopen( $filename, $mode, $use_include_path = 0 ) {
		return gzopen64( $filename, $mode, $use_include_path );
	}
}

if ( ! function_exists( 'gztell' ) && function_exists( 'gztell64' ) ) {
	/**
	 * This function is used to gztell.
	 *
	 * @param string $zp .
	 */
	function gztell( $zp ) {
		return gztell64( $zp );
	}
}

if ( ! function_exists( 'gzseek' ) && function_exists( 'gzseek64' ) ) {
	/**
	 * This function is used for gzseek.
	 *
	 * @param string $zp .
	 * @param string $offset .
	 * @param string $whence .
	 */
	function gzseek( $zp, $offset, $whence = SEEK_SET ) {
		return gzseek64( $zp, $offset, $whence );
	}
}
/**
 * Creates a (compressed) Tar archive
 *
 * @package Archive_Tar
 * @version $Revision$
 */
class Archive_Tar extends PEAR {
	/**
	 * Name of the Tar.
	 *
	 * @var string $_tarname .
	 */
	public $_tarname = '';
	/**
	 * If true, the Tar file will be gzipped .
	 *
	 * @var boolean $_compress
	 */
	public $_compress = false;
	/**
	 * Type of compression : "none", "gz" or "bz2" .
	 *
	 * @var string
	 */
	public $_compress_type = 'none';
	/**
	 * To Explode separator .
	 *
	 * @var string
	 */
	public $_separator = ' ';
	/**
	 * The file descriptor .
	 *
	 * @var file
	 */
	public $_file = 0;
	/**
	 *  Local Tar name of a remote Tar (http:// or ftp://)
	 *
	 * @var string
	 */
	public $_temp_tarname = '';
	/**
	 *  PEAR_Error object .
	 *
	 * @var object
	 */
	public $error_object = null;
	/**
	 * Format for data extraction
	 *
	 * @var string
	 */
	public $_fmt = '';
	/**
	 * Format for data extraction
	 *
	 * @var string
	 */
	public $count_files = 0;
	/**
	 * Gives the total file directories
	 *
	 * @var string
	 */
	public $total_files_directories = 0;
	/**
	 * Gives the total size of file
	 *
	 * @var string
	 */
	public $total_file_size;
	/**
	 * Gives the log file name
	 *
	 * @var string
	 */
	public $log_file_name;
	/**
	 * Gives the tar file name
	 *
	 * @var string
	 */
	public $tar_file_name;
	/**
	 * Gives the size of file
	 *
	 * @var string
	 */
	public $size_of_file;
	/**
	 * Gives the type of backup
	 *
	 * @var string
	 */
	public $backup_type;
	/**
	 * Gives the complete persentage.
	 *
	 * @var string
	 */
	public $complete_percentage = '';
	/**
	 * Gives the status
	 *
	 * @var string
	 */
	public $status;
	/**
	 * Exclude the dir path
	 *
	 * @var string
	 */
	public $exclude_dir_path = '';
	/**
	 * Percentage of the backup
	 *
	 * @var string
	 */
	public $backup_percentage;
	/**
	 * Type of the compress file
	 *
	 * @var string
	 */
	public $file_compress_type;
	/**
	 * File name of the database
	 *
	 * @var string
	 */
	public $database_file_name;
	/**
	 * Full path of the tar path
	 *
	 * @var string
	 */
	public $tar_file_fullpath;
	/**
	 * Gives the destination of the backup
	 *
	 * @var string
	 */
	public $backup_destination;
	/**
	 * Name of the backup file
	 *
	 * @var string
	 */
	public $backup_file_name;
	/**
	 * Time start to micro zip.
	 *
	 * @var string
	 */
	public $zip_microtime_start;
	/**
	 * Format for data extraction
	 *
	 * @var string
	 */
	public $compress_log;
	/**
	 * Name of the archieve file
	 *
	 * @var string
	 */
	public $archive_name;
	/**
	 * Kbsize of file
	 *
	 * @var string
	 */
	public $kbsize;
	/**
	 * Time taken
	 *
	 * @var string
	 */
	public $timetaken;
	/**
	 * Name of the json file
	 *
	 * @var string
	 */
	public $json_file_name;
	/**
	 * Use to execution
	 *
	 * @var string
	 */
	public $execution;
	/**
	 * Size of the added files
	 *
	 * @var string
	 */
	public $files_size_added;
	/**
	 * Total size of the files
	 *
	 * @var string
	 */
	public $total_files_size;
	/**
	 * Use for cloud file
	 *
	 * @var string
	 */
	public $cloud;
	/**
	 * Time taken by log generator
	 *
	 * @var string
	 */
	public $log_timetaken;
	/**
	 * Archive_Tar Class constructor. This flavour of the constructor only
	 * declare a new Archive_Tar object, identifying it by the name of the
	 * tar file.
	 * If the compress argument is set the tar will be read or created as a
	 * gzip or bz2 compressed TAR file.
	 *
	 * @param string $p_tarname The name of the tar archive to create .
	 * @param string $compress .
	 * @param string $backup .
	 * @param string $database_file .
	 * @param string $file_destination .
	 * @param string $backup_filename .
	 * @param string $execution_type .
	 *
	 * @return bool
	 */
	public function __construct( $p_tarname, $compress, $backup, $database_file, $file_destination, $backup_filename, $execution_type ) {
		parent::__construct();
		$this->log_file_name      = str_replace( $compress, '.txt', $p_tarname );
		$this->json_file_name     = str_replace( $compress, '.json', $p_tarname );
		$this->logfile_handle     = fopen( $this->log_file_name, 'a' ); // @codingStandardsIgnoreLine.
		$this->tar_file_name      = basename( $p_tarname );
		$this->tar_file_fullpath  = $p_tarname;
		$this->file_compress_type = $compress;
		$this->archive_name       = str_replace( $compress, '', $this->tar_file_name );
		$this->backup_file_name   = $backup_filename;
		$this->backup_destination = $file_destination;
		$this->database_file_name = basename( $database_file );
		$this->execution          = $execution_type;
		$this->backup_percentage  = '';
		$this->_compress          = false;
		$this->_compress_type     = 'none';
		$this->backup_type        = $backup;
		$this->exclude_dir_path   = str_replace( basename( $p_tarname ), '', $p_tarname );
		$this->cloud              = 1;

		if ( 'complete_backup' == $backup ) { // WPCS:loose comparison ok.
			$this->complete_percentage = 24;
		}
		$this->compress_log = 'Tar';
		if ( '.tar' != $compress ) {// WPCS:loose comparison ok.
			$this->_compress = true;
			if ( '.tar.gz' == $compress || '.sql.gz' == $compress ) {// WPCS:loose comparison ok.
				$this->compress_log   = 'Tar.Gz';
				$this->_compress_type = 'gz';
			} else {
				$this->compress_log   = 'Tar.Bz2';
				$this->_compress_type = 'bz2';
			}
		}
		$this->_tarname = $p_tarname;
		if ( $this->_compress ) { // assert zlib or bz2 extension support .
			if ( 'gz' == $this->_compress_type ) {// WPCS:loose comparison ok.
				$extname = 'zlib';
			} else {
				if ( 'bz2' == $this->_compress_type ) {// WPCS:loose comparison ok.
					$extname = 'bz2';
				}
			}

			if ( ! extension_loaded( $extname ) ) {
				PEAR::loadExtension( $extname );
			}
			if ( ! extension_loaded( $extname ) ) {
				$this->_error(
					"The extension '$extname' couldn't be found.\n" .
					'Please make sure your version of PHP was built ' .
					"with '$extname' support.\n"
				);
				return false;
			}
		}

		if ( version_compare( PHP_VERSION, '5.5.0-dev' ) < 0 ) {
			$this->_fmt = 'a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/' .
			'a8checksum/a1typeflag/a100link/a6magic/a2version/' .
			'a32uname/a32gname/a8devmajor/a8devminor/a131prefix';
		} else {
			$this->_fmt = 'Z100filename/Z8mode/Z8uid/Z8gid/Z12size/Z12mtime/' .
			'Z8checksum/Z1typeflag/Z100link/Z6magic/Z2version/' .
			'Z32uname/Z32gname/Z8devmajor/Z8devminor/Z131prefix';
		}
	}
	/**
	 * This function is used to destruct .
	 */
	public function __destruct() {
		$this->_close();
		// ----- Look for a local copy to delete
		if ( '' != $this->_temp_tarname ) {// WPCS:loose comparison ok.
			@unlink( $this->_temp_tarname ); // @codingStandardsIgnoreLine.
		}
	}
	/**
	 * This method creates the archive file and add the files / directories
	 * that are listed in $p_filelist.
	 * If a file with the same name exist and is writable, it is replaced
	 * by the new tar.
	 * The method return false and a PEAR error text.
	 * The $p_filelist parameter can be an array of string, each string
	 * representing a filename or a directory name with their path if
	 * needed. It can also be a single string with names separated by a
	 * single blank.
	 * For each directory added in the archive, the files and
	 * sub-directories are also added.
	 * See also createModify() method for more details.
	 *
	 * @param array $p_filelist An array of filenames and directory names, or a
	 *                   single string with names separated by a single
	 *                   blank space.
	 * @param array $exclude_file_list .
	 * @param array $backup_file_path .
	 *
	 * @return true on success, false on error.
	 * @see   createModify()
	 */
	public function create( $p_filelist, $exclude_file_list = '', $backup_file_path = '' ) {
		$this->zip_microtime_start = microtime( true );
		return $this->createModify( $p_filelist, '', '', $exclude_file_list, $backup_file_path );
	}
	/**
	 * This function is used to set destination of the backup.
	 *
	 * @param string $file .
	 */
	public function backup_destination_backup_bank( $file ) {
		switch ( $this->backup_destination ) {
			case 'dropbox':
				$backup_dest = 'Dropbox';
				$this->cloud = 2;
				break;

			case 'email':
				$backup_dest = 'Email';
				break;

			case 'ftp':
				$backup_dest = 'Ftp';
				$this->cloud = 2;
				break;

			case 'onedrive':
				$backup_dest = 'OneDrive';
				if ( PHP_VERSION >= '5.5.0' ) {
					$this->cloud = 2;
				}
				break;

			case 'google_drive':
				$backup_dest = 'Google Drive';
				$this->cloud = 2;
				break;

			case 'amazons3':
				$backup_dest = 'Amazon S3';
				$this->cloud = 2;
				break;

			case 'rackspace':
				$backup_dest = 'Rackspace';
				$this->cloud = 2;
				break;

			case 'azure':
				$backup_dest = 'Microsoft Azure';
				$this->cloud = 2;
				break;
		}
		$this->backup_bank_log( "Starting Sending <b>$file Backup</b> to <b>$backup_dest</b>.\r\n" );
	}
	/**
	 * This function is used to Extract.
	 *
	 * @param string $p_path .
	 * @param bool   $p_preserve .
	 * @return bool
	 */
	public function extract( $p_path = '', $p_preserve = false ) {
		return $this->extractModify( $p_path, '', $p_preserve );
	}
	/**
	 * This method creates the archive file and add the files / directories
	 * that are listed in $p_filelist.
	 * If the file already exists and is writable, it is replaced by the
	 * new tar. It is a create and not an add. If the file exists and is
	 * read-only or is a directory it is not replaced. The method return
	 * false and a PEAR error text.
	 * The $p_filelist parameter can be an array of string, each string
	 * representing a filename or a directory name with their path if
	 * needed. It can also be a single string with names separated by a
	 * single blank.
	 * The path indicated in $p_remove_dir will be removed from the
	 * memorized path of each file / directory listed when this path
	 * exists. By default nothing is removed (empty path "")
	 * The path indicated in $p_add_dir will be added at the beginning of
	 * the memorized path of each file / directory listed. However it can
	 * be set to empty "". The adding of a path is done after the removing
	 * of path.
	 * The path add/remove ability enables the user to prepare an archive
	 * for extraction in a different path than the origin files are.
	 * See also addModify() method for file adding properties.
	 *
	 * @param array  $p_filelist An array of filenames and directory names,
	 *                                        or a single string with names separated by
	 *                                        a single blank space.
	 * @param string $p_add_dir A string which contains a path to be added
	 *                                       to the memorized path of each element in
	 *                                       the list.
	 * @param string $p_remove_dir A string which contains a path to be
	 *                                       removed from the memorized path of each
	 *                                       element in the list, when relevant.
	 * @param string $exclude_file_list .
	 * @param string $backup_file_path .
	 * @return boolean true on success, false on error.
	 * @see addModify()
	 */
	public function createModify( $p_filelist, $p_add_dir, $p_remove_dir = '', $exclude_file_list = '', $backup_file_path = '' ) {
		$v_result = true;

		if ( ! $this->_openWrite() ) {
			return false;
		}

		if ( '' != $p_filelist ) { // WPCS:loose comparison ok.
			if ( is_array( $p_filelist ) ) {
				$path_name = WP_CONTENT_DIR;
				$v_list    = $p_filelist;
			} elseif ( is_string( $p_filelist ) ) {
				$path_name = $p_filelist;
				$v_list    = explode( $this->_separator, $p_filelist );
			} else {
				$this->_cleanFile();
				$this->_error( 'Invalid file list' );
				return false;
			}
			if ( '.sql.gz' != $this->file_compress_type ) { // WPCS:loose comparison ok.
				$this->_countDirectories( $v_list, $exclude_file_list );
				$this->count_files = 0;
				if ( 'complete_backup' == $this->backup_type || 'only_filesystem' == $this->backup_type || 'only_wp_content_folder' == $this->backup_type ) { // WPCS:loose comparison ok.
					$skipped_dir_name = str_replace( realpath( dirname( ABSPATH ) ) . '\\', '', realpath( BACKUP_BANK_BACKUPS_DIR ) );
					$this->backup_bank_log( 'Directory Path <b>' . $skipped_dir_name . "</b> has been Skipped.\r\n" );
				}
				$this->backup_bank_log( '<b>' . $this->total_files_directories . ' </b>Files of size <b>' . round( $this->total_file_size / 1048576, 1 ) . " Mb</b> are the total entities for the Tar file.\r\n" );
			}

			$message          = '{' . "\r\n";
			$message         .= '"name": "WP Backup Bank"' . "\r\n";
			$message         .= '}';
			$json_file_to_put = dirname( $this->tar_file_fullpath ) . '/' . $this->archive_name . '-' . $this->backup_type . '.json';
			file_put_contents( $json_file_to_put, $message ); // @codingStandardsIgnoreLine.

			if ( is_array( $json_file_to_put ) ) {
				$json_file = $json_file_to_put;
			} elseif ( is_string( $json_file_to_put ) ) {
				$json_file = explode( $this->_separator, $json_file_to_put );
			}
			unlink( $json_file_to_put ); // @codingStandardsIgnoreLine.

			if ( '' != $backup_file_path ) { // WPCS:loose comparison ok.
				if ( is_array( $backup_file_path ) ) {
					$v_list_db = $backup_file_path;
				} elseif ( is_string( $backup_file_path ) ) {
					$v_list_db = explode( $this->_separator, $backup_file_path );
				}
				$this->backup_bank_log( 'Adding Compressed Sql Database File <b>' . $this->database_file_name . '</b> to <b>' . $this->tar_file_name . "</b>.\r\n" );
				$this->_addList( $v_list_db, '', pathinfo( $backup_file_path, PATHINFO_DIRNAME ), '' );
				@unlink( $backup_file_path ); // @codingStandardsIgnoreLine.
			}
			$this->size_of_file = 0;
			$v_result           = $this->_addList( $v_list, $p_add_dir, $p_remove_dir, $path_name, $exclude_file_list );
		}
		if ( $v_result ) {
			if ( 'only_database' != $this->backup_type ) { // WPCS:loose comparison ok.
				$this->backup_percentage = 100;
				$this->kbsize            = round( filesize( $this->tar_file_fullpath ) / 1048576, 1 );
				if ( 0 != $this->timetaken ) { // WPCS:loose comparison ok.
					$zip_creating_rate = round( $this->kbsize / $this->timetaken, 1 );
				} else {
					$zip_creating_rate = round( $this->kbsize, 1 );
				}
				if ( '.sql.gz' != $this->file_compress_type ) { // WPCS:loose comparison ok.
					if ( 'local_folder' == $this->backup_destination ) { // WPCS:loose comparison ok.
						$this->status = 'completed_successfully';
						$this->backup_bank_log( 'Total Size on Disk : <b>' . round( $this->kbsize, 1 ) . " Mb</b> Transferred @ <b>$zip_creating_rate Mb/s</b>.<br/>Completed Backup Successfully.\r\n" );
					} else {
						if ( 'schedule' == $this->execution ) { // WPCS:loose comparison ok.
							$this->status = 'completed_successfully';
						} else {
							$this->status = 'completed';
						}
						$this->backup_bank_log( 'Total Size on Disk : <b>' . round( $this->kbsize, 1 ) . " Mb</b> Transferred @ <b>$zip_creating_rate Mb/s</b>.<br/>Completed Backup Successfully.\r\n" );
						$this->backup_destination_backup_bank( $this->backup_file_name );
					}
				}
			}

			$this->_writeFooter();
			$this->_close();
		} else {
			$this->_cleanFile();
		}
		return $v_result;
	}
	/**
	 * This method add the files / directories listed in $p_filelist at the
	 * end of the existing archive. If the archive does not yet exists it
	 * is created.
	 * The $p_filelist parameter can be an array of string, each string
	 * representing a filename or a directory name with their path if
	 * needed. It can also be a single string with names separated by a
	 * single blank.
	 * The path indicated in $p_remove_dir will be removed from the
	 * memorized path of each file / directory listed when this path
	 * exists. By default nothing is removed (empty path "")
	 * The path indicated in $p_add_dir will be added at the beginning of
	 * the memorized path of each file / directory listed. However it can
	 * be set to empty "". The adding of a path is done after the removing
	 * of path.
	 * The path add/remove ability enables the user to prepare an archive
	 * for extraction in a different path than the origin files are.
	 * If a file/dir is already in the archive it will only be added at the
	 * end of the archive. There is no update of the existing archived
	 * file/dir. However while extracting the archive, the last file will
	 * replace the first one. This results in a none optimization of the
	 * archive size.
	 * If a file/dir does not exist the file/dir is ignored. However an
	 * error text is send to PEAR error.
	 * If a file/dir is not readable the file/dir is ignored. However an
	 * error text is send to PEAR error.
	 *
	 * @param array  $p_filelist An array of filenames and directory
	 *                                        names, or a single string with names
	 *                                        separated by a single blank space.
	 * @param string $p_add_dir A string which contains a path to be
	 *                                       added to the memorized path of each
	 *                                       element in the list.
	 * @param string $p_remove_dir A string which contains a path to be
	 *                                       removed from the memorized path of
	 *                                       each element in the list, when
	 *                                       relevant.
	 *
	 * @return true on success, false on error.
	 */
	public function addModify( $p_filelist, $p_add_dir, $p_remove_dir = '' ) {

		$v_result = true;

		if ( ! $this->_isArchive() ) {
			$v_result = $this->createModify(
				$p_filelist, $p_add_dir, $p_remove_dir
			);
		} else {
			if ( is_array( $p_filelist ) ) {
				$v_list = $p_filelist;
			} elseif ( is_string( $p_filelist ) ) {
				$v_list = explode( $this->_separator, $p_filelist );
			} else {
				$this->_error( 'Invalid file list' );
				return false;
			}
			$v_result = $this->_append( $v_list, $p_add_dir, $p_remove_dir );
		}
		return $v_result;
	}
	/**
	 * This method extract all the content of the archive in the directory
	 * indicated by $p_path. When relevant the memorized path of the
	 * files/dir can be modified by removing the $p_remove_path path at the
	 * beginning of the file/dir path.
	 * While extracting a file, if the directory path does not exists it is
	 * created.
	 * While extracting a file, if the file already exists it is replaced
	 * without looking for last modification date.
	 * While extracting a file, if the file already exists and is write
	 * protected, the extraction is aborted.
	 * While extracting a file, if a directory with the same name already
	 * exists, the extraction is aborted.
	 * While extracting a directory, if a file with the same name already
	 * exists, the extraction is aborted.
	 * While extracting a file/directory if the destination directory exist
	 * and is write protected, or does not exist but can not be created,
	 * the extraction is aborted.
	 * If after extraction an extracted file does not show the correct
	 * stored file size, the extraction is aborted.
	 * When the extraction is aborted, a PEAR error text is set and false
	 * is returned. However the result can be a partial extraction that may
	 * need to be manually cleaned.
	 *
	 * @param string  $p_path The path of the directory where the
	 *                                         files/dir need to by extracted.
	 * @param string  $p_remove_path Part of the memorized path that can be
	 *                                         removed if present at the beginning of
	 *                                         the file/dir path.
	 * @param boolean $p_preserve Preserve user/group ownership of files .
	 *
	 * @return boolean true on success, false on error.
	 * @see   extractList()
	 */
	public function extractModify( $p_path, $p_remove_path, $p_preserve = false ) {
		$v_result      = true;
		$v_list_detail = array();

		if ( $v_result = $this->_openRead() ) { // @codingStandardsIgnoreLine.
			$v_result = $this->_extractList(
				$p_path, $v_list_detail, 'complete', 0, $p_remove_path, $p_preserve
			);
			$this->_close();
		}
		return $v_result;
	}
	/**
	 * This function is used to show error messsage .
	 *
	 * @param string $p_message .
	 */
	public function _error( $p_message ) { // @codingStandardsIgnoreLine.
		$this->status       = 'terminated';
		$this->error_object = $this->raiseError( $p_message );
		$this->backup_bank_log( 'Error: ' . $p_message . "\r\n" );
	}
	/**
	 * This function is used to show warning message.
	 *
	 * @param string $p_message .
	 */
	public function _warning( $p_message ) { // @codingStandardsIgnoreLine.
		$this->error_object = $this->raiseError( $p_message );
		$this->backup_bank_log( 'Warning: ' . $p_message . "\r\n" );
	}
	/**
	 * This function is to archieve file.
	 *
	 * @param string $p_filename .
	 * @return bool
	 */
	public function _isArchive( $p_filename = null ) { // @codingStandardsIgnoreLine.
		if ( null == $p_filename ) { // WPCS:loose comparison ok.
			$p_filename = $this->_tarname;
		}
		clearstatcache();
		return @is_file( $p_filename ) && ! @is_link( $p_filename ); // @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to open file in write mode.
	 *
	 * @return bool
	 */
	public function _openWrite() { // @codingStandardsIgnoreLine.
		if ( 'gz' == $this->_compress_type && function_exists( 'gzopen' ) ) { // WPCS:loose comparison ok.
			$this->_file = @gzopen( $this->_tarname, 'wb9' ); // @codingStandardsIgnoreLine.
		} else {
			if ( 'bz2' == $this->_compress_type && function_exists( 'bzopen' ) ) { // WPCS:loose comparison ok.
				$this->_file = @bzopen( $this->_tarname, 'w' ); // @codingStandardsIgnoreLine.
			} else {
				if ( 'none' == $this->_compress_type ) { // WPCS:loose comparison ok.
					$this->_file = @fopen( $this->_tarname, 'wb' ); // @codingStandardsIgnoreLine.
				} else {
					$this->_error(
						'Unknown or missing compression type ('
						. $this->_compress_type . ')'
					);
					return false;
				}
			}
		}

		if ( 0 == $this->_file ) { // WPCS:loose comparison ok.
			$this->_error(
				'Unable to open in write mode "'
				. $this->_tarname . '"'
			);
			return false;
		}
		return true;
	}
	/**
	 * This function is used to open file in read mode.
	 *
	 * @return bool
	 */
	public function _openRead() { // @codingStandardsIgnoreLine.
		if ( strtolower( substr( $this->_tarname, 0, 7 ) ) == 'http://' ) { // WPCS:loose comparison ok.
			// ----- Look if a local copy need to be done .
			if ( '' == $this->_temp_tarname ) { // WPCS:loose comparison ok.
				$this->_temp_tarname = uniqid( 'tar' ) . '.tmp';
				if ( ! $v_file_from = @fopen( $this->_tarname, 'rb' ) ) { // @codingStandardsIgnoreLine.
					$this->_error(
						'Unable to open in read mode "'
						. $this->_tarname . '"'
					);
					$this->_temp_tarname = '';
					return false;
				}
				if ( ! $v_file_to = @fopen( $this->_temp_tarname, 'wb' ) ) { // @codingStandardsIgnoreLine.
					$this->_error(
						'Unable to open in write mode "'
						. $this->_temp_tarname . '"'
					);
					$this->_temp_tarname = '';
					return false;
				}
				while ( $v_data = @fread( $v_file_from, 1024 ) ) { // @codingStandardsIgnoreLine.
					@fwrite( $v_file_to, $v_data ); // @codingStandardsIgnoreLine.
				}
				@fclose( $v_file_from ); // @codingStandardsIgnoreLine.
				@fclose( $v_file_to ); // @codingStandardsIgnoreLine.
			}

			// ----- File to open if the local copy
			$v_filename = $this->_temp_tarname;
		} else {
			// ----- File to open if the normal Tar file
			$v_filename = $this->_tarname;
		}

		if ( 'gz' == $this->_compress_type && function_exists( 'gzopen' ) ) { // WPCS:loose comparison ok.
			$this->_file = @gzopen( $v_filename, 'rb' ); // @codingStandardsIgnoreLine.
		} else {
			if ( 'bz2' == $this->_compress_type && function_exists( 'bzopen' ) ) { // WPCS:loose comparison ok.
				$this->_file = @bzopen( $v_filename, 'r' ); // @codingStandardsIgnoreLine.
			} else {
				if ( 'none' == $this->_compress_type ) { // WPCS:loose comparison ok.
					$this->_file = @fopen( $v_filename, 'rb' ); // @codingStandardsIgnoreLine.
				} else {
					$this->_error(
						'Unknown or missing compression type ('
						. $this->_compress_type . ')'
					);
					return false;
				}
			}
		}

		if ( 0 == $this->_file ) { // WPCS:loose comparison ok.
			$this->_error( 'Unable to open in read mode "' . $v_filename . '"' );
			return false;
		}
		return true;
	}
	/**
	 * This function is used to open file for both read and write mode.
	 *
	 * @return bool
	 */
	public function _openReadWrite() { // @codingStandardsIgnoreLine
		if ( 'gz' == $this->_compress_type ) { // WPCS:loose comparison ok.
			$this->_file = @gzopen( $this->_tarname, 'r+b' ); // @codingStandardsIgnoreLine.
		} else {
			if ( 'bz2' == $this->_compress_type ) { // WPCS:loose comparison ok.
				$this->_error(
					'Unable to open bz2 in read/write mode "'
					. $this->_tarname . '" (limitation of bz2 extension)'
				);
				return false;
			} else {
				if ( 'none' == $this->_compress_type ) { // WPCS:loose comparison ok.
					$this->_file = @fopen( $this->_tarname, 'r+b' ); // @codingStandardsIgnoreLine.
				} else {
					$this->_error(
						'Unknown or missing compression type ('
						. $this->_compress_type . ')'
					);
					return false;
				}
			}
		}

		if ( 0 == $this->_file ) { // WPCS:loose comparison ok.
			$this->_error(
				'Unable to open in read/write mode "'
				. $this->_tarname . '"'
			);
			return false;
		}
		return true;
	}
	/**
	 * This function is to close file.
	 *
	 * @return bool
	 */
	public function _close() { // @codingStandardsIgnoreLine.
		if ( is_resource( $this->_file ) ) {
			if ( 'gz' == $this->_compress_type ) { // WPCS:loose comparison ok.
				@gzclose( $this->_file ); // @codingStandardsIgnoreLine.
			} else {
				if ( 'bz2' == $this->_compress_type ) { // WPCS:loose comparison ok.
					@bzclose( $this->_file ); // @codingStandardsIgnoreLine.
				} else {
					if ( 'none' == $this->_compress_type ) { // WPCS:loose comparison ok.
						@fclose( $this->_file ); // @codingStandardsIgnoreLine.
					} else {
						$this->_error(
							'Unknown or missing compression type ('
							. $this->_compress_type . ')'
						);
					}
				}
			}
			$this->_file = 0;
		}

		// ----- Look if a local copy need to be erase
		// Note that it might be interesting to keep the url for a time : ToDo
		if ( '' != $this->_temp_tarname ) { // WPCS:loose comparison ok.
			@unlink( $this->_temp_tarname ); // @codingStandardsIgnoreLine.
			$this->_temp_tarname = '';
		}

		return true;
	}
	/**
	 * This function is used to clean file.
	 *
	 * @return bool
	 */
	public function _cleanFile() { // @codingStandardsIgnoreLine.
		$this->_close();

		// ----- Look for a local copy
		if ( '' != $this->_temp_tarname ) { // WPCS:loose comparison ok.
			// ----- Remove the local copy but not the remote tarname .
			@unlink( $this->_temp_tarname ); // @codingStandardsIgnoreLine.
			$this->_temp_tarname = '';
		} else {
			// ----- Remove the local tarname file
			@unlink( $this->_tarname ); // @codingStandardsIgnoreLine.
		}
		$this->_tarname = '';

		return true;
	}
	/**
	 * This function is used to write the block.
	 *
	 * @param mixed   $p_binary_data .
	 * @param integer $p_len .
	 * @return bool
	 */
	public function _writeBlock( $p_binary_data, $p_len = null ) { // @codingStandardsIgnoreLine.
		if ( is_resource( $this->_file ) ) {
			if ( null === $p_len ) {
				if ( 'gz' == $this->_compress_type ) { // WPCS:loose comparison ok.
					@gzputs( $this->_file, $p_binary_data ); // @codingStandardsIgnoreLine.
				} else {
					if ( 'bz2' == $this->_compress_type ) { // WPCS:loose comparison ok.
						@bzwrite( $this->_file, $p_binary_data ); // @codingStandardsIgnoreLine.
					} else {
						if ( 'none' == $this->_compress_type ) { // WPCS:loose comparison ok.
							@fputs( $this->_file, $p_binary_data ); // @codingStandardsIgnoreLine.
						} else {
							$this->_error(
								'Unknown or missing compression type ('
								. $this->_compress_type . ')'
							);
						}
					}
				}
			} else {
				if ( 'gz' == $this->_compress_type ) { // WPCS:loose comparison ok.
					@gzputs( $this->_file, $p_binary_data, $p_len ); // @codingStandardsIgnoreLine.
				} else {
					if ( 'bz2' == $this->_compress_type ) { // WPCS:loose comparison ok.
						@bzwrite( $this->_file, $p_binary_data, $p_len ); // @codingStandardsIgnoreLine.
					} else {
						if ( 'none' == $this->_compress_type ) { // WPCS:loose comparison ok.
							@fputs( $this->_file, $p_binary_data, $p_len ); // @codingStandardsIgnoreLine.
						} else {
							$this->_error(
								'Unknown or missing compression type ('
								. $this->_compress_type . ')'
							);
						}
					}
				}
			}
		}
		return true;
	}
	/**
	 * This function is used to read block.
	 *
	 * @return null|string
	 */
	public function _readBlock() { // @codingStandardsIgnoreLine.
		$v_block = null;
		if ( is_resource( $this->_file ) ) {
			if ( 'gz' == $this->_compress_type ) { // WPCS:loose comparison ok.
				$v_block = @gzread( $this->_file, 512 ); // @codingStandardsIgnoreLine.
			} else {
				if ( 'bz2' == $this->_compress_type ) { // WPCS:loose comparison ok.
					$v_block = @bzread( $this->_file, 512 ); // @codingStandardsIgnoreLine.
				} else {
					if ( 'none' == $this->_compress_type ) { // WPCS:loose comparison ok.
						$v_block = @fread( $this->_file, 512 ); // @codingStandardsIgnoreLine.
					} else {
						$this->_error(
							'Unknown or missing compression type ('
							. $this->_compress_type . ')'
						);
					}
				}
			}
		}
		return $v_block;
	}
	/**
	 * This function is used to jump block .
	 *
	 * @param null $p_len .
	 * @return bool
	 */
	public function _jumpBlock( $p_len = null ) { // @codingStandardsIgnoreLine.
		if ( is_resource( $this->_file ) ) {
			if ( null === $p_len ) {
				$p_len = 1;
			}

			if ( 'gz' == $this->_compress_type ) { // WPCS:loose comparison ok.
				@gzseek( $this->_file, gztell( $this->_file ) + ( $p_len * 512 ) ); // @codingStandardsIgnoreLine
			} else {
				if ( 'bz2' == $this->_compress_type ) { // WPCS:loose comparison ok.
					// ----- Replace missing bztell() and bzseek() .
					for ( $i = 0; $i < $p_len; $i++ ) {
						$this->_readBlock();
					}
				} else {
					if ( 'none' == $this->_compress_type ) { // WPCS:loose comparison ok.
						@fseek( $this->_file, $p_len * 512, SEEK_CUR ); // @codingStandardsIgnoreLine.
					} else {
						$this->_error(
							'Unknown or missing compression type ('
							. $this->_compress_type . ')'
						);
					}
				}
			}
		}
		return true;
	}
	/**
	 * This function is used to write footer.
	 *
	 * @return bool
	 */
	public function _writeFooter() { // @codingStandardsIgnoreLine.
		if ( is_resource( $this->_file ) ) {
			// ----- Write the last 0 filled block for end of archive
			$v_binary_data = pack( 'a1024', '' );
			$this->_writeBlock( $v_binary_data );
		}
		return true;
	}
	/**
	 * This function is used to generate backup bank log.
	 *
	 * @param string $line .
	 */
	public function backup_bank_log( $line ) {
		$last_open_time = fileatime( $this->log_file_name );

		if ( $this->logfile_handle ) {
			// Record log file times relative to the backup start, if possible .
			$rtime               = microtime( true ) - $last_open_time;
			$this->timetaken     = $rtime;
			$this->log_timetaken = microtime( true ) - $last_open_time;
			fwrite( $this->logfile_handle, sprintf( '%08.03f', round( $rtime, 3 ) ) . ' ' . strip_tags( $line ) ); // @codingStandardsIgnoreLine
		}
		if ( 'only_database' != $this->backup_type ) { // WPCS:loose comparison ok.
			switch ( $this->backup_type ) {
				case 'complete_backup':
					$zipfiles_batched_count = '' == $this->total_files_size ? '74' : $this->total_files_size; // WPCS:loose comparison ok.
					$count_zipfiles_added   = '' == $this->files_size_added ? '1' : $this->files_size_added; // WPCS:loose comparison ok.
					if ( '' == $this->backup_percentage ) { // WPCS:loose comparison ok.
						$result  = ceil( $count_zipfiles_added / $zipfiles_batched_count * 74 );
						$result += $this->complete_percentage;
					} else {
						$result = $this->backup_percentage;
					}
					break;

				default:
					$zipfiles_batched_count = '' == $this->total_files_size ? 99 : $this->total_files_size; // WPCS:loose comparison ok.
					$count_zipfiles_added   = '' == $this->files_size_added ? 1 : $this->files_size_added; // WPCS:loose comparison ok.
					if ( '' == $this->backup_percentage ) { // WPCS:loose comparison ok.
						$result = ceil( $count_zipfiles_added / $zipfiles_batched_count * 99 );
					} else {
						$result = $this->backup_percentage;
					}
			}
			if ( 'manual' == $this->execution ) { // WPCS:loose comparison ok.
				if ( '' != $line ) { // WPCS:loose comparison ok.
					if ( 1 != $this->cloud ) { // WPCS:loose comparison ok.
						$result = 1;
					}
					$new_line = str_replace( "\r\n", '', $line );
					file_put_contents( $this->json_file_name, '' ); // @codingStandardsIgnoreLine.
					$message  = '{' . "\r\n";
					$message .= '"log": "' . $new_line . '",' . "\r\n";
					$message .= '"perc": ' . $result . ',' . "\r\n";
					$message .= '"status": "' . $this->status . '",' . "\r\n";
					$message .= '"cloud": ' . $this->cloud . "\r\n";
					$message .= '}';

					file_put_contents( $this->json_file_name, $message ); // @codingStandardsIgnoreLine.
				}
			}
		}
	}
	/**
	 * This function is used to count Directories.
	 *
	 * @param array $p_list .
	 * @param array $exclude_file_list .
	 */
	public function _countDirectories( $p_list, $exclude_file_list ) {// @codingStandardsIgnoreLine
		foreach ( $p_list as $v_filename ) {
			if ( realpath( $v_filename ) != realpath( BACKUP_BANK_BACKUPS_DIR ) ) { // WPCS:loose comparison ok.
				if ( realpath( $v_filename ) != realpath( $this->exclude_dir_path ) ) { // WPCS:loose comparison ok.
					if ( is_file( $v_filename ) ) {
						$exclude_ext = strstr( basename( $v_filename ), '.' );
						if ( is_array( $exclude_file_list ) ) {
							if ( ! in_array( $exclude_ext, $exclude_file_list ) ) { // @codingStandardsIgnoreLine.
								$this->total_file_size += filesize( $v_filename );
								$this->total_files_directories++;
							}
						}
					}
				}
			}
			if ( @is_dir( $v_filename ) && ! @is_link( $v_filename ) ) { // @codingStandardsIgnoreLine.
				if ( realpath( $v_filename ) != realpath( BACKUP_BANK_BACKUPS_DIR ) ) { // WPCS:loose comparison ok.
					if ( realpath( $v_filename ) != realpath( $this->exclude_dir_path ) ) { // WPCS:loose comparison ok.
						if ( ! ( $p_hdir = opendir( $v_filename ) ) ) { // @codingStandardsIgnoreLine.
							$this->_warning( "Directory '$v_filename' cannot be read" );
							continue;
						}
						while ( false !== ( $p_hitem = readdir( $p_hdir ) ) ) { // @codingStandardsIgnoreLine.
							if ( ( '.' != $p_hitem ) && ( '..' != $p_hitem ) ) { // WPCS:loose comparison ok.
								if ( '.' != $v_filename ) { // WPCS:loose comparison ok.
									$p_temp_list[0] = $v_filename . '/' . $p_hitem;
								} else {
									$p_temp_list[0] = $p_hitem;
								}
								$v_result = $this->_countDirectories( $p_temp_list, $exclude_file_list );
							}
						}
						unset( $p_temp_list );
						unset( $p_hdir );
						unset( $p_hitem );
					}
				}
			}
		}
		return true;
	}
	/**
	 * This function is used to add list .
	 *
	 * @param array  $p_list .
	 * @param string $p_add_dir .
	 * @param string $p_remove_dir .
	 * @param string $path_name  .
	 * @param array  $exclude_file_list .
	 * @return bool
	 */
	public function _addList( $p_list, $p_add_dir, $p_remove_dir, $path_name, $exclude_file_list = '' ) { // @codingStandardsIgnoreLine.
		$v_result = true;
		$v_header = array();
		if ( ! $this->_file ) {
			$this->_error( 'Invalid file descriptor' );
			return false;
		}

		if ( sizeof( $p_list ) == 0 ) { // @codingStandardsIgnoreLine.
			return true;
		}
		foreach ( $p_list as $v_filename ) {
			if ( is_file( $v_filename ) ) {
				$exclude_ext = strstr( basename( $v_filename ), '.' );
				if ( ! empty( $exclude_file_list ) ) {
					if ( in_array( $exclude_ext, $exclude_file_list ) ) { // @codingStandardsIgnoreLine.
						continue;
					}
				}
			}

			if ( realpath( $v_filename ) != realpath( BACKUP_BANK_BACKUPS_DIR ) ) { // WPCS:loose comparison ok.
				if ( is_file( $v_filename ) ) {
					$fsize = filesize( $v_filename );
					if ( $fsize > BACKUP_BANK_WARN_FILE_SIZE ) {
						$this->backup_bank_log( "File <b>$v_filename</b> of size <b>" . round( $fsize / 1048576, 1 ) . "Mb</b> has been Encountered.\r\n" );
					}
					$this->size_of_file += filesize( $v_filename );
					$this->count_files++;
					$this->files_size_added = round( $this->size_of_file / 1048576, 1 );
					$this->total_files_size = round( $this->total_file_size / 1048576, 1 );
					if ( $this->count_files % 100 == 0 || $this->count_files == $this->total_files_directories ) {// @codingStandardsIgnoreLine
						$this->backup_bank_log( $this->compress_log . ' Compression : <b>' . $this->count_files . '</b> Files out of <b>' . $this->total_files_directories . '</b> Files added on <b>' . $this->tar_file_name . '</b><br/> Completed (<b>' . round( $this->size_of_file / 1048576, 1 ) . 'Mb</b> out of <b>' . round( $this->total_file_size / 1048576, 1 ) . "Mb</b>).\r\n" );
					}
				}
				if ( ! $this->_addFile( $v_filename, $v_header, $p_add_dir, $p_remove_dir, null, $path_name ) ) {
					return false;
				}
			}
			if ( @is_dir( $v_filename ) && ! @is_link( $v_filename ) ) { // @codingStandardsIgnoreLine.
				if ( realpath( $v_filename ) != realpath( BACKUP_BANK_BACKUPS_DIR ) ) { // WPCS:loose comparison ok.
					if ( realpath( $v_filename ) != realpath( $this->exclude_dir_path ) ) { // WPCS:loose comparison ok.
						if ( ! ( $p_hdir = opendir( $v_filename ) ) ) { // @codingStandardsIgnoreLine.
							$this->_warning( "Directory '$v_filename' can not be read" );
							continue;
						}
						while ( false !== ( $p_hitem = readdir( $p_hdir ) ) ) { // @codingStandardsIgnoreLine.
							if ( ( '.' != $p_hitem ) && ( '..' != $p_hitem ) ) { // WPCS:loose comparison ok.
								if ( '.' != $v_filename ) { // WPCS:loose comparison ok.
									$p_temp_list[0] = $v_filename . '/' . $p_hitem;
								} else {
									$p_temp_list[0] = $p_hitem;
								}
								$v_result = $this->_addList(
									$p_temp_list, $p_add_dir, $p_remove_dir, $path_name, $exclude_file_list
								);
							}
						}

						unset( $p_temp_list );
						unset( $p_hdir );
						unset( $p_hitem );
					}
				}
			}
		}

		return $v_result;
	}
	/**
	 * This function is used to add file.
	 *
	 * @param string $p_filename .
	 * @param mixed  $p_header .
	 * @param string $p_add_dir .
	 * @param string $p_remove_dir .
	 * @param null   $v_stored_filename .
	 * @param string $path_name .
	 * @return bool
	 */
	public function _addFile( $p_filename, &$p_header, $p_add_dir, $p_remove_dir, $v_stored_filename = null, $path_name ) { // @codingStandardsIgnoreLine.
		if ( ! $this->_file ) {
			$this->_error( 'Invalid file descriptor' );
			return false;
		}

		if ( '' == $p_filename ) { // WPCS:loose comparison ok.
			$this->_error( 'Invalid file name' );
			return false;
		}

		if ( is_null( $v_stored_filename ) ) {
			// ----- Calculate the stored filename
			// $p_filename = $this->_translateWinPath($p_filename, false,$path_name);
			$v_stored_filename = $p_filename;

			if ( strcmp( $p_filename, $p_remove_dir ) == 0 ) { // WPCS:loose comparison ok.
				return true;
			}

			if ( '' != $p_remove_dir ) { // WPCS:loose comparison ok.
				if ( substr( $p_remove_dir, -1 ) != '/' ) { // WPCS:loose comparison ok.
					$p_remove_dir .= '/';
				}

				if ( substr( $p_filename, 0, strlen( $p_remove_dir ) ) == $p_remove_dir ) { // WPCS:loose comparison ok.
					$v_stored_filename = substr( $p_filename, strlen( $p_remove_dir ) );
				}
			}

			$v_stored_filename = $this->_translateWinPath( $v_stored_filename, true, $path_name );
			if ( '' != $p_add_dir ) { // WPCS:loose comparison ok.
				if ( substr( $p_add_dir, -1 ) == '/' ) { // WPCS:loose comparison ok.
					$v_stored_filename = $p_add_dir . $v_stored_filename;
				} else {
					$v_stored_filename = $p_add_dir . '/' . $v_stored_filename;
				}
			}

			$v_stored_filename = $this->_pathReduction( $v_stored_filename );
		}

		if ( $this->_isArchive( $p_filename ) ) {
			if ( ( $v_file = @fopen( $p_filename, 'rb' ) ) == 0 ) { // @codingStandardsIgnoreLine.
				$this->_warning(
					"Unable to open file '" . $p_filename
					. "' in binary read mode"
				);
				return true;
			}
			if ( '.sql.gz' != $this->file_compress_type ) { // WPCS:loose comparison ok.
				if ( ! $this->_writeHeader( $p_filename, $v_stored_filename ) ) {
					return false;
				}
			}

			while ( ( $v_buffer = fread( $v_file, 512 ) ) != '' ) { // @codingStandardsIgnoreLine.
				$v_binary_data = pack( 'a512', "$v_buffer" );
				$this->_writeBlock( $v_binary_data );
			}
			fclose( $v_file ); // @codingStandardsIgnoreLine
		} else {
			// ----- Only header for dir
			if ( realpath( ABSPATH ) != $p_filename ) { // WPCS:loose comparison ok.
				if ( ! $this->_writeHeader( $p_filename, $v_stored_filename ) ) {
					return false;
				}
			}
		}

		return true;
	}
	/**
	 * This function is used to write header .
	 *
	 * @param string $p_filename .
	 * @param string $p_stored_filename .
	 * @return bool
	 */
	public function _writeHeader( $p_filename, $p_stored_filename ) { // @codingStandardsIgnoreLine.
		if ( '' == $p_stored_filename ) { // WPCS:loose comparison ok.
			$p_stored_filename = $p_filename;
		}
		$v_reduce_filename = $this->_pathReduction( $p_stored_filename );
		if ( strlen( $v_reduce_filename ) > 99 ) {
			if ( ! $this->_writeLongHeader( $v_reduce_filename ) ) {
				return false;
			}
		}

		$v_info  = lstat( $p_filename );
		$v_uid   = sprintf( '%07s', DecOct( $v_info[4] ) );
		$v_gid   = sprintf( '%07s', DecOct( $v_info[5] ) );
		$v_perms = sprintf( '%07s', DecOct( $v_info['mode'] & 000777 ) );

		$v_mtime = sprintf( '%011s', DecOct( $v_info['mtime'] ) );

		$v_linkname = '';

		if ( @is_link( $p_filename ) ) { // @codingStandardsIgnoreLine.
			$v_typeflag = '2';
			$v_linkname = readlink( $p_filename );
			$v_size     = sprintf( '%011s', DecOct( 0 ) );
		} elseif ( @is_dir( $p_filename ) ) { // @codingStandardsIgnoreLine.
			$v_typeflag = '5';
			$v_size     = sprintf( '%011s', DecOct( 0 ) );
		} else {
			$v_typeflag = '0';
			clearstatcache();
			$v_size = sprintf( '%011s', DecOct( $v_info['size'] ) );
		}

		$v_magic   = 'ustar ';
		$v_version = ' ';

		if ( function_exists( 'posix_getpwuid' ) ) {
			$userinfo  = posix_getpwuid( $v_info[4] );
			$groupinfo = posix_getgrgid( $v_info[5] );

			$v_uname = $userinfo['name'];
			$v_gname = $groupinfo['name'];
		} else {
			$v_uname = '';
			$v_gname = '';
		}

		$v_devmajor = '';
		$v_devminor = '';
		$v_prefix   = '';

		$v_binary_data_first = pack(
			'a100a8a8a8a12a12', $v_reduce_filename, $v_perms, $v_uid, $v_gid, $v_size, $v_mtime
		);
		$v_binary_data_last  = pack(
			'a1a100a6a2a32a32a8a8a155a12', $v_typeflag, $v_linkname, $v_magic, $v_version, $v_uname, $v_gname, $v_devmajor, $v_devminor, $v_prefix, ''
		);

		// ----- Calculate the checksum
		$v_checksum = 0;
		// ..... First part of the header
		for ( $i = 0; $i < 148; $i++ ) {
			$v_checksum += ord( substr( $v_binary_data_first, $i, 1 ) );
		}
		// ..... Ignore the checksum value and replace it by " " (space)
		for ( $i = 148; $i < 156; $i++ ) {
			$v_checksum += ord( ' ' );
		}
		// ..... Last part of the header
		for ( $i = 156, $j = 0; $i < 512; $i++, $j++ ) { // @codingStandardsIgnoreLine.
			$v_checksum += ord( substr( $v_binary_data_last, $j, 1 ) );
		}
		// ----- Write the first 148 bytes of the header in the archive
		$this->_writeBlock( $v_binary_data_first, 148 );
		// ----- Write the calculated checksum
		$v_checksum    = sprintf( '%06s ', DecOct( $v_checksum ) );
		$v_binary_data = pack( 'a8', $v_checksum );
		$this->_writeBlock( $v_binary_data, 8 );
		// ----- Write the last 356 bytes of the header in the archive
		$this->_writeBlock( $v_binary_data_last, 356 );

		return true;
	}
	/**
	 * This function is used to write header block .
	 *
	 * @param string $p_filename .
	 * @param int    $p_size .
	 * @param int    $p_mtime .
	 * @param int    $p_perms .
	 * @param string $p_type .
	 * @param int    $p_uid .
	 * @param int    $p_gid .
	 * @return bool
	 */
	public function _writeHeaderBlock( // @codingStandardsIgnoreLine.
	$p_filename, $p_size, $p_mtime = 0, $p_perms = 0, $p_type = '', $p_uid = 0, $p_gid = 0
	) {
		$p_filename = $this->_pathReduction( $p_filename );

		if ( strlen( $p_filename ) > 99 ) {
			if ( ! $this->_writeLongHeader( $p_filename ) ) {
				return false;
			}
		}

		if ( '5' == $p_type ) { // WPCS:loose comparison ok.
			$v_size = sprintf( '%011s', DecOct( 0 ) );
		} else {
			$v_size = sprintf( '%011s', DecOct( $p_size ) );
		}

		$v_uid      = sprintf( '%07s', DecOct( $p_uid ) );
		$v_gid      = sprintf( '%07s', DecOct( $p_gid ) );
		$v_perms    = sprintf( '%07s', DecOct( $p_perms & 000777 ) );
		$v_mtime    = sprintf( '%11s', DecOct( $p_mtime ) );
		$v_linkname = '';
		$v_magic    = 'ustar ';
		$v_version  = ' ';

		if ( function_exists( 'posix_getpwuid' ) ) {
			$userinfo  = posix_getpwuid( $p_uid );
			$groupinfo = posix_getgrgid( $p_gid );

			$v_uname = $userinfo['name'];
			$v_gname = $groupinfo['name'];
		} else {
			$v_uname = '';
			$v_gname = '';
		}

		$v_devmajor = '';
		$v_devminor = '';
		$v_prefix   = '';

		$v_binary_data_first = pack(
			'a100a8a8a8a12A12', $p_filename, $v_perms, $v_uid, $v_gid, $v_size, $v_mtime
		);
		$v_binary_data_last  = pack(
			'a1a100a6a2a32a32a8a8a155a12', $p_type, $v_linkname, $v_magic, $v_version, $v_uname, $v_gname, $v_devmajor, $v_devminor, $v_prefix, ''
		);

		// ----- Calculate the checksum
		$v_checksum = 0;
		// ..... First part of the header
		for ( $i = 0; $i < 148; $i++ ) {
			$v_checksum += ord( substr( $v_binary_data_first, $i, 1 ) );
		}
		// ..... Ignore the checksum value and replace it by " " (space)
		for ( $i = 148; $i < 156; $i++ ) {
			$v_checksum += ord( ' ' );
		}
		// ..... Last part of the header
		for ( $i = 156, $j = 0; $i < 512; $i++, $j++ ) { // @codingStandardsIgnoreLine.
			$v_checksum += ord( substr( $v_binary_data_last, $j, 1 ) );
		}

		// ----- Write the first 148 bytes of the header in the archive
		$this->_writeBlock( $v_binary_data_first, 148 );
		// ----- Write the calculated checksum
		$v_checksum    = sprintf( '%06s ', DecOct( $v_checksum ) );
		$v_binary_data = pack( 'a8', $v_checksum );
		$this->_writeBlock( $v_binary_data, 8 );
		// ----- Write the last 356 bytes of the header in the archive
		$this->_writeBlock( $v_binary_data_last, 356 );

		return true;
	}
	/**
	 * This function is used write long header .
	 *
	 * @param string $p_filename .
	 * @return bool
	 */
	public function _writeLongHeader( $p_filename ) { // @codingStandardsIgnoreLine.
		$v_size     = sprintf( '%11s ', DecOct( strlen( $p_filename ) ) );
		$v_typeflag = 'L';
		$v_linkname = '';
		$v_magic    = '';
		$v_version  = '';
		$v_uname    = '';
		$v_gname    = '';
		$v_devmajor = '';
		$v_devminor = '';
		$v_prefix   = '';

		$v_binary_data_first = pack(
			'a100a8a8a8a12a12', '././@LongLink', 0, 0, 0, $v_size, 0
		);
		$v_binary_data_last  = pack(
			'a1a100a6a2a32a32a8a8a155a12', $v_typeflag, $v_linkname, $v_magic, $v_version, $v_uname, $v_gname, $v_devmajor, $v_devminor, $v_prefix, ''
		);

		// ----- Calculate the checksum
		$v_checksum = 0;
		// ..... First part of the header
		for ( $i = 0; $i < 148; $i++ ) {
			$v_checksum += ord( substr( $v_binary_data_first, $i, 1 ) );
		}
		// ..... Ignore the checksum value and replace it by " " (space)
		for ( $i = 148; $i < 156; $i++ ) {
			$v_checksum += ord( ' ' );
		}
		// ..... Last part of the header
		for ( $i = 156, $j = 0; $i < 512; $i++, $j++ ) { // @codingStandardsIgnoreLine.
			$v_checksum += ord( substr( $v_binary_data_last, $j, 1 ) );
		}

		// ----- Write the first 148 bytes of the header in the archive
		$this->_writeBlock( $v_binary_data_first, 148 );

		// ----- Write the calculated checksum
		$v_checksum    = sprintf( '%06s ', DecOct( $v_checksum ) );
		$v_binary_data = pack( 'a8', $v_checksum );
		$this->_writeBlock( $v_binary_data, 8 );

		// ----- Write the last 356 bytes of the header in the archive
		$this->_writeBlock( $v_binary_data_last, 356 );

		// ----- Write the filename as content of the block
		$i = 0;
		while ( ( $v_buffer = substr( $p_filename, ( ( $i++ ) * 512 ), 512 ) ) != '' ) { // @codingStandardsIgnoreLine.
			$v_binary_data = pack( 'a512', "$v_buffer" );
			$this->_writeBlock( $v_binary_data );
		}
		return true;
	}
	/**
	 * This function is used to read headers.
	 *
	 * @param mixed $v_binary_data .
	 * @param mixed $v_header .
	 * @return bool
	 */
	public function _readHeader( $v_binary_data, &$v_header ) { // @codingStandardsIgnoreLine.
		if ( strlen( $v_binary_data ) == 0 ) { // WPCS:loose comparison ok.
			$v_header['filename'] = '';
			return true;
		}

		if ( strlen( $v_binary_data ) != 512 ) { // WPCS:loose comparison ok.
			$v_header['filename'] = '';
			$this->_error( 'Invalid block size : ' . strlen( $v_binary_data ) );
			return false;
		}

		if ( ! is_array( $v_header ) ) {
			$v_header = array();
		}
		// ----- Calculate the checksum
		$v_checksum = 0;
		// ..... First part of the header
		$v_binary_split = str_split( $v_binary_data );
		$v_checksum    += array_sum( array_map( 'ord', array_slice( $v_binary_split, 0, 148 ) ) );
		$v_checksum    += array_sum( array_map( 'ord', array( ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' ) ) );
		$v_checksum    += array_sum( array_map( 'ord', array_slice( $v_binary_split, 156, 512 ) ) );

		$v_data = unpack( $this->_fmt, $v_binary_data );

		if ( strlen( $v_data['prefix'] ) > 0 ) {
			$v_data['filename'] = "$v_data[prefix]/$v_data[filename]";
		}

		// ----- Extract the checksum
		$v_header['checksum'] = OctDec( trim( $v_data['checksum'] ) );
		if ( $v_header['checksum'] != $v_checksum ) { // WPCS:loose comparison ok.
			$v_header['filename'] = '';

			// ----- Look for last block (empty block)
			if ( ( 256 == $v_checksum ) && ( 0 == $v_header['checksum'] ) ) { // WPCS:loose comparison ok.
				return true;
			}

			$this->_error(
				'Invalid checksum for file "' . $v_data['filename']
				. '" : ' . $v_checksum . ' calculated, '
				. $v_header['checksum'] . ' expected'
			);
			return false;
		}

		// ----- Extract the properties
		$v_header['filename'] = rtrim( $v_data['filename'], "\0" );
		if ( $this->_maliciousFilename( $v_header['filename'] ) ) {
			$this->_error(
				'Malicious .tar detected, file "' . $v_header['filename'] .
				'" will not install in desired directory tree'
			);
			return false;
		}
		$v_header['mode']  = OctDec( trim( $v_data['mode'] ) );
		$v_header['uid']   = OctDec( trim( $v_data['uid'] ) );
		$v_header['gid']   = OctDec( trim( $v_data['gid'] ) );
		$v_header['size']  = $this->_tarRecToSize( $v_data['size'] );
		$v_header['mtime'] = OctDec( trim( $v_data['mtime'] ) );
		if ( ( $v_header['typeflag'] = $v_data['typeflag'] ) == '5' ) { // @codingStandardsIgnoreLine.
			$v_header['size'] = 0;
		}
		$v_header['link'] = trim( $v_data['link'] );
		return true;
	}
	/**
	 * Convert Tar record size to actual size
	 *
	 * @param string $tar_size .
	 * @return size of tar record in bytes
	 */
	private function _tarRecToSize( $tar_size ) { // @codingStandardsIgnoreLine.
		/**
		* First byte of size has a special meaning if bit 7 is set.
		*
		* Bit 7 indicates base-256 encoding if set.
		* Bit 6 is the sign bit.
		* Bits 5:0 are most significant value bits.
		*/

		$ch = ord( $tar_size[0] );
		if ( $ch & 0x80 ) {
			// Full 12-bytes record is required.
			$rec_str = $tar_size . "\x00";

			$size = ( $ch & 0x40 ) ? -1 : 0;
			$size = ( $size << 6 ) | ( $ch & 0x3f );

			for ( $num_ch = 1; $num_ch < 12; ++$num_ch ) {
				$size = ( $size * 256 ) + ord( $rec_str[ $num_ch ] );
			}

			return $size;
		} else {
			return OctDec( trim( $tar_size ) );
		}
	}
	/**
	 * Detect and report a malicious file name
	 *
	 * @param string $file .
	 *
	 * @return bool
	 */
	private function _maliciousFilename( $file ) { // @codingStandardsIgnoreLine.
		if ( strpos( $file, '/../' ) !== false ) {
			return true;
		}
		if ( strpos( $file, '../' ) === 0 ) {
			return true;
		}
		return false;
	}
	/**
	 * This function is used read long header .
	 *
	 * @param string $v_header .
	 * @return bool
	 */
	public function _readLongHeader( &$v_header ) { // @codingStandardsIgnoreLine.
		$v_filename = '';
		$v_filesize = $v_header['size'];
		$n          = floor( $v_header['size'] / 512 );
		for ( $i = 0; $i < $n; $i++ ) {
			$v_content   = $this->_readBlock();
			$v_filename .= $v_content;
		}
		if ( ( $v_header['size'] % 512 ) != 0 ) { // WPCS:loose comparison ok.
			$v_content   = $this->_readBlock();
			$v_filename .= $v_content;
		}

		// ----- Read the next header
		$v_binary_data = $this->_readBlock();

		if ( ! $this->_readHeader( $v_binary_data, $v_header ) ) {
			return false;
		}

		$v_filename           = rtrim( substr( $v_filename, 0, $v_filesize ), "\0" );
		$v_header['filename'] = $v_filename;
		if ( $this->_maliciousFilename( $v_filename ) ) {
			$this->_error(
				'Malicious .tar detected, file "' . $v_filename .
				'" will not install in desired directory tree'
			);
			return false;
		}

		return true;
	}
	/**
	 * This function is used to extract list .
	 *
	 * @param string $p_path .
	 * @param string $p_list_detail .
	 * @param string $p_mode .
	 * @param string $p_file_list .
	 * @param string $p_remove_path .
	 * @param bool   $p_preserve .
	 * @return bool
	 */
	public function _extractList( // @codingStandardsIgnoreLine.
	$p_path, &$p_list_detail, $p_mode, $p_file_list, $p_remove_path, $p_preserve = false
	) {
		$v_result      = true;
		$v_nb          = 0;
		$v_extract_all = true;
		$v_listing     = false;

		$p_path = $this->_translateWinPath( $p_path, false, '' );
		if ( '' == $p_path || ( substr( $p_path, 0, 1 ) != '/' && substr( $p_path, 0, 3 ) != '../' && ! strpos( $p_path, ':' ) ) // WPCS:loose comparison ok.
		) {
			$p_path = './' . $p_path;
		}
		$p_remove_path = $this->_translateWinPath( $p_remove_path, true, '' );

		// ----- Look for path to remove format (should end by /)
		if ( ( '' != $p_remove_path ) && ( '/' != substr( $p_remove_path, -1 ) ) ) { // WPCS:loose comparison ok.
			$p_remove_path .= '/';
		}
		$p_remove_path_size = strlen( $p_remove_path );

		switch ( $p_mode ) {
			case 'complete':
				$v_extract_all = true;
				$v_listing     = false;
				break;

			case 'partial':
				$v_extract_all = false;
				$v_listing     = false;
				break;

			case 'list':
				$v_extract_all = false;
				$v_listing     = true;
				break;

			default:
				$this->_error( 'Invalid extract mode (' . $p_mode . ')' );
				return false;
		}

		clearstatcache();

		while ( strlen( $v_binary_data = $this->_readBlock() ) != 0 ) { // @codingStandardsIgnoreLine.
			$v_extract_file       = false;
			$v_extraction_stopped = 0;

			if ( ! $this->_readHeader( $v_binary_data, $v_header ) ) {
				return false;
			}

			if ( '' == $v_header['filename'] ) { // WPCS:loose comparison ok.
				continue;
			}

			// ----- Look for long filename
			if ( 'L' == $v_header['typeflag'] ) { // WPCS:loose comparison ok.
				if ( ! $this->_readLongHeader( $v_header ) ) {
					return false;
				}
			}

			// ignore extended / pax headers .
			if ( 'x' == $v_header['typeflag'] || 'g' == $v_header['typeflag'] ) { // WPCS:loose comparison ok.
				$this->_jumpBlock( ceil( ( $v_header['size'] / 512 ) ) );
				continue;
			}

			if ( ( ! $v_extract_all ) && ( is_array( $p_file_list ) ) ) {
				// ----- By default no unzip if the file is not found
				$v_extract_file = false;

				for ( $i = 0; $i < sizeof( $p_file_list ); $i++ ) { // @codingStandardsIgnoreLine.
					// ----- Look if it is a directory
					if ( substr( $p_file_list[ $i ], -1 ) == '/' ) { // WPCS:loose comparison ok.
						// ----- Look if the directory is in the filename path .
						if ( ( strlen( $v_header['filename'] ) > strlen( $p_file_list[ $i ] ) ) && ( substr( $v_header['filename'], 0, strlen( $p_file_list[ $i ] ) ) == $p_file_list[ $i ] ) // WPCS:loose comparison ok.
						) {
							$v_extract_file = true;
							break;
						}
					} elseif ( $p_file_list[ $i ] == $v_header['filename'] ) { // WPCS:loose comparison ok.
						$v_extract_file = true;
						break;
					}
				}
			} else {
				$v_extract_file = true;
			}

			// ----- Look if this file need to be extracted
			if ( ( $v_extract_file ) && ( ! $v_listing ) ) {
				if ( ( '' != $p_remove_path ) && ( substr( $v_header['filename'] . '/', 0, $p_remove_path_size ) == $p_remove_path ) // WPCS:loose comparison ok.
				) {
					$v_header['filename'] = substr(
						$v_header['filename'], $p_remove_path_size
					);
					if ( '' == $v_header['filename'] ) { // WPCS:loose comparison ok.
						continue;
					}
				}
				if ( ( './' != $p_path ) && ( '/' != $p_path ) ) { // WPCS:loose comparison ok.
					while ( substr( $p_path, -1 ) == '/' ) { // WPCS:loose comparison ok.
						$p_path = substr( $p_path, 0, strlen( $p_path ) - 1 );
					}

					if ( substr( $v_header['filename'], 0, 1 ) == '/' ) { // WPCS:loose comparison ok.
						$v_header['filename'] = $p_path . $v_header['filename'];
					} else {
						$v_header['filename'] = $p_path . '/' . $v_header['filename'];
					}
				}
				if ( file_exists( $v_header['filename'] ) ) {
					if ( ( @is_dir( $v_header['filename'] ) ) && ( $v_header['typeflag'] == '' ) // @codingStandardsIgnoreLine.
					) {
						$this->_error(
							'File ' . $v_header['filename']
							. ' already exists as a directory'
						);
						return false;
					}
					if ( ( $this->_isArchive( $v_header['filename'] ) ) && ( '5' == $v_header['typeflag'] ) // WPCS:loose comparison ok.
					) {
						$this->_error(
							'Directory ' . $v_header['filename']
							. ' already exists as a file'
						);
						return false;
					}
					if ( ! is_writeable( $v_header['filename'] ) ) { // @codingStandardsIgnoreLine.
						$this->_error(
							'File ' . $v_header['filename']
							. ' already exists and is write protected'
						);
						return false;
					}
					if ( filemtime( $v_header['filename'] ) > $v_header['mtime'] ) { // @codingStandardsIgnoreLine.
						// To be completed : An error or silent no replace ?
					}
				} elseif ( ( $v_result = $this->_dirCheck( // @codingStandardsIgnoreLine.
					( '5' == $v_header['typeflag'] ? $v_header['filename'] : dirname( $v_header['filename'] ) ) // WPCS:loose comparison ok.
				) ) != 1 // WPCS:loose comparison ok.
				) {
					$this->_error( 'Unable to create path for ' . $v_header['filename'] );
					return false;
				}

				if ( $v_extract_file ) {
					if ( '5' == $v_header['typeflag'] ) { // WPCS:loose comparison ok.
						if ( ! @file_exists( $v_header['filename'] ) ) { // @codingStandardsIgnoreLine.
							if ( ! @mkdir( $v_header['filename'], 0777 ) ) { // @codingStandardsIgnoreLine.
								$this->_error(
									'Unable to create directory {'
									. $v_header['filename'] . '}'
								);
								return false;
							}
						}
					} elseif ( '2' == $v_header['typeflag'] ) { // WPCS:loose comparison ok.
						if ( @file_exists( $v_header['filename'] ) ) { // @codingStandardsIgnoreLine.
							@unlink( $v_header['filename'] ); // @codingStandardsIgnoreLine.
						}
						if ( ! @symlink( $v_header['link'], $v_header['filename'] ) ) { // @codingStandardsIgnoreLine.
							$this->_error(
								'Unable to extract symbolic link {'
								. $v_header['filename'] . '}'
							);
							return false;
						}
					} else {
						if ( ( $v_dest_file = @fopen( $v_header['filename'], 'wb' ) ) == 0 ) { // @codingStandardsIgnoreLine.
							$this->_error(
								'Error while opening {' . $v_header['filename']
								. '} in write binary mode'
							);
							return false;
						} else {
							$n = floor( $v_header['size'] / 512 );
							for ( $i = 0; $i < $n; $i++ ) {
								$v_content = $this->_readBlock();
								fwrite( $v_dest_file, $v_content, 512 ); // @codingStandardsIgnoreLine.
							}
							if ( ( $v_header['size'] % 512 ) != 0 ) { // WPCS:loose comparison ok.
								$v_content = $this->_readBlock();
								fwrite( $v_dest_file, $v_content, ( $v_header['size'] % 512 ) ); // @codingStandardsIgnoreLine.
							}

							@fclose( $v_dest_file ); // @codingStandardsIgnoreLine.

							if ( $p_preserve ) {
								@chown( $v_header['filename'], $v_header['uid'] ); // @codingStandardsIgnoreLine.
								@chgrp( $v_header['filename'], $v_header['gid'] ); // @codingStandardsIgnoreLine.
							}

							// ----- Change the file mode, mtime
							@touch( $v_header['filename'], $v_header['mtime'] ); // @codingStandardsIgnoreLine.
							if ( $v_header['mode'] & 0111 ) {
								// make file executable, obey umask .
								$mode = fileperms( $v_header['filename'] ) | ( ~umask() & 0111 );
								@chmod( $v_header['filename'], $mode ); // @codingStandardsIgnoreLine.
							}
						}

						// ----- Check the file size
						clearstatcache();
						if ( ! is_file( $v_header['filename'] ) ) {
							$this->_error(
								'Extracted file ' . $v_header['filename']
								. 'does not exist. Archive may be corrupted.'
							);
							return false;
						}

						$filesize = filesize( $v_header['filename'] );
						if ( $filesize != $v_header['size'] ) { // WPCS: loose comparison ok.
							$this->_error(
								'Extracted file ' . $v_header['filename']
								. ' does not have the correct file size "'
								. $filesize
								. '" (' . $v_header['size']
								. ' expected). Archive may be corrupted.'
							);
							return false;
						}
					}
				} else {
					$this->_jumpBlock( ceil( ( $v_header['size'] / 512 ) ) );
				}
			} else {
				$this->_jumpBlock( ceil( ( $v_header['size'] / 512 ) ) );
			}

			if ( $v_listing || $v_extract_file || $v_extraction_stopped ) {
				// ----- Log extracted files
				if ( ( $v_file_dir = dirname( $v_header['filename'] ) ) == $v_header['filename'] // @codingStandardsIgnoreLine.
				) {
					$v_file_dir = '';
				}
				if ( ( substr( $v_header['filename'], 0, 1 ) == '/' ) && ( '' == $v_file_dir ) ) { // WPCS:loose comparison ok.
					$v_file_dir = '/';
				}

				$p_list_detail[ $v_nb++ ] = $v_header;
				if ( is_array( $p_file_list ) && ( count( $p_list_detail ) == count( $p_file_list ) ) ) { // WPCS:loose comparison ok.
					return true;
				}
			}
		}

		return true;
	}
	/**
	 * This function is used to open append .
	 *
	 * @return bool
	 */
	public function _openAppend() { // @codingStandardsIgnoreLine.
		if ( filesize( $this->_tarname ) == 0 ) { // WPCS:loose comparison ok.
			return $this->_openWrite();
		}

		if ( $this->_compress ) {

			$this->_close();

			if ( ! @rename( $this->_tarname, $this->_tarname . '.tmp' ) ) { // @codingStandardsIgnoreLine.
				$this->_error(
					'Error while renaming "' . $this->_tarname
					. '" to temporary file "' . $this->_tarname
					. '.tmp"'
				);
				return false;
			}

			if ( 'gz' == $this->_compress_type ) { // WPCS:loose comparison ok.
				$v_temp_tar = @gzopen( $this->_tarname . '.tmp', 'rb' ); // @codingStandardsIgnoreLine.
			} elseif ( 'bz2' == $this->_compress_type ) { // WPCS:loose comparison ok.
				$v_temp_tar = @bzopen( $this->_tarname . '.tmp', 'r' ); // @codingStandardsIgnoreLine.
			}

			if ( 0 == $v_temp_tar ) { // WPCS:loose comparison ok.
				$this->_error(
					'Unable to open file "' . $this->_tarname
					. '.tmp" in binary read mode'
				);
				@rename( $this->_tarname . '.tmp', $this->_tarname ); // @codingStandardsIgnoreLine.
				return false;
			}

			if ( ! $this->_openWrite() ) {
				@rename( $this->_tarname . '.tmp', $this->_tarname ); // @codingStandardsIgnoreLine.
				return false;
			}

			if ( 'gz' == $this->_compress_type ) { // WPCS:loose comparison ok.
				$end_blocks = 0;

				while ( ! @gzeof( $v_temp_tar ) ) { // @codingStandardsIgnoreLine.
					$v_buffer = @gzread( $v_temp_tar, 512 ); // @codingStandardsIgnoreLine.
					if ( ARCHIVE_TAR_END_BLOCK == $v_buffer || strlen( $v_buffer ) == 0 ) { // WPCS:loose comparison ok.
						$end_blocks++;
						// do not copy end blocks, we will re-make them
						// after appending .
						continue;
					} elseif ( $end_blocks > 0 ) {
						for ( $i = 0; $i < $end_blocks; $i++ ) {
							$this->_writeBlock( ARCHIVE_TAR_END_BLOCK );
						}
						$end_blocks = 0;
					}
					$v_binary_data = pack( 'a512', $v_buffer );
					$this->_writeBlock( $v_binary_data );
				}

				@gzclose( $v_temp_tar ); // @codingStandardsIgnoreLine.
			} elseif ( 'bz2' == $this->_compress_type ) { // WPCS:loose comparison ok.
				$end_blocks = 0;

				while ( strlen( $v_buffer = @bzread( $v_temp_tar, 512 ) ) > 0 ) { // @codingStandardsIgnoreLine.
					if ( ARCHIVE_TAR_END_BLOCK == $v_buffer || strlen( $v_buffer ) == 0 ) { // WPCS:loose comparison ok.
						$end_blocks++;
						// do not copy end blocks, we will re-make them
						// after appending .
						continue;
					} elseif ( $end_blocks > 0 ) {
						for ( $i = 0; $i < $end_blocks; $i++ ) {
							$this->_writeBlock( ARCHIVE_TAR_END_BLOCK );
						}
						$end_blocks = 0;
					}
					$v_binary_data = pack( 'a512', $v_buffer );
					$this->_writeBlock( $v_binary_data );
				}

				@bzclose( $v_temp_tar ); // @codingStandardsIgnoreLine.
			}

			if ( ! @unlink( $this->_tarname . '.tmp' ) ) { // @codingStandardsIgnoreLine.
				$this->_error(
					'Error while deleting temporary file "'
					. $this->_tarname . '.tmp"'
				);
			}
		} else {
			// ----- For not compressed tar, just add files before the last
			// one or two 512 bytes block
			if ( ! $this->_openReadWrite() ) {
				return false;
			}

			clearstatcache();
			$v_size = filesize( $this->_tarname );

			// We might have zero, one or two end blocks.
			// The standard is two, but we should try to handle
			// other cases.
			fseek( $this->_file, $v_size - 1024 );
			if ( fread( $this->_file, 512 ) == ARCHIVE_TAR_END_BLOCK ) { // @codingStandardsIgnoreLine.
				fseek( $this->_file, $v_size - 1024 );
			} elseif ( fread( $this->_file, 512 ) == ARCHIVE_TAR_END_BLOCK ) { // @codingStandardsIgnoreLine.
				fseek( $this->_file, $v_size - 512 );
			}
		}

		return true;
	}
	/**
	 * This function is used to append .
	 *
	 * @param string $p_filelist .
	 * @param string $p_add_dir .
	 * @param string $p_remove_dir .
	 * @return bool
	 */
	public function _append( $p_filelist, $p_add_dir = '', $p_remove_dir = '' ) { // @codingStandardsIgnoreLine.
		if ( ! $this->_openAppend() ) {
			return false;
		}

		if ( $this->_addList( $p_filelist, $p_add_dir, $p_remove_dir, '' ) ) {
			$this->_writeFooter();
		}

		$this->_close();

		return true;
	}
	/**
	 * Check if a directory exists and create it (including parent
	 * dirs) if not.
	 *
	 * @param string $p_dir directory to check .
	 *
	 * @return bool true if the directory exists or was created
	 */
	public function _dirCheck( $p_dir ) { // @codingStandardsIgnoreLine.
		clearstatcache();
		if ( ( @is_dir( $p_dir ) ) || ( $p_dir == '' ) ) { // @codingStandardsIgnoreLine.
			return true;
		}

		$p_parent_dir = dirname( $p_dir );

		if ( ( $p_parent_dir != $p_dir ) && // WPCS:loose comparison ok.
			( '' != $p_parent_dir ) && // WPCS:loose comparison ok.
			( ! $this->_dirCheck( $p_parent_dir ) )
		) {
			return false;
		}

		if ( ! @mkdir( $p_dir, 0777 ) ) { // @codingStandardsIgnoreLine.
			$this->_error( "Unable to create directory '$p_dir'" );
			return false;
		}

		return true;
	}
	/**
	 * Compress path by changing for example "/dir/foo/../bar" to "/dir/bar",
	 * rand emove double slashes.
	 *
	 * @param string $p_dir path to reduce .
	 *
	 * @return string reduced path
	 */
	private function _pathReduction( $p_dir ) { // @codingStandardsIgnoreLine.
		$v_result = '';

		// ----- Look for not empty path
		if ( '' != $p_dir ) { // WPCS:loose comparison ok.
			// ----- Explode path by directory names .
			$v_list = explode( '/', $p_dir );

			// ----- Study directories from last to first
			for ( $i = sizeof( $v_list ) - 1; $i >= 0; $i-- ) { // @codingStandardsIgnoreLine.
				// ----- Look for current path
				if ( '.' == $v_list[ $i ] ) { // @codingStandardsIgnoreLine.
					// ----- Ignore this directory
					// Should be the first $i=0, but no check is done .
				} else {
					if ( '..' == $v_list[ $i ] ) { // WPCS:loose comparison ok.
						// ----- Ignore it and ignore the $i-1 .
						$i--;
					} else {
						if ( ( $v_list[ $i ] == '' ) && ( $i != ( sizeof( $v_list ) - 1 ) ) && ( $i != 0 ) // @codingStandardsIgnoreLine.
						) {
							// ----- Ignore only the double "//" in path,
							// but not the first and last /
						} else {
							$v_result = $v_list[ $i ] . ( $i != ( sizeof( $v_list ) - 1 ) ? '/' // @codingStandardsIgnoreLine.
							. $v_result : '' );
						}
					}
				}
			}
		}

		return $v_result;
	}
	/**
	 * This function is used to translate Winpath.
	 *
	 * @param string $p_path .
	 * @param bool   $p_remove_disk_letter .
	 * @param string $path_name .
	 * @return string
	 */
	public function _translateWinPath( $p_path, $p_remove_disk_letter = true, $path_name = '' ) { // @codingStandardsIgnoreLine.
		if ( '' != $path_name ) { // WPCS:loose comparison ok.
			if ( $p_remove_disk_letter && ( 'complete_backup' == $this->backup_type || 'only_filesystem' == $this->backup_type ) ) { // WPCS:loose comparison ok.
				$p_path = substr( $p_path, strlen( ABSPATH ) );
				$p_path = $this->archive_name . '/' . $p_path;
			} elseif ( $p_remove_disk_letter ) {
				if ( WP_CONTENT_DIR == $path_name ) { // WPCS:loose comparison ok.
					$p_path = substr( realpath( $p_path ), strlen( trailingslashit( realpath( $path_name ) ) ) );
					$p_path = $this->archive_name . '/' . $p_path;
				} else {
					$p_path = substr( realpath( $p_path ), strlen( trailingslashit( realpath( $path_name ) ) ) );
					$p_path = $this->archive_name . '/' . $p_path;
				}
			}
		}
		return $p_path;
	}
}
