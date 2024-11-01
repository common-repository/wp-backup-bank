<?php
/**
 * This file is used for fetching data from database.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/includes
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly.
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
			 * This function is used to get unserialized data.
			 *
			 * @param string $manage_data .
			 */
			function get_backup_bank_unserialize_data( $manage_data ) {
				$unserialize_complete_data = array();
				if ( count( $manage_data ) > 0 ) {
					foreach ( $manage_data as $value ) {
						$unserialize_data = maybe_unserialize( $value->meta_value );

						$unserialize_data['meta_id'] = $value->meta_id;
						array_push( $unserialize_complete_data, $unserialize_data );
					}
				}
				return $unserialize_complete_data;
			}
		}

		if ( ! function_exists( 'get_backup_bank_destinations_unserialize_data' ) ) {
			/**
			 * This function is used to get destinations unserialized data.
			 *
			 * @param string $manage_data .
			 */
			function get_backup_bank_destinations_unserialize_data( $manage_data ) {
				$unserialize_complete_data = array();
				if ( count( $manage_data ) > 0 ) {
					foreach ( $manage_data as $value ) {
						$unserialize_destination_data = maybe_unserialize( $value->meta_value );
						foreach ( $unserialize_destination_data as $key => $data ) {
							$unserialize_complete_data[ $key ] = $data;
						}
					}
				}
				return $unserialize_complete_data;
			}
		}

		if ( ! function_exists( 'get_backup_bank_tables' ) ) {
			/**
			 * This function is used to get tables.
			 *
			 * @param string $result .
			 */
			function get_backup_bank_tables( $result ) {
				$tables = array();
				for ( $flag = 0; $flag < count( $result ); $flag++ ) { // @codingStandardsIgnoreLine.
					if ( backup_bank_restore() != $result[ $flag ]->Name ) { // WPCS: loose comparison ok.
						array_push( $tables, $result[ $flag ]->Name );
					}
				}
				return $tables;
			}
		}

		if ( ! function_exists( 'get_backup_bank_schedule_time' ) ) {
			/**
			 * This function is used to get schedule time.
			 *
			 * @param string $schedule_name .
			 */
			function get_backup_bank_schedule_time( $schedule_name ) {
				$execution_time        = '';
				$scheduler_backup_bank = _get_cron_array();
				if ( count( $scheduler_backup_bank ) > 0 ) {
					foreach ( $scheduler_backup_bank as $value => $key ) {
						$arr_key = array_keys( $key );
						foreach ( $arr_key as $row ) {
							if ( strstr( $row, $schedule_name ) ) {
								$execution_time = $value;
							}
						}
					}
				}
				return $execution_time;
			}
		}
		if ( isset( $_GET['page'] ) ) {
			$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );// WPCS: CSRF ok,WPCS: input var ok.
		}
		$check_backup_bank_wizard = get_option( 'backup-bank-wizard' );
		$licensing_url            = false === $check_backup_bank_wizard ? 'bb_wizard_backup' : $page;
		if ( isset( $_GET['page'] ) ) { // WPCS: CSRF ok, input var ok.
			switch ( $licensing_url ) {
				case 'bb_roles_and_capabilities':
					$roles_data                 = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'roles_and_capabilities'
						)
					);// WPCS: db call ok, no-cache ok.
					$details_roles_capabilities = maybe_unserialize( $roles_data );
					$other_roles_array          = $details_roles_capabilities['capabilities'];
					break;

				case 'bb_start_backup':
					if ( is_multisite() ) {
						$name     = '';
						$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );// WPCS: db call ok, no-cache ok.
						if ( isset( $blog_ids ) && count( $blog_ids ) > 0 ) {
							foreach ( $blog_ids as $blog_id ) {
								$name .= " AND Name NOT LIKE '" . $wpdb->prefix . $blog_id . "%'";
							}
						}
						$backup_tables = 'SHOW TABLE STATUS FROM `' . DB_NAME . "` WHERE Name LIKE '" . $wpdb->prefix . "%'" . $name;
					} else {
						$backup_tables = 'SHOW TABLE STATUS FROM `' . DB_NAME . '`';
					}
					$result = $wpdb->get_results( $backup_tables );// @codingStandardsIgnoreLine.
					$result = get_backup_bank_tables( $result );

					$settings_data       = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key in (%s,%s,%s,%s,%s,%s,%s,%s)', 'dropbox_settings', 'email_settings', 'ftp_settings', 'amazons3_settings', 'onedrive_settings', 'rackspace_settings', 'azure_settings', 'google_drive'
						)
					);// WPCS: db call ok, no-cache ok.
					$settings_data_array = get_backup_bank_destinations_unserialize_data( $settings_data );
					break;

				case 'bb_schedule_backup':
					if ( is_multisite() ) {
						$name     = '';
						$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );// WPCS: db call ok, no-cache ok.
						if ( isset( $blog_ids ) && count( $blog_ids ) > 0 ) {
							foreach ( $blog_ids as $blog_id ) {
								$name .= " AND Name NOT LIKE '" . $wpdb->prefix . $blog_id . "%'";
							}
						}
						$backup_tables = 'SHOW TABLE STATUS FROM `' . DB_NAME . "` WHERE Name LIKE '" . $wpdb->prefix . "%'" . $name;
					} else {
						$backup_tables = 'SHOW TABLE STATUS FROM `' . DB_NAME . '`';
					}
					$result = $wpdb->get_results( $backup_tables );// @codingStandardsIgnoreLine.
					$result = get_backup_bank_tables( $result );

					$settings_data       = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key in (%s,%s,%s,%s,%s,%s,%s,%s)', 'dropbox_settings', 'email_settings', 'ftp_settings', 'amazons3_settings', 'onedrive_settings', 'rackspace_settings', 'azure_settings', 'google_drive'
						)
					);// WPCS: db call ok, no-cache ok.
					$settings_data_array = get_backup_bank_destinations_unserialize_data( $settings_data );
					break;

				case 'bb_alert_setup':
					$bb_alert_setup_updated_data = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'alert_setup'
						)
					);// WPCS: db call ok, no-cache ok.
					$bb_alert_setup_array        = maybe_unserialize( $bb_alert_setup_updated_data );
					break;

				case 'bb_other_settings':
					$bb_other_settings_updated_data = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'other_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$bb_other_settings_array        = maybe_unserialize( $bb_other_settings_updated_data );

					$bb_other_settings_maintenance_mode      = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_restore WHERE meta_key = %s', 'maintenance_mode_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$bb_other_settings_maintenance_mode_data = maybe_unserialize( $bb_other_settings_maintenance_mode );
					break;

				case 'bb_email_settings':
					$email_setting_data       = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'email_settings'
						)
					); // WPCS: db call ok, no-cache ok.
					$email_setting_data_array = maybe_unserialize( $email_setting_data );
					break;

				case 'bb_onedrive_settings':
					$bb_onedrive_settings_data             = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'onedrive_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$bb_onedrive_settings_unserialize_data = maybe_unserialize( $bb_onedrive_settings_data );
					if ( ! empty( $_REQUEST['access_code'] ) ) {// WPCS: input var ok, CSRF ok.
						$backup_bank_onedrive_settings = get_option( 'backup_bank_one_drive_array' );
						$auth_code                     = esc_attr( $_REQUEST['access_code'] );// WPCS: CSRF ok, input var ok, sanitization ok.
						$obj_onedrive_auth_backup_bank = new onedrive_auth_backup_bank( $backup_bank_onedrive_settings['client_id'], $backup_bank_onedrive_settings['client_secret'] );
						$response                      = $obj_onedrive_auth_backup_bank->get_oauth_token( $auth_code );

						if ( ! isset( $response['error_description'] ) ) {
							$obj_dbhelper_backup_bank           = new Dbhelper_Backup_Bank();
							$bb_onedrive_settings_id            = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type = %s', 'onedrive_settings'
								)
							);// WPCS: db call ok, no-cache ok.
							$where                              = array();
							$backup_bank_onedrive_settings_data = array();
							$where['meta_id']                   = $bb_onedrive_settings_id;
							$where['meta_key']                  = 'onedrive_settings'; // WPCS: slow query ok.
							$backup_bank_onedrive_settings_data['meta_value'] = maybe_serialize( $backup_bank_onedrive_settings );// WPCS: slow query ok.
							$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $backup_bank_onedrive_settings_data, $where );
							$obj_onedrive_auth_backup_bank->save_tokens_to_store( $response );
						}
					}
					break;

				case 'bb_manage_backups':
					$bb_backups_id = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type = %s', 'backups'
						)
					);// WPCS: db call ok, no-cache ok.

					$bb_backups_data              = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'backup_bank_meta INNER JOIN ' . $wpdb->prefix . 'backup_bank ON ' . $wpdb->prefix . 'backup_bank.id=' . $wpdb->prefix . 'backup_bank_meta.meta_id WHERE parent_id = %d ORDER BY ' . $wpdb->prefix . 'backup_bank.id desc', $bb_backups_id
						)
					);// WPCS: db call ok, no-cache ok.
					$bb_backups_unserialized_data = get_backup_bank_unserialize_data( $bb_backups_data );
					break;

				case 'bb_ftp_settings':
					$ftp_settings_data       = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'ftp_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$ftp_settings_data_array = maybe_unserialize( $ftp_settings_data );
					break;

				case 'bb_amazons3_settings':
					$amazons3_settings_data       = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'amazons3_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$amazons3_settings_data_array = maybe_unserialize( $amazons3_settings_data );
					break;

				case 'bb_dropbox_settings':
					$obj_dbhelper_backup_bank = new Dbhelper_Backup_Bank();
					if ( isset( $_REQUEST['code'] ) ) { // WPCS: CSRF ok, input var ok.
						$backup_bank_dropbox_array = get_option( 'backup_bank_dropbox_array' );
						$obj_dropbox_backup_bank   = new Dropbox_Backup_Bank();
						$code                      = wp_unslash( $_REQUEST['code'] );// WPCS: CSRF ok, input var ok, sanitization ok.
						$obj_dropbox               = $obj_dropbox_backup_bank->dropbox_client( $backup_bank_dropbox_array['api_key'], $backup_bank_dropbox_array['secret_key'] );
						$access_token              = $obj_dropbox->get_bearer_token( $code, admin_url() . 'admin.php?page=bb_dropbox_settings' );
						$obj_dropbox_backup_bank->store_token( $access_token, 'access' );
						$bb_dropbox_settings_id = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type = %s', 'dropbox_settings'
							)
						);// WPCS: db call ok, no-cache ok.

						$backup_bank_dropbox_settings                      = array();
						$backup_bank_dropbox_settings['backup_to_dropbox'] = $backup_bank_dropbox_array['backup_to_dropbox'];
						$backup_bank_dropbox_settings['api_key']           = $backup_bank_dropbox_array['api_key'];
						$backup_bank_dropbox_settings['secret_key']        = $backup_bank_dropbox_array['secret_key'];

						$where                             = array();
						$backup_bank_dropbox_settings_data = array();
						$where['meta_id']                  = $bb_dropbox_settings_id;
						$where['meta_key']                 = 'dropbox_settings'; // WPCS: slow query ok.
						$backup_bank_dropbox_settings_data['meta_value'] = maybe_serialize( $backup_bank_dropbox_settings );// WPCS: slow query ok.
						$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $backup_bank_dropbox_settings_data, $where );

					}
					$bb_dropbox_settings_data             = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'dropbox_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$bb_dropbox_settings_unserialize_data = maybe_unserialize( $bb_dropbox_settings_data );
					break;
				case 'bb_rackspace_settings':
					$rackspace_settings_data       = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'rackspace_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$rackspace_settings_data_array = maybe_unserialize( $rackspace_settings_data );
					break;
				case 'bb_ms_azure_settings':
					$ms_azure_settings_data = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'azure_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$ms_azure_data_array    = maybe_unserialize( $ms_azure_settings_data );
					break;
				case 'bb_google_drive':
					$google_drive_data       = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'google_drive'
						)
					);// WPCS: db call ok, no-cache ok.
					$google_drive_data_array = maybe_unserialize( $google_drive_data );
					if ( isset( $_REQUEST['code'] ) ) { // WPCS: CSRF ok, input var ok.
						$backup_bank_google_drive     = get_option( 'backup_bank_google_drive_array' );
						$obj_google_drive_backup_bank = new Google_Drive_Backup_Bank();
						$google_auth                  = $obj_google_drive_backup_bank->google_auth_token( $_REQUEST['code'], $backup_bank_google_drive['client_id'], $backup_bank_google_drive['secret_key'], $backup_bank_google_drive['redirect_uri'] );// WPCS: CSRF ok, input var ok, sanitization ok.
						if ( '602' == $google_auth ) { // WPCS: loose comparison ok.
							return $google_auth;
						}
						$obj_dbhelper_backup_bank            = new Dbhelper_Backup_Bank();
						$bb_google_id                        = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT id FROM ' . $wpdb->prefix . 'backup_bank WHERE type = %s', 'google_drive'
							)
						);// WPCS: db call ok, no-cache ok.
						$where                               = array();
						$backup_bank_google_drive_data_array = array();
						$where['meta_id']                    = $bb_google_id;
						$where['meta_key']                   = 'google_drive'; // WPCS: slow query ok.
						$backup_bank_google_drive_data_array['meta_value'] = maybe_serialize( $backup_bank_google_drive );// WPCS: slow query ok.
						$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $backup_bank_google_drive_data_array, $where );
					}
					break;
			}
		}
	}
}
