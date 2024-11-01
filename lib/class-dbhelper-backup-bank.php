<?php
/**
 * This file is used for creating dbHelper class.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! is_user_logged_in() ) {
	return;
} else {
	if ( isset( $user_role_permission ) && count( $user_role_permission ) > 0 ) {
		foreach ( $user_role_permission as $permission ) {
			if ( current_user_can( $permission ) ) {
				$access_granted = true;
				break;
			}
		}
	}
	if ( ! $access_granted ) {
		return;
	} else {

		if ( ! class_exists( 'Dbhelper_Backup_Bank' ) ) {
			/**
			 * This Class is used for Insert Update and Delete operations.
			 */
			class Dbhelper_Backup_Bank {
				/**
				 * This Function is used for Insert data in database.
				 *
				 * @param string $table_name .
				 * @param array  $data .
				 */
				public function insert_command( $table_name, $data ) {
					global $wpdb;
					$wpdb->insert( $table_name, $data ); // WPCS: db call ok, no-cache ok.
					return $wpdb->insert_id;
				}
				/**
				 * This function is used for Update data.
				 *
				 * @param string $table_name .
				 * @param array  $data .
				 * @param string $where .
				 */
				public function update_command( $table_name, $data, $where ) {
					global $wpdb;
					$wpdb->update( $table_name, $data, $where );// WPCS: db call ok, no-cache ok.
				}

				/**
				 * This function is used for delete data.
				 *
				 * @param string $table_name .
				 * @param string $where .
				 */
				public function delete_command( $table_name, $where ) {
					global $wpdb;
					$wpdb->delete( $table_name, $where );// WPCS: db call ok, no-cache ok.
				}
			}
		}

		if ( ! class_exists( 'Dropbox_Backup_Bank' ) ) {
			/**
			 * This Class is used for Dropbox APP .
			 */
			class Dropbox_Backup_Bank {// @codingStandardsIgnoreLine.

				/**
				 * This function is being used to return Dropbox object.
				 *
				 * @param string $api_key .
				 * @param string $secret_key .
				 */
				public function dropbox_client( $api_key, $secret_key ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/dropbox/class-dropboxclient.php' ) ) {
						include_once BACKUP_BANK_DIR_PATH . 'lib/dropbox/class-dropboxclient.php';
					}
					$dropbox = new DropboxClient(
						array(
							'app_key'         => $api_key,
							'app_secret'      => $secret_key,
							'app_full_access' => false,
						)
					);
					return $dropbox;
				}

				/**
				 * This function is being used to store token.
				 *
				 * @param array  $token .
				 * @param string $name .
				 */
				public function store_token( $token, $name ) {
					@file_put_contents( BACKUP_BANK_DIR_PATH . "lib/dropbox/tokens/$name.token", maybe_serialize( $token ) );// @codingStandardsIgnoreLine.
				}

				/**
				 * This function is being used to return token.
				 *
				 * @param string $name .
				 */
				public function delete_token( $name ) {
					@unlink( BACKUP_BANK_DIR_PATH . "lib/dropbox/tokens/$name.token" );// @codingStandardsIgnoreLine.
				}

				/**
				 * This function is being used to load token.
				 *
				 * @param string $name .
				 */
				public function load_token( $name ) {
					if ( ! file_exists( BACKUP_BANK_DIR_PATH . "lib/dropbox/tokens/$name.token" ) ) {
						return null;
					}
					return @maybe_unserialize( @file_get_contents( BACKUP_BANK_DIR_PATH . "lib/dropbox/tokens/$name.token" ) );// @codingStandardsIgnoreLine.
				}

				/**
				 * This function is being used to handle dropbox authentication.
				 *
				 * @param string $dropbox .
				 * @param array  $configure .
				 * @param string $api_key .
				 * @param string $secret_key .
				 */
				public function handle_dropbox_auth( $dropbox, $configure, $api_key, $secret_key ) {
					// first try to load existing access token.
					$access_token = $this->load_token( 'access' );
					if ( ! empty( $access_token ) ) {
						$this->delete_token( $access_token );
					}

					// checks if access token is required.
					if ( ! $dropbox->is_authorized() ) {
						$return_url = admin_url() . 'admin.php?page=bb_dropbox_settings';
						$auth_url   = $dropbox->build_authorize_url( $return_url );
						echo $auth_url;// WPCS: XSS ok.
					}
				}

				/**
				 * This function is being used to upload files on Dropbox.
				 *
				 * @param string $dropbox .
				 * @param array  $files .
				 * @param string $path .
				 * @param string $file_name .
				 * @param string $logfile_name .
				 * @param array  $backup_bank_data .
				 *
				 * @throws Exception $e .
				 */
				public function handle_dropbox_auth_upload( $dropbox, $files, $path, $file_name, $logfile_name, $backup_bank_data ) {
					// first try to load existing access token.
					$access_token = $this->load_token( 'access' );
					if ( ! empty( $access_token ) ) {
						$dropbox->set_bearer_token( $access_token );
					}
					if ( isset( $files ) && count( $files ) > 0 ) {
						foreach ( $files as $file ) {
							try {
								$dropbox->upload_file( $file, trailingslashit( $path ) . basename( $file ), $file_name, $logfile_name, $backup_bank_data );
							} catch ( Exception $e ) {
								throw $e;
							}
						}
					}
				}

				/**
				 * This function is used to check authorization for dropbox.
				 *
				 * @param string $dropbox .
				 */
				public function check_dropbox_build_authorize( $dropbox ) {
					if ( ! $dropbox->is_authorized() ) {
						$return_url = admin_url() . 'admin.php?page=bb_manual_backup';
						$auth_url   = $dropbox->build_authorize_url( $return_url );
					}
				}

				/**
				 * This function is used to check dropbox access token is valid or not.
				 *
				 * @param string $dropbox .
				 */
				public function check_handle_dropbox_auth( $dropbox ) {
					$access_token = $this->load_token( 'access' );
					if ( ! empty( $access_token ) ) {
						$dropbox->set_bearer_token( $access_token );
					}

					if ( ! $dropbox->is_authorized() ) {
						$auth = false;
					} else {
						$auth = true;
					}
					return $auth;
				}

				/**
				 * This function is used to create folder in dropbox.
				 *
				 * @param string $dropbox .
				 * @param array  $folders .
				 */
				public function create_folder( $dropbox, $folders ) {
					$access_token = $this->load_token( 'access' );
					if ( ! empty( $access_token ) ) {
						$dropbox->set_bearer_token( $access_token );
					}
					$dropbox->create_folder( $folders );
				}
			}
		}

		if ( ! class_exists( 'Ftp_Connection_Backup_Bank' ) ) {

			/**
			 * This Class is used for FTP Connection.
			 */
			class Ftp_Connection_Backup_Bank {// @codingStandardsIgnoreLine.

				/**
				 * This Function is used for ftp Connection.
				 *
				 * @param string $ftp_host .
				 * @param array  $protocol .
				 * @param array  $port .
				 */
				public function ftp_connect( $ftp_host, $protocol, $port ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/ftp-client/ftp-client.php' ) ) {
						include_once BACKUP_BANK_DIR_PATH . 'lib/ftp-client/ftp-client.php';
					}
					switch ( $protocol ) {
						case 'ftp':
							$is_ssl = false;
							break;

						case 'ftps':
							$is_ssl = true;
							break;

						case 'sfpt_over_ssh':
							$is_ssl = true;
							break;
					}
					$ftp    = new FtpClient();
					$result = $ftp->connect( $ftp_host, $is_ssl, $port );
					return false != $result ? $ftp : $result;// WPCS: Loose Comparison ok.
				}
				/**
				 * This Function is used for ftp login.
				 *
				 * @param object $ftp .
				 * @param string $login_type .
				 * @param string $ftp_username .
				 * @param string $ftp_password .
				 */
				public function login_ftp( $ftp, $login_type, $ftp_username = '', $ftp_password = '' ) {
					switch ( $login_type ) {
						case 'username_password':
							$result = @$ftp->login( $ftp_username, $ftp_password );// @codingStandardsIgnoreLine.
							break;

						case 'username_only':
							$result = @$ftp->login( $ftp_username );// @codingStandardsIgnoreLine.
							break;

						case 'anonymous':
							$result = @$ftp->login( $ftp_username = 'anonymous' );// @codingStandardsIgnoreLine.
							break;

						case 'no_login':
							$result = @$ftp->login();// @codingStandardsIgnoreLine.
							break;
					}
					return $result;
				}

				/**
				 * This Function is used for creating directory.
				 *
				 * @param string $con_id .
				 * @param string $path .
				 */
				public function ftp_mkdir_recusive( $con_id, $path ) {
					$parts         = explode( '/', $path );
					$return        = true;
					$full_filepath = '';
					if ( isset( $parts ) && count( $parts ) > 0 ) {
						foreach ( $parts as $part ) {
							if ( empty( $part ) ) {
								$full_filepath .= '/';
								continue;
							}
							$full_filepath .= $part . '/';

							if ( @$con_id->chdir( $full_filepath ) ) {// @codingStandardsIgnoreLine.
								$full_filepath = '';
							} else {
								if ( @$con_id->mkdir( $part ) ) {// @codingStandardsIgnoreLine.
									$con_id->chdir( $part );
								} else {
									$return = false;
								}
							}
						}
					}
					return $return;
				}

				/**
				 * This Function is used for Uploading files to FTP.
				 *
				 * @param string $conn .
				 * @param string $local_file_path .
				 * @param string $remote_file_path .
				 * @param string $file_name .
				 * @param array  $backup_bank_data .
				 */
				public function custom_ftp_put( $conn, $local_file_path, $remote_file_path, $file_name, $backup_bank_data ) {
					$upload_path    = untrailingslashit( $backup_bank_data['folder_location'] );
					$archive_name   = implode( '', maybe_unserialize( $backup_bank_data['archive_name'] ) );
					$log_file_path  = $upload_path . '/' . $archive_name . '.txt';
					$start_time     = microtime( true );
					$file_size      = filesize( $local_file_path );
					$file_extention = strstr( $remote_file_path, '.' );
					$existing_size  = 0;

					$file_size = max( $file_size, 1 );

					$fh = fopen( $local_file_path, 'rb' );// @codingStandardsIgnoreLine.
					if ( $existing_size ) {
						fseek( $fh, $existing_size );
					}

					$ret = $conn->nb_fput( $remote_file_path, $fh, FTP_BINARY, $existing_size );

					$backup_status = 'completed';
					$cloud         = 2;
					while ( FTP_MOREDATA == $ret ) {// WPCS Loose Comparison ok.
						$new_size = ftell( $fh );

						if ( $new_size - $existing_size > 524288 ) {
							$existing_size = $new_size;
							$percent       = ceil( $new_size / $file_size * 100 );
							$rtime         = microtime( true ) - $start_time;
							if ( '.txt' !== $file_extention ) {
								$log      = 'Uploading to <b>FTP</b> (<b>' . round( ( $new_size / 1048576 ), 1 ) . 'MB</b> out of <b>' . round( ( $file_size / 1048576 ), 1 ) . 'MB</b>).';
								$message  = '{' . "\r\n";
								$message .= '"log": "' . $log . '" ,' . "\r\n";
								$message .= '"perc": ' . $percent . ',' . "\r\n";
								$message .= '"status": "' . $backup_status . '" ,' . "\r\n";
								$message .= '"cloud": ' . $cloud . "\r\n";
								$message .= '}';
								@file_put_contents( $file_name, $message );// @codingStandardsIgnoreLine.
								@file_put_contents( $log_file_path, strip_tags( sprintf( '%08.03f', round( $rtime, 3 ) ) . ' ' . $log . "\r\n" ), FILE_APPEND );// @codingStandardsIgnoreLine.
							}
						}
						$ret = $conn->nb_continue();
					}

					fclose( $fh );// @codingStandardsIgnoreLine.
					return true;
				}
			}
		}

		global $generated_backups;
		$generated_backups = array();

		if ( ! class_exists( 'Purge_Folder_Backup_Bank' ) ) {
			/**
			 * This class is used to purge backups.
			 */
			class Purge_Folder_Backup_Bank {// @codingStandardsIgnoreLine.

				/**
				 * This function is being used to fetch files in the Directory.
				 *
				 * @param string $dir .
				 */
				public function fetch_folder_files( $dir ) {
					global $generated_backups;
					$folder_files = scandir( $dir );
					if ( isset( $folder_files ) && count( $folder_files ) > 0 ) {
						foreach ( $folder_files as $folder_file ) {
							if ( '.' != $folder_file && '..' != $folder_file ) {// WPCS: Loose Comparison ok.
								if ( is_dir( $dir . '/' . $folder_file ) ) {
									$this->fetch_folder_files( $dir . '/' . $folder_file );
								}
								if ( is_file( $dir . '/' . $folder_file ) ) {
									$path = $dir . '/' . $folder_file;
									$path = str_replace( '\\', '/', $path );
									array_push( $generated_backups, $path );
								}
							}
						}
					}
					return $generated_backups;
				}

				/**
				 * This function is being used to purge in backup Directory.
				 *
				 * @param array $backups .
				 */
				public function purge_backups( $backups ) {
					$folder_files = $this->fetch_folder_files( BACKUP_BANK_BACKUPS_DIR );

					$backups_array = array();
					if ( isset( $backups ) && count( $backups ) > 0 ) {
						foreach ( $backups as $backup ) {
							array_push( $backups_array, $backup );
						}
						$delete_backups = array_diff( $folder_files, $backups_array );
					}
					if ( isset( $delete_backups ) && count( $delete_backups ) > 0 ) {
						foreach ( $delete_backups as $backup ) {
							unlink( $backup );// @codingStandardsIgnoreLine.
						}
					}
				}
			}
		}
		if ( ! class_exists( 'Backup_bank_PclZip' ) ) {
			require_once BACKUP_BANK_DIR_PATH . 'lib/class-zip.php';
		}
		if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/pear-archive-tar/tar.php' ) ) {
			require_once BACKUP_BANK_DIR_PATH . 'lib/pear-archive-tar/tar.php';
		}
		if ( ! class_exists( 'Backup_Data_Backup_Bank' ) ) {
			class Backup_Data_Backup_Bank {// @codingStandardsIgnoreLine.
				/**
				 * This handles logfile..
				 *
				 * @access   public
				 * @var      string    $logfile_handle.
				 */
				public $logfile_handle = false;
				/**
				 * It consists of zip files.
				 *
				 * @access   public
				 * @var      string    $zipfiles_dirbatched.
				 */
				public $zipfiles_dirbatched;
				/**
				 * Zip Object.
				 *
				 * @access   private
				 * @var      string    $use_zip_object.
				 */
				private $use_zip_object = 'Backup_bank_ZipArchive';
				/**
				 * The contains upload path..
				 *
				 * @access   public
				 * @var      string    $upload_path.
				 */
				public $upload_path;
				/**
				 * The name of the archive.
				 *
				 * @access   public
				 * @var      string    $archive_name.
				 */
				public $archive_name;
				/**
				 * Upload directory path.
				 *
				 * @access   public
				 * @var      string    $upload_dir_realpath.
				 */
				public $upload_dir_realpath;
				/**
				 * It contains backup data.
				 *
				 * @access   public
				 * @var      string    $backup_bank_data.
				 */
				public $backup_bank_data;
				/**
				 * The type of backup.
				 *
				 * @access   public
				 * @var      string    $backup_type.
				 */
				public $backup_type;
				/**
				 * The compression type of file.
				 *
				 * @access   public
				 * @var      string    $file_compression_type.
				 */
				public $file_compression_type;
				/**
				 * The file path of backup.
				 *
				 * @access   public
				 * @var      string    $backup_file_path.
				 */
				public $backup_file_path;
				/**
				 * The list of types want to excluded in the backup.
				 *
				 * @access   public
				 * @var      string    $exclude_list;.
				 */
				public $exclude_list;
				/**
				 * The array of errors occured.
				 *
				 * @access   public
				 * @var      array    $error
				 */
				public $error = array();
				/**
				 * The name of the log file..
				 *
				 * @access   public
				 * @var      string    $logfile_name.
				 */
				public $logfile_name;
				/**
				 * The count of the zip files batched.
				 *
				 * @access   public
				 * @var      string    $count_zipfiles_batched.
				 */
				public $count_zipfiles_batched;
				/**
				 * The count of the tables.
				 *
				 * @access   public
				 * @var      string    $how_many_tables.
				 */
				public $how_many_tables;
				/**
				 * The total number of tables.
				 *
				 * @access   public
				 * @var      string    $total_tables.
				 */
				public $total_tables;
				/**
				 * The zip files added.
				 *
				 * @access   public
				 * @var      string    $zipfiles_added.
				 */
				public $zipfiles_added;
				/**
				 * The status of the backup.
				 *
				 * @access   public
				 * @var      string    $status.
				 */
				public $status;
				/**
				 * The backup completed.
				 *
				 * @access   public
				 * @var      string    $backup_completed.
				 */
				public $backup_completed;
				/**
				 * The database compression type.
				 *
				 * @access   public
				 * @var      string    $db_compression_type.
				 */
				public $db_compression_type;
				/**
				 * The destination of the backup.
				 *
				 * @access   public
				 * @var      string    $backup_destination.
				 */
				public $backup_destination;
				/**
				 * The backup file.
				 *
				 * @access   public
				 * @var      string    $backup_file.
				 */
				public $backup_file;
				/**
				 * The name of the database file.
				 *
				 * @access   public
				 * @var      string    $database_file_name.
				 */
				public $database_file_name;
				/**
				 * The size in kb.
				 *
				 * @access   public
				 * @var      string    $kbsize.
				 */
				public $kbsize;
				/**
				 * The time taken for the backup to create.
				 *
				 * @access   public
				 * @var      string    $timetaken.
				 */
				public $timetaken;
				/**
				 * The time for the zip file in microtime.
				 *
				 * @access   public
				 * @var      string    $zip_microtime_start.
				 */
				public $zip_microtime_start;
				/**
				 * The name of the json file.
				 *
				 * @access   public
				 * @var      string    $json_file_name.
				 */
				public $json_file_name;
				/**
				 * The files size added.
				 *
				 * @access   public
				 * @var      string    $files_size_added.
				 */
				public $files_size_added;
				/**
				 * It consists of size of total files.
				 *
				 * @access   public
				 * @var      string    $total_files_size.
				 */
				public $total_files_size;
				/**
				 * It consists of size of total files.
				 *
				 * @access   public
				 * @var      string    $total_files_size.
				 */
				public $cloud;
				/**
				 * The timetaken for the logfile.
				 *
				 * @access   public
				 * @var      string    $log_timetaken.
				 */
				public $log_timetaken;
				/**
				 * Public Constructor
				 *
				 * @param array $backup_bank_data_array .
				 */
				public function __construct( $backup_bank_data_array = '' ) {
					if ( '' != $backup_bank_data_array ) {// WPCS: Loose comparison ok.
						! is_dir( $backup_bank_data_array['folder_location'] ) ? wp_mkdir_p( $backup_bank_data_array['folder_location'] ) : '';
						$this->upload_path      = untrailingslashit( $backup_bank_data_array['folder_location'] );
						$this->archive_name     = implode( '', maybe_unserialize( $backup_bank_data_array['archive_name'] ) );
						$this->backup_completed = '';
						$this->json_file_name   = $this->upload_path . '/' . $this->archive_name . '.json';
						$this->open_logfile_backup_bank( $this->upload_path . '/' . $this->archive_name . '.txt' );
						$this->backup_bank_data      = $backup_bank_data_array;
						$this->backup_type           = $backup_bank_data_array['backup_type'];
						$this->file_compression_type = $backup_bank_data_array['file_compression_type'];
						$this->db_compression_type   = $backup_bank_data_array['db_compression_type'];
						$this->exclude_list          = explode( ',', str_replace( ' ', '', $backup_bank_data_array['exclude_list'] ) );
						$this->backup_destination    = $backup_bank_data_array['backup_destination'];
						$this->upload_dir_realpath   = realpath( BACKUP_BANK_BACKUPS_DIR );
						$this->cloud                 = 1;
						if ( ( ( ( '.sql.zip' == $this->db_compression_type || '.zip' == $this->file_compression_type ) && 'complete_backup' == $this->backup_type ) || ( 'complete_backup' != $this->backup_type && 'only_database' != $this->backup_type && '.zip' != $this->file_compression_type ) || // WPCS: Loose comparison ok.
						( 'only_database' == $this->backup_type && '.sql.zip' == $this->db_compression_type ) ) ) {// WPCS: Loose comparison ok.
							if ( ! class_exists( 'ZipArchive' ) || ! class_exists( 'Backup_bank_ZipArchive' ) || ( ! extension_loaded( 'zip' ) && ! method_exists( 'ZipArchive', 'AddFile' ) ) ) {
								$this->backup_bank_log( "Zip Engine: ZipArchive is not Available or is Disabled (Use PclZip if needed).\r\n" );
								$this->use_zip_object = 'Backup_bank_PclZip';
							}
						}
					}
				}
				/**
				 * This function is used to close browser connection.
				 */
				public function close_browser_connection() {
					// Close browser connection so that it can resume AJAX polling.
					header( 'Content-Length: 0' );
					header( 'Connection: close' );
					header( 'Content-Encoding: none' );
					if ( session_id() ) {// @codingStandardsIgnoreLine.
						session_write_close();// @codingStandardsIgnoreLine.
					}
					echo "\r\n\r\n";
					if ( ob_get_level() ) {
						ob_end_flush();
					}
					flush();
				}
				/**
				 * This function is used to get table prefix.
				 */
				public function table_prefix_backup_bank() {
					global $wpdb;
					if ( is_multisite() && ! defined( 'MULTISITE' ) ) {
						$prefix = $wpdb->base_prefix;
					} else {
						$prefix = $wpdb->prefix;
					}
					return $prefix;
				}
				/**
				 * This function is used to count the errors occurred.
				 *
				 * @param string $level .
				 */
				public function error_count_backup_bank( $level = 'error' ) {
					$count = 0;
					if ( isset( $this->errors ) && count( $this->errors ) > 0 ) {
						foreach ( $this->errors as $err ) {
							if ( ( 'error' == $level && ( is_string( $err ) || is_wp_error( $err ) ) ) || ( is_array( $err ) && $level == $err['level'] ) ) {// WPCS: Loose comparison ok.
								$count++;
							}
						}
					}
					return $count;
				}
				/**
				 * This function is used to log the file.
				 *
				 * @param string $line .
				 * @param string $level .
				 * @param bool   $uniq_id .
				 */
				public function backup_bank_log( $line, $level = 'notice', $uniq_id = false ) {
					if ( 'error' == $level || 'warning' == $level ) {// WPCS: Loose comparison ok.
						if ( 'error' == $level && 0 == $this->error_count_backup_bank() ) {// WPCS: Loose comparison ok.
							$this->backup_bank_log( "An error condition has been occurred for the first time during this job.\r\n" );
						}
						if ( $uniq_id ) {
							$this->errors[ $uniq_id ] = array(
								'level'   => $level,
								'message' => $line,
							);
						} else {
							$this->errors[] = array(
								'level'   => $level,
								'message' => $line,
							);
						}
						if ( 'error' == $level ) {// WPCS: Loose comparison ok.
							2;
						}
					}
					if ( $this->logfile_handle ) {
						$rtime = microtime( true ) - $this->opened_log_time;
						fwrite( $this->logfile_handle, sprintf( '%08.03f', round( $rtime, 3 ) ) . ' ' . ( ( 'notice' != $level ) ? '[' . ucfirst( $level ) . '] ' : '' ) . strip_tags( $line ) );// @codingStandardsIgnoreLine.
					}

					switch ( $this->backup_type ) {
						case 'complete_backup':
							$database_tables_count = '' == $this->how_many_tables ? 24 : $this->how_many_tables;// WPCS: Loose comparison ok.
							$count_table           = '' == $this->total_tables ? 1 : $this->total_tables;// WPCS: Loose comparison ok.
							$result                = ceil( $count_table / $database_tables_count * 24 );
							if ( '.zip' == $this->file_compression_type ) {// WPCS: Loose comparison ok.
								$zipfiles_batched_count = '' == $this->total_files_size ? 74 : $this->total_files_size;// WPCS: Loose comparison ok.
								$count_zipfiles_added   = '' == $this->files_size_added ? 1 : $this->files_size_added;// WPCS: Loose comparison ok.
								if ( '' == $this->backup_completed ) {// WPCS: Loose comparison ok.
									$total_result = ceil( $count_zipfiles_added / $zipfiles_batched_count * 74 );
									$result      += $total_result;
								} else {
									$result = $this->backup_completed;
								}
							}
							break;

						case 'only_database':
							$databse_tables_count = '' == $this->how_many_tables ? 98 : $this->how_many_tables;// WPCS: Loose comparison ok.
							$count_tables         = '' == $this->total_tables ? 1 : $this->total_tables;// WPCS: Loose comparison ok.
							if ( '' == $this->backup_completed ) {// WPCS: Loose comparison ok.
								$result = ceil( $count_tables / $databse_tables_count * 98 );
							} else {
								$result = $this->backup_completed;
							}
							break;

						default:
							$zipfiles_batched_count = '' == $this->total_files_size ? 98 : $this->total_files_size;// WPCS: Loose comparison ok.
							$count_zipfiles_added   = '' == $this->files_size_added ? 1 : $this->files_size_added;// WPCS: Loose comparison ok.
							if ( '' == $this->backup_completed ) {// WPCS: Loose comparison ok.
								$result = ceil( $count_zipfiles_added / $zipfiles_batched_count * 98 );
							} else {
								$result = $this->backup_completed;
							}
					}
					if ( '' != $line ) { // WPCS: Loose comparison ok.
						if ( 1 != $this->cloud ) {// WPCS: Loose comparison ok.
							$result = 1;
						}
						$new_line = str_replace( "\r\n", '', $line );

						@file_put_contents( $this->json_file_name, '' );// @codingStandardsIgnoreLine.
						$message  = '{' . "\r\n";
						$message .= '"log": "' . $new_line . '" ,' . "\r\n";
						$message .= '"perc": ' . $result . ',' . "\r\n";
						$message .= '"status": "' . $this->status . '" ,' . "\r\n";
						$message .= '"cloud": ' . $this->cloud . "\r\n";
						$message .= '}';

						@file_put_contents( $this->json_file_name, $message );// @codingStandardsIgnoreLine.
					}
				}
				/**
				 * This function is used to log for sending backup to destination.
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
						case 'google_drive':
							$backup_dest = 'Google Drive';
							$this->cloud = 2;
							break;
					}
					$this->backup_bank_log( "Starting Sending <b>$file Backup</b> to <b>$backup_dest</b>.\r\n" );
				}
				/**
				 * This function is used to check whether directory is writable or not.
				 *
				 * @param string $dir .
				 */
				public function is_writable_backup_bank( $dir ) {
					if ( ! @is_writable( $dir ) ) {// @codingStandardsIgnoreLine.
						return false;
					}
					$rand_file = "$dir/test-" . md5( rand() . time() ) . '.txt';
					$ret       = @file_put_contents( $rand_file, 'testing...' );// @codingStandardsIgnoreLine.
					@unlink( $rand_file );// @codingStandardsIgnoreLine.
					return ( $ret > 0 );
				}
				/**
				 * This function is used to open database file.
				 *
				 * @param string $file .
				 */
				public function open_database_file_backup_bank( $file ) {
					$this->dbhandle = @fopen( $file, 'w' );// @codingStandardsIgnoreLine.

					if ( false === $this->dbhandle ) {
						$this->backup_bank_log( "ERROR: Backup File <b>$file</b> couldn't be open for writing.\r\n" );
					}
					return $this->dbhandle;
				}
				/**
				 * This function is used to sort database tables.
				 *
				 * @param array $a_arr .
				 * @param array $b_arr .
				 */
				private function database_sorttables_backup_bank( $a_arr, $b_arr ) {
					$a            = $a_arr['name'];
					$a_table_type = $a_arr['type'];
					$b            = $b_arr['name'];
					$b_table_type = $b_arr['type'];

					if ( 'VIEW' == $a_table_type && 'VIEW' != $b_table_type ) {// WPCS: Loose comparison ok.
						return 1;
					}
					if ( 'VIEW' == $b_table_type && 'VIEW' != $a_table_type ) {// WPCS: Loose comparison ok.
						return -1;
					}

					if ( $a == $b ) {// WPCS: Loose comparison ok.
						return 0;
					}
					$our_table_prefix = $this->table_prefix;
					if ( $a == $our_table_prefix . 'options' ) {// WPCS: Loose comparison ok.
						return -1;
					}
					if ( $b == $our_table_prefix . 'options' ) {// WPCS: Loose comparison ok.
						return 1;
					}
					if ( $a == $our_table_prefix . 'site' ) {// WPCS: Loose comparison ok.
						return -1;
					}
					if ( $b == $our_table_prefix . 'site' ) {// WPCS: Loose comparison ok.
						return 1;
					}
					if ( $a == $our_table_prefix . 'blogs' ) {// WPCS: Loose comparison ok.
						return -1;
					}
					if ( $b == $our_table_prefix . 'blogs' ) {// WPCS: Loose comparison ok.
						return 1;
					}
					if ( $a == $our_table_prefix . 'users' ) {// WPCS: Loose comparison ok.
						return -1;
					}
					if ( $b == $our_table_prefix . 'users' ) {// WPCS: Loose comparison ok.
						return 1;
					}
					if ( $a == $our_table_prefix . 'usermeta' ) {// WPCS: Loose comparison ok.
						return -1;
					}
					if ( $b == $our_table_prefix . 'usermeta' ) {// WPCS: Loose comparison ok.
						return 1;
					}

					if ( empty( $our_table_prefix ) ) {
						return strcmp( $a, $b );
					}

					try {
						$core_tables = array_merge( $this->wpdb_obj->tables, $this->wpdb_obj->global_tables, $this->wpdb_obj->ms_global_tables );
					} catch ( Exception $e ) {
						$this->backup_bank_log( $e->getMessage() . "\r\n" );
					}

					if ( empty( $core_tables ) ) {
						$core_tables = array( 'terms', 'term_taxonomy', 'termmeta', 'term_relationships', 'commentmeta', 'comments', 'links', 'postmeta', 'posts', 'site', 'sitemeta', 'blogs', 'blogversions' );
					}

					$na = $this->str_replace_once_backup_bank( $our_table_prefix, '', $a );
					$nb = $this->str_replace_once_backup_bank( $our_table_prefix, '', $b );
					if ( in_array( $na, $core_tables ) && ! in_array( $nb, $core_tables ) ) {// @codingStandardsIgnoreLine.
						return -1;
					}
					if ( ! in_array( $na, $core_tables ) && in_array( $nb, $core_tables ) ) {// @codingStandardsIgnoreLine.
						return 1;
					}
					return strcmp( $a, $b );
				}
				/**
				 * This function is used to provide database backup header.
				 */
				private function database_backup_header_backup_bank() {
					global $wp_version;

					$mysql_version = $this->wpdb_obj->db_version();

					$wp_upload_dir = wp_upload_dir();
					$this->backup_bank_store( "# --------------------------------------------------------\n" );
					$this->backup_bank_store( "# Database Backup\n" );
					$this->backup_bank_store( "# Plugin: Backup Bank Created by Tech Banker\n" );
					$this->backup_bank_store( "# WordPress Version: $wp_version, running on PHP Version" . phpversion() . ' (' . wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) . "), MySQL Version $mysql_version\n" );// @codingStandardsIgnoreLine.
					$this->backup_bank_store( '# Backup of: ' . untrailingslashit( site_url() ) . "\n" );
					$this->backup_bank_store( '# Home URL: ' . untrailingslashit( home_url() ) . "\n" );
					$this->backup_bank_store( '# Content URL: ' . untrailingslashit( content_url() ) . "\n" );
					$this->backup_bank_store( '# Uploads URL: ' . untrailingslashit( $wp_upload_dir['baseurl'] ) . "\n" );
					$this->backup_bank_store( '# Table prefix: ' . $this->table_prefix . "\n" );
					$this->backup_bank_store( '# Site info: multisite = ' . ( is_multisite() ? '1' : '0' ) . "\n\n" );

					$this->backup_bank_store( '# Generated On: ' . date( 'l j,F Y H:i T' ) . "\n" );
					$this->backup_bank_store( '# Hostname: ' . $this->dbinfo['host'] . "\n" );
					$this->backup_bank_store( '# Database Name: ' . $this->backup_bank_backquote( $this->dbinfo['name'] ) . "\n" );
					$this->backup_bank_store( "# --------------------------------------------------------\n" );

					if ( defined( 'DB_CHARSET' ) ) {
						$this->backup_bank_store( "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n" );
						$this->backup_bank_store( "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n" );
						$this->backup_bank_store( "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n" );
						$this->backup_bank_store( '/*!40101 SET NAMES ' . DB_CHARSET . " */;\n" );
					}
					$this->backup_bank_store( "/*!40101 SET foreign_key_checks = 0 */;\n\n" );
				}
				/**
				 * This function is used to store the backup log.
				 *
				 * @param string $query_line .
				 */
				public function backup_bank_store( $query_line ) {
					if ( false == ( $ret = @fwrite( $this->dbhandle, $query_line ) ) ) {// @codingStandardsIgnoreLine.
						$this->backup_bank_log( "Error occurred while writing a line to Backup.\r\n" );
					}
					return $ret;
				}
				/**
				 * This function is used to close the backup.
				 */
				public function close() {
					return fclose( $this->dbhandle );// @codingStandardsIgnoreLine.
				}
				/**
				 * This function is used to return the backquote.
				 *
				 * @param string $a_name .
				 */
				public function backup_bank_backquote( $a_name ) {
					if ( ! empty( $a_name ) && '*' != $a_name ) {// WPCS: Loose comparison ok.
						return '`' . $a_name . '`';
					} else {
						return $a_name;
					}
				}
				/**
				 * This function is used to replace the string.
				 *
				 * @param string $needle .
				 * @param string $replace .
				 * @param string $haystack .
				 */
				public function str_replace_once_backup_bank( $needle, $replace, $haystack ) {
					$pos = strpos( $haystack, $needle );
					return ( false !== $pos ) ? substr_replace( $haystack, $replace, $pos, strlen( $needle ) ) : $haystack;
				}
				/**
				 * This function is used to replace the string.
				 *
				 * @param string $search .
				 * @param string $replace .
				 * @param string $subject .
				 */
				public function str_lreplace_backup_bank( $search, $replace, $subject ) {
					$pos = strrpos( $subject, $search );
					if ( false !== $pos ) {
						$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
					}
					return $subject;
				}
				/**
				 * This function is used to get the details of tables.
				 *
				 * @param string $table .
				 * @param string $where .
				 * @param string $table_type .
				 */
				private function table_backup_bank( $table, $where = '', $table_type = 'BASE TABLE' ) {
					$microtime = microtime( true );

					$dump_as_table = ( false == $this->duplicate_tables_exist && stripos( $table, $this->table_prefix ) === 0 && strpos( $table, $this->table_prefix ) !== 0 ) ? $this->table_prefix . substr( $table, strlen( $this->table_prefix ) ) : $table;// WPCS: Loose comparison ok.

					$table_structure = $this->wpdb_obj->get_results( 'DESCRIBE ' . $this->backup_bank_backquote( $table ) );
					if ( ! $table_structure ) {
						$this->backup_bank_log( "Error occurred while getting details of Tables.\r\n" );
						return false;
					}

					$this->backup_bank_store( 'DROP TABLE IF EXISTS ' . $this->backup_bank_backquote( $dump_as_table ) . ";\n" );

					if ( 'VIEW' == $table_type ) {// WPCS: Loose comparison ok.
						$this->backup_bank_store( 'DROP VIEW IF EXISTS ' . $this->backup_bank_backquote( $dump_as_table ) . ";\n" );
					}

					$description = ( 'VIEW' == $table_type ) ? 'view' : 'table';// WPCS: Loose comparison ok.

					$this->backup_bank_store( "\n# Table structure for " . $this->backup_bank_backquote( $table ) . "\n\n" );

					$create_table = $this->wpdb_obj->get_results( 'SHOW CREATE TABLE ' . $this->backup_bank_backquote( $table ), ARRAY_N );
					if ( false === $create_table ) {
						$err_msg = 'SHOW CREATE TABLE for ' . $table . ' not Supported.';
						$this->backup_bank_log( $err_msg . "\r\n", 'error' );
						$this->backup_bank_store( "#\n# $err_msg\n#\n" );
					}
					$create_line = $this->str_lreplace_backup_bank( 'TYPE=', 'ENGINE=', $create_table[0][1] );

					if ( preg_match( '/ENGINE=([^\s;]+)/', $create_line, $eng_match ) ) {
						$engine = $eng_match[1];
						if ( 'myisam' == strtolower( $engine ) ) {// WPCS: Loose comparison ok.
							$create_line = preg_replace( '/PAGE_CHECKSUM=\d\s?/', '', $create_line, 1 );
						}
					}

					if ( $dump_as_table !== $table ) {
						$create_line = $this->str_replace_once_backup_bank( $table, $dump_as_table, $create_line );
					}

					$this->backup_bank_store( $create_line . ' ;' );

					if ( false === $table_structure ) {
						$err_msg = "Error while getting $description structure for " . $table;
						$this->backup_bank_store( "#\n# $err_msg\n#\n" );
					}

					$this->backup_bank_store( "\n\n# Backup Data for $description " . $this->backup_bank_backquote( $table ) . "\n" );

					if ( 'VIEW' != $table_type ) {// WPCS: Loose comparison ok.
						$defs           = array();
						$integer_fields = array();
						if ( isset( $table_structure ) && count( $table_structure ) > 0 ) {
							foreach ( $table_structure as $struct ) {
								if ( ( 0 === strpos( $struct->Type, 'tinyint' ) ) || ( 0 === strpos( strtolower( $struct->Type ), 'smallint' ) ) ||// @codingStandardsIgnoreLine.
								( 0 === strpos( strtolower( $struct->Type ), 'mediumint' ) ) || ( 0 === strpos( strtolower( $struct->Type ), 'int' ) ) || ( 0 === strpos( strtolower( $struct->Type ), 'bigint' ) ) ) { // @codingStandardsIgnoreLine.
									$defs[ strtolower( $struct->Field ) ]           = ( null === $struct->Default ) ? 'NULL' : $struct->Default;// @codingStandardsIgnoreLine.
									$integer_fields[ strtolower( $struct->Field ) ] = '1';// @codingStandardsIgnoreLine.
								}
							}
						}

						$increment = 500;
						$row_start = 0;
						$row_inc   = $increment;

						$search  = array( "\x00", "\x0A", "\x0D", "\x1a" );
						$replace = array( '\0', '\n', '\r', '\Z' );

						if ( $where ) {
							$where = "WHERE $where";
						}

						$lock_table = 'LOCK TABLES ' . $this->backup_bank_backquote( $dump_as_table ) . ' WRITE;';
						$this->backup_bank_store( $lock_table );
						do {
							@set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );// @codingStandardsIgnoreLine.

							$table_data = $this->wpdb_obj->get_results( "SELECT * FROM $table $where LIMIT {$row_start}, {$row_inc}", ARRAY_A );
							$entries    = 'INSERT INTO ' . $this->backup_bank_backquote( $dump_as_table ) . ' VALUES ';
							if ( $table_data ) {
								$thisentry = '';
								if ( isset( $table_data ) && count( $table_data ) > 0 ) {
									foreach ( $table_data as $row ) {
										$values = array();
										foreach ( $row as $key => $value ) {
											if ( isset( $integer_fields[ strtolower( $key ) ] ) ) {
												$value    = ( null === $value || '' === $value ) ? $defs[ strtolower( $key ) ] : $value;
												$values[] = ( '' === $value ) ? "''" : $value;
											} else {
												$values[] = ( null === $value ) ? 'NULL' : "'" . str_replace( $search, $replace, str_replace( '\'', '\\\'', str_replace( '\\', '\\\\', $value ) ) ) . "'";
											}
										}
										if ( $thisentry ) {
											$thisentry .= ",\n ";
										}
										$thisentry .= '(' . implode( ', ', $values ) . ')';
										if ( strlen( $thisentry ) > 524288 ) {
											$this->backup_bank_store( " \n" . $entries . $thisentry . ';' );
											$thisentry = '';
										}
									}
								}
								if ( $thisentry ) {
									$this->backup_bank_store( " \n" . $entries . $thisentry . ';' );
								}
								$row_start += $row_inc;
							}
						} while ( count( $table_data ) > 0 );// @codingStandardsIgnoreLine.
					}

					$this->backup_bank_store( "\n" );
					$unlock_table = 'UNLOCK TABLES; ';
					$this->backup_bank_store( $unlock_table . "\n" );
					$this->backup_bank_store( '# End of Backup Data for Table ' . $this->backup_bank_backquote( $table ) . "\n\n" );

					$table_file_prefix = $this->archive_name . '-table-' . $table . '.table';
					$table_name        = '<b>' . $table . '</b>';
					$table_size        = '<b>' . round( filesize( $this->upload_path . '/' . $table_file_prefix . '.tmp.sql' ) / 1024, 1 ) . '</b>';
					$this->backup_bank_log( "Completed Compressing Table $table_name in (<b>" . sprintf( '%.02f', max( microtime( true ) - $this->zip_microtime_start, 0.00001 ) ) . " seconds</b>) with size (<b>$table_size kb</b>).\r\n" );
				}
				/**
				 * This function is used to detect the safe mode.
				 */
				public function detect_safe_mode_backup_bank() {
					return ( @ini_get( 'safe_mode' ) && strtolower( @ini_get( 'safe_mode' ) ) != 'off' ) ? 1 : 0;// @codingStandardsIgnoreLine.
				}
				/**
				 * This function is used to open the log file.
				 *
				 * @param string $logfile_name .
				 */
				public function open_logfile_backup_bank( $logfile_name ) {
					$this->logfile_name   = $logfile_name;
					$this->logfile_handle = fopen( $this->logfile_name, 'a' );// @codingStandardsIgnoreLine.

					$this->opened_log_time = microtime( true );
					$this->backup_bank_log( 'Log file opened on ' . date( 'r' ) . ' on ' . network_site_url() . "\r\n" );
					global $wpdb, $wp_version;
					$this->zip_microtime_start = microtime( true );

					@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', WP_MAX_MEMORY_LIMIT ) );// @codingStandardsIgnoreLine.
					$mysql_version = $wpdb->db_version();
					@ini_set( 'error_log', $this->logfile_name );// @codingStandardsIgnoreLine.
					$safe_mode = $this->detect_safe_mode_backup_bank();
					@ini_set( 'log_errors', '1' );// @codingStandardsIgnoreLine.

					$memory_limit  = ini_get( 'memory_limit' );
					$memory_usage  = round( @memory_get_usage( false ) / 1048576, 1 );// @codingStandardsIgnoreLine.
					$memory_usage2 = round( @memory_get_usage( true ) / 1048576, 1 );// @codingStandardsIgnoreLine.

					@set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );// @codingStandardsIgnoreLine.
					@ignore_user_abort( true );// @codingStandardsIgnoreLine.
					$max_execution_time = (int) @ini_get( 'max_execution_time' );// @codingStandardsIgnoreLine.

					$logline = 'Backup Bank WordPress Backup plugin : WP: ' . $wp_version . ' PHP: ' . phpversion() . ' (' . @php_uname() . ") MySQL: $mysql_version Server: " . $_SERVER['SERVER_SOFTWARE'] . " safe_mode: $safe_mode max_execution_time: $max_execution_time memory_limit: $memory_limit (used: ${memory_usage}M | ${memory_usage2}M) multisite: " . ( ( is_multisite() ) ? 'Y' : 'N' );// @codingStandardsIgnoreLine.

					$this->backup_bank_log( $logline . "\r\n" );

					$disk_free_space = @disk_free_space( $this->upload_path );// @codingStandardsIgnoreLine.
					if ( false == $disk_free_space ) {// WPCS: Loose comparison ok.
						$this->backup_bank_log( "Unknown Free space in your disk containing Backup Bank Directory.\r\n" );
					} else {
						$this->backup_bank_log( 'Only <b>' . round( $disk_free_space / 1048576, 1 ) . " Mb</b> space left in your Disk.\r\n" );
						$disk_free_mb = round( $disk_free_space / 1048576, 1 );
						if ( $disk_free_space < 50 * 1048576 ) {
							$this->backup_bank_log( sprintf( "Only <b>%s Mb</b> space left in your Disk.\r\n", round( $disk_free_space / 1048576, 1 ) ), 'warning', 'lowdiskspace' . $disk_free_mb );
						}
					}
				}
				/**
				 * This function is used to compres the database file.
				 *
				 * @param string $file .
				 * @param string $type .
				 */
				public function compress_database_file_backup_bank( $file, $type ) {
					$this->backup_bank_log( "Compressing Database file.\r\n" );
					switch ( $type ) {
						case '.sql.zip':
							$compress_file_name = str_replace( '.sql', '.sql.zip', $file );
							$file_name          = basename( $file );
							$zip                = new $this->use_zip_object();
							$create_file        = ( version_compare( PHP_VERSION, '5.2.12', '>' ) && defined( 'ZIPARCHIVE::CREATE' ) ) ? ZIPARCHIVE::CREATE : 1;
							$zip->open( $compress_file_name, $create_file );
							$zip->addFile( $file, $file_name );
							$zip->close();
							@unlink( $file );// @codingStandardsIgnoreLine.
							break;

						case '.sql':
							$compress_file_name = $file;
							break;

						case '.sql.gz':
							$compress_file_name = str_replace( '.sql', '.sql.gz', $file );
							$zip                = new Archive_Tar( $compress_file_name, '.sql.gz', $this->backup_type, $this->database_file_name, $this->backup_destination, $this->backup_file, 'manual' );
							$file_name          = basename( $file );
							$zip->addModify( $file, $file_name );
							@unlink( $file );// @codingStandardsIgnoreLine.
							break;
					}
					return $compress_file_name;
				}
				/**
				 * This function is used to return database name.
				 *
				 * @param array $a .
				 */
				public function get_table_names_backup_bank( $a ) {
					return $a['name'];
				}
				/**
				 * This function is used to return base type.
				 *
				 * @param array $a .
				 */
				public function get_base_type_backup_bank( $a ) {
					return array(
						'name' => $a[0],
						'type' => 'BASE TABLE',
					);
				}
				/**
				 * This function is used to return name type.
				 *
				 * @param array $a .
				 */
				public function get_name_type_backup_bank( $a ) {
					return array(
						'name' => $a[0],
						'type' => $a[1],
					);
				}
				/**
				 * This function is used to create a database backup.
				 *
				 * @param array $dbinfo .
				 */
				public function database_backup_bank( $dbinfo = array() ) {
					global $wpdb;
					$check_file = $this->upload_path . '/' . $this->archive_name . $this->db_compression_type;
					if ( file_exists( $check_file ) ) {
						$this->status = 'file_exists';
						$this->backup_bank_log( 'File <b>' . basename( $check_file ) . "</b> already Exists.\r\n" );
						return $this->status;
					}
					$this->wpdb_obj     = $wpdb;
					$this->table_prefix = $this->table_prefix_backup_bank();
					$dbinfo['host']     = DB_HOST;
					$dbinfo['name']     = DB_NAME;
					$dbinfo['user']     = DB_USER;
					$dbinfo['pass']     = DB_PASSWORD;
					$this->dbinfo       = $dbinfo;
					$errors             = 0;

					$this->total_tables = 0;
					$all_tables         = $wpdb->get_results( 'SHOW FULL TABLES', ARRAY_N );// WPCS: db call ok, no-cache ok.
					if ( empty( $all_tables ) && ! empty( $this->wpdb_obj->last_error ) ) {
						$all_tables = $this->wpdb_obj->get_results( 'SHOW TABLES', ARRAY_N );
						$all_tables = array_map( array( $this, 'get_base_type_backup_bank'), $all_tables );// @codingStandardsIgnoreLine.
					} else {
						$all_tables = array_map( array( $this, 'get_name_type_backup_bank'), $all_tables );// @codingStandardsIgnoreLine.
					}
					if ( 0 == count( $all_tables ) ) {// WPCS: Loose comparison ok.
						$this->status = 'terminated';
						$this->backup_bank_log( "Error: Database Tables not found.\r\n" );
						return $this->status;
					}

					$this->backup_bank_log( 'Starting Sorting Tables.' . "\r\n" );
					usort( $all_tables, array( $this, 'database_sorttables_backup_bank' ) );

					$all_table_names = array_map( array($this, 'get_table_names_backup_bank'), $all_tables );// @codingStandardsIgnoreLine.

					if ( ! $this->is_writable_backup_bank( $this->upload_path ) ) {
						$this->backup_bank_log( 'Your Database Backup failed as Directory <b>' . $this->upload_path . "</b> is not writable.\r\n" );
					}

					$this->duplicate_tables_exist = false;
					if ( isset( $all_table_names ) && count( $all_table_names ) > 0 ) {
						foreach ( $all_table_names as $table ) {
							if ( strtolower( $table ) != $table && in_array( strtolower( $table ), $all_table_names ) ) {// @codingStandardsIgnoreLine.
								$this->duplicate_tables_exist = true;
								$this->backup_bank_log( "Table names differs only based on case-sensitivity $table / " . strtolower( $table ) . "\r\n" );
							}
						}
						$this->how_many_tables = count( explode( ',', $this->backup_bank_data['backup_tables'] ) );
					}

					$stitch_files = array();
					if ( isset( $all_tables ) && count( $all_tables ) > 0 ) {
						foreach ( $all_tables as $ti ) {
							$table = $ti['name'];
							if ( in_array( $table, explode( ',', $this->backup_bank_data['backup_tables'] ) ) ) {// @codingStandardsIgnoreLine.
								$table_type = $ti['type'];
								$this->total_tables++;

								@set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );// @codingStandardsIgnoreLine.
								$table_file_prefix = $this->archive_name . '-table-' . $table . '.table';

								if ( file_exists( $this->upload_path . '/' . $table_file_prefix . '.sql' ) ) {
									$this->backup_bank_log( "$table File already Exists.\r\n" );
									$stitch_files[] = $table_file_prefix;
								} else {
									$opened = $this->open_database_file_backup_bank( $this->upload_path . '/' . $table_file_prefix . '.tmp.sql' );
									if ( false === $opened ) {
										$this->status = 'terminated';
										$this->backup_bank_log( 'File <b>' . $this->upload_path . '/' . $table_file_prefix . ".tmp.sql</b> has been Failed to open.\r\n" );
										return $this->status;
									}

									$this->backup_bank_store( '# Table Name: ' . $this->backup_bank_backquote( $table ) . "\n" );

									$table_status = $this->wpdb_obj->get_row( "SHOW TABLE STATUS WHERE Name='$table'" );
									$tablename    = '<b>' . $table . '</b>';

									if ( isset( $table_status->Rows ) ) {// @codingStandardsIgnoreLine.
										$this->backup_bank_log( "Table found $tablename.\r\n" );
										$this->backup_bank_log( "Starting Compressing Table $tablename.\r\n" );
										$this->backup_bank_log( "Total Rows found <b>$table_status->Rows </b>in $tablename.\r\n" );
										$this->backup_bank_store( "# Total Rows found $table_status->Rows in $table \n" );
										if ( $table_status->Rows > BACKUP_BANK_WARN_DB_ROWS ) {// @codingStandardsIgnoreLine.
											$this->backup_bank_log( "Rows in Table $tablename has been increased to its unexpected size.\r\n", 'warning', 'manyrows_' . $table );
										}
									}

									$this->table_backup_bank( $table, $where = '', $table_type );// @codingStandardsIgnoreLine.
									$this->close();

									rename( $this->upload_path . '/' . $table_file_prefix . '.tmp.sql', $this->upload_path . '/' . $table_file_prefix . '.sql' );// @codingStandardsIgnoreLine.
									$stitch_files[] = $table_file_prefix;
								}
							}
						}
					}

					$time_this_run = time() - $this->opened_log_time;
					if ( $time_this_run > 2000 ) {
						$this->status = 'terminated';
						$this->backup_bank_log( "Process had been running for a very long time that leads to failure of Backup.\r\n" );
						return $this->status;
					}

					$backup_final_file_name = $this->upload_path . '/' . $this->archive_name . '.sql';

					if ( false === $this->open_database_file_backup_bank( $backup_final_file_name ) ) {
						return false;
					}

					$this->database_backup_header_backup_bank();

					$unlink_files = array();

					$sind = 1;
					if ( isset( $stitch_files ) && count( $stitch_files ) > 0 ) {
						foreach ( $stitch_files as $table_file ) {
							$this->backup_bank_log( "<b>{$table_file}.sql ($sind/$this->how_many_tables) :</b> Added to Final Database.\r\n" );
							if ( ! $handle = fopen( $this->upload_path . '/' . $table_file . '.sql', 'r' ) ) {// @codingStandardsIgnoreLine.
								$this->backup_bank_log( "Database File <b>{$table_file}.sql</b> failed to open.\r\n" );
								$errors++;
							} else {
								while ( $line = fgets( $handle, 2048 ) ) {// @codingStandardsIgnoreLine.
									$this->backup_bank_store( $line );
								}
								fclose( $handle );// @codingStandardsIgnoreLine.
								$unlink_files[] = $this->upload_path . '/' . $table_file . '.sql';
							}
							$sind++;
						}
					}

					if ( defined( 'DB_CHARSET' ) ) {
						$this->backup_bank_store( "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n" );
					}

					$this->backup_bank_log( 'Finishing file writing of <b>' . $this->archive_name . '.sql <b>file (Size:<b>' . round( filesize( $backup_final_file_name ) / 1048576, 1 ) . " Mb</b>).\r\n" );
					if ( ! $this->close() ) {
						$this->backup_bank_log( "Error occurred while closing Database file.\r\n" );
						$errors++;
					}
					if ( isset( $unlink_files ) && count( $unlink_files ) > 0 ) {
						foreach ( $unlink_files as $unlink_file ) {
							@unlink( $unlink_file );// @codingStandardsIgnoreLine.
						}
					}

					if ( $errors > 0 ) {
						$this->status = 'terminated';
						$this->backup_bank_log( "Database backup has been Failed.\r\n" );
						return $this->status;
					} else {
						$this->backup_completed  = 'complete_backup' == $this->backup_type ? '' : 100;// WPCS: Loose Comparison ok.
						$backup_file             = $this->compress_database_file_backup_bank( $backup_final_file_name, $this->backup_bank_data['db_compression_type'] );
						$this->database_filesize = filesize( $backup_file );
						$this->backup_bank_log( "<b>$this->total_tables</b> Tables are Successfully Backed up.\r\n" );
						if ( 'only_database' == $this->backup_type ) {// WPCS: Loose Comparison ok.
							$this->timetaken     = max( microtime( true ) - $this->zip_microtime_start, 0.000001 );
							$this->log_timetaken = microtime( true ) - $this->zip_microtime_start;
							$this->kbsize        = round( filesize( $backup_file ) / 1048576, 1 );
							if ( 'local_folder' == $this->backup_destination ) {// WPCS: Loose Comparison ok.
								$this->status = 'completed_successfully';
							}
						}
						$this->backup_bank_log( "Completed Backup for Database.\r\n" );
						if ( 'complete_backup' != $this->backup_type && 'local_folder' != $this->backup_destination ) {// WPCS: Loose Comparison ok.
							$this->status = 'completed';
							$file         = 'Database';
							$this->backup_destination_backup_bank( $file );
						}

						return 'complete_backup' == $this->backup_type ? $backup_file : $this->status;// WPCS: Loose Comparison ok.
					}
				}
				/**
				 * This function is used to take System Files Backup.
				 *
				 * @param string $backup_bank_backup_type .
				 */
				public function get_backup_type_files_backup_bank( $backup_bank_backup_type ) {
					switch ( $backup_bank_backup_type ) {
						case 'only_themes':
							$backup_type_array = array( 'Themes' => WP_CONTENT_DIR . '/themes' );
							break;

						case 'only_plugins':
							$backup_type_array = array( 'Plugins' => untrailingslashit( WP_PLUGIN_DIR ) );
							break;

						case 'only_wp_content_folder':
							$backup_type_array = array( 'Contents' => untrailingslashit( BACKUP_BANK_CONTENT_DIR ) );
							break;

						case 'complete_backup':
							$backup_type_array = array( 'Complete' => untrailingslashit( ABSPATH ) );
							break;

						case 'only_filesystem':
							$backup_type_array = array( 'Filesystem' => untrailingslashit( ABSPATH ) );
							break;

						case 'only_plugins_and_themes':
							$backup_type_array = array( 'Plugins_Themes' => array( untrailingslashit( WP_PLUGIN_DIR ), WP_CONTENT_DIR . '/themes' ) );
							break;
					}
					return $backup_type_array;
				}
				/**
				 * This function is used to add zip file recursively.
				 *
				 * @param string $full_filepath .
				 * @param string $use_path_when_storing .
				 * @param string $original_fullpath .
				 */
				public function recursively_addzip_backup_bank( $full_filepath, $use_path_when_storing, $original_fullpath ) {
					$full_filepath     = realpath( $full_filepath );
					$original_fullpath = realpath( $original_fullpath );

					if ( is_dir( $full_filepath ) ) {
						if ( $full_filepath == $this->upload_dir_realpath ) {// WPCS: Loose comparison ok.
							$skipped_dir_name = str_replace( realpath( dirname( ABSPATH ) ) . '\\', '', realpath( BACKUP_BANK_BACKUPS_DIR ) );
							$this->backup_bank_log( "Directory Path <b>$skipped_dir_name</b> has been Skipped.\r\n" );
							return true;
						}

						$this->zipfiles_dirbatched[] = $use_path_when_storing;
						if ( ! $dir_handle = @opendir( $full_filepath ) ) {// @codingStandardsIgnoreLine.
							$this->backup_bank_log( "Directory <b>$full_filepath</b> has been Failed to open.\r\n" );
							return false;
						}

						while ( false !== ( $e = readdir( $dir_handle ) ) ) {// @codingStandardsIgnoreLine.
							if ( '.' == $e || '..' == $e ) {// WPCS: Loose comparison ok.
								continue;
							}
							if ( is_file( $full_filepath . '/' . $e ) ) {
								$file_extention = strstr( basename( $full_filepath . '/' . $e ), '.' );
								if ( in_array( $file_extention, $this->exclude_list ) ) {// @codingStandardsIgnoreLine.
									continue;
								}
								if ( is_readable( $full_filepath . '/' . $e ) ) {
									$store_path = '' == $use_path_when_storing ? $e : $use_path_when_storing . '/' . $e;// WPCS: Loose comparison ok.
									$this->zipfiles_batched[ $full_filepath . '/' . $e ] = $store_path;
									$this->makezip_recursive_batchedbytes               += @filesize( $full_filepath . '/' . $e );// @codingStandardsIgnoreLine.
								} else {
									$this->status = 'terminated';
									$this->backup_bank_log( "Backup of <b>$full_filepath/$e</b> File has been Failed as <b>$full_filepath/$e</b> File is not readable.\r\n" );
									return $this->status;
								}
							} elseif ( is_dir( $full_filepath . '/' . $e ) ) {
								$store_path = '' == $use_path_when_storing ? $e : $use_path_when_storing . '/' . $e;// WPCS: Loose comparison ok.
								$this->recursively_addzip_backup_bank( $full_filepath . '/' . $e, $store_path, $original_fullpath );
							}
						}
						closedir( $dir_handle );
					} else {
						$this->backup_bank_log( "File Path <b>$use_path_when_storing</b> is Unexpected.\r\n" );
					}
					return true;
				}
				/**
				 * This function is used to add files to zip.
				 */
				public function zip_addfiles_backup_bank() {
					$this->count_zipfiles_batched = count( $this->zipfiles_batched );
					$ret                          = true;
					$zipfile                      = $this->zip_basename . $this->file_compression_type . '.tmp';
					if ( 0 == count( $this->zipfiles_dirbatched ) && 0 == count( $this->zipfiles_batched ) ) {// WPCS: Loose comparison ok.
						return true;
					}
					$force_allinone = true;
					if ( 'Backup_bank_PclZip' == $this->use_zip_object ) {// WPCS: Loose comparison ok.
						$force_allinone = false;
					}
					$data_added_since_reopen      = 0;
					$zipfiles_added_over_maxbatch = 0;

					$message  = '{' . "\r\n";
					$message .= '"name": "WP Backup Bank"' . "\r\n";
					$message .= '}';
					@file_put_contents( $this->upload_path . '/' . $this->archive_name . '-' . $this->backup_type . '.json', $message );// @codingStandardsIgnoreLine.
					$zip = $this->file_compression_backup_bank( $zipfile );
					$zip->addFile( $this->upload_path . '/' . $this->archive_name . '-' . $this->backup_type . '.json', $this->archive_name . '-' . $this->backup_type . '.json' );
					if ( ! $force_allinone ) {
						$zip->addFiles_unset();
					} else {
						unset( $zip );
						$zip = $this->file_compression_backup_bank( $zipfile );
					}
					unlink( $this->upload_path . '/' . $this->archive_name . '-' . $this->backup_type . '.json' );// @codingStandardsIgnoreLine.

					$zip                = $this->file_compression_backup_bank( $zipfile );
					$system_file_name   = $this->upload_path . '/' . $this->archive_name . $this->file_compression_type;
					$database_file_name = $this->upload_path . '/' . $this->archive_name . $this->db_compression_type;
					if ( '' != $this->backup_file_path ) {// WPCS: Loose comparison ok.
						$this->backup_bank_log( 'Adding Compressed Sql Database File <b>' . basename( $database_file_name ) . '</b> to <b>' . basename( $system_file_name ) . "</b>.\r\n" );
						$zip->addFile( $this->backup_file_path, basename( $this->backup_file_path ) );
						if ( ! $force_allinone ) {
							$zip->addFiles_unset();
						} else {
							unset( $zip );
							$zip = $this->file_compression_backup_bank( $zipfile );
						}
					}
					'' != $this->backup_file_path ? unlink( $this->backup_file_path ) : '';// @codingStandardsIgnoreLine.

					while ( $dir = array_pop( $this->zipfiles_dirbatched ) ) {// @codingStandardsIgnoreLine.
						$zip->addEmptyDir( $dir );
					}
					$zipfiles_added_thisbatch = 0;
					if ( isset( $this->zipfiles_batched ) && count( $this->zipfiles_batched ) > 0 ) {
						foreach ( $this->zipfiles_batched as $file => $add_as ) {
							if ( ! file_exists( $file ) ) {
								$this->backup_bank_log( 'Dropping File<b>' . $add_as . "</b>\r\n" );
								continue;
							}
							$fsize = filesize( $file );

							if ( $fsize > BACKUP_BANK_WARN_FILE_SIZE ) {
								$this->backup_bank_log( "File <b>$add_as</b> of size <b>" . round( $fsize / 1048576, 1 ) . "Mb</b> has been Encountered.\r\n", 'warning', 'vlargefile_' . md5( $this->filename . '#' . $add_as ) );
							}
							@touch( $zipfile );// @codingStandardsIgnoreLine.
							$zip->addFile( $file, $add_as );
							$zipfiles_added_thisbatch++;
							$this->zipfiles_added_thisrun++;
							$data_added_since_reopen      += $fsize;
							$zipfiles_added_over_maxbatch += $fsize;

							$maxzipbatch = 26214400;
							$this->zipfiles_added++;

							if ( $force_allinone ) {
								if ( $zipfiles_added_over_maxbatch > $maxzipbatch ) {
									@set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );// @codingStandardsIgnoreLine.
									$zipfiles_added_thisbatch = 0;
									unset( $zip );
									$zipfiles_added_over_maxbatch = 0;
									clearstatcache();
									if ( empty( $zip ) ) {
										$zip = $this->file_compression_backup_bank( $zipfile );
									}
								}

								if ( $this->zipfiles_added == $this->count_zipfiles_batched ) {// WPCS: Loose comparison ok.
									@set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );// @codingStandardsIgnoreLine.
									$zipfiles_added_thisbatch = 0;
									unset( $zip );
									$zipfiles_added_over_maxbatch = 0;
									if ( empty( $zip ) ) {
										$zip = $this->file_compression_backup_bank( $zipfile );
									}
									clearstatcache();
								}
							} else {
								if ( $zipfiles_added_over_maxbatch > $maxzipbatch ) {
									@set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );// @codingStandardsIgnoreLine.
									$zip->addFiles_unset();
									$zipfiles_added_over_maxbatch = 0;
									clearstatcache();
								}
								if ( $this->zipfiles_added == $this->count_zipfiles_batched ) {// WPCS: Loose comparison ok.
									@set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );// @codingStandardsIgnoreLine.
									$zip->addFiles_unset();
									$zipfiles_added_over_maxbatch = 0;
									clearstatcache();
								}
							}

							$this->files_size_added = round( $data_added_since_reopen / 1048576, 1 );
							$this->total_files_size = round( $this->makezip_recursive_batchedbytes / 1048576, 1 );

							if ( 0 == $this->zipfiles_added % 100 || $this->zipfiles_added == $this->count_zipfiles_batched ) {// WPCS: Loose comparison ok.
								$this->backup_bank_log( 'Zip Compression : <b>' . $this->zipfiles_added . '</b> Files out of <b>' . $this->count_zipfiles_batched . '</b> Files added on <b>' . basename( $zipfile ) . '</b> <br/> Completed (<b>' . round( $data_added_since_reopen / 1048576, 1 ) . 'Mb</b> out of <b>' . round( $this->makezip_recursive_batchedbytes / 1048576, 1 ) . "Mb</b>).\r\n" );
							}
						}
					}
					$this->zipfiles_batched = array();

					$nret = $zip->close();

					unset( $zip );
					clearstatcache();
					return ( false == $ret ) ? false : $nret;// WPCS: Loose comparison ok.
				}
				/**
				 * This function is used to create zip file.
				 *
				 * @param string $source .
				 * @param string $file_path .
				 * @param string $backup_filename .
				 * @param string $filename .
				 */
				public function makezipfile_backup_bank( $source, $file_path, $backup_filename, $filename ) {
					$tmp_file = $this->upload_path . '/' . $file_path . '.tmp';
					if ( file_exists( $tmp_file ) ) {
						$this->backup_bank_log( 'File <b>' . basename( $tmp_file ) . "</b> has been removed as Zip file already Exists.\r\n" );
						@unlink( $tmp_file );// @codingStandardsIgnoreLine.
					}
					$this->zipfiles_added         = 0;
					$this->zipfiles_added_thisrun = 0;
					$this->zipfiles_dirbatched    = array();
					$this->zipfiles_batched       = array();
					$this->zip_basename           = $this->upload_path . '/' . $backup_filename;

					$error_occurred                       = false;
					$this->makezip_recursive_batchedbytes = 0;
					if ( ! is_array( $source ) ) {
						$source = array( $source );
					}
					if ( isset( $source ) && count( $source ) > 0 ) {
						foreach ( $source as $element ) {
							$use_path = 'only_plugins_and_themes' != $this->backup_type ? $this->archive_name : $this->archive_name . '/' . basename( $element );// WPCS: Loose comparison ok.
							$add_them = $this->recursively_addzip_backup_bank( $element, $use_path, $element );
							if ( is_wp_error( $add_them ) || false === $add_them ) {
								$error_occurred = true;
							}
						}
					}
					if ( count( $this->zipfiles_dirbatched ) > 0 || count( $this->zipfiles_batched ) > 0 ) {
						$this->backup_bank_log( '<b>' . count( $this->zipfiles_dirbatched ) . ' </b>Directories,<b> ' . count( $this->zipfiles_batched ) . ' </b>Files of size <b>' . round( $this->makezip_recursive_batchedbytes / 1048576, 1 ) . " Mb</b> are the total entities for the Zip file.\r\n" );
						$add_them = $this->zip_addfiles_backup_bank();

						if ( is_wp_error( $add_them ) ) {
							foreach ( $add_them->get_error_messages() as $msg ) {
								$this->backup_bank_log( "zip_addfiles_backup_bank returned an error <b>$msg</b>.\r\n" );
							}
							$error_occurred = true;
						} elseif ( false === $add_them ) {
							$this->backup_bank_log( "zip_addfiles_backup_bank returned false.\r\n" );
							$error_occurred = true;
						}
					}
					if ( false == $error_occurred || $this->zipfiles_added > 0 ) {// WPCS: Loose comparison ok.
						return true;
					} else {
						$this->backup_bank_log( 'Error occurred while adding Zipfiles <b>' . $this->zipfiles_added . '</b> (Method=<b>' . $this->use_zip_object . "</b>)\r\n" );
						return false;
					}
				}
				/**
				 * This function is used to create zip file.
				 *
				 * @param string $dirname .
				 * @param string $filename .
				 * @param string $backup_filename .
				 */
				public function create_zip_backup_bank( $dirname, $filename, $backup_filename ) {
					@set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );// @codingStandardsIgnoreLine.

					$this->filename = $filename;
					$this->backup_bank_log( "Starting Compression for <b>$filename</b> Backup.\r\n" );

					if ( is_string( $dirname ) && ! file_exists( $dirname ) ) {
						$this->backup_bank_log( "Backup of <b>$filename</b> File has failed as Directory <b>$dirname</b> does not Exist.\r\n" );
						return false;
					}
					$file_path        = $backup_filename . $this->file_compression_type;
					$backup_full_path = $this->upload_path . '/' . $file_path;

					clearstatcache();
					$zipcode = $this->makezipfile_backup_bank( $dirname, $file_path, $backup_filename, $filename );
					if ( true !== $zipcode ) {
						$this->status = 'terminated';
						$this->backup_bank_log( "Error occrred while creating <b>$filename</b> zip file.\r\n" );
					} else {
						if ( file_exists( $backup_full_path . '.tmp' ) ) {
							if ( 0 === @filesize( $backup_full_path . '.tmp' ) ) {// @codingStandardsIgnoreLine.
								$this->status = 'terminated';
								$this->backup_bank_log( "Backup of <b>$filename</b> zip has been Failed.\r\n" );
								@unlink( $backup_full_path . '.tmp' );// @codingStandardsIgnoreLine.
							} else {
								$this->status           = 'completed_successfully';
								$this->backup_completed = 100;
								@rename( $backup_full_path . '.tmp', $backup_full_path );// @codingStandardsIgnoreLine.
								$this->kbsize        = round( filesize( $backup_full_path ) / 1048576, 1 );
								$this->log_timetaken = microtime( true ) - $this->zip_microtime_start;
								$this->timetaken     = max( microtime( true ) - $this->zip_microtime_start, 0.000001 );
								$zip_creating_rate   = round( $this->kbsize / $this->timetaken, 1 );
								$this->backup_bank_log( 'Total Size on Disk : <b>' . round( $this->kbsize, 1 ) . " Mb</b> Transferred @ <b>$zip_creating_rate Mb/s</b>.<br/>Completed Backup Successfully.\r\n" );
							}
						} else {
							$this->status = 'terminated';
							$this->backup_bank_log( 'File <b>' . basename( $backup_full_path ) . ".tmp</b> not Found.\r\n", 'warning' );
						}
					}
					return $this->status;
				}
				/**
				 * This function is used to get directories.
				 */
				public function get_directories_backup_bank() {
					$check_file = $this->upload_path . '/' . $this->archive_name . $this->file_compression_type;
					if ( file_exists( $check_file ) ) {
						$this->backup_completed = 1;
						$this->status           = 'file_exists';
						$this->backup_bank_log( 'File <b>' . basename( $check_file ) . "</b> already Exists.\r\n" );
					} else {
						if ( 'complete_backup' == $this->backup_type ) {// WPCS: Loose comparison ok.
							$this->backup_file_path = $this->database_backup_bank();
							if ( 'terminated' == $this->backup_file_path || 'file_exists' == $this->backup_file_path ) {// WPCS: Loose comparison ok.
								return $this->backup_file_path;
							}
						}

						$backup_filename  = $this->archive_name;
						$backup_filetypes = $this->get_backup_type_files_backup_bank( $this->backup_type );

						if ( ! $this->is_writable_backup_bank( BACKUP_BANK_BACKUPS_DIR ) ) {
							$this->backup_bank_log( 'Backup Directory (' . $this->upload_path . ") is not writable.\r\n" );
							return array();
						}

						if ( isset( $backup_filetypes ) && count( $backup_filetypes ) > 0 ) {
							foreach ( $backup_filetypes as $filename => $file_dir ) {
								$this->backup_file = $filename;
								if ( '.zip' == $this->file_compression_type ) {// WPCS: Loose comparison ok.
									if ( '' != $file_dir ) { // WPCS:loose comparison ok .
										$backup_path = $this->create_zip_backup_bank( $file_dir, $filename, $backup_filename );
										if ( 'terminated' == $backup_path ) {// WPCS: Loose comparison ok.
											$this->status = 'terminated';
											$this->backup_bank_log( "Error occurred while creating <b>$filename</b> zip file.\r\n" );
										}
									} else {
										$this->status = 'terminated';
										$this->backup_bank_log( "Backup of <b>$filename</b> has Failed.\r\n" );
									}
									if ( 'local_folder' != $this->backup_destination ) {// WPCS: Loose comparison ok.
										$this->status           = 'completed';
										$this->backup_completed = 100;
										$this->backup_destination_backup_bank( $this->backup_file );
									}
								} else {
									$this->backup_bank_tar_compression( $filename, $file_dir );
								}
							}
						}
						if ( 'terminated' == $this->status || 'file_exists' == $this->status ) {// WPCS: Loose comparison ok.
							$database_file = $this->upload_path . '/' . $this->archive_name . $this->db_compression_type;
							@unlink( $database_file );// @codingStandardsIgnoreLine.
						}
					}

					return $this->status;
				}
				/**
				 * This function is used to compress the file.
				 *
				 * @param string $zipfile .
				 */
				public function file_compression_backup_bank( $zipfile ) {
					switch ( $this->file_compression_type ) {
						case '.zip':
							$zip = new $this->use_zip_object();
							if ( file_exists( $zipfile ) ) {
								$openfile = $zip->open( $zipfile );
								clearstatcache();
							} else {
								$create_file = ( version_compare( PHP_VERSION, '5.2.12', '>' ) && defined( 'ZIPARCHIVE::CREATE' ) ) ? ZIPARCHIVE::CREATE : 1;
								$openfile    = $zip->open( $zipfile, $create_file );
							}
							if ( true !== $openfile ) {
								$this->backup_bank_log( $zip->last_error . "\r\n" );
								die();
							}
							break;
					}
					return $zip;
				}
				/**
				 * This function is used to compress the file in tar format.
				 *
				 * @param string $filename .
				 * @param string $file_dir .
				 */
				public function backup_bank_tar_compression( $filename, $file_dir ) {
					$this->backup_bank_log( "Starting Compression for <b>$filename</b> Backup.\r\n" );
					$zipfile                  = $this->upload_path . '/' . $this->archive_name . $this->file_compression_type;
					$this->database_file_name = $this->upload_path . '/' . $this->archive_name . $this->db_compression_type;
					$tar                      = new Archive_Tar( $zipfile, $this->file_compression_type, $this->backup_type, $this->database_file_name, $this->backup_destination, $this->backup_file, 'manual' );
					'' != $this->backup_file_path ? $tar->create( $file_dir, $this->exclude_list, $this->backup_file_path ) : $tar->create( $file_dir, $this->exclude_list, '' );// WPCS: Loose comparison ok.
					$this->status        = $tar->status;
					$this->kbsize        = $tar->kbsize;
					$this->timetaken     = $tar->timetaken;
					$this->log_timetaken = $tar->log_timetaken;
				}
			}
		}

		if ( ! class_exists( 'backup_bank_restore' ) ) {
			class backup_bank_restore {// @codingStandardsIgnoreLine.
				/**
				 * The object of backup.
				 *
				 * @access   public
				 * @var      string    $obj_backup_data_backup_bank.
				 */
				public $obj_backup_data_backup_bank;
				/**
				 * The filename of restore.
				 *
				 * @access   public
				 * @var      string    $restore_filename.
				 */
				public $restore_filename;
				/**
				 * The status of restore.
				 *
				 * @access   public
				 * @var      string    $restore_status.
				 */
				public $restore_status;
				/**
				 * The time taken for restore.
				 *
				 * @access   public
				 * @var      string    $restore_timetaken.
				 */
				public $restore_timetaken;
				/**
				 * The restore start time in microtime.
				 *
				 * @access   public
				 * @var      string    $restore_microtime_start.
				 */
				public $restore_microtime_start;
				/**
				 * The files for restore batched.
				 *
				 * @access   public
				 * @var      string    $restore_files_batched.
				 */
				public $restore_files_batched = array();
				/**
				 * The array of the restore directory.
				 *
				 * @access   public
				 * @var      string    $restore_dirbatched.
				 */
				public $restore_dirbatched = array();
				/**
				 * The files restored.
				 *
				 * @access   public
				 * @var      string    $files_restored.
				 */
				public $files_restored;
				/**
				 * The completed status for restore.
				 *
				 * @access   public
				 * @var      string    $restore_completed.
				 */
				public $restore_completed = '';
				/**
				 * The number of files.
				 *
				 * @access   public
				 * @var      string    $count_files.
				 */
				public $count_files;
				/**
				 * The name of the logfile.
				 *
				 * @access   public
				 * @var      string    $logfile_name.
				 */
				public $logfile_name;
				/**
				 * The version of this plugin.
				 *
				 * @access   public
				 * @var      string    $logfile_handle.
				 */
				public $logfile_handle;
				/**
				 * The version of this plugin.
				 *
				 * @access   public
				 * @var      string    $opened_log_time.
				 */
				public $opened_log_time;
				/**
				 * The count of the database tables.
				 *
				 * @access   public
				 * @var      string    $count_database_tables.
				 */
				public $count_database_tables;
				/**
				 * The total number of database tables.
				 *
				 * @access   public
				 * @var      string    $total_database_tables.
				 */
				public $total_database_tables;
				/**
				 * It consists of database tables.
				 *
				 * @access   public
				 * @var      string    $database_tables.
				 */
				public $database_tables;
				/**
				 * The name of the table.
				 *
				 * @access   public
				 * @var      string    $new_table_name.
				 */
				public $new_table_name;
				/**
				 * It checks for multisite.
				 *
				 * @access   public
				 * @var      string    $bb_backup_is_multisite.
				 */
				public $bb_backup_is_multisite = -1;
				/**
				 * The options for restore.
				 *
				 * @access   public
				 * @var      string    $bb_restore_options.
				 */
				public $bb_restore_options;
				/**
				 * The object.
				 *
				 * @access   public
				 * @var      string    $wpdb_obj.
				 */
				public $wpdb_obj = false;
				/**
				 * It checkes for multisite.
				 *
				 * @access   public
				 * @var      string    $is_multisite.
				 */
				public $is_multisite;
				/**
				 * It consists of site_url.
				 *
				 * @access   public
				 * @var      string    $our_siteurl.
				 */
				public $our_siteurl;
				/**
				 * The line having last logged.
				 *
				 * @access   public
				 * @var      string    $line_last_logged.
				 */
				public $line_last_logged = 0;
				/**
				 * The name of the json file.
				 *
				 * @access   public
				 * @var      string    $json_file_name.
				 */
				public $json_file_name;
				/**
				 * The version of this plugin.
				 *
				 * @param array  $bb_backup_data_array .
				 * @param string $restore_path .
				 */
				public function __construct( $bb_backup_data_array, $restore_path ) {
					$this->obj_backup_data_backup_bank = new Backup_Data_Backup_Bank();
					$this->is_multisite                = is_multisite();
					$this->backup_file                 = str_replace( content_url(), WP_CONTENT_DIR, $restore_path );
					$this->backup_type                 = $bb_backup_data_array['backup_type'];
					$this->file_name_basepath          = basename( $this->backup_file );
					$extension                         = 'only_database' == $this->backup_type ? $bb_backup_data_array['db_compression_type'] : $bb_backup_data_array['file_compression_type'];// WPCS: Loose comparison ok.
					$this->archive_name                = str_replace( $extension, '', $this->file_name_basepath );
					$this->file_compression_type       = $bb_backup_data_array['file_compression_type'];
					$this->backup_folder_location      = $bb_backup_data_array['folder_location'];
					$this->db_compression_type         = $bb_backup_data_array['db_compression_type'];
					$this->database_tables             = $bb_backup_data_array['backup_tables'];
					$this->restore_directory           = trailingslashit( dirname( $bb_backup_data_array['folder_location'] ) ) . 'restore';
					! is_dir( $this->restore_directory ) ? mkdir( $this->restore_directory ) : '';// @codingStandardsIgnoreLine.
					$this->logfile_name   = $this->restore_directory . '/' . $this->archive_name . '.txt';
					$this->json_file_name = $this->restore_directory . '/' . $this->archive_name . '.json';
					$this->open_logfile_backup_bank( $this->logfile_name );
				}
				/**
				 * This function is used to get directory.
				 *
				 * @param string $src .
				 * @param string $dst .
				 */
				public function get_directory_backup_bank( $src, $dst ) {
					$dir = opendir( $src );
					@mkdir( $dst );// @codingStandardsIgnoreLine.
					while ( false !== ( $file = readdir( $dir ) ) ) {// @codingStandardsIgnoreLine.
						if ( ( '.' != $file ) && ( '..' != $file ) ) {// WPCS: Loose comparison.
							if ( is_dir( $src . '/' . $file ) ) {
								$this->get_directory_backup_bank( $src . '/' . $file, $dst . '/' . $file );
							} else {
								if ( is_dir( $dst ) ) {
									@copy( $src . '/' . $file, $dst . '/' . $file );// @codingStandardsIgnoreLine.
								}
								$this->files_restored++;
								if ( 0 == $this->files_restored % 100 ) {// WPCS: Loose comparison ok.
									$this->backup_bank_log( '<b>' . $this->files_restored . "</b> Files has been Restored.\r\n" );
								}
							}
						}
					}
					closedir( $dir );
				}
				/**
				 * This function is used to open log file.
				 *
				 * @param string $logfile_name .
				 */
				public function open_logfile_backup_bank( $logfile_name ) {
					$this->logfile_name    = $logfile_name;
					$this->logfile_handle  = fopen( $this->logfile_name, 'a' );// @codingStandardsIgnoreLine.
					$this->opened_log_time = microtime( true );
					$this->backup_bank_log( 'Log file opened on ' . date( 'r' ) . ' on ' . network_site_url() . "\r\n" );
					global $wpdb, $wp_version;
					$this->restore_microtime_start = microtime( true );
				}
				/**
				 * This function is used to write logs.
				 *
				 * @param string $line .
				 */
				public function backup_bank_log( $line ) {
					if ( $this->logfile_handle ) {
						$rtime = microtime( true ) - $this->opened_log_time;
						fwrite( $this->logfile_handle, sprintf( '%08.03f', round( $rtime, 3 ) ) . ' ' . strip_tags( $line ) );// @codingStandardsIgnoreLine.
					}

					switch ( $this->backup_type ) {
						case 'complete_backup':
							$zipfiles_database_count = '' == $this->total_database_tables ? 24 : $this->total_database_tables;// WPCS: Loose comparison ok.
							$count_database_added    = '' == $this->count_database_tables ? 1 : $this->count_database_tables;// WPCS: Loose comparison ok.
							$result                  = ceil( $count_database_added / $zipfiles_database_count * 24 );

							$zipfiles_batched_count = '' == $this->count_files ? 74 : $this->count_files;// WPCS: Loose comparison ok.
							$count_zipfiles_added   = '' == $this->files_restored ? 1 : $this->files_restored;// WPCS: Loose comparison ok.
							if ( '' == $this->restore_completed ) {// WPCS: Loose comparison ok.
								$result += floor( $count_zipfiles_added / $zipfiles_batched_count * 74 );
							} else {
								$result = $this->restore_completed;
							}
							break;

						case 'only_database':
							$zipfiles_batched_count = '' == $this->total_database_tables ? 98 : $this->total_database_tables;// WPCS: Loose comparison ok.
							$count_zipfiles_added   = '' == $this->count_database_tables ? 1 : $this->count_database_tables;// WPCS: Loose comparison ok.
							if ( '' == $this->restore_completed ) { // WPCS: Loose comparison ok.
								$result = ceil( $count_zipfiles_added / $zipfiles_batched_count * 98 );
							} else {
								$result = $this->restore_completed;
							}
							break;

						default:
							$zipfiles_batched_count = '' == $this->count_files ? 98 : $this->count_files;// WPCS: Loose comparison ok.
							$count_zipfiles_added   = '' == $this->files_restored ? 1 : $this->files_restored;// WPCS: Loose comparison ok.
							if ( '' == $this->restore_completed ) {// WPCS: Loose comparison ok.
								$result = ceil( $count_zipfiles_added / $zipfiles_batched_count * 98 );
							} else {
								$result = $this->restore_completed;
							}
					}
					$new_line = str_replace( "\r\n", '', $line );
					@file_put_contents( $this->json_file_name, '' );// @codingStandardsIgnoreLine.
					$message  = '{' . "\r\n";
					$message .= '"log": "' . $new_line . '" ,' . "\r\n";
					$message .= '"perc": ' . $result . "\r\n";
					$message .= '}';

					@file_put_contents( $this->json_file_name, $message );// @codingStandardsIgnoreLine.
				}
				/**
				 * This function is used to count the directories.
				 *
				 * @param string $full_filepath .
				 */
				public function count_directories_backup_bank( $full_filepath ) {
					$full_filepath = realpath( $full_filepath );

					if ( is_dir( $full_filepath ) ) {
						if ( ! $dir_handle = @opendir( $full_filepath ) ) {// @codingStandardsIgnoreLine.
							$this->backup_bank_log( "Directory $full_filepath has been Failed to open.\r\n" );
							return false;
						}

						while ( false !== ( $e = readdir( $dir_handle ) ) ) {// @codingStandardsIgnoreLine.
							if ( '.' == $e || '..' == $e ) {// WPCS: Loose comparison ok.
								continue;
							}
							if ( is_file( $full_filepath . '/' . $e ) ) {
								if ( is_readable( $full_filepath . '/' . $e ) ) {
									$store_path = $e;
									$this->restore_files_batched[ $full_filepath . '/' . $e ] = $store_path;
								} else {
									$this->backup_bank_log( "<b>$full_filepath/$e</b> File has been Failed to Restore as <b>$full_filepath/$e</b> File is not readable.\r\n" );
									$this->restore_status = 'restore_terminated';
									return $this->restore_status;
								}
							} elseif ( is_dir( $full_filepath . '/' . $e ) ) {
								$store_path = $e;
								$this->count_directories_backup_bank( $full_filepath . '/' . $e );
							}
						}
						closedir( $dir_handle );
					}
					return true;
				}
				/**
				 * This function is used to restore the backup.
				 */
				public function backup_bank_restore_backup() {
					set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );
					if ( ! file_exists( $this->backup_file ) ) {
						$this->restore_completed = 1;
						$this->backup_bank_log( 'File <b>' . basename( $this->backup_file ) . "</b> does not Exist.\r\n" );
						$this->restore_status = 'restore_terminated';
						return $this->restore_status;
					}
					$backupable_entities = $this->obj_backup_data_backup_bank->get_backup_type_files_backup_bank( $this->backup_type );

					if ( isset( $backupable_entities ) && count( $backupable_entities ) > 0 ) {
						foreach ( $backupable_entities as $key => $path_info ) {
							$this->restore_filename = $key;
							$basepath               = $path_info;
						}
					}
					$this->backup_bank_log( 'Starting Restoring <b>' . $this->restore_filename . "</b> Backup.\r\n" );
					$basepath = is_string( $basepath ) ? array( $basepath ) : $basepath;
					$this->backup_bank_log( 'Compression Type of <b>' . $this->restore_filename . '</b> is <b>' . $this->file_compression_type . "</b>.\r\n" );
					if ( '.tar' == $this->file_compression_type || '.tar.gz' == $this->file_compression_type ) {// WPCS: Loose comparison ok.
						$p_compress = $this->file_compression_type;
					}

					switch ( $this->file_compression_type ) {
						case '.zip':
							$this->backup_bank_log( 'Unzipping File <b>' . basename( $this->backup_file ) . '</b> to <b>' . $this->restore_directory . "</b>.\r\n" );
							$zip = new Backup_bank_PclZip();
							$zip->extract( $this->backup_file, $this->restore_directory );
							break;

						case '.tar':
						case '.tar.gz':
							$tar_obj = new Archive_tar( $this->backup_file, $p_compress, '', '', '', '', 'manual' );
							$this->backup_bank_log( 'Unzipping File <b>' . basename( $this->backup_file ) . '</b> to <b>' . $this->restore_directory . "</b>.\r\n" );
							$tar_obj->extract( $this->restore_directory, false );
							break;
					}

					if ( 'complete_backup' == $this->backup_type ) { // WPCS: Loose comparison ok.
						$database_file      = str_replace( $this->file_compression_type, '', $this->file_name_basepath );
						$database_file_name = $this->restore_directory . '/' . $database_file . $this->db_compression_type;
						$restore_db_ret     = $this->backup_bank_restore_backup_db( $database_file_name );
						if ( 'restore_terminated' == $restore_db_ret ) { // WPCS: Loose comparison ok.
							$this->remove_directory( $this->restore_directory . '/' . $this->archive_name );
							$this->restore_status = 'restore_terminated';
							return $this->restore_status;
						}
					}
					$this->count_directories_backup_bank( $this->restore_directory . '/' . $this->archive_name );
					$this->count_files = count( $this->restore_files_batched );
					if ( isset( $basepath ) && count( $basepath ) > 0 ) {
						foreach ( $basepath as $dest_directory ) {
							$type = 'only_plugins_and_themes' != $this->backup_type ? $this->archive_name : $this->archive_name . '/' . basename( $dest_directory );// WPCS: Loose comparison ok.
							$this->get_directory_backup_bank( $this->restore_directory . '/' . $type, $dest_directory );
						}
					}
					$this->remove_directory( $this->restore_directory . '/' . $this->archive_name );
					$this->backup_bank_log( 'Directory Path <b>' . $this->restore_directory . '/' . $this->archive_name . "</b> has been Removed Successfully.\r\n" );

					$this->restore_timetaken = max( microtime( true ) - $this->restore_microtime_start, 0.000001 );
					$this->restore_completed = '100';
					$this->restore_status    = 'restored_successfully';
					$this->backup_bank_log( '<b>' . $this->restore_filename . '</b> Backup has been Restored Successfully in <b>' . round( $this->restore_timetaken, 1 ) . " seconds</b>.\r\n" );
					return $this->restore_status;
				}
				/**
				 * This function is used to remove directory.
				 *
				 * @param string $dirname .
				 */
				public function remove_directory( $dirname ) {
					if ( is_dir( $dirname ) ) {
						$dir_handle = opendir( $dirname );
					}

					if ( ! $dir_handle ) {
						$this->backup_bank_log( "Directory <b>$dirname</b> has been Failed to open.\r\n" );
						$this->restore_status = 'restore_terminated';
						return $this->restore_status;
					}
					while ( $file = readdir( $dir_handle ) ) {// @codingStandardsIgnoreLine.
						if ( '.' != $file && '..' != $file ) { // WPCS: Loose comparison ok.
							if ( ! is_dir( $dirname . '/' . $file ) ) {
								@unlink( $dirname . '/' . $file );// @codingStandardsIgnoreLine.
							} else {
								$this->remove_directory( $dirname . '/' . $file );
							}
						}
					}
					closedir( $dir_handle );
					@rmdir( $dirname );// @codingStandardsIgnoreLine.
					return true;
				}
				/**
				 * This function is used to replace the string.
				 *
				 * @param string $search .
				 * @param string $replace .
				 * @param string $subject .
				 */
				public function str_lreplace( $search, $replace, $subject ) {
					$pos = strrpos( $subject, $search );
					if ( false !== $pos ) {
						$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
					}
					return $subject;
				}
				/**
				 * This function is used for backquote.
				 *
				 * @param array/string $a_name .
				 */
				public function backquote( $a_name ) {
					if ( ! empty( $a_name ) && '*' != $a_name ) {// WPCS: Loose comparison ok.
						if ( is_array( $a_name ) ) {
							$result = array();
							reset( $a_name );
							while ( list($key, $val) = each( $a_name ) ) {// @codingStandardsIgnoreLine.
								$result[ $key ] = '`' . $val . '`';
							}
							return $result;
						} else {
							return '`' . $a_name . '`';
						}
					} else {
						return $a_name;
					}
				}
				/**
				 * This function is used for maintenance mode.
				 *
				 * @param string $enable .
				 */
				public function maintenance_mode( $enable ) {
					global $wpdb;
					$enable_maintenance_mode                       = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_restore WHERE meta_key = %s', 'maintenance_mode_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$enable_maintenance_mode_data                  = maybe_unserialize( $enable_maintenance_mode );
					$obj_dbhelper_backup_bank                      = new Dbhelper_Backup_Bank();
					$message                                       = $enable_maintenance_mode_data['message_when_restore'];
					$maintenance_mode_data                         = array();
					$maintenance_mode_data['message_when_restore'] = "$message";
					$maintenance_mode_data['restoring']            = "$enable";

					$update_data               = array();
					$where                     = array();
					$where['meta_key']         = 'maintenance_mode_settings';// WPCS: Slow query.
					$update_data['meta_value'] = maybe_serialize( $maintenance_mode_data );// WPCS: Slow query.
					$obj_dbhelper_backup_bank->update_command( backup_bank_restore(), $update_data, $where );
				}
				/**
				 * This function is used for execute the sql file.
				 *
				 * @param string $sql_line .
				 * @param string $sql_type .
				 * @param string $import_table_prefix .
				 * @param string $check_skipping .
				 */
				public function sql_exec( $sql_line, $sql_type, $import_table_prefix = '', $check_skipping = true ) {
					global $wpdb;
					if ( $check_skipping && ! empty( $this->table_name ) ) {
						if ( ! isset( $this->restore_this_table[ $this->table_name ] ) ) {
							$this->restore_this_table[ $this->table_name ] = apply_filters( 'backup_bank_restore_this_table', true, substr( $this->table_name, strlen( $this->old_table_prefix ) ), $this->bb_restore_options );
						}
						if ( false === $this->restore_this_table[ $this->table_name ] ) {
							$this->backup_bank_log( '<b>' . $this->table_name . "</b> Table will not be Restored.\r\n" );
							$this->restore_this_table[ $this->table_name ] = 0;
						}
						if ( ! $this->restore_this_table[ $this->table_name ] ) {
							return;
						}
					}

					$ignore_errors = false;
					// Type 2 = CREATE TABLE.
					if ( 2 == $sql_type && $this->create_forbidden ) {// WPCS: Loose comparison ok.
						$this->backup_bank_log( 'Tables cannot be Created.So,Command ' . htmlspecialchars( $sql_line ) . " has been Skipped.\r\n" );
						$req = true;
					} else {
						if ( 2 == $sql_type && ! $this->drop_forbidden ) {// WPCS: Loose comparison ok.
							if ( ! in_array( $this->new_table_name, $this->tables_been_dropped ) ) {// @codingStandardsIgnoreLine.
								$this->backup_bank_log( '<b>' . $this->new_table_name . "</b> Table has been Dropped.\r\n" );
								$this->sql_exec( 'DROP TABLE IF EXISTS ' . esc_sql( $this->new_table_name ), 1, '', false );
								$this->tables_been_dropped[] = $this->new_table_name;
							}
						}
						// Type 1 = DROP TABLE.
						if ( 1 == $sql_type ) {// WPCS: Loose comparison ok.
							if ( $this->drop_forbidden ) {
								$sql_line = 'DELETE FROM ' . $this->backquote( $this->new_table_name );
								$this->backup_bank_log( "Tables cannot be Dropped.\r\n" );
								$ignore_errors = true;
							}
						}

						if ( 3 == $sql_type && $sql_line && strlen( $sql_line ) > $this->max_allowed_packet ) {// WPCS: Loose comparison ok.
							$logit = substr( $sql_line, 0, 100 );
							$this->backup_bank_log( sprintf( "An SQL line that is larger than the maximum packet size and cannot be split was found: %s \r\n", '(' . strlen( $sql_line ) . ', ' . $logit . ' ...)' ) );
							$this->errors++;
							if ( 0 == $this->insert_statements_run && $this->new_table_name && $this->new_table_name == $import_table_prefix . 'options' ) {// WPCS: Loose comparison ok.
								$this->backup_bank_log( "Leaving Maintenance Mode.\r\n" );
								$this->maintenance_mode( 'disable' );
								$this->backup_bank_log( "An error occurred while inserting data in Tables.\r\n" );
								$this->restore_status = 'restore_terminated';
								return $this->restore_status;
							}
							return false;
						}

						if ( $this->use_wpdb ) {
							$req = $wpdb->query( $sql_line );// WPCS: db call ok, no-cache ok, unprepared SQL ok.
							if ( ! $req ) {
								$this->last_error = $wpdb->last_error;
							}
						} else {
							if ( $this->use_mysqli ) {
								$req = mysqli_query( $this->mysql_dbh, $sql_line );// @codingStandardsIgnoreLine.
								if ( ! $req ) {
									$this->last_error = mysqli_error( $this->mysql_dbh );// @codingStandardsIgnoreLine.
								}
							} else {
								$req = mysql_unbuffered_query( $sql_line, $this->mysql_dbh );// @codingStandardsIgnoreLine.
								if ( ! $req ) {
									$this->last_error = mysql_error( $this->mysql_dbh );// @codingStandardsIgnoreLine.
								}
							}
						}
						if ( 3 == $sql_type ) {// WPCS: Loose comparison ok.
							$this->insert_statements_run++;
						}
						if ( 1 == $sql_type ) {// WPCS: Loose comparison ok.
							$this->tables_been_dropped[] = $this->new_table_name;
						}
						$this->statements_run++;
					}

					if ( ! $req ) {
						if ( ! $ignore_errors ) {
							$this->errors++;
						}
						$print_err = ( strlen( $sql_line ) > 100 ) ? substr( $sql_line, 0, 100 ) . ' ...' : $sql_line;
						$this->backup_bank_log( 'An error (' . $this->errors . ') occurred: ' . $this->last_error . " - SQL query was (type=$sql_type): " . substr( $sql_line, 0, 65536 ) . "\r\n" );
						if ( 1 == $this->errors && 2 == $sql_type && 0 == $this->tables_created ) {// WPCS: Loose comparison ok.
							if ( $this->drop_forbidden ) {
								$this->backup_bank_log( "Failed to create table as table already Exists.\r\n" );
							} else {
								$this->backup_bank_log( "Leaving Maintenance Mode.\r\n" );
								$this->maintenance_mode( 'disable' );
								$this->backup_bank_log( "An error occurred while creating Table.\r\n" );
								$this->restore_status = 'restore_terminated';
								return $this->restore_status;
							}
						} elseif ( 2 == $sql_type && 0 == $this->tables_created && $this->drop_forbidden ) {// WPCS: Loose comparison ok.
							if ( ! $ignore_errors ) {
								$this->errors--;
							}
						} elseif ( 8 == $sql_type && 1 == $this->errors ) {// WPCS: Loose comparison ok.
							$this->backup_bank_log( 'Aborted: SET NAMES ' . $this->set_names . " failed: Maintenance Mode.\r\n" );
							$this->maintenance_mode( 'disable' );
							$extra_msg = '';
							$dbv       = $wpdb->db_version();
							if ( 'utf8mb4' == strtolower( $this->set_names ) && $dbv && version_compare( $dbv, '5.2.0', '<=' ) ) {// WPCS: Loose comparison ok.
								$this->backup_bank_log( "An error occurred while restoring Database as MySQL version is very old.\r\n" );
								$extra_msg = 'This problem is caused by trying to restore a database on a very old MySQL version that is incompatible with the source database. This database needs to be deployed on MySQL version 5.5 or later.';
							}
							$this->backup_bank_log( 'Database Server does not Support <b>' . $this->set_names . " </b>character set.\r\n" );
							$this->restore_status = 'restore_terminated';
							return $this->restore_status;
						}

						if ( $this->errors > 49 ) {
							$this->maintenance_mode( 'disable' );
							$this->backup_bank_log( "Restoring Database Tables has been Terminated.\r\n" );
							$this->restore_status = 'restore_terminated';
							return $this->restore_status;
						}
					} elseif ( 2 == $sql_type ) {// WPCS: Loose comparison ok.
						$this->tables_created++;
					}

					if ( $this->line > 0 && 0 == ( $this->line ) % 50 ) {// WPCS: Loose comparison ok.
						if ( $this->line > $this->line_last_logged && ( ( $this->line ) % 250 == 0 || $this->line < 250 ) ) {// WPCS: Loose comparison ok.
							$this->line_last_logged = $this->line;
							$time_taken             = microtime( true ) - $this->start_time;
							$this->backup_bank_log( '<b>' . $this->line . '</b> Database queries has been Processed in <b>' . round( $time_taken, 1 ) . " seconds</b>.\r\n" );
						}
					}
					return $req;
				}
				/**
				 * This function is used to flush rewrite rules.
				 */
				private function flush_rewrite_rules() {
					global $wp_rewrite;
					$wp_rewrite->init();

					if ( function_exists( 'save_mod_rewrite_rules' ) ) {
						save_mod_rewrite_rules();
					}
					if ( function_exists( 'iis7_save_url_rewrite_rules' ) ) {
						iis7_save_url_rewrite_rules();
					}
				}
				/**
				 * This function is used to restore table.
				 *
				 * @param string $table .
				 * @param string $import_table_prefix .
				 * @param string $old_table_prefix .
				 */
				public function restored_table( $table, $import_table_prefix, $old_table_prefix ) {
					$table_without_prefix = substr( $table, strlen( $import_table_prefix ) );

					if ( isset( $this->restore_this_table[ $old_table_prefix . $table_without_prefix ] ) && ! $this->restore_this_table[ $old_table_prefix . $table_without_prefix ] ) {
						return;
					}
					global $wpdb;
					if ( preg_match( '/^([\d+]_)?options$/', substr( $table, strlen( $import_table_prefix ) ), $matches ) ) {
						if ( ( $this->is_multisite && ! empty( $matches[1] ) ) || $table == $import_table_prefix . 'options' ) {// WPCS: Loose comparison ok.
							$mprefix        = ( empty( $matches[1] ) ) ? '' : $matches[1];
							$new_table_name = $import_table_prefix . $mprefix . 'options';

							if ( $import_table_prefix != $old_table_prefix ) {// WPCS: Loose comparison ok.
								$this->backup_bank_log( "Table Prefix has been Changed.\r\n" );
								if ( false === $wpdb->query( "UPDATE $new_table_name SET option_name='${import_table_prefix}" . $mprefix . "user_roles' WHERE option_name='${old_table_prefix}" . $mprefix . "user_roles' LIMIT 1" ) ) {// WPCS: db call ok, no-cache ok, unprepared sql ok.
									$this->backup_bank_log( "Error occurred while changing Options Table fields.\r\n" );// WPCS: db call ok, no-cache ok.
								} else {
									$this->backup_bank_log( "Fields of Options table has been changed Successfully.\r\n" );
								}
							}

							$new_upload_path = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM ${import_table_prefix}" . $mprefix . 'options WHERE option_name = %s LIMIT 1', 'upload_path' ) );// WPCS: db call ok, no-cache ok, unprepared sql ok.
							$new_upload_path = ( is_object( $new_upload_path ) ) ? $new_upload_path->option_value : '';

							if ( ! empty( $new_upload_path ) && ( strpos( $new_upload_path, '/' ) === 0 ) || preg_match( '#^[A-Za-z]:[/\\\]#', $new_upload_path ) ) {
								if ( ! file_exists( $new_upload_path ) || $this->old_siteurl != $this->our_siteurl ) {// WPCS: Loose comparison ok.
									if ( ! file_exists( $new_upload_path ) ) {
										$this->backup_bank_log( "Uploads Path <b>$new_upload_path</b> does not Exist.\r\n" );
									} else {
										$this->backup_bank_log( "Uploads Path <b>$new_upload_path</b> has been Changed.\r\n" );
									}
								}
							}
						}
					} elseif ( $import_table_prefix != $old_table_prefix && preg_match( '/^([\d+]_)?usermeta$/', substr( $table, strlen( $import_table_prefix ) ), $matches ) ) {// WPCS: Loose comparison ok.
						$this->backup_bank_log( "<b>Usermeta</b> Table fields has been Changed.\r\n" );
						$errors_occurred = false;

						if ( false === strpos( $old_table_prefix, '_' ) ) {
							$old_prefix_length = strlen( $old_table_prefix );

							$um_sql    = "SELECT umeta_id, meta_key
								FROM ${import_table_prefix}usermeta
								WHERE meta_key
								LIKE '" . str_replace( '_', '\_', $old_table_prefix ) . "%'";
							$meta_keys = $wpdb->get_results( $um_sql );// WPCS: db call ok, no-cache ok, unprepared sql.
							if ( count( $meta_keys ) > 0 ) {
								foreach ( $meta_keys as $meta_key ) {
									$new_meta_key = $import_table_prefix . substr( $meta_key->meta_key, $old_prefix_length );
									$query        = 'UPDATE ' . $import_table_prefix . "usermeta
										SET meta_key='" . $new_meta_key . "'
										WHERE umeta_id=" . $meta_key->umeta_id;

									if ( false === $wpdb->query( $query ) ) {// WPCS: db call ok, no-cache ok, unprepared sql.
										$errors_occurred = true;// WPCS: db call ok, no-cache ok.
									}
								}
							}
						} else {
							$sql = "UPDATE ${import_table_prefix}usermeta SET meta_key = REPLACE(meta_key, '$old_table_prefix', '${import_table_prefix}') WHERE meta_key LIKE '" . str_replace( '_', '\_', $old_table_prefix ) . "%';";
							if ( false === $wpdb->query( $sql ) ) {// WPCS: db call ok, no-cache ok, unprepared sql.
								$errors_occurred = true;// WPCS: db call ok, no-cache ok.
							}
						}

						if ( $errors_occurred ) {
							$this->backup_bank_log( "An error occurred while changing field of <b>usermeta</b> Table.\r\n" );
						} else {
							$this->backup_bank_log( "Fields of <b>usermeta</b> Table has been changed Successfully.\r\n" );
						}
					}

					if ( $table == $import_table_prefix . 'options' ) {// WPCS: Loose comparison ok.
						$this->flush_rewrite_rules();
					}
				}
				/**
				 * This function is used to check db connection.
				 *
				 * @param string $handle .
				 */
				public function check_db_connection( $handle ) {
					$db_connected = -1;
					if ( is_a( $handle, 'wpdb' ) ) {
						if ( method_exists( $handle, 'check_connection' ) ) {
							if ( ! $handle->check_connection( false ) ) {
								$this->backup_bank_log( "An error occurred while making connection with Database.\r\n" );
								$db_connected = false;
							} else {
								$db_connected = true;
							}
						}
					}
					return $db_connected;
				}
				/**
				 * This function is used to get max packet size.
				 */
				public function get_max_packet_size() {
					global $wpdb;
					$mp = (int) $wpdb->get_var( 'SELECT @@session.max_allowed_packet' );// WPCS: db call ok, no-cache ok.
					$mp = ( is_numeric( $mp ) && $mp > 0 ) ? $mp : 1048576;

					if ( $mp < 33554432 ) {
						$save = $wpdb->show_errors( false );
						$req  = @$wpdb->query( 'SET GLOBAL max_allowed_packet=33554432' );// @codingStandardsIgnoreLine.
						$wpdb->show_errors( $save );
						if ( ! $req ) {
							$this->backup_bank_log( 'Failed to raise max_allowed_packet size from <b>' . round( $mp / 1048576, 1 ) . 'Mb </b> to <b>32 Mb</b>, but failed (' . $wpdb->last_error . ', ' . maybe_serialize( $req ) . ").\r\n" );
						}
						$mp = (int) $wpdb->get_var( 'SELECT @@session.max_allowed_packet' );// WPCS: db call ok, no-cache ok.
						$mp = ( is_numeric( $mp ) && $mp > 0 ) ? $mp : 1048576;
					}

					$this->backup_bank_log( 'Max Packet size is<b> ' . round( $mp / 1048576, 1 ) . " MB</b>.\r\n" );
					return $mp;
				}
				/**
				 * This function is used to restore database backup.
				 *
				 * @param string $file_path .
				 */
				public function backup_bank_restore_backup_db( $file_path ) {
					set_time_limit( BACKUP_BANK_SET_TIME_LIMIT );
					if ( ! file_exists( $file_path ) ) {
						$this->backup_bank_log( 'File <b>' . basename( $file_path ) . "</b> does not Exist.\r\n" );
						$this->restore_status = 'restore_terminated';
						return $this->restore_status;
					}
					$this->backup_bank_log( "Starting Restoring Database Backup.\r\n" );
					global $wpdb;
					$this->total_database_tables = count( explode( ',', $this->database_tables ) );
					$working_dir                 = $file_path;
					$working_dir_localpath       = $file_path;
					$import_table_prefix         = $wpdb->prefix;

					if ( @ini_get( 'safe_mode' ) && 'off' != strtolower( @ini_get( 'safe_mode' ) ) ) {// @codingStandardsIgnoreLine.
						$this->backup_bank_log( " Warning: PHP safe_mode is active on your server. Timeouts are much more likely. If these happen, then you will need to manually restore the file via phpMyAdmin or another method.\r\n" );
					}

					$is_plain = ( substr( $working_dir_localpath, -3, 3 ) == 'sql' );// WPCS: Loose comparison ok.
					$is_bz2   = ( substr( $working_dir_localpath, -7, 7 ) == 'sql.bz2' );// WPCS: Loose comparison ok.
					$is_zip   = ( substr( $working_dir_localpath, -7, 7 ) == 'sql.zip' );// WPCS: Loose comparison ok.

					if ( $is_plain ) {
						$dbhandle = fopen( $working_dir_localpath, 'r' );// @codingStandardsIgnoreLine.
					} elseif ( $is_zip ) {
						$zip = new Backup_bank_PclZip();
						$zip->extract( $working_dir_localpath, dirname( $working_dir_localpath ) );
						$working_dir_localpath = str_replace( '.sql.zip', '.sql', $working_dir_localpath );
						$dbhandle              = fopen( $working_dir_localpath, 'r' );// @codingStandardsIgnoreLine.
					} elseif ( $is_bz2 ) {
						if ( ! function_exists( 'bzopen' ) ) {
							$this->backup_bank_log( "Function <b>bzopen</b> is Disabled.\r\n" );
						}
						$dbhandle = bzopen( $working_dir_localpath, 'r' );
					} else {
						$dbhandle = gzopen( $working_dir_localpath, 'r' );
					}
					if ( ! $dbhandle ) {
						$this->backup_bank_log( "Database File <b>$working_dir_localpath</b> has been Failed to open.\r\n" );
						$this->restore_status = 'restore_terminated';
						return $this->restore_status;
					}

					$wpdb_obj         = new backup_bank_WPDB( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
					$this->mysql_dbh  = $wpdb_obj->backup_bank_getdbh();
					$this->use_mysqli = $wpdb_obj->backup_bank_use_mysqli();
					$this->line       = 0;
					$this->use_wpdb   = ( ( ! function_exists( 'mysql_query' ) && ! function_exists( 'mysqli_query' ) ) || ! $wpdb->is_mysql || ! $wpdb->ready ) ? true : false;

					if ( true == $this->use_wpdb ) {// WPCS: Loose comparison ok.
						$this->backup_bank_log( "MySQL access is not available, so will use wpdb.\r\n" );
					} else {
						$this->backup_bank_log( 'Direct MySQL access has been Used. <br/>Value of use_mysqli is <b>' . ( $this->use_mysqli ? '1' : '0' ) . "</b>.\r\n" );
						if ( $this->use_mysqli ) {
							$ret = mysqli_query( $this->mysql_dbh, 'SET SESSION query_cache_type = OFF;' );// @codingStandardsIgnoreLine.
						} else {
							@mysql_query( $this->mysql_dbh, 'SET SESSION query_cache_type = OFF;' );// @codingStandardsIgnoreLine.
						}
					}

					$supported_engines = $wpdb->get_results( 'SHOW ENGINES', OBJECT_K );// WPCS: db call ok, no-cache ok.

					$this->errors                = 0;
					$this->statements_run        = 0;
					$this->insert_statements_run = 0;
					$this->tables_created        = 0;
					$sql_line                    = '';
					$sql_type                    = -1;
					$this->start_time            = microtime( true );
					$old_wpversion               = '';
					$this->old_siteurl           = '';
					$this->old_home              = '';
					$this->old_content           = '';
					$this->old_uploads           = '';
					$this->old_table_prefix      = '';
					$old_siteinfo                = array();
					$gathering_siteinfo          = true;
					$this->create_forbidden      = false;
					$this->drop_forbidden        = false;
					$this->last_error            = '';
					$random_table_name           = 'backup_bank_tmp_' . rand( 0, 9999999 );

					if ( $this->use_wpdb ) {
						$req = $wpdb->query( 'CREATE TABLE ' . $random_table_name . '(name longtext)' );// @codingStandardsIgnoreLine.
						if ( ! $req ) {
							$this->last_error = $wpdb->last_error;
						}
						$this->last_error_no = false;
					} else {
						if ( $this->use_mysqli ) {
							$req = mysqli_query( $this->mysql_dbh, 'CREATE TABLE ' . $random_table_name . '(name longtext)' );// @codingStandardsIgnoreLine.
						} else {
							$req = mysql_unbuffered_query( 'CREATE TABLE ' . $random_table_name . '(name longtext)', $this->mysql_dbh );// @codingStandardsIgnoreLine.
						}
						if ( ! $req ) {
							$this->last_error    = ( $this->use_mysqli ) ? mysqli_error( $this->mysql_dbh ) : mysql_error( $this->mysql_dbh );// @codingStandardsIgnoreLine.
							$this->last_error_no = ( $this->use_mysqli ) ? mysqli_errno( $this->mysql_dbh ) : mysql_errno( $this->mysql_dbh );// @codingStandardsIgnoreLine.
						}
					}
					if ( ! $req && ( $this->use_wpdb || 1142 === $this->last_error_no ) ) {
						$this->create_forbidden = true;
						$this->drop_forbidden   = true;
						$this->backup_bank_log( "Database Tables are restoring by simply emptying the Tables.\r\n" );
					} else {
						if ( $this->use_wpdb ) {
							$req = $wpdb->query( "DROP TABLE $random_table_name" );// @codingStandardsIgnoreLine.
							if ( ! $req ) {
								$this->last_error = $wpdb->last_error;
							}
							$this->last_error_no = false;
						} else {
							if ( $this->use_mysqli ) {
								$req = mysqli_query( $this->mysql_dbh, "DROP TABLE $random_table_name" );// @codingStandardsIgnoreLine.
							} else {
								$req = mysql_unbuffered_query( "DROP TABLE $random_table_name", $this->mysql_dbh );// @codingStandardsIgnoreLine.
							}
							if ( ! $req ) {
								$this->last_error    = ( $this->use_mysqli ) ? mysqli_error( $this->mysql_dbh ) : mysql_error( $this->mysql_dbh );// @codingStandardsIgnoreLine.
								$this->last_error_no = ( $this->use_mysqli ) ? mysqli_errno( $this->mysql_dbh ) : mysql_errno( $this->mysql_dbh );// @codingStandardsIgnoreLine.
							}
						}
						if ( ! $req && ( $this->use_wpdb || 1142 === $this->last_error_no ) ) {
							$this->drop_forbidden = true;
							$this->backup_bank_log( "Database Tables cannot be Dropped.\r\n" );
						}
					}
					$restoring_table          = '';
					$this->max_allowed_packet = $this->get_max_packet_size();
					$this->backup_bank_log( "Entering Maintenance Mode.\r\n" );
					$this->maintenance_mode( 'enable' );
					while ( ( $is_plain && ! feof( $dbhandle ) ) || ( ! $is_plain && ( ( $is_bz2 ) || ( ! $is_bz2 && ! gzeof( $dbhandle ) ) ) ) ) {
						if ( $is_plain ) {
							$buffer = rtrim( fgets( $dbhandle, 1048576 ) );
						} elseif ( $is_bz2 ) {
							if ( ! isset( $bz2_buffer ) ) {
								$bz2_buffer = '';
							}
							$buffer = '';
							if ( strlen( $bz2_buffer ) < 524288 ) {
								$bz2_buffer .= bzread( $dbhandle, 1048576 );
							}
							if ( bzerrno( $dbhandle ) !== 0 ) {
								$this->backup_bank_log( 'bz2 error: ' . bzerrstr( $dbhandle ) . ' (code: ' . bzerrno( $bzhandle ) . ")\r\n" );
								break;
							}
							if ( false !== $bz2_buffer && '' !== $bz2_buffer ) {
								if ( false !== ( $p = strpos( $bz2_buffer, "\n" ) ) ) {// @codingStandardsIgnoreLine.
									$buffer    .= substr( $bz2_buffer, 0, $p + 1 );
									$bz2_buffer = substr( $bz2_buffer, $p + 1 );
								} else {
									$buffer    .= $bz2_buffer;
									$bz2_buffer = '';
								}
							} else {
								break;
							}
							$buffer = rtrim( $buffer );
						} else {
							$buffer = rtrim( gzgets( $dbhandle, 1048576 ) );
						}

						if ( empty( $buffer ) || substr( $buffer, 0, 1 ) == '#' || preg_match( '/^--(\s|$)/', substr( $buffer, 0, 3 ) ) ) {// WPCS: Loose comparison ok.
							if ( '' == $this->old_siteurl && preg_match( '/^\# Backup of: (http(.*))$/', $buffer, $matches ) ) {// WPCS: Loose comparison ok.
								$this->old_siteurl = untrailingslashit( $matches[1] );
								$this->backup_bank_log( 'Restoring Database Tables of Site <b>' . $this->old_siteurl . "</b>.\r\n" );
							} elseif ( '' == $this->old_home && preg_match( '/^\# Home URL: (http(.*))$/', $buffer, $matches ) ) {// WPCS: Loose comparison ok.
								$this->old_home = untrailingslashit( $matches[1] );
								if ( $this->old_siteurl && $this->old_home != $this->old_siteurl ) {// WPCS: Loose comparison ok.
									$this->backup_bank_log( 'Site home: <b>' . $this->old_home . "</b>\r\n" );
								}
							} elseif ( '' == $this->old_content && preg_match( '/^\# Content URL: (http(.*))$/', $buffer, $matches ) ) {// WPCS: Loose comparison ok.
								$this->old_content = untrailingslashit( $matches[1] );
								$this->backup_bank_log( 'Content URL: <b>' . $this->old_content . "</b>\r\n" );
							} elseif ( '' == $this->old_uploads && preg_match( '/^\# Uploads URL: (http(.*))$/', $buffer, $matches ) ) {// WPCS: Loose comparison ok.
								$this->old_uploads = untrailingslashit( $matches[1] );
								$this->backup_bank_log( 'Uploads URL: <b>' . $this->old_uploads . "</b>\r\n" );
							} elseif ( '' == $this->old_table_prefix && ( preg_match( '/^\# Table prefix: (\S+)$/', $buffer, $matches ) || preg_match( '/^-- Table Prefix: (\S+)$/i', $buffer, $matches ) ) ) {// WPCS: Loose comparison ok.
								$this->old_table_prefix = $matches[1];
								$this->backup_bank_log( 'Table prefix: <b>' . $this->old_table_prefix . "</b>\r\n" );
							} elseif ( $gathering_siteinfo && preg_match( '/^\# Site info: (\S+)$/', $buffer, $matches ) ) {
								if ( 'end' == $matches[1] ) {// WPCS: Loose comparison ok.
									$gathering_siteinfo = false;
								} elseif ( preg_match( '/^([^=]+)=(.*)$/', $matches[1], $kvmatches ) ) {
									$key = $kvmatches[1];
									$val = $kvmatches[2];
									$this->backup_bank_log( 'Site information: <b>' . $key . '=' . $val . "</b>\r\n" );
									$old_siteinfo[ $key ] = $val;
									if ( 'multisite' == $key ) {// WPCS: Loose comparison ok.
										$this->bb_backup_is_multisite = ( $val ) ? 1 : 0;
									}
								}
							}
							continue;
						}

						if ( preg_match( '/^\s*(insert into \`?([^\`]*)\`?\s+(values|\())/i', $sql_line . $buffer, $matches ) ) {
							$this->table_name = $matches[2];
							$sql_type         = 3;
							$insert_prefix    = $matches[1];
						}

						if ( 3 == $sql_type && $sql_line && strlen( $sql_line . $buffer ) > ( $this->max_allowed_packet - 100 ) && preg_match( '/,\s*$/', $sql_line ) && preg_match( '/^\s*\(/', $buffer ) ) {// WPCS: Loose comparison ok.
							$sql_line = substr( rtrim( $sql_line ), 0, strlen( $sql_line ) - 1 ) . ';';
							if ( '' != $this->old_table_prefix && $import_table_prefix != $this->old_table_prefix ) {// WPCS: Loose comparison ok.
								$sql_line = $this->obj_backup_data_backup_bank->str_replace_once_backup_bank( $this->old_table_prefix, $import_table_prefix, $sql_line );
							}
							$this->line++;
							$this->backup_bank_log( "Line has been Splitted to avoid exceeding maximum packet size.\r\n" );
							$do_exec = $this->sql_exec( $sql_line, $sql_type, $import_table_prefix );
							if ( is_wp_error( $do_exec ) ) {
								return $do_exec;
							}
							$sql_line = $insert_prefix . ' ';
						}

						$sql_line .= $buffer;
						if (
						( 3 == $sql_type && ! preg_match( '/\)\s*;$/', substr( $sql_line, -3, 3 ) ) ) || ( 3 != $sql_type && ';' != substr( $sql_line, -1, 1 ) )// WPCS: Loose comparison ok.
						) {
							continue;
						}

						$this->line++;

						if ( 3 == $sql_type && $sql_line && strlen( $sql_line ) > $this->max_allowed_packet ) {// WPCS: Loose comparison ok.
							$logit = substr( $sql_line, 0, 100 );
							$this->backup_bank_log( sprintf( "An SQL line that is larger than the maximum packet size and cannot be split was found: %s \r\n", '(' . strlen( $sql_line ) . ', ' . $logit . ' ...)' ) );
							$sql_line = '';
							$sql_type = -1;
							if ( 0 == $this->insert_statements_run && $restoring_table && $restoring_table == $import_table_prefix . 'options' ) {// WPCS: Loose comparison ok.
								$this->backup_bank_log( "Leaving Maintenance Mode\r\n" );
								$this->maintenance_mode( 'disable' );
								$this->backup_bank_log( "Restore process has been Terminated.\r\n" );
								$this->restore_status = 'restore_terminated';
								return $this->restore_status;
							}
							continue;
						}

						if ( preg_match( '/^\s*drop table (if exists )?\`?([^\`]*)\`?\s*;/i', $sql_line, $matches ) ) {
							$sql_type = 1;

							$this->table_name = $matches[2];

							if ( '' == $this->old_table_prefix && preg_match( '/^([a-z0-9]+)_.*$/i', $this->table_name, $tmatches ) ) {// WPCS: Loose comparison ok.
								$this->old_table_prefix = $tmatches[1] . '_';
								$this->backup_bank_log( 'Old Table prefix <b>' . $this->old_table_prefix . "</b> has been Detected.\r\n" );
							}

							$this->new_table_name = ( $this->old_table_prefix ) ? $this->obj_backup_data_backup_bank->str_replace_once_backup_bank( $this->old_table_prefix, $import_table_prefix, $this->table_name ) : $this->table_name;

							if ( '' != $this->old_table_prefix && $import_table_prefix != $this->old_table_prefix ) {// WPCS: Loose comparison ok.
								$sql_line = $this->obj_backup_data_backup_bank->str_replace_once_backup_bank( $this->old_table_prefix, $import_table_prefix, $sql_line );
							}

							if ( empty( $matches[1] ) ) {
								$sql_line = preg_replace( '/drop table/i', 'drop table if exists', $sql_line, 1 );
							}
							$this->backup_bank_log( 'Table <b>' . $this->new_table_name . "</b> has been Dropped.\r\n" );
							$this->tables_been_dropped[] = $this->new_table_name;
						} elseif ( preg_match( '/^\s*create table \`?([^\`\(]*)\`?\s*\(/i', $sql_line, $matches ) ) {
							$sql_type                    = 2;
							$this->insert_statements_run = 0;
							$this->table_name            = $matches[1];

							if ( '' == $this->old_table_prefix && preg_match( '/^([a-z0-9]+)_.*$/i', $this->table_name, $tmatches ) ) {// WPCS: Loose comparison ok.
								$this->old_table_prefix = $tmatches[1] . '_';
								$this->backup_bank_log( 'Table Prefix <b>' . $this->old_table_prefix . "</b> has been Detected while creating Table.\r\n" );
							}
							$sql_line = $this->str_lreplace( 'TYPE=', 'ENGINE=', $sql_line );

							$this->new_table_name = ( $this->old_table_prefix ) ? $this->obj_backup_data_backup_bank->str_replace_once_backup_bank( $this->old_table_prefix, $import_table_prefix, $this->table_name ) : $this->table_name;

							if ( $restoring_table ) {
								$this->check_db_connection( $this->wpdb_obj );
								if ( $restoring_table != $this->new_table_name ) {// WPCS: Loose comparison ok.
									$this->restored_table( $restoring_table, $import_table_prefix, $this->old_table_prefix );
								}
							}

							$engine                = '(?)';
							$engine_change_message = '';
							if ( preg_match( '/ENGINE=([^\s;]+)/', $sql_line, $eng_match ) ) {
								$engine = $eng_match[1];
								if ( isset( $supported_engines[ $engine ] ) ) {
									if ( 'myisam' == strtolower( $engine ) ) {// WPCS: Loose comparison ok.
										$sql_line = preg_replace( '/PAGE_CHECKSUM=\d\s?/', '', $sql_line, 1 );
									}
								} else {
									$engine_change_message = 'Requested table engine ' . $engine . 'is not present - changing to MyISAM.';
									$sql_line              = $this->str_lreplace( "ENGINE=$eng_match", 'ENGINE=MyISAM', $sql_line );
									if ( 'maria' == strtolower( $engine ) || 'aria' == strtolower( $engine ) ) {// WPCS: Loose comparison ok.
										$sql_line = preg_replace( '/PAGE_CHECKSUM=\d\s?/', '', $sql_line, 1 );
										$sql_line = preg_replace( '/TRANSACTIONAL=\d\s?/', '', $sql_line, 1 );
									}
								}
							}

							if ( '' != $this->old_table_prefix && $import_table_prefix != $this->old_table_prefix ) {// WPCS: Loose comparison ok.
								if ( ! isset( $this->restore_this_table[ $this->table_name ] ) || $this->restore_this_table[ $this->table_name ] ) {
									$this->backup_bank_log( 'Database Table <b>' . $this->table_name . '</b> will restore as <b>' . $this->new_table_name . "</b>.\r\n" );
								} else {
									$this->backup_bank_log( 'Database Table <b>' . $this->table_name . "</b> has been Skipped.\r\n" );
								}
								$sql_line = $this->obj_backup_data_backup_bank->str_replace_once_backup_bank( $this->old_table_prefix, $import_table_prefix, $sql_line );
							}

							$this->count_database_tables++;
							$this->backup_bank_log( 'Restoring Table <b>' . $this->table_name . "</b>.\r\n" );
							$restoring_table = $this->new_table_name;
							if ( $engine_change_message ) {
								$this->backup_bank_log( $engine_change_message . "\r\n" );
							}
						} elseif ( preg_match( '/^\s*(insert into \`?([^\`]*)\`?\s+(values|\())/i', $sql_line, $matches ) ) {
							$sql_type         = 3;
							$this->table_name = $matches[2];
							if ( '' != $this->old_table_prefix && $import_table_prefix != $this->old_table_prefix ) {// WPCS: Loose comparison ok.
								$sql_line = $this->obj_backup_data_backup_bank->str_replace_once_backup_bank( $this->old_table_prefix, $import_table_prefix, $sql_line );
							}
						} elseif ( preg_match( '/^\s*(\/\*\!40000 )?(alter|lock) tables? \`?([^\`\(]*)\`?\s+(write|disable|enable)/i', $sql_line, $matches ) ) {
							$sql_type = 4;
							if ( '' != $this->old_table_prefix && $import_table_prefix != $this->old_table_prefix ) {// WPCS: Loose comparison ok.
								$sql_line = $this->obj_backup_data_backup_bank->str_replace_once_backup_bank( $this->old_table_prefix, $import_table_prefix, $sql_line );
							}
						} elseif ( preg_match( '/^(un)?lock tables/i', $sql_line ) ) {
							$sql_type = 5;
						} elseif ( preg_match( '/^(create|drop) database /i', $sql_line ) ) {
							$sql_type = 6;
						} elseif ( preg_match( '/^use /i', $sql_line ) ) {
							$sql_type = 7;
						} elseif ( preg_match( '#/\*\!40\d+ SET NAMES (\S+)#', $sql_line, $smatches ) ) {
							$sql_type        = 8;
							$this->set_names = $smatches[1];
						} else {
							$sql_type = 0;
						}

						if ( 6 != $sql_type && 7 != $sql_type ) {// WPCS: Loose comparison ok.
							$do_exec = $this->sql_exec( $sql_line, $sql_type );
							if ( is_wp_error( $do_exec ) ) {
								return $do_exec;
							}
						} else {
							$this->backup_bank_log( "Skipped SQL statement (unwanted type=$sql_type): $sql_line \r\n" );
						}
						$sql_line = '';
						$sql_type = -1;
					}
					$this->backup_bank_log( "Leaving Maintenance Mode.\r\n" );
					$this->maintenance_mode( 'disable' );

					if ( $restoring_table ) {
						$this->restored_table( $restoring_table, $import_table_prefix, $this->old_table_prefix );
					}
					$time_taken = microtime( true ) - $this->start_time;
					$this->backup_bank_log( 'Total <b>' . $this->line . '</b> Database queries has been Processed in <b>' . round( $time_taken, 1 ) . " seconds</b>.\r\n" );
					if ( $is_plain ) {
						fclose( $dbhandle );// @codingStandardsIgnoreLine.
					} elseif ( $is_zip ) {
						fclose( $dbhandle );// @codingStandardsIgnoreLine.
						unlink( $working_dir_localpath );// @codingStandardsIgnoreLine.
					} elseif ( $is_bz2 ) {
						bzclose( $dbhandle );
					} else {
						gzclose( $dbhandle );
					}
					'complete_backup' == $this->backup_type ? @unlink( $file_path ) : '';// @codingStandardsIgnoreLine.
					$this->restore_completed = 'only_database' == $this->backup_type ? 100 : '';// WPCS: Loose comparison ok.
					$this->restore_timetaken = max( microtime( true ) - $this->restore_microtime_start, 0.000001 );
					$this->backup_bank_log( 'Database Tables has been Restored Successfully in <b>' . round( $this->restore_timetaken, 1 ) . " seconds</b>.\r\n" );
					$this->restore_status = 'restored_successfully';
					return $this->restore_status;
				}
			}
		}
		/**
		 * This class is used to create backup.
		 */
		class backup_bank_WPDB extends wpdb {// @codingStandardsIgnoreLine.
			/**
			 * This function is used to get dbh.
			 */
			public function backup_bank_getdbh() {
				return $this->dbh;
			}
			/**
			 * This function use mysqli.
			 */
			public function backup_bank_use_mysqli() {
				return ! empty( $this->use_mysqli );
			}
		}

		if ( ! class_exists( 'Google_Drive_Backup_Bank' ) ) {
			/**
			 * This class is used for Google Drive App.
			 */
			class Google_Drive_Backup_Bank {// @codingStandardsIgnoreLine.
				/**
				 * This function is being used to load token.
				 *
				 * @param string $name .
				 */
				public function google_drive_load_token( $name ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . "lib/Google/token/$name.token" ) ) {
						return @maybe_unserialize( @file_get_contents( BACKUP_BANK_DIR_PATH . "lib/Google/token/$name.token" ) );// @codingStandardsIgnoreLine.
					}
				}
				/**
				 * This function is being used to authenticate token.
				 *
				 * @param string $client_id .
				 * @param string $secret_key .
				 * @param string $redirect_uri .
				 */
				public function google_drive_check_auth_token( $client_id, $secret_key, $redirect_uri ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php' ) ) {
						include_once BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php';
					}
					$url    = $redirect_uri;
					$client = new Google_Client();
					$client->setClientId( $client_id );
					$client->setClientSecret( $secret_key );
					$client->setRedirectUri( $url );
					$client->setScopes( array( 'https://www.googleapis.com/auth/drive' ) );
					$accesstoken = $this->google_drive_load_token( 'access' );
					if ( ! empty( $accesstoken ) ) {
						$client->setAccessToken( $accesstoken );
					}
					$service = new Google_Service_Drive( $client );
					$folders = $service->files->listFiles();
					return $folders;
				}
				/**
				 * This function is being used to generate token.
				 *
				 * @param string $code .
				 * @param string $client_id .
				 * @param string $secret_key .
				 * @param string $redirect_uri .
				 */
				public function google_auth_token( $code, $client_id, $secret_key, $redirect_uri ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php' ) ) {
						include_once BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php';
					}
					$url    = $redirect_uri;
					$client = new Google_Client();
					$client->setClientId( $client_id );
					$client->setClientSecret( $secret_key );
					$client->setRedirectUri( $url );
					$client->setScopes( array( 'https://www.googleapis.com/auth/drive' ) );
					$token = $client->authenticate( $code );
					if ( '602' == $token ) {// WPCS: Loose comparison ok.
						return '602';
					}
					$this->store_google_token( $token, 'access' );
				}
				/**
				 * This function is being used to return folder id.
				 *
				 * @param string $client_id .
				 * @param string $secret_key .
				 * @param array  $backup_array .
				 * @param string $backup_filename .
				 * @param string $redirect_uri .
				 */
				public function google_drive_create_folder( $client_id, $secret_key, $backup_array, $backup_filename, $redirect_uri ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php' ) ) {
						include_once BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php';
					}
					$url    = $redirect_uri;
					$client = new Google_Client();
					$client->setClientId( $client_id );
					$client->setClientSecret( $secret_key );
					$client->setRedirectUri( $url );
					$client->setScopes( array( 'https://www.googleapis.com/auth/drive' ) );
					$accesstoken = $this->google_drive_load_token( 'access' );
					if ( ! empty( $accesstoken ) ) {
						$client->setAccessToken( $accesstoken );
					}
					$service      = new Google_Service_Drive( $client );
					$folderslist  = $service->files->listFiles();
					$folder_found = '0';
					$folder_id    = '';
					$parent_id    = '';
					$foldername   = 'wp-backup-bank';

					foreach ( $folderslist->items as $item ) {
						if ( $item['title'] == $foldername ) {// WPCS: Loose comparison ok.
							foreach ( $item['labels'] as $labels => $label ) {
								if ( 'trashed' == $labels ) {// WPCS: Loose comparison ok.
									if ( '0' == $label ) {// WPCS: Loose comparison ok.
										$folder_found = '1';
										$folder_id    = $item['id'];
										$parent_id    = $folder_id;
									}
								}
							}
						}
					}
					if ( '0' == $folder_found ) {// WPCS: Loose comparison ok.
						$folder1 = new Google_Service_Drive_DriveFile();
						$folder1->setTitle( $foldername ); // Name of the folder.
						$folder1->setDescription( 'BACKUP BANK folder' );
						$folder1->setMimeType( 'application/vnd.google-apps.folder' );
						$createdFile = $service->files->insert(// @codingStandardsIgnoreLine.
							$folder1, array(
								'mimeType' => 'application/vnd.google-apps.folder',
							)
						);
						$folders     = $service->files->listFiles();
						foreach ( $folders->items as $item ) {
							if ( $item['title'] == $foldername ) {// WPCS: Loose comparison ok.
								foreach ( $item['labels'] as $labels => $label ) {
									if ( 'trashed' == $labels ) {// WPCS: Loose comparison ok.
										if ( '0' == $label ) {// WPCS: Loose comparison ok.
											$folder_id = $item['id'];
											$parent_id = $folder_id;
										}
									}
								}
							}
						}
					}
					$current_year  = date( 'Y' );
					$current_month = date( 'm' );
					$current_date  = date( 'd' );
					$folderkeys    = array( $current_year, $current_month, $current_date, $backup_filename );
					foreach ( $folderkeys as $value ) {
						$found_keys       = '0';
						$folderskeys_list = $service->files->listFiles();
						foreach ( $folderskeys_list->items as $item ) {
							if ( $item['title'] == $value ) {// WPCS: Loose comparison ok.
								foreach ( $item['labels'] as $labels => $label ) {
									if ( 'trashed' == $labels ) {// WPCS: Loose comparison ok.
										if ( '0' == $label ) {// WPCS: Loose comparison ok.
											foreach ( $item['parents'] as $parentid ) {
												if ( $folder_id == $parentid['id'] ) {// WPCS: Loose comparison ok.
													$found_keys = '1';
													$folder_id  = $item['id'];
													$parent_id  = $parentid['id'];
												}
											}
										}
									}
								}
							}
						}
						if ( '0' == $found_keys ) {// WPCS: Loose comparison ok.
							$folder = new Google_Service_Drive_DriveFile();
							$parent = new Google_Service_Drive_ParentReference();
							$parent->setId( $folder_id );
							$folder->setParents( array( $parent ) );
							$folder->setTitle( $value ); // Name of the folder.
							$folder->setDescription( 'BACKUP BANK folder' );
							$folder->setMimeType( 'application/vnd.google-apps.folder' );
							$createdFile = $service->files->insert(// @codingStandardsIgnoreLine.
								$folder, array(
									'mimeType' => 'application/vnd.google-apps.folder',
								)
							);
							$files       = $service->files->listFiles();
							foreach ( $files->items as $item ) {
								if ( $item['title'] == $value ) {// WPCS: Loose comparison ok.
									foreach ( $item['labels'] as $labels => $label ) {
										if ( 'trashed' == $labels ) {// WPCS: Loose comparison ok.
											if ( '0' == $label ) {// WPCS: Loose comparison ok.
												foreach ( $item['parents'] as $parentid ) {
													if ( $parentid['id'] == $folder_id ) {// WPCS: Loose comparison ok.
														$folder_id = $item['id'];
														$parent_id = $parentid['id'];
													}
												}
											}
										}
									}
								}
							}
						}
					}
					return $folder_id;
				}
				/**
				 * This function is being used to store token.
				 *
				 * @param array  $token_array .
				 * @param string $name .
				 */
				public function store_google_token( $token_array, $name ) {
					@file_put_contents( BACKUP_BANK_DIR_PATH . "lib/Google/token/$name.token", serialize( $token_array ) );// @codingStandardsIgnoreLine.
				}
				/**
				 * This function is being used to return Google Drive object.
				 *
				 * @param string $client_id .
				 * @param string $secret_key .
				 * @param string $redirect_uri .
				 */
				public function google_drive_client( $client_id, $secret_key, $redirect_uri ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php' ) ) {
						include_once BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php';
					}
					$url    = $redirect_uri;
					$client = new Google_Client();
					$client->setClientId( $client_id );
					$client->setClientSecret( $secret_key );
					$client->setRedirectUri( $url );
					$client->setScopes( array( 'https://www.googleapis.com/auth/drive' ) );
					if ( ! isset( $_GET['code'] ) ) {// WPCS: Input var ok, CSRF ok.
						exit( $client->createAuthUrl( $url ) );// WPCS: XSS ok.
					}
					return $client;
				}
				/**
				 * This function is being used to upload file to Google drive.
				 *
				 * @param string $client_id .
				 * @param string $secret_key .
				 * @param string $file .
				 * @param string $parent_id .
				 * @param string $url .
				 * @param string $file_name .
				 * @param array  $backup_bank_data .
				 * @param bool   $try_again .
				 *
				 * @throws Exception $e .
				 */
				public function upload_file( $client_id, $secret_key, $file, $parent_id, $url, $file_name, $backup_bank_data, $try_again = true ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php' ) ) {
						include_once BACKUP_BANK_DIR_PATH . 'lib/Google/includes.php';
					}
					$basename       = basename( $file );
					$file_extention = strstr( $basename, '.' );

					$client = new Google_Client();
					$client->setClientId( $client_id );
					$client->setClientSecret( $secret_key );
					$client->setRedirectUri( $url );
					$client->setScopes( array( 'https://www.googleapis.com/auth/drive' ) );
					$accesstoken   = $this->google_drive_load_token( 'access' );
					$upload_path   = untrailingslashit( $backup_bank_data['folder_location'] );
					$archive_name  = implode( '', maybe_unserialize( $backup_bank_data['archive_name'] ) );
					$log_file_path = $upload_path . '/' . $archive_name . '.txt';
					$start_time    = microtime( true );
					if ( ! empty( $accesstoken ) ) {
						$client->setAccessToken( $accesstoken );
					}
					$finfo     = finfo_open();
					$mime_type = finfo_file( $finfo, $file, FILEINFO_MIME );

					$client->setDefer( true );

					$local_size = filesize( $file );

					$gdfile        = new Google_Service_Drive_DriveFile();
					$gdfile->title = $basename;

					$ref = new Google_Service_Drive_ParentReference();
					$ref->setId( $parent_id );
					$gdfile->setParents( array( $ref ) );

					$size    = 0;
					$gb      = new Google_Service_Drive( $client );
					$request = $gb->files->insert( $gdfile );

					$chunk_bytes = 2097152;

					$headers = array( 'content-range' => 'bytes */' . $local_size );

					$http_request = new Google_Http_Request(
						'', 'PUT', $headers, ''
					);
					$response     = $client->getIo()->makeRequest( $http_request );
					$can_resume   = false;
					if ( 308 == $response->getResponseHttpCode() ) {// @codingStandardsIgnoreLine.

					}

					$media = new backup_bank_Google_Http_MediaFileUpload(
						$client, $request, $mime_type, null, true, $chunk_bytes
					);
					$media->setFileSize( $local_size );

					if ( $size >= $local_size ) {
						return true;
					}

					$status = false;
					$handle = fopen( $file, 'rb' );// @codingStandardsIgnoreLine.
					if ( $size > 0 && 0 != fseek( $handle, $size ) ) {// WPCS: Loose comparison ok.
						return false;
					}

					$cloud         = 2;
					$pointer       = $size;
					$upload_status = 'completed';
					try {
						while ( ! $status && ! feof( $handle ) ) {
							$chunk    = fread( $handle, $chunk_bytes );// @codingStandardsIgnoreLine.
							$pointer += strlen( $chunk );
							$rtime    = microtime( true ) - $start_time;

							$status = $media->nextChunk( $chunk );
							if ( '.txt' != $file_extention ) {// WPCS: Loose comparison ok.
								$result   = ceil( ( $pointer / $local_size ) * 100 );
								$new_line = 'Uploading to <b>Google Drive</b> (<b>' . round( ( $pointer / 1048576 ), 1 ) . 'MB</b> out of <b>' . round( ( $local_size / 1048576 ), 1 ) . 'MB</b>).';

								$message  = '{' . "\r\n";
								$message .= '"log": "' . $new_line . '",' . "\r\n";
								$message .= '"perc": ' . $result . ',' . "\r\n";
								$message .= '"status": "' . $upload_status . '",' . "\r\n";
								$message .= '"cloud": ' . $cloud . "\r\n";
								$message .= '}';

								@file_put_contents( $file_name, $message );// @codingStandardsIgnoreLine.
								@file_put_contents( $log_file_path, strip_tags( sprintf( '%08.03f', round( $rtime, 3 ) ) . ' ' . $new_line . "\r\n" ), FILE_APPEND );// @codingStandardsIgnoreLine.
							}
						}
					} catch ( Google_Service_Exception $e ) {
						$client->setDefer( false );
						fclose( $handle );// @codingStandardsIgnoreLine.
						if ( false == $try_again ) {// WPCS: Loose comparison ok.
							throw($e);
						}
						return $this->upload_file( $client_id, $secret_key, $file, $parent_id, $url, false );
					}

					$result = false;
					if ( false != $status ) {// WPCS: Loose comparison ok.
						$result = $status;
					}

					fclose( $handle );// @codingStandardsIgnoreLine.
					$client->setDefer( false );

					return true;
				}
			}
		}
		if ( ! class_exists( 'class_plugin_info' ) ) {
			/**
			 * This class is used to get plugin info.
			 */
			class class_plugin_info {// @codingStandardsIgnoreLine.
				/**
				 * This function is used to get plugin info.
				 */
				function get_plugin_info() {// @codingStandardsIgnoreLine.
					$active_plugins = (array) get_option( 'active_plugins', array() );
					if ( is_multisite() ) {
						$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
					}
					$plugins = array();
					if ( count( $active_plugins ) > 0 ) {
						$get_plugins = array();
						foreach ( $active_plugins as $plugin ) {
							$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );// @codingStandardsIgnoreLine.

							$get_plugins['plugin_name']    = strip_tags( $plugin_data['Name'] );
							$get_plugins['plugin_author']  = strip_tags( $plugin_data['Author'] );
							$get_plugins['plugin_version'] = strip_tags( $plugin_data['Version'] );
							array_push( $plugins, $get_plugins );
						}
						return $plugins;
					}
				}
			}
		}
	}
}
