<?php
/**
 * This file is used for sending emails.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! class_exists( 'Dbmailer_Backup_Bank' ) ) {
	/**
	 * This Class is used for send Emails.
	 */
	class Dbmailer_Backup_Bank {
		/**
		 * This function is used for sending Emails when backup is successfully generated.
		 *
		 * @param array $backup_generated_data_array .
		 * @param array $backup_bank_data .
		 */
		public function email_when_backup_generated_successfully( $backup_generated_data_array, $backup_bank_data ) {
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
				include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
			}
			$datetime = date_i18n( 'd M Y H:i:s' );
			global $current_user;
			$headers  = '';
			$headers .= 'Content-Type: text/html; charset= utf-8' . "\r\n";
			if ( '' !== $backup_generated_data_array['email_cc'] ) {
				$headers .= 'Cc: ' . $backup_generated_data_array['email_cc'] . "\r\n";
			}
			if ( '' !== $backup_generated_data_array['email_bcc'] ) {
				$headers .= 'Bcc: ' . $backup_generated_data_array['email_bcc'] . "\r\n";
			}
			switch ( $backup_bank_data['backup_type'] ) {
				case 'only_themes':
					$type             = $bb_themes;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_plugins':
					$type             = $bb_plugins;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_wp_content_folder':
					$type             = $bb_contents;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'complete_backup':
					$type           = $bb_complete_backup;
					$exclude        = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression = $backup_bank_data['db_compression_type'];
					$db_tables      = $backup_bank_data['backup_tables'];

					$data_table_array = explode( ',', $db_tables );
					$database_table   = '<ul>';
					if ( isset( $data_table_array ) && count( $data_table_array ) > 0 ) {
						foreach ( $data_table_array as $row ) {
							$database_table .= '<li style="margin-left: 0px;">' . $row . '</li>';
						}
					}
					$database_table  .= '</ul>';
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_filesystem':
					$type             = $bb_filesystem;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_plugins_and_themes':
					$type             = $bb_plugins_themes;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_database':
					$type           = $bb_database;
					$exclude        = $bb_na;
					$db_compression = $backup_bank_data['db_compression_type'];
					$db_tables      = $backup_bank_data['backup_tables'];

					$data_table_array = explode( ',', $db_tables );
					$database_table   = '<ul>';
					if ( isset( $data_table_array ) && count( $data_table_array ) > 0 ) {
						foreach ( $data_table_array as $row ) {
							$database_table .= '<li style="margin-left: 0px;">' . $row . '</li>';
						}
					}
					$database_table  .= '</ul>';
					$file_compression = $bb_na;
					break;
			}

			switch ( $backup_bank_data['backup_destination'] ) {
				case 'dropbox':
					$backup_dest = $bb_dropbox;
					break;

				case 'email':
					$backup_dest = $bb_email;
					break;

				case 'ftp':
					$backup_dest = $bb_ftp;
					break;

				case 'google_drive':
					$backup_dest = $bb_google_drive_settings;
					break;

				default:
					$backup_dest = $bb_local_folder;
			}

			$subject         = $backup_generated_data_array['email_subject'];
			$replace_subject = str_replace( '[backup_type]', $type, $subject );
			$message         = '<div style="font-family: Calibri;">';
			$message        .= $backup_generated_data_array['email_message'];
			$message        .= '</div>';

			$archive_name                    = implode( '', maybe_unserialize( $backup_bank_data['archive'] ) );
			$replace_type_message            = str_replace( '[backup_type]', $type, $message );
			$replace_type_site_url           = str_replace( '[site_url]', site_url(), $replace_type_message );
			$replace_type_archive            = str_replace( '[archive_name]', '<a href=' . $backup_bank_data['backup_urlpath'] . $archive_name . '>' . $archive_name . '</a>', $replace_type_site_url );
			$replace_type_backup_name        = str_replace( '[backup_name]', $backup_bank_data['backup_name'], $replace_type_archive );
			$replace_type_exclude_list       = str_replace( '[exclude_list]', $exclude, $replace_type_backup_name );
			$replace_type_file_compression   = str_replace( '[file_compression_type]', $file_compression, $replace_type_exclude_list );
			$replace_type_db_compression     = str_replace( '[db_compression_type]', $db_compression, $replace_type_file_compression );
			$replace_type_table              = str_replace( '[backup_tables]', $database_table, $replace_type_db_compression );
			$replace_type_location           = str_replace( '[folder_location]', $backup_bank_data['folder_location'], $replace_type_table );
			$replace_type_start_time         = str_replace( '[start_time]', $datetime, $replace_type_location );
			$user                            = 'scheduled' === $backup_bank_data['execution'] ? $bb_scheduler : $current_user->display_name;
			$replace_type_username           = str_replace( '[username]', $user, $replace_type_start_time );
			$replace_type_backup_destination = str_replace( '[backup_destination]', $backup_dest, $replace_type_username );

			wp_mail( $backup_generated_data_array['email_send_to'], $replace_subject, $replace_type_backup_destination, $headers );
		}
		/**
		 * This function is used for sending Emails when backup is failed.
		 *
		 * @param array $email_backup_data_array .
		 * @param array $backup_bank_data .
		 */
		public function email_when_backup_failed( $email_backup_data_array, $backup_bank_data ) {
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
				include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
			}
			$start_time = date_i18n( 'd M Y H:i:s' );
			global $current_user;

			$headers  = '';
			$headers .= 'Content-Type: text/html; charset= utf-8' . "\r\n";
			if ( '' !== $email_backup_data_array['email_cc'] ) {
				$headers .= 'Cc: ' . $email_backup_data_array['email_cc'] . "\r\n";
			}
			if ( '' !== $email_backup_data_array['email_bcc'] ) {
				$headers .= 'Bcc: ' . $email_backup_data_array['email_bcc'] . "\r\n";
			}
			switch ( $backup_bank_data['backup_type'] ) {
				case 'only_themes':
					$type             = $bb_themes;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_plugins':
					$type             = $bb_plugins;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_wp_content_folder':
					$type             = $bb_contents;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'complete_backup':
					$type           = $bb_complete_backup;
					$exclude        = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression = $backup_bank_data['db_compression_type'];
					$db_tables      = $backup_bank_data['backup_tables'];

					$data_table_array = explode( ',', $db_tables );
					$database_table   = '<ul>';
					if ( isset( $data_table_array ) && count( $data_table_array ) > 0 ) {
						foreach ( $data_table_array as $row ) {
							$database_table .= '<li style="margin-left: 0px;">' . $row . '</li>';
						}
					}
					$database_table  .= '</ul>';
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_filesystem':
					$type             = $bb_filesystem;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_plugins_and_themes':
					$type             = $bb_plugins_themes;
					$exclude          = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_compression   = $bb_na;
					$database_table   = $bb_na;
					$file_compression = $backup_bank_data['file_compression_type'];
					break;

				case 'only_database':
					$type           = $bb_database;
					$exclude        = $bb_na;
					$db_compression = $backup_bank_data['db_compression_type'];
					$db_tables      = $backup_bank_data['backup_tables'];

					$data_table_array = explode( ',', $db_tables );
					$database_table   = '<ul>';
					if ( isset( $data_table_array ) && count( $data_table_array ) > 0 ) {
						foreach ( $data_table_array as $row ) {
							$database_table .= '<li style="margin-left: 0px;">' . $row . '</li>';
						}
					}
					$database_table  .= '</ul>';
					$file_compression = $bb_na;
					break;
			}

			switch ( $backup_bank_data['backup_destination'] ) {
				case 'ftp':
					$backup_destination = $bb_ftp;
					break;

				case 'dropbox':
					$backup_destination = $bb_dropbox;
					break;

				case 'email':
					$backup_destination = $bb_email;
					break;

				case 'google_drive':
					$backup_destination = $bb_google_drive_settings;
					break;

				default:
					$backup_destination = $bb_local_folder;
			}

			$subject         = $email_backup_data_array['email_subject'];
			$replace_subject = str_replace( '[backup_type]', $type, $subject );
			$message         = '<div style="font-family: Calibri;">';
			$message        .= $email_backup_data_array['email_message'];
			$message        .= '</div>';
			$backup_site_url = str_replace( '[site_url]', site_url(), $message );

			$backup_archive_name     = str_replace( '[archive_name]', $bb_na, $backup_site_url );
			$backup_name             = str_replace( '[backup_name]', $backup_bank_data['backup_name'], $backup_archive_name );
			$backup_exclude_list     = str_replace( '[exclude_list]', $exclude, $backup_name );
			$backup_file_compression = str_replace( '[file_compression_type]', $file_compression, $backup_exclude_list );
			$backup_db_compression   = str_replace( '[db_compression_type]', $db_compression, $backup_file_compression );
			$backup_table            = str_replace( '[backup_tables]', $database_table, $backup_db_compression );
			$backup_location         = str_replace( '[folder_location]', $backup_bank_data['folder_location'], $backup_table );
			$user                    = 'scheduled' === $backup_bank_data['execution'] ? $bb_scheduler : $current_user->display_name;
			$backup_current_user     = str_replace( '[username]', $user, $backup_location );
			$start_date_time         = str_replace( '[start_time]', $start_time, $backup_current_user );
			$destination             = str_replace( '[backup_destination]', $backup_destination, $start_date_time );
			$replace_backup_type     = str_replace( '[backup_type]', $type, $destination );

			wp_mail( $email_backup_data_array['email_send_to'], $replace_subject, $replace_backup_type, $headers );
		}

		/**
		 * This function is used for sending backup to Email.
		 *
		 * @param array $email_settings_array .
		 * @param array $backup_bank_data .
		 */
		public function sending_backup_to_email( $email_settings_array, $backup_bank_data ) {
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
				include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
			}
			$start_time = date_i18n( 'd M Y H:i:s' );
			global $current_user;
			$headers  = '';
			$headers .= 'Content-Type: text/html; charset= utf-8' . "\r\n";
			if ( '' !== $email_settings_array['cc_email'] ) {
				$headers .= 'Cc: ' . $email_settings_array['cc_email'] . "\r\n";
			}
			if ( '' !== $email_settings_array['bcc_email'] ) {
				$headers .= 'Bcc: ' . $email_settings_array['bcc_email'] . "\r\n";
			}

			switch ( $backup_bank_data['backup_type'] ) {
				case 'only_themes':
					$type           = $bb_themes;
					$exclude        = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$database_table = $bb_na;
					$compression    = $backup_bank_data['file_compression_type'];
					break;

				case 'only_plugins':
					$type           = $bb_plugins;
					$exclude        = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_tables      = $bb_na;
					$database_table = $bb_na;
					$compression    = $backup_bank_data['file_compression_type'];
					break;

				case 'only_wp_content_folder':
					$type           = $bb_contents;
					$exclude        = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$database_table = $bb_na;
					$compression    = $backup_bank_data['file_compression_type'];
					break;

				case 'complete_backup':
					$type      = $bb_complete_backup;
					$exclude   = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$db_tables = $backup_bank_data['backup_tables'];

					$data_table_array = explode( ',', $db_tables );
					$database_table   = '<ul>';
					if ( isset( $data_table_array ) && count( $data_table_array ) > 0 ) {
						foreach ( $data_table_array as $row ) {
							$database_table .= '<li style="margin-left: 0px;">' . $row . '</li>';
						}
					}
					$database_table .= '</ul>';
					$compression     = $backup_bank_data['file_compression_type'];
					break;

				case 'only_filesystem':
					$type           = $bb_filesystem;
					$exclude        = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$database_table = $bb_na;
					$compression    = $backup_bank_data['file_compression_type'];
					break;

				case 'only_plugins_and_themes':
					$type           = $bb_plugins_themes;
					$exclude        = '' !== $backup_bank_data['exclude_list'] ? $backup_bank_data['exclude_list'] : $bb_na;
					$database_table = $bb_na;
					$compression    = $backup_bank_data['file_compression_type'];
					break;

				case 'only_database':
					$type           = $bb_database;
					$exclude        = $bb_na;
					$db_compression = $backup_bank_data['db_compression_type'];
					$db_tables      = $backup_bank_data['backup_tables'];

					$data_table_array = explode( ',', $db_tables );
					$database_table   = '<ul>';
					if ( isset( $data_table_array ) && count( $data_table_array ) > 0 ) {
						foreach ( $data_table_array as $row ) {
							$database_table .= '<li style="margin-left: 0px;">' . $row . '</li>';
						}
					}
					$database_table .= '</ul>';
					$compression     = $backup_bank_data['db_compression_type'];
					break;
			}

			$subject         = $email_settings_array['email_subject'];
			$replace_subject = str_replace( '[backup_type]', $type, $subject );
			$message         = '<div style="font-family: Calibri;">';
			$message        .= $email_settings_array['email_message'];
			$message        .= '</div>';

			if ( 'scheduled' === $backup_bank_data['execution'] ) {
				$archive_array = maybe_unserialize( $backup_bank_data['archive'] );
				$archive_name  = $archive_array[ count( $archive_array ) - 2 ];
				$logfile_array = maybe_unserialize( $backup_bank_data['log_filename'] );
				$logfile_name  = $logfile_array[ count( $logfile_array ) - 2 ];
			} else {
				$archive_name = implode( '', maybe_unserialize( $backup_bank_data['archive'] ) );
				$logfile_name = implode( '', maybe_unserialize( $backup_bank_data['log_filename'] ) );
			}
			$replace_type_message        = str_replace( '[backup_type]', $type, $message );
			$replace_type_site_url       = str_replace( '[site_url]', site_url(), $replace_type_message );
			$replace_type_archive        = str_replace( '[archive_name]', '<a href=' . $backup_bank_data['backup_urlpath'] . $archive_name . '>' . $archive_name . '</a>', $replace_type_site_url );
			$replace_type_backup_name    = str_replace( '[backup_name]', $backup_bank_data['backup_name'], $replace_type_archive );
			$replace_type_exclude_list   = str_replace( '[exclude_list]', $exclude, $replace_type_backup_name );
			$replace_type_db_compression = str_replace( '[compression_type]', $compression, $replace_type_exclude_list );
			$replace_type_table          = str_replace( '[backup_tables]', $database_table, $replace_type_db_compression );
			$user                        = 'scheduled' === $backup_bank_data['execution'] ? $bb_scheduler : $current_user->display_name;
			$backup_current_user         = str_replace( '[username]', $user, $replace_type_table );
			$start_date_time             = str_replace( '[start_time]', $start_time, $backup_current_user );

			if ( filesize( untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . $archive_name ) <= 20971520 ) {
				$attachment = array( untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . $archive_name, untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . $logfile_name );
				wp_mail( $email_settings_array['email_address'], $replace_subject, $start_date_time, $headers, $attachment );
			}
		}

		/**
		 * This function is used for sending Emails when backup is successfully restored.
		 *
		 * @param array $backup_restore_data_array .
		 * @param array $bb_backup_data_array .
		 */
		public function template_for_restore_successfully( $backup_restore_data_array, $bb_backup_data_array ) {
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
				include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
			}
			$datetime = date_i18n( 'd M Y H:i:s' );
			global $current_user;
			$headers  = '';
			$headers .= 'Content-Type: text/html; charset= utf-8' . "\r\n";
			if ( '' !== $backup_restore_data_array['email_cc'] ) {
				$headers .= 'Cc: ' . $backup_restore_data_array['email_cc'] . "\r\n";
			}
			if ( '' !== $backup_restore_data_array['email_bcc'] ) {
				$headers .= 'Bcc: ' . $backup_restore_data_array['email_bcc'] . "\r\n";
			}
			switch ( $bb_backup_data_array['backup_type'] ) {
				case 'only_themes':
					$type = $bb_themes;
					break;

				case 'only_plugins':
					$type = $bb_plugins;
					break;

				case 'only_wp_content_folder':
					$type = $bb_contents;
					break;

				case 'complete_backup':
					$type = $bb_complete_backup;
					break;

				case 'only_filesystem':
					$type = $bb_filesystem;
					break;

				case 'only_plugins_and_themes':
					$type = $bb_plugins_themes;
					break;

				case 'only_database':
					$type = $bb_database;
					break;
			}

			$restore_status = $bb_manage_backups_restored_successfully;
			$subject        = $backup_restore_data_array['email_subject'];
			$message        = '<div style="font-family: Calibri;">';
			$message       .= $backup_restore_data_array['email_message'];
			$message       .= '</div>';

			$replace_type_message     = str_replace( '[backup_type]', $type, $message );
			$replace_type_site_url    = str_replace( '[site_url]', site_url(), $replace_type_message );
			$replace_type_backup_name = str_replace( '[backup_name]', $bb_backup_data_array['backup_name'], $replace_type_site_url );
			$replace_type_start_time  = str_replace( '[start_time]', $datetime, $replace_type_backup_name );
			$replace_type_username    = str_replace( '[username]', $current_user->display_name, $replace_type_start_time );
			$backup_source            = str_replace( '[backup_source]', $bb_backup_data_array['folder_location'], $replace_type_username );
			$backup_status            = str_replace( '[status]', $restore_status, $backup_source );
			$backup_time_taken        = str_replace( '[time_taken]', date_i18n( 'H:i:s', $bb_backup_data_array['executed_in'] ), $backup_status );

			$attachment = array( $bb_backup_data_array['restore_log_filename'] );
			wp_mail( $backup_restore_data_array['email_send_to'], $subject, $backup_time_taken, $headers, $attachment );
		}

		/**
		 * This function is used for sending Emails when backup is failed.
		 *
		 * @param array $backup_restore_data_array .
		 * @param array $bb_backup_data_array .
		 */
		public function template_for_restore_failure( $backup_restore_data_array, $bb_backup_data_array ) {
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
				include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
			}
			$datetime = date_i18n( 'd M Y H:i:s' );
			global $current_user;
			$headers  = '';
			$headers .= 'Content-Type: text/html; charset= utf-8' . "\r\n";
			if ( '' !== $backup_restore_data_array['email_cc'] ) {
				$headers .= 'Cc: ' . $backup_restore_data_array['email_cc'] . "\r\n";
			}
			if ( '' !== $backup_restore_data_array['email_bcc'] ) {
				$headers .= 'Bcc: ' . $backup_restore_data_array['email_bcc'] . "\r\n";
			}
			switch ( $bb_backup_data_array['backup_type'] ) {
				case 'only_themes':
					$type = $bb_themes;
					break;

				case 'only_plugins':
					$type = $bb_plugins;
					break;

				case 'only_wp_content_folder':
					$type = $bb_contents;
					break;

				case 'complete_backup':
					$type = $bb_complete_backup;
					break;

				case 'only_filesystem':
					$type = $bb_filesystem;
					break;

				case 'only_plugins_and_themes':
					$type = $bb_plugins_themes;
					break;

				case 'only_database':
					$type = $bb_database;
					break;
			}

			$restore_status = $bb_manage_backups_restore_terminated;
			$subject        = $backup_restore_data_array['email_subject'];
			$message        = '<div style="font-family: Calibri;">';
			$message       .= $backup_restore_data_array['email_message'];
			$message       .= '</div>';

			$replace_type_message     = str_replace( '[backup_type]', $type, $message );
			$replace_type_site_url    = str_replace( '[site_url]', site_url(), $replace_type_message );
			$replace_type_backup_name = str_replace( '[backup_name]', $bb_backup_data_array['backup_name'], $replace_type_site_url );
			$replace_type_start_time  = str_replace( '[start_time]', $datetime, $replace_type_backup_name );
			$replace_type_username    = str_replace( '[username]', $current_user->display_name, $replace_type_start_time );
			$backup_source            = str_replace( '[backup_source]', $bb_backup_data_array['folder_location'], $replace_type_username );
			$backup_status            = str_replace( '[status]', $restore_status, $backup_source );
			$backup_time_taken        = str_replace( '[time_taken]', $bb_na, $backup_status );

			$attachment = array( $bb_backup_data_array['restore_log_filename'] );
			wp_mail( $backup_restore_data_array['email_send_to'], $subject, $backup_time_taken, $headers, $attachment );
		}
	}
}
