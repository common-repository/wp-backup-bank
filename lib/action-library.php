<?php
/**
 * This file is used for managing data in database.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
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
		if ( ! function_exists( 'get_backup_bank_unserialize_data' ) ) {
			/**
			 * This Function is used get data.
			 *
			 * @param string $manage_data passes parameter as manage data.
			 */
			function get_backup_bank_unserialize_data( $manage_data ) {
				$unserialize_complete_data = array();
				if ( count( $manage_data ) > 0 ) {
					foreach ( $manage_data as $value ) {
						$unserialize_data            = maybe_unserialize( $value->meta_value );
						$unserialize_data['meta_id'] = $value->meta_id;
						array_push( $unserialize_complete_data, $unserialize_data );
					}
				}
				return $unserialize_complete_data;
			}
		}

		if ( isset( $_REQUEST['param'] ) ) {// WPCS: input var ok.
			$obj_dbhelper_backup_bank = new Dbhelper_Backup_Bank();
			$dbmailer_backup_bank_obj = new Dbmailer_Backup_Bank();
			switch ( sanitize_text_field( wp_unslash( $_REQUEST['param'] ) ) ) {// WPCS: input var ok, CSRF ok.
				case 'backup_bank_manual_backup_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_manual_backup' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $backup_bank_data_array );// WPCS: input var ok.
						$encrypted_tables    = isset( $_REQUEST['encrypted_tables'] ) ? array_map( 'sanitize_text_field', is_array( json_decode( stripslashes( $_REQUEST['encrypted_tables'] ) ) ) ? json_decode( stripslashes( $_REQUEST['encrypted_tables'] ) ) : array() ) : array();// WPCS: input var ok, sanitization ok.
						$timezone_difference = '';
						$backups_id          = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type=%s', 'backups'
							)
						);// WPCS: db call ok, cache ok.

						$backup_bank_data                          = array();
						$backup_bank_data['timezone_difference']   = '';
						$backup_bank_data['backup_name']           = 'Backup - ' . date_i18n( get_option( 'date_format' ) );
						$backup_bank_data['backup_type']           = sanitize_text_field( $backup_bank_data_array['ux_ddl_backup_type'] );
						$backup_bank_data['exclude_list']          = sanitize_text_field( $backup_bank_data_array['ux_txt_return_email'] );
						$backup_bank_data['file_compression_type'] = sanitize_text_field( $backup_bank_data_array['ux_ddl_file_compression_type'] );
						$backup_bank_data['db_compression_type']   = sanitize_text_field( $backup_bank_data_array['ux_ddl_db_compression_type'] );
						$backup_bank_data['backup_tables']         = sanitize_text_field( implode( ',', $encrypted_tables ) );
						$backup_bank_data['archive']               = isset( $_REQUEST['archive'] ) ? maybe_serialize( array( sanitize_text_field( wp_unslash( $_REQUEST['archive'] ) ) ) ) : '';// WPCS: input var ok.
						$backup_bank_data['archive_name']          = isset( $_REQUEST['archive_name'] ) ? maybe_serialize( array( sanitize_text_field( wp_unslash( $_REQUEST['archive_name'] ) ) ) ) : '';// WPCS: input var ok.
						$backup_bank_data['backup_destination']    = sanitize_text_field( $backup_bank_data_array['ux_rdl_backup_destination_type'] );
						$backup_bank_data['folder_location']       = sanitize_text_field( $backup_bank_data_array['ux_txt_content_location'] ) . sanitize_text_field( $backup_bank_data_array['ux_txt_folder_location'] );
						$backup_bank_data['execution']             = sanitize_text_field( 'manual' );
						$backup_bank_data['backup_urlpath']        = content_url() . sanitize_text_field( $backup_bank_data_array['ux_txt_folder_location'] );
						$backup_bank_data['log_filename']          = isset( $_REQUEST['archive_name'] ) ? maybe_serialize( array( sanitize_text_field( wp_unslash( $_REQUEST['archive_name'] ) ) . '.txt' ) ) : '';// WPCS: input var ok.
						$backup_bank_data['executed_time']         = LOCAL_TIME_BACKUP_BANK;
						$backup_bank_data['status']                = 'running';

						$insert_manual_data              = array();
						$insert_manual_data['type']      = 'manual_backup';
						$insert_manual_data['parent_id'] = $backups_id;
						$last_id                         = $obj_dbhelper_backup_bank->insert_command( backup_bank(), $insert_manual_data );

						$backup_bank_data['meta_id'] = $last_id;

						$backup_bank_insert_data               = array();
						$backup_bank_insert_data['meta_key']   = 'manual_backup_meta';// WPCS: db sql slow query.
						$backup_bank_insert_data['meta_value'] = maybe_serialize( $backup_bank_data );// WPCS: db sql slow query.
						$backup_bank_insert_data['meta_id']    = $last_id;
						$obj_dbhelper_backup_bank->insert_command( backup_bank_meta(), $backup_bank_insert_data );

						$obj_backup_data_backup_bank = new Backup_Data_Backup_Bank();
						$obj_backup_data_backup_bank->close_browser_connection();
						do_action( 'start_backup', $backup_bank_data );
					}
					break;

				case 'backup_bank_change_email_template_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_change_template' ) ) {// WPCS: input var ok.
						$template_type = isset( $_REQUEST['data'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['data'] ) ) : '';// WPCS: input var ok.

						$email_templates_data_array = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', "$template_type"
							)
						);// WPCS: db call ok, cache ok.
						$email_templates_data       = get_backup_bank_unserialize_data( $email_templates_data_array );
						echo wp_json_encode( $email_templates_data );
					}
					break;

				case 'backup_bank_other_settings_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_other_settings' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $backup_bank_other_settings_data );// WPCS: input var ok.

						$bb_other_settings_id = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type = %s', 'other_settings'
							)
						);// WPCS: db call ok, cache ok.

						$bb_other_settings_data                               = array();
						$bb_other_settings_data['automatic_plugin_updates']   = 'disable';
						$bb_other_settings_data['remove_tables_at_uninstall'] = sanitize_text_field( $backup_bank_other_settings_data['ux_ddl_remove_tables'] );
						$bb_other_settings_data['Maintenance_mode']           = sanitize_text_field( $backup_bank_other_settings_data['ux_ddl_maintenance_mode'] );

						$update_other_settings               = array();
						$where                               = array();
						$where['meta_id']                    = $bb_other_settings_id;
						$where['meta_key']                   = 'other_settings';// WPCS: db sql slow query.
						$update_other_settings['meta_value'] = maybe_serialize( $bb_other_settings_data );// WPCS: db sql slow query.
						$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $update_other_settings, $where );

						$bb_other_settings_maintenance_mode                         = array();
						$bb_other_settings_maintenance_mode['message_when_restore'] = sanitize_text_field( $backup_bank_other_settings_data['ux_txt_maintenance_mode_message'] );
						$bb_other_settings_maintenance_mode['restoring']            = sanitize_text_field( $backup_bank_other_settings_data['ux_ddl_maintenance_mode'] );
						$update_other_settings_maintenance_mode                     = array();
						$where             = array();
						$where['meta_key'] = 'maintenance_mode_settings';// WPCS: db sql slow query.
						$update_other_settings_maintenance_mode['meta_value'] = maybe_serialize( $bb_other_settings_maintenance_mode );// WPCS: db sql slow query.
						$obj_dbhelper_backup_bank->update_command( backup_bank_restore(), $update_other_settings_maintenance_mode, $where );
					}
					break;

				case 'backup_bank_email_settings_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_email_settings' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $email_settings_form_data );// WPCS: input var ok.

						$bb_email_settings_id                         = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type = %s', 'email_settings'
							)
						);// WPCS: db call ok, cache ok.
						$update_email_setting_data                    = array();
						$update_email_setting_data['backup_to_email'] = sanitize_text_field( $email_settings_form_data['ux_ddl_email_settings_enable_disable'] );
						$update_email_setting_data['email_address']   = sanitize_text_field( $email_settings_form_data['ux_txt_email_address'] );
						$update_email_setting_data['cc_email']        = sanitize_text_field( $email_settings_form_data['ux_txt_email_cc'] );
						$update_email_setting_data['bcc_email']       = sanitize_text_field( $email_settings_form_data['ux_txt_email_bcc'] );
						$update_email_setting_data['email_subject']   = sanitize_text_field( $email_settings_form_data['ux_txt_email_subject'] );
						$update_email_setting_data['email_message']   = htmlspecialchars_decode( $email_settings_form_data['ux_txt_email_settings_message'] );

						$email_setting_data               = array();
						$where                            = array();
						$where['meta_id']                 = $bb_email_settings_id;
						$where['meta_key']                = 'email_settings';// WPCS: db sql slow query.
						$email_setting_data['meta_value'] = maybe_serialize( $update_email_setting_data );// WPCS: db sql slow query.
						$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $email_setting_data, $where );
					}
					break;

				case 'backup_bank_manage_backups_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_manage_backups' ) ) {
						$backup_id    = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : '';// WPCS: input var ok.
						$restore_path = isset( $_REQUEST['restore_path'] ) ? sanitize_text_field( $_REQUEST['restore_path'] ) : '';// WPCS: input var ok, sanitization ok.

						$bb_backup_data          = $wpdb->get_row(
							$wpdb->prepare(
								'SELECT meta_key,meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_id = %d', $backup_id
							)
						);// WPCS: db call ok, cache ok.
						$bb_backup_data_array    = maybe_unserialize( $bb_backup_data->meta_value );
						$file_name               = basename( $restore_path );
						$obj_backup_bank_restore = new backup_bank_restore( $bb_backup_data_array, $restore_path );
						switch ( $bb_backup_data_array['backup_type'] ) {
							case 'only_database':
								$restore_ret = $obj_backup_bank_restore->backup_bank_restore_backup_db( untrailingslashit( $bb_backup_data_array['folder_location'] ) . '/' . $file_name );
								break;

							default:
								$restore_ret = $obj_backup_bank_restore->backup_bank_restore_backup();
						}

						$restore_time = $obj_backup_bank_restore->restore_timetaken;
						$time_taken   = round( $restore_time, 1 );
						$log_file     = $obj_backup_bank_restore->logfile_name;

						$backup_bank_data['timezone_difference']   = '';
						$backup_bank_data['backup_name']           = $bb_backup_data_array['backup_name'];
						$backup_bank_data['backup_type']           = $bb_backup_data_array['backup_type'];
						$backup_bank_data['exclude_list']          = $bb_backup_data_array['exclude_list'];
						$backup_bank_data['file_compression_type'] = $bb_backup_data_array['file_compression_type'];
						$backup_bank_data['db_compression_type']   = $bb_backup_data_array['db_compression_type'];
						$backup_bank_data['backup_tables']         = $bb_backup_data_array['backup_tables'];
						$backup_bank_data['archive']               = $bb_backup_data_array['archive'];
						$backup_bank_data['archive_name']          = $bb_backup_data_array['archive_name'];
						$backup_bank_data['backup_destination']    = $bb_backup_data_array['backup_destination'];
						$backup_bank_data['folder_location']       = $bb_backup_data_array['folder_location'];
						$backup_bank_data['execution']             = $bb_backup_data_array['execution'];
						$backup_bank_data['status']                = $restore_ret;
						$backup_bank_data['executed_in']           = $time_taken;
						$backup_bank_data['total_size']            = $bb_backup_data_array['total_size'];
						$backup_bank_data['meta_id']               = $bb_backup_data_array['meta_id'];
						$backup_bank_data['executed_time']         = LOCAL_TIME_BACKUP_BANK;
						$backup_bank_data['backup_urlpath']        = $bb_backup_data_array['backup_urlpath'];
						$backup_bank_data['execution_time']        = $bb_backup_data_array['execution_time'];
						$backup_bank_data['log_filename']          = $bb_backup_data_array['log_filename'];

						$log_url_filename = str_replace( str_replace( '\\', '/', untrailingslashit( ABSPATH ) ), site_url(), $log_file );

						if ( ! isset( $bb_backup_data_array['restore_log_filename'] ) ) {
							$backup_bank_data['restore_log_urlpath']    = maybe_serialize( array( $log_url_filename ) );
							$backup_bank_data['restore_log_filename']   = maybe_serialize( array( $log_file ) );
							$backup_bank_data['restore_execution_time'] = maybe_serialize( array( LOCAL_TIME_BACKUP_BANK ) );
						} else {
							$restore_log_urlpath    = maybe_unserialize( $bb_backup_data_array['restore_log_urlpath'] );
							$restore_execution_time = maybe_unserialize( $bb_backup_data_array['restore_execution_time'] );
							$restore_log_array      = maybe_unserialize( $bb_backup_data_array['restore_log_filename'] );
							if ( ! in_array( $log_file, $restore_log_array, true ) ) {
								$restore_log_array[]      = $log_file;
								$restore_log_urlpath[]    = $log_url_filename;
								$restore_execution_time[] = LOCAL_TIME_BACKUP_BANK;
							} else {
								$index                            = array_search( $log_file, $restore_log_array, true );
								$restore_execution_time[ $index ] = LOCAL_TIME_BACKUP_BANK;
							}
							$backup_bank_data['restore_log_urlpath']    = maybe_serialize( $restore_log_urlpath );
							$backup_bank_data['restore_log_filename']   = maybe_serialize( $restore_log_array );
							$backup_bank_data['restore_execution_time'] = maybe_serialize( $restore_execution_time );
						}

						echo 'restore_terminated' === $restore_ret ? '11' : '';

						$bb_restore_data = $wpdb->get_row(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_id = %d', $backup_id
							)
						);// WPCS: db call ok, cache ok.
						if ( '' !== $bb_restore_data ) {
							$manage_backups_data               = array();
							$where                             = array();
							$where['meta_id']                  = $backup_id;
							$where['meta_key']                 = $bb_backup_data->meta_key;// WPCS: db sql slow query.
							$manage_backups_data['meta_value'] = maybe_serialize( $backup_bank_data );// WPCS: db sql slow query.
							$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $manage_backups_data, $where );
						} else {
							$restore_id = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT id From ' . $wpdb->prefix . 'backup_bank WHERE type= %s', 'backups'
								)
							);// WPCS: db call ok, cache ok.

							$insert_restore_data              = array();
							$insert_restore_data['type']      = 'backup_schedule_meta' === $bb_backup_data->meta_key ? 'schedule_backup' : 'manual_backup';
							$insert_restore_data['parent_id'] = $restore_id;
							$last_id                          = $obj_dbhelper_backup_bank->insert_command( backup_bank(), $insert_restore_data );

							$backup_bank_restore_data               = array();
							$backup_bank_restore_data['meta_key']   = $bb_backup_data->meta_key;// WPCS: db sql slow query.
							$backup_bank_restore_data['meta_value'] = maybe_serialize( $backup_bank_data );// WPCS: db sql slow query.
							$backup_bank_restore_data['meta_id']    = $last_id;
							$obj_dbhelper_backup_bank->insert_command( backup_bank_meta(), $backup_bank_restore_data );
						}

						$alert_setup_data       = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'alert_setup'
							)
						);// WPCS: db call ok, cache ok.
						$alert_setup_data_array = maybe_unserialize( $alert_setup_data );
						if ( 'restore_terminated' !== $restore_ret ) {
							if ( 'enable' === $alert_setup_data_array['email_when_restore_completed_successfully'] ) {
								$backup_restore_data       = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'template_for_restore_successfully'
									)
								);// WPCS: db call ok, cache ok.
								$backup_restore_data_array = maybe_unserialize( $backup_restore_data );
								$dbmailer_backup_bank_obj->template_for_restore_successfully( $backup_restore_data_array, $backup_bank_data );
							}
						} else {
							if ( 'enable' === $alert_setup_data_array['email_when_restore_failed'] ) {
								$email_backup_restore       = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key =%s', 'template_for_restore_failure'
									)
								);// WPCS: db call ok, cache ok.
								$email_backup_restore_array = maybe_unserialize( $email_backup_restore );
								$dbmailer_backup_bank_obj->template_for_restore_failure( $email_backup_restore_array, $backup_bank_data );
							}
						}
					}
					break;

				case 'backup_bank_manage_backups_delete_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_manage_backups_delete' ) ) {// WPCS: input var ok.
						$where                 = array();
						$where_meta            = array();
						$where['id']           = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : '';// WPCS: input var ok.
						$bb_backup_data        = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_id = %d', $where['id']
							)
						);// WPCS: db call ok, cache ok.
						$bb_backup_data_array  = maybe_unserialize( $bb_backup_data );
						$where_meta['meta_id'] = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : '';// WPCS: input var ok.
						$obj_dbhelper_backup_bank->delete_command( backup_bank(), $where );
						$obj_dbhelper_backup_bank->delete_command( backup_bank_meta(), $where_meta );

						if ( 'scheduled' === $bb_backup_data_array['execution'] ) {
							$cron_name = 'backup_scheduler_' . $where['id'];
							unschedule_events_backup_bank( $cron_name );
						}
						$backup_archive  = maybe_unserialize( $bb_backup_data_array['archive'] );
						$logfile_archive = maybe_unserialize( $bb_backup_data_array['log_filename'] );
						if ( isset( $backup_archive ) && count( $backup_archive ) > 0 ) {
							foreach ( $backup_archive as $value ) {
								if ( 'file_exists' !== $bb_backup_data_array['status'] ) {
									@unlink( untrailingslashit( $bb_backup_data_array['folder_location'] ) . '/' . $value );// @codingStandardsIgnoreLine
								}
							}
						}
						if ( isset( $logfile_archive ) && count( $logfile_archive ) > 0 ) {
							foreach ( $logfile_archive as $value ) {
								if ( 'file_exists' !== $bb_backup_data_array['status'] ) {
									@unlink( untrailingslashit( $bb_backup_data_array['folder_location'] ) . '/' . $value );// @codingStandardsIgnoreLine
									@unlink( untrailingslashit( $bb_backup_data_array['folder_location'] ) . '/' . str_replace( '.txt', '.json', $value ) );// @codingStandardsIgnoreLine
								}
							}
						}
						if ( isset( $bb_backup_data_array['restore_log_filename'] ) ) {
							$restore_logfile = maybe_unserialize( $bb_backup_data_array['restore_log_filename'] );
							if ( 'file_exists' !== $bb_backup_data_array['status'] ) {
								if ( isset( $restore_logfile ) && count( $restore_logfile ) > 0 ) {
									foreach ( $restore_logfile as $value ) {
										@unlink( $value );// @codingStandardsIgnoreLine
										@unlink( str_replace( '.txt', '.json', $value ) );// @codingStandardsIgnoreLine
									}
								}
							}
						}
					}
					break;

				case 'backup_bank_ftp_settings_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_ftp_settings' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $ftp_settings_data_array );// WPCS: input var ok.

						$bb_ftp_settings_id = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type = %s', 'ftp_settings'
							)
						);// WPCS: db call ok, cache ok.

						$ftp_settings_data                  = array();
						$ftp_settings_data['backup_to_ftp'] = sanitize_text_field( $ftp_settings_data_array['ux_ddl_ftp_settings_enable_disable'] );
						$ftp_settings_data['protocol']      = sanitize_text_field( $ftp_settings_data_array['ux_ddl_ftp_protocol'] );
						$ftp_settings_data['host']          = sanitize_text_field( $ftp_settings_data_array['ux_txt_ftp_settings_host'] );
						$ftp_settings_data['login_type']    = sanitize_text_field( $ftp_settings_data_array['ux_ddl_login_type'] );
						$ftp_settings_data['ftp_username']  = sanitize_text_field( $ftp_settings_data_array['ux_txt_ftp_settings_username'] );
						$ftp_settings_data['ftp_password']  = sanitize_text_field( $ftp_settings_data_array['ux_txt_ftp_settings_password'] );

						$ftp_settings_data['port']        = sanitize_text_field( $ftp_settings_data_array['ux_txt_ftp_settings_port'] );
						$ftp_settings_data['remote_path'] = sanitize_text_field( $ftp_settings_data_array['ux_txt_ftp_settings_remote_path'] );
						$ftp_settings_data['ftp_mode']    = sanitize_text_field( $ftp_settings_data_array['ux_ddl_ftp_mode'] );

						if ( 'enable' === $ftp_settings_data['backup_to_ftp'] ) {
							$obj_ftp_connect = new Ftp_Connection_Backup_Bank();
							$ftp_connection  = $obj_ftp_connect->ftp_connect( $ftp_settings_data['host'], $ftp_settings_data['protocol'], $ftp_settings_data['port'] );
							$ftp_login       = false;
							if ( false !== $ftp_connection ) {
								$ftp_login = $obj_ftp_connect->login_ftp( $ftp_connection, $ftp_settings_data['login_type'], $ftp_settings_data['ftp_username'], $ftp_settings_data_array['ux_txt_ftp_settings_password'] );
							}
						} else {
							$ftp_login = true;
						}

						if ( false !== $ftp_login ) {
							$update_ftp_settings               = array();
							$where                             = array();
							$where['meta_id']                  = $bb_ftp_settings_id;
							$where['meta_key']                 = 'ftp_settings';// WPCS: db sql slow query.
							$update_ftp_settings['meta_value'] = maybe_serialize( $ftp_settings_data );// WPCS: db sql slow query.
							$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $update_ftp_settings, $where );
						}
					}
					break;

				case 'backup_bank_dropbox_settings_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_dropbox_settings' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $backup_bank_dropbox_settings_data_array );// WPCS: input var ok.

						$backup_bank_dropbox_settings                      = array();
						$backup_bank_dropbox_settings['backup_to_dropbox'] = sanitize_text_field( $backup_bank_dropbox_settings_data_array['ux_ddl_dropbox_settings_enable_disable'] );
						$backup_bank_dropbox_settings['api_key']           = trim( sanitize_text_field( $backup_bank_dropbox_settings_data_array['ux_txt_dropbox_api_key'] ) );
						$backup_bank_dropbox_settings['secret_key']        = trim( sanitize_text_field( $backup_bank_dropbox_settings_data_array['ux_txt_dropbox_secret_key'] ) );

						if ( sanitize_text_field( $backup_bank_dropbox_settings_data_array['ux_ddl_dropbox_settings_enable_disable'] ) === 'enable' ) {
							$obj_dropbox_backup_bank = new Dropbox_Backup_Bank();
							update_option( 'backup_bank_dropbox_array', $backup_bank_dropbox_settings );
							$obj_dropbox = $obj_dropbox_backup_bank->dropbox_client( trim( sanitize_text_field( $backup_bank_dropbox_settings_data_array['ux_txt_dropbox_api_key'] ) ), trim( sanitize_text_field( $backup_bank_dropbox_settings_data_array['ux_txt_dropbox_secret_key'] ) ) );
							$obj_dropbox_backup_bank->handle_dropbox_auth( $obj_dropbox, sanitize_text_field( $backup_bank_dropbox_settings_data_array['ux_ddl_dropbox_settings_enable_disable'] ), trim( sanitize_text_field( $backup_bank_dropbox_settings_data_array['ux_txt_dropbox_api_key'] ) ), trim( sanitize_text_field( $backup_bank_dropbox_settings_data_array['ux_txt_dropbox_secret_key'] ) ) );

						} else {
							$bb_dropbox_settings_id = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type = %s', 'dropbox_settings'
								)
							);// WPCS: db call ok, cache ok.

							$where                             = array();
							$backup_bank_dropbox_settings_data = array();
							$where['meta_id']                  = $bb_dropbox_settings_id;
							$where['meta_key']                 = 'dropbox_settings';// WPCS: db sql slow query.
							$backup_bank_dropbox_settings_data['meta_value'] = maybe_serialize( $backup_bank_dropbox_settings );// WPCS: db sql slow query.
							$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $backup_bank_dropbox_settings_data, $where );
						}
					}
					break;

				case 'backup_bank_restore_message':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_restore_message' ) ) {
						$restore_path = isset( $_REQUEST['restore_path'] ) ? sanitize_text_field( $_REQUEST['restore_path'] ) : '';// WPCS: input var ok, sanitization ok.
						$location     = trailingslashit( str_replace( content_url(), WP_CONTENT_DIR, dirname( dirname( $restore_path ) ) ) ) . 'restore/';
						! is_dir( $location ) ? wp_mkdir_p( $location ) : '';

						$remove_ext   = strstr( basename( $restore_path ), '.' );
						$archive_name = str_replace( $remove_ext, '', basename( $restore_path ) );

						$file_name     = trailingslashit( $location ) . $archive_name . '.json';
						$file_url_path = trailingslashit( dirname( dirname( $restore_path ) ) ) . 'restore/' . $archive_name . '.json';

						$result = 1;
						file_put_contents( $file_name, '' );// @codingStandardsIgnoreLine
						$message  = '{' . "\r\n";
						$message .= '"log": "Restoring Backup" ,' . "\r\n";
						$message .= '"perc": ' . $result . "\r\n";
						$message .= '}';
						file_put_contents( $file_name, $message );// @codingStandardsIgnoreLine

						echo $file_url_path;// WPCS: XSS ok.
					}
					break;

				case 'check_cloud_connection':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_check_ftp_dropbox_connection' ) ) {// WPCS: input var ok.
						$backup_destination = isset( $_REQUEST['backup_destination'] ) ? base64_decode( wp_unslash( $_REQUEST['backup_destination'] ) ) : '';// WPCS: input var ok, sanitization ok.
						$backup_type        = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';// WPCS: input var ok.

						$archive_name = isset( $_REQUEST['archive_name'] ) ? base64_decode( wp_unslash( $_REQUEST['archive_name'] ) ) : '';// WPCS: input var ok, sanitization ok.
						$location     = base64_decode( isset( $_REQUEST['content_location'] ) ? $_REQUEST['content_location'] : '' ) . base64_decode( isset( $_REQUEST['folder_location'] ) ? wp_unslash( $_REQUEST['folder_location'] ) : '' );// WPCS: input var ok, sanitization ok.
						! is_dir( $location ) ? wp_mkdir_p( $location ) : '';

						$file_name = trailingslashit( $location ) . $archive_name . '.json';

						$path          = trailingslashit( base64_decode( isset( $_REQUEST['folder_location'] ) ? $_REQUEST['folder_location'] : '' ) );// WPCS: input var ok, sanitization ok.
						$file_url_path = content_url() . $path . $archive_name . '.json';

						$result = 1;
						switch ( $backup_destination ) {
							case 'ftp':
								$ftp_settings_data       = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'ftp_settings'
									)
								);// WPCS: db call ok, cache ok.
								$upload_ftp              = '';
								$ftp_settings_data_array = maybe_unserialize( $ftp_settings_data );
								$obj_ftp_connect         = new Ftp_Connection_Backup_Bank();
								$ftp_connection          = $obj_ftp_connect->ftp_connect( $ftp_settings_data_array['host'], $ftp_settings_data_array['protocol'], $ftp_settings_data_array['port'] );

								if ( false !== $ftp_connection ) {
									$ftp_login = $obj_ftp_connect->login_ftp( $ftp_connection, $ftp_settings_data_array['login_type'], $ftp_settings_data_array['ftp_username'], $ftp_settings_data_array['ftp_password'] );
									if ( false === $ftp_login ) {
										die();
									}
									$ftp_result = $obj_ftp_connect->ftp_mkdir_recusive( $ftp_connection, trailingslashit( $ftp_settings_data_array['remote_path'] ) . BACKUP_BANK_FOLDER_DROPBOX );

									if ( false !== $ftp_result ) {
										$ftp_connection->pasv( $ftp_settings_data_array['ftp_mode'] );
										$test_file   = BACKUP_BANK_DIR_PATH . 'lib/ftp-client/backup-bank-ftp-test.txt';
										$backup_name = basename( $test_file );
										if ( ! @$ftp_connection->put( $backup_name, $test_file, FTP_BINARY ) ) {// @codingStandardsIgnoreLine
											$upload_ftp = '550';
										}
									} else {
										$upload_ftp = '550';
									}
								} else {
									die();
								}
								if ( '' !== $upload_ftp ) {
									echo $upload_ftp;// WPCS: XSS ok.
									die();
								}
								break;

							case 'dropbox':
								$bb_dropbox_settings_data = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'dropbox_settings'
									)
								);// WPCS: db call ok, cache ok.

								$bb_dropbox_settings_unserialize_data = maybe_unserialize( $bb_dropbox_settings_data );
								$obj_dropbox_backup_bank              = new Dropbox_Backup_Bank();
								$obj_dropbox                          = $obj_dropbox_backup_bank->dropbox_client( $bb_dropbox_settings_unserialize_data['api_key'], $bb_dropbox_settings_unserialize_data['secret_key'] );
								$check_account                        = $obj_dropbox_backup_bank->check_dropbox_build_authorize( $obj_dropbox );
								$dropbox_connect                      = $obj_dropbox_backup_bank->check_handle_dropbox_auth( $obj_dropbox );
								if ( false === $dropbox_connect ) {
									echo '101';
									die();
								}
								break;

							case 'google_drive':
								$google_drive_data            = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'google_drive'
									)
								);// WPCS: db call ok, cache ok.
								$google_drive_data_array      = maybe_unserialize( $google_drive_data );
								$obj_google_drive_backup_bank = new Google_Drive_Backup_Bank();
								$check                        = $obj_google_drive_backup_bank->google_drive_check_auth_token( $google_drive_data_array['client_id'], $google_drive_data_array['secret_key'], $google_drive_data_array['redirect_uri'] );
								if ( '601' === $check ) {
									echo '601';
									die();
								}
								break;
						}

						file_put_contents( $file_name, '' );// @codingStandardsIgnoreLine
						$message  = '{' . "\r\n";
						$message .= '"log": "Starting Backup" ,' . "\r\n";
						$message .= '"perc": ' . $result . ',' . "\r\n";
						$message .= '"status": "Starting" ,' . "\r\n";
						$message .= '"cloud": 1' . "\r\n";
						$message .= '}';
						file_put_contents( $file_name, $message );// @codingStandardsIgnoreLine
						echo untrailingslashit( $file_url_path );// @codingStandardsIgnoreLine
					}
					break;

				case 'check_cloud_connection_rerun':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_check_ftp_dropbox_connection_rerun' ) ) {// WPCS: input var ok.
						$backup_destination = isset( $_REQUEST['backup_destination'] ) ? base64_decode( $_REQUEST['backup_destination'] ) : '';// WPCS: input var ok, sanitization ok.

						$archive_name = isset( $_REQUEST['archive_name'] ) ? base64_decode( $_REQUEST['archive_name'] ) : '';// WPCS: input var ok, sanitization ok.
						$location     = base64_decode( $_REQUEST['location'] );// @codingStandardsIgnoreLine
						! is_dir( $location ) ? wp_mkdir_p( $location ) : '';

						$file_name     = trailingslashit( $location ) . $archive_name . '.json';
						$file_url_path = str_replace( str_replace( '\\', '/', WP_CONTENT_DIR ), content_url(), $file_name );

						$result = 1;
						switch ( $backup_destination ) {
							case 'ftp':
								$ftp_settings_data       = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'ftp_settings'
									)
								);// WPCS: db call ok, cache ok.
								$upload_ftp              = '';
								$ftp_settings_data_array = maybe_unserialize( $ftp_settings_data );
								if ( 'disable' === $ftp_settings_data_array['backup_to_ftp'] ) {
									echo '553';
									die();
								}
								$obj_ftp_connect = new Ftp_Connection_Backup_Bank();
								$ftp_connection  = $obj_ftp_connect->ftp_connect( $ftp_settings_data_array['host'], $ftp_settings_data_array['protocol'], $ftp_settings_data_array['port'] );

								if ( false !== $ftp_connection ) {
									$ftp_login = $obj_ftp_connect->login_ftp( $ftp_connection, $ftp_settings_data_array['login_type'], $ftp_settings_data_array['ftp_username'], $ftp_settings_data_array['ftp_password'] );
									if ( false === $ftp_login ) {
										die();
									}
									$ftp_result = $obj_ftp_connect->ftp_mkdir_recusive( $ftp_connection, $ftp_settings_data_array['remote_path'] );

									if ( false !== $ftp_result ) {
										$ftp_connection->pasv( $ftp_settings_data_array['ftp_mode'] );
										$test_file   = BACKUP_BANK_DIR_PATH . 'lib/ftp-client/backup-bank-ftp-test.txt';
										$backup_name = basename( $test_file );
										if ( ! @$ftp_connection->put( $backup_name, $test_file, FTP_BINARY ) ) {// @codingStandardsIgnoreLine
											$upload_ftp = '550';
										}
									} else {
										$upload_ftp = '550';
									}
								} else {
									die();
								}
								if ( '' !== $upload_ftp ) {
									echo $upload_ftp;// WPCS: XSS ok.
									die();
								}
								break;

							case 'dropbox':
								$bb_dropbox_settings_data = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'dropbox_settings'
									)
								);// WPCS: db call ok, cache ok.

								$bb_dropbox_settings_unserialize_data = maybe_unserialize( $bb_dropbox_settings_data );
								if ( 'disable' === $bb_dropbox_settings_unserialize_data['backup_to_dropbox'] ) {
									echo '554';
									die();
								}
								$obj_dropbox_backup_bank = new Dropbox_Backup_Bank();
								$obj_dropbox             = $obj_dropbox_backup_bank->dropbox_client( $bb_dropbox_settings_unserialize_data['api_key'], $bb_dropbox_settings_unserialize_data['secret_key'] );
								$check_account           = $obj_dropbox_backup_bank->check_dropbox_build_authorize( $obj_dropbox );
								$dropbox_connect         = $obj_dropbox_backup_bank->check_handle_dropbox_auth( $obj_dropbox );
								if ( false == $dropbox_connect ) {// WPCS: loose comparison ok.
									echo '101';
									die();
								}
								break;

							case 'email':
								$email_setting_data = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'email_settings'
									)
								);// WPCS: db call ok, cache ok.

								$email_settings_array = maybe_unserialize( $email_setting_data );
								if ( 'disable' === $email_settings_array['backup_to_email'] ) {
									echo '555';
									die();
								}
								break;

							case 'google_drive':
								$google_drive_data       = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'google_drive'
									)
								);// WPCS: db call ok, cache ok.
								$google_drive_data_array = maybe_unserialize( $google_drive_data );
								if ( 'disable' === $google_drive_data_array['backup_to_google_drive'] ) {
									echo '600';
									die();
								}
								$obj_google_drive_backup_bank = new Google_Drive_Backup_Bank();
								$check                        = $obj_google_drive_backup_bank->google_drive_check_auth_token( $google_drive_data_array['client_id'], $google_drive_data_array['secret_key'], $google_drive_data_array['redirect_uri'] );
								if ( '601' == $check ) {// WPCS: loose comparison ok.
									echo '601';
									die();
								}
								break;
						}
						$message  = '{' . "\r\n";
						$message .= '"log": "Re-running Backup" ,' . "\r\n";
						$message .= '"perc": ' . $result . "\r\n";
						$message .= '"cloud": 1' . "\r\n";
						$message .= '}';
						file_put_contents( $file_name, $message );// @codingStandardsIgnoreLine
						echo $file_url_path;// WPCS: XSS ok.
					}
					break;

				case 'backup_bank_rerun_backups':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_manage_rerun_backups' ) ) {// WPCS: input var ok.
						$backup_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : '';// WPCS: input var ok.

						$backups_data       = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_id=%d', $backup_id
							)
						);// WPCS: db call ok, cache ok.
						$backups_data_array = maybe_unserialize( $backups_data );

						@unlink( untrailingslashit( $backups_data_array['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backups_data_array['archive'] ) ) );// @codingStandardsIgnoreLine
						@unlink( untrailingslashit( $backups_data_array['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backups_data_array['log_filename'] ) ) );// @codingStandardsIgnoreLine

						$backups_data_array['timezone_difference'] = '';
						if ( isset( $backups_data_array['old_backup'] ) ) {
							unset( $backups_data_array['old_backup'] );
							unset( $backups_data_array['old_backup_logfile'] );
						}
						$obj_backup_data_backup_bank = new Backup_Data_Backup_Bank();
						$obj_backup_data_backup_bank->close_browser_connection();
						do_action( 'start_backup', $backups_data_array );
					}
					break;

				case 'backup_bank_google_drive_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_google_drive_settings' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $backup_bank_google_drive_data_array );// WPCS: input var ok.

						$bb_google_drive_id = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type = %s', 'google_drive'
							)
						);// WPCS: db call ok, cache ok.

						$backup_bank_google_drive                           = array();
						$backup_bank_google_drive['backup_to_google_drive'] = sanitize_text_field( $backup_bank_google_drive_data_array['ux_ddl_google_drive_enable_disable'] );
						$backup_bank_google_drive['client_id']              = trim( sanitize_text_field( $backup_bank_google_drive_data_array['ux_txt_google_drive_client_id'] ) );
						$backup_bank_google_drive['secret_key']             = trim( sanitize_text_field( $backup_bank_google_drive_data_array['ux_txt_google_drive_secret_key'] ) );
						$backup_bank_google_drive['redirect_uri']           = sanitize_text_field( $backup_bank_google_drive_data_array['ux_txt_google_drive_redirect_uri'] );

						if ( 'enable' === $backup_bank_google_drive_data_array['ux_ddl_google_drive_enable_disable'] ) {
							update_option( 'backup_bank_google_drive_array', $backup_bank_google_drive );
							$obj_google_drive_backup_bank = new Google_Drive_Backup_Bank();
							$obj_google_drive_backup_bank->google_drive_client( $backup_bank_google_drive['client_id'], $backup_bank_google_drive['secret_key'], $backup_bank_google_drive['redirect_uri'] );
						} else {
							$where                                       = array();
							$backup_bank_google_drive_data               = array();
							$where['meta_id']                            = $bb_google_drive_id;
							$where['meta_key']                           = 'google_drive';// WPCS: db sql slow query.
							$backup_bank_google_drive_data['meta_value'] = maybe_serialize( $backup_bank_google_drive );// WPCS: db sql slow query.
							$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $backup_bank_google_drive_data, $where );
						}
					}
					break;

				case 'wizard_backup':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'backup_bank_check_status' ) ) {// WPCS: input var ok.
						$type             = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';// WPCS: input var ok.
						$user_admin_email = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';// WPCS: input var ok.
						if ( '' === $user_admin_email ) {
							$user_admin_email = get_option( 'admin_email' );
						}
						update_option( 'backup-bank-admin-email', $user_admin_email );
						update_option( 'backup-bank-wizard', $type );
						if ( 'opt_in' === $type ) {
							$class_plugin_info = new class_plugin_info();
							global $wp_version;
							$theme_details = array();
							if ( $wp_version >= 3.4 ) {
								$active_theme                   = wp_get_theme();
								$theme_details['theme_name']    = strip_tags( $active_theme->name );
								$theme_details['theme_version'] = strip_tags( $active_theme->version );
								$theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
							}
							$plugin_stat_data                     = array();
							$plugin_stat_data['plugin_slug']      = 'wp-backup-bank';
							$plugin_stat_data['type']             = 'standard_edition';
							$plugin_stat_data['version_number']   = BACKUP_BANK_VERSION_NUMBER;
							$plugin_stat_data['status']           = $type;
							$plugin_stat_data['event']            = 'activate';
							$plugin_stat_data['domain_url']       = site_url();
							$plugin_stat_data['wp_language']      = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
							$plugin_stat_data['email']            = $user_admin_email;
							$plugin_stat_data['wp_version']       = $wp_version;
							$plugin_stat_data['php_version']      = sanitize_text_field( phpversion() );
							$plugin_stat_data['mysql_version']    = $wpdb->db_version();
							$plugin_stat_data['max_input_vars']   = ini_get( 'max_input_vars' );
							$plugin_stat_data['operating_system'] = PHP_OS . '  (' . PHP_INT_SIZE * 8 . ') BIT';
							$plugin_stat_data['php_memory_limit'] = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
							$plugin_stat_data['extensions']       = get_loaded_extensions();
							$plugin_stat_data['plugins']          = $class_plugin_info->get_plugin_info();
							$plugin_stat_data['themes']           = $theme_details;
							$url                                  = TECH_BANKER_STATS_URL . '/wp-admin/admin-ajax.php';
							$response                             = wp_safe_remote_post(
								$url, array(
									'method'      => 'POST',
									'timeout'     => 45,
									'redirection' => 5,
									'httpversion' => '1.0',
									'blocking'    => true,
									'headers'     => array(),
									'body'        => array(
										'data'    => maybe_serialize( $plugin_stat_data ),
										'site_id' => false !== get_option( 'tech_banker_site_id' ) ? get_option( 'tech_banker_site_id' ) : '',
										'action'  => 'plugin_analysis_data',
									),
								)
							);
							if ( ! is_wp_error( $response ) ) {
								false !== $response['body'] ? update_option( 'tech_banker_site_id', $response['body'] ) : '';
							}
						}
					}
					break;
			}
			die();
		}
	}
}
