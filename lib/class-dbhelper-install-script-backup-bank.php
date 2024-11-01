<?php
/**
 * This file is used for creating tables in database on the activation hook.
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
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	} else {
		if ( ! class_exists( 'DbHelper_Install_Script_Backup_Bank' ) ) {
			/**
			 * This Class is used for Insert Update operations.
			 */
			class DbHelper_Install_Script_Backup_Bank {
				/**
				 * This Function is used for Insert data in database.
				 *
				 * @param string $table_name .
				 * @param array  $data .
				 */
				public function insert_command( $table_name, $data ) {
					global $wpdb;
					$wpdb->insert( $table_name, $data );// WPCS: db call ok, no-cache ok.
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
			}
		}

		if ( ! function_exists( 'backup_bank_table' ) ) {
			/**
			 * This function is used to create parent table.
			 */
			function backup_bank_table() {
				global $wpdb;
				$obj_dbhelper_backup_bank_parent = new DbHelper_Install_Script_Backup_Bank();
				$sql                             = 'CREATE TABLE IF NOT EXISTS ' . backup_bank() . '
				(
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`type` varchar(100) NOT NULL,
					`parent_id` int(11) NOT NULL,
					PRIMARY KEY (`id`)
				)
				ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
				dbDelta( $sql );

				$data = 'INSERT INTO ' . backup_bank() . " (`type`, `parent_id`) VALUES
				('backups', 0),
				('general_settings', 0),
				('email_templates', 0),
				('collation_type', 0),
				('roles_and_capabilities', 0)";
				dbDelta( $data );

				$backup_bank_parent_table = $wpdb->get_results(
					'SELECT * FROM ' . $wpdb->prefix . 'backup_bank'
				);// WPCS: db call ok, no-cache ok.
				if ( isset( $backup_bank_parent_table ) && count( $backup_bank_parent_table ) > 0 ) {
					foreach ( $backup_bank_parent_table as $row ) {
						switch ( $row->type ) {
							case 'general_settings':
								$general_settings                       = array();
								$general_settings['alert_setup']        = $row->id;
								$general_settings['other_settings']     = $row->id;
								$general_settings['dropbox_settings']   = $row->id;
								$general_settings['email_settings']     = $row->id;
								$general_settings['ftp_settings']       = $row->id;
								$general_settings['amazons3_settings']  = $row->id;
								$general_settings['onedrive_settings']  = $row->id;
								$general_settings['rackspace_settings'] = $row->id;
								$general_settings['azure_settings']     = $row->id;
								$general_settings['google_drive']       = $row->id;

								foreach ( $general_settings as $key => $value ) {
									$general_settings_data              = array();
									$general_settings_data['type']      = $key;
									$general_settings_data['parent_id'] = $value;
									$obj_dbhelper_backup_bank_parent->insert_command( backup_bank(), $general_settings_data );
								}
								break;
						}
					}
				}
			}
		}

		if ( ! function_exists( 'backup_bank_meta_table' ) ) {
			/**
			 * This function is used to create meta table.
			 */
			function backup_bank_meta_table() {
				$obj_dbhelper_install_script_backup_bank = new DbHelper_Install_Script_Backup_Bank();
				global $wpdb;
				$sql = 'CREATE TABLE IF NOT EXISTS ' . backup_bank_meta() . '
				(
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`meta_id` int(11) NOT NULL,
					`meta_key` varchar(255) NOT NULL,
					`meta_value` longtext NOT NULL,
					PRIMARY KEY (`id`)
				)
				ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
				dbDelta( $sql );

				$admin_email = get_option( 'admin_email' );
				$admin_name  = get_option( 'blogname' );

				$backup_bank_table_data = $wpdb->get_results(
					'SELECT * FROM ' . $wpdb->prefix . 'backup_bank'
				);// WPCS: db call ok, no-cache ok.
				if ( isset( $backup_bank_table_data ) && count( $backup_bank_table_data ) > 0 ) {
					foreach ( $backup_bank_table_data as $row ) {
						switch ( $row->type ) {
							case 'alert_setup':
								$alert_setup_data_array = array();
								$alert_setup_data_array['email_when_backup_scheduled_successfully']  = 'enable';
								$alert_setup_data_array['email_when_backup_generated_successfully']  = 'enable';
								$alert_setup_data_array['email_when_backup_failed']                  = 'enable';
								$alert_setup_data_array['email_when_restore_completed_successfully'] = 'enable';
								$alert_setup_data_array['email_when_restore_failed']                 = 'enable';

								$alert_setup_data               = array();
								$alert_setup_data['meta_id']    = $row->id;
								$alert_setup_data['meta_key']   = 'alert_setup';// WPCS: Slow query.
								$alert_setup_data['meta_value'] = maybe_serialize( $alert_setup_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $alert_setup_data );
								break;

							case 'other_settings':
								$other_settings_data_array                               = array();
								$other_settings_data_array['automatic_plugin_updates']   = 'disable';
								$other_settings_data_array['remove_tables_at_uninstall'] = 'enable';
								$other_settings_data_array['Maintenance_mode']           = 'disable';

								$other_settings_data               = array();
								$other_settings_data['meta_id']    = $row->id;
								$other_settings_data['meta_key']   = 'other_settings';// WPCS: Slow query.
								$other_settings_data['meta_value'] = maybe_serialize( $other_settings_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $other_settings_data );
								break;

							case 'amazons3_settings':
								$amazons3_settings_data_array                       = array();
								$amazons3_settings_data_array['backup_to_amazons3'] = 'disable';
								$amazons3_settings_data_array['access_key_id']      = '';
								$amazons3_settings_data_array['secret_key']         = '';
								$amazons3_settings_data_array['bucket_name']        = '';

								$amazons3_settings_data               = array();
								$amazons3_settings_data['meta_id']    = $row->id;
								$amazons3_settings_data['meta_key']   = 'amazons3_settings';// WPCS: Slow query.
								$amazons3_settings_data['meta_value'] = maybe_serialize( $amazons3_settings_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $amazons3_settings_data );
								break;

							case 'dropbox_settings':
								$dropbox_settings_data_array                      = array();
								$dropbox_settings_data_array['backup_to_dropbox'] = 'disable';
								$dropbox_settings_data_array['api_key']           = '';
								$dropbox_settings_data_array['secret_key']        = '';

								$dropbox_settings_data               = array();
								$dropbox_settings_data['meta_id']    = $row->id;
								$dropbox_settings_data['meta_key']   = 'dropbox_settings';// WPCS: Slow query.
								$dropbox_settings_data['meta_value'] = maybe_serialize( $dropbox_settings_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $dropbox_settings_data );
								break;

							case 'email_settings':
								$email_settings_data_array                    = array();
								$email_settings_data_array['backup_to_email'] = 'disable';
								$email_settings_data_array['email_address']   = $admin_email;
								$email_settings_data_array['cc_email']        = '';
								$email_settings_data_array['bcc_email']       = '';
								$email_settings_data_array['email_subject']   = '[backup_type] Successfully Generated - Backup Bank';
								$email_settings_data_array['email_message']   = '<p>Hi Admin,</p><p>Kindly find attached Compressed Backup for <strong>[backup_type]</strong> in <strong>[compression_type]</strong> Format with Detailed Log executed by <strong>[username]</strong> on <strong>[start_time]</strong> for your website <strong>[site_url]</strong>.</p><p><u>Here are the details for the Backup :-</u></p><p><strong>Archive Name: </strong>[archive_name]</p><p><strong>Backup Name</strong>: [backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Exclude List</strong>: [exclude_list]</p><p><strong>Compression Type</strong>: [compression_type]</p><p><strong>Backup Tables</strong>: [backup_tables]</p><p>Thank you for using <strong>Backup Bank</strong> Plugin.</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>';

								$email_settings_data               = array();
								$email_settings_data['meta_id']    = $row->id;
								$email_settings_data['meta_key']   = 'email_settings';// WPCS: Slow query.
								$email_settings_data['meta_value'] = maybe_serialize( $email_settings_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $email_settings_data );
								break;

							case 'ftp_settings':
								$ftp_settings_data_array                  = array();
								$ftp_settings_data_array['backup_to_ftp'] = 'disable';
								$ftp_settings_data_array['protocol']      = 'ftp';
								$ftp_settings_data_array['host']          = '';
								$ftp_settings_data_array['login_type']    = 'username_password';
								$ftp_settings_data_array['ftp_username']  = '';
								$ftp_settings_data_array['ftp_password']  = '';
								$ftp_settings_data_array['port']          = '';
								$ftp_settings_data_array['remote_path']   = '';
								$ftp_settings_data_array['ftp_mode']      = 'false';

								$ftp_settings_data               = array();
								$ftp_settings_data['meta_id']    = $row->id;
								$ftp_settings_data['meta_key']   = 'ftp_settings';// WPCS: Slow query.
								$ftp_settings_data['meta_value'] = maybe_serialize( $ftp_settings_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $ftp_settings_data );
								break;

							case 'onedrive_settings':
								$onedrive_settings_data_array                       = array();
								$onedrive_settings_data_array['backup_to_onedrive'] = 'disable';
								$onedrive_settings_data_array['client_id']          = '';
								$onedrive_settings_data_array['client_secret']      = '';

								$onedrive_settings_data               = array();
								$onedrive_settings_data['meta_id']    = $row->id;
								$onedrive_settings_data['meta_key']   = 'onedrive_settings';// WPCS: Slow query.
								$onedrive_settings_data['meta_value'] = maybe_serialize( $onedrive_settings_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $onedrive_settings_data );
								break;

							case 'rackspace_settings':
								$rackspace_settings_data_array                        = array();
								$rackspace_settings_data_array['backup_to_rackspace'] = 'disable';
								$rackspace_settings_data_array['username']            = '';
								$rackspace_settings_data_array['api_key']             = '';
								$rackspace_settings_data_array['container']           = '';
								$rackspace_settings_data_array['region']              = 'DFW';

								$rackspace_settings_data               = array();
								$rackspace_settings_data['meta_id']    = $row->id;
								$rackspace_settings_data['meta_key']   = 'rackspace_settings';// WPCS: Slow query.
								$rackspace_settings_data['meta_value'] = maybe_serialize( $rackspace_settings_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $rackspace_settings_data );
								break;
							case 'azure_settings':
								$ms_azure_data_array                       = array();
								$ms_azure_data_array['backup_to_ms_azure'] = 'disable';
								$ms_azure_data_array['account_name']       = '';
								$ms_azure_data_array['access_key']         = '';
								$ms_azure_data_array['container']          = '';

								$ms_azure_data               = array();
								$ms_azure_data['meta_id']    = $row->id;
								$ms_azure_data['meta_key']   = 'azure_settings';// WPCS: Slow query.
								$ms_azure_data['meta_value'] = maybe_serialize( $ms_azure_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $ms_azure_data );
								break;

							case 'google_drive':
								$google_drive_data_array                           = array();
								$google_drive_data_array['backup_to_google_drive'] = 'disable';
								$google_drive_data_array['client_id']              = '';
								$google_drive_data_array['secret_key']             = '';
								$google_drive_data_array['redirect_uri']           = admin_url() . 'admin.php?page=bb_google_drive';

								$google_drive_data               = array();
								$google_drive_data['meta_id']    = $row->id;
								$google_drive_data['meta_key']   = 'google_drive';// WPCS: Slow query.
								$google_drive_data['meta_value'] = maybe_serialize( $google_drive_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $google_drive_data );
								break;

							case 'email_templates':
								$email_templates = array();
								$email_templates['template_for_backup_successful_generated'] = '<p>Hi,</p><p>A Backup has been Successfully Generated for your website <strong>[site_url]</strong> by <strong>[username] </strong>at <strong>[start_time]</strong>.</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name: </strong>[backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Exclude List:</strong> [exclude_list]</p><p><strong>File Compression Type:</strong> [file_compression_type]</p><p><strong>DB Compression Type:</strong> [db_compression_type]</p><p><strong>Backup Tables:</strong> [backup_tables]</p><p><strong>Archive Name:</strong> [archive_name]</p><p><strong>Backup Destination:</strong> [backup_destination]</p><p><strong>Folder Location:</strong> [folder_location]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>';
								$email_templates['template_for_scheduled_backup']            = '<p>Hi,</p><p>A Backup has been Successfully Scheduled to run on your website <strong>[site_url]</strong> starting <strong>[start_date]</strong> at <strong>[start_time]</strong> ending <strong>[end_on]</strong> according to Time Zone <strong>[time_zone]</strong>.</p><p><u>Here is the Detailed footprint at the Request :-</u></p><p><strong>Start On:</strong> [start_date]/[start_time]</p><p><strong>Duration: </strong>[duration]</p><p><strong>End On:</strong> [end_on]</p><p><strong>Repeat Every:</strong> [repeat_every]</p><p><strong>Time Zone:</strong> [time_zone]</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name: </strong>[backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Exclude List:</strong> [exclude_list]</p><p><strong>File Compression Type:</strong> [file_compression_type]</p><p><strong>DB Compression Type:</strong> [db_compression_type]</p><p><strong>Backup Tables:</strong> [backup_tables]</p><p><strong>Archive Name:</strong> [archive_name]</p><p><strong>Backup Destination:</strong> [backup_destination]</p><p><strong>Folder Location:</strong> [folder_location]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>';
								$email_templates['template_for_restore_successfully']        = '<p>Hi,</p><p>A Backup has been Successfully Restored to your website <strong>[site_url]</strong> by <strong>[username]</strong> at <strong>[start_time]</strong>.</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name:</strong> [backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Backup Source:</strong> [backup_source]</p><p><strong>Time Taken:</strong> [time_taken]</p><p><strong>Status:</strong> [status]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>';
								$email_templates['template_for_backup_failure']              = '<p>Hi,</p><p>A Backup has been Failed to Generate to your website <strong>[site_url]</strong> by <strong>[username]</strong> at <strong>[start_time]</strong>.</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name: </strong>[backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Exclude List:</strong> [exclude_list]</p><p><strong>File Compression Type:</strong> [file_compression_type]</p><p><strong>DB Compression Type:</strong> [db_compression_type]</p><p><strong>Backup Tables:</strong> [backup_tables]</p><p><strong>Archive Name:</strong> [archive_name]</p><p><strong>Backup Destination:</strong> [backup_destination]</p><p><strong>Folder Location:</strong> [folder_location]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>';
								$email_templates['template_for_restore_failure']             = '<p>Hi,</p><p>A Backup has been Failed to Restore to your website <strong>[site_url]</strong> by <strong>[username]</strong> at <strong>[start_time]</strong>.</p><p><u>Here are the Details for the Backup :-</u></p><p><strong>Backup Name: </strong>[backup_name]</p><p><strong>Backup Type:</strong> [backup_type]</p><p><strong>Backup Source:</strong> [backup_source]</p><p><strong>Time Taken:</strong> [time_taken]</p><p><p><strong>Status:</strong> [status]</p><p><strong>Thanks & Regards</strong></p><p><strong>Support Team</strong><br /> <strong>Tech Banker<br /> </strong></p>';

								$email_templates_message = array( 'Backup Successfully Generated Notification - Backup Bank', 'Backup Successfully Scheduled Notification - Backup Bank', 'Backup Restore Success Notification - Backup Bank', 'Backup Failure Notification - Backup Bank', 'Backup Restore Failure Notification - Backup Bank' );
								$count                   = 0;
								foreach ( $email_templates as $key => $value ) {
									$email_templates_scheduled_backup_array                  = array();
									$email_templates_scheduled_backup_array['email_send_to'] = $admin_email;
									$email_templates_scheduled_backup_array['email_cc']      = '';
									$email_templates_scheduled_backup_array['email_bcc']     = '';
									$email_templates_scheduled_backup_array['email_subject'] = $email_templates_message[ $count ];
									$email_templates_scheduled_backup_array['email_message'] = $value;
									$count++;

									$email_templates_for_scheduled_backup               = array();
									$email_templates_for_scheduled_backup['meta_id']    = $row->id;
									$email_templates_for_scheduled_backup['meta_key']   = $key;// WPCS: Slow query.
									$email_templates_for_scheduled_backup['meta_value'] = maybe_serialize( $email_templates_scheduled_backup_array );// WPCS: Slow query.
									$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $email_templates_for_scheduled_backup );
								}
								break;

							case 'roles_and_capabilities':
								$roles_capabilities_data_array                                   = array();
								$roles_capabilities_data_array['roles_and_capabilities']         = '1,1,1,0,0,0';
								$roles_capabilities_data_array['show_backup_bank_top_bar_menu']  = 'enable';
								$roles_capabilities_data_array['administrator_privileges']       = '1,1,1,1,1,1,1,1,1';
								$roles_capabilities_data_array['author_privileges']              = '0,1,0,0,0,1,0,0,0';
								$roles_capabilities_data_array['editor_privileges']              = '0,1,1,0,0,1,0,1,0';
								$roles_capabilities_data_array['contributor_privileges']         = '0,0,0,0,0,1,0,0,0';
								$roles_capabilities_data_array['subscriber_privileges']          = '0,0,0,0,0,0,0,0,0';
								$roles_capabilities_data_array['others_full_control_capability'] = '0';
								$roles_capabilities_data_array['other_privileges']               = '0,0,0,0,0,0,0,0,0';

								$user_capabilities        = get_others_capabilities_backup_bank();
								$other_roles_array        = array();
								$other_roles_access_array = array(
									'manage_options',
									'edit_plugins',
									'edit_posts',
									'publish_posts',
									'publish_pages',
									'edit_pages',
									'read',
								);
								foreach ( $other_roles_access_array as $role ) {
									if ( in_array( $role, $user_capabilities, true ) ) {
										array_push( $other_roles_array, $role );
									}
								}
								$roles_capabilities_data_array['capabilities'] = $other_roles_array;

								$roles_data_array               = array();
								$roles_data_array['meta_id']    = $row->id;
								$roles_data_array['meta_key']   = 'roles_and_capabilities';// WPCS: Slow query.
								$roles_data_array['meta_value'] = maybe_serialize( $roles_capabilities_data_array );// WPCS: Slow query.
								$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_meta(), $roles_data_array );
								break;
						}
					}
				}
			}
		}


		if ( ! function_exists( 'backup_bank_table_restore' ) ) {
			/**
			 * This function is used to create restore table.
			 */
			function backup_bank_table_restore() {
				global $wpdb;
				$obj_dbhelper_install_script_backup_bank = new DbHelper_Install_Script_Backup_Bank();
				$sql                                     = 'CREATE TABLE IF NOT EXISTS ' . backup_bank_restore() . '
				(
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`meta_key` varchar(100) NOT NULL,
					`meta_value` longtext,
					PRIMARY KEY (`id`)
				)
				ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
				dbDelta( $sql );

				$maintenance_mode_settings                         = array();
				$maintenance_mode_settings['message_when_restore'] = 'Site in Maintenance Mode';
				$maintenance_mode_settings['restoring']            = 'disable';

				$maintenance_mode_settings_data               = array();
				$maintenance_mode_settings_data['meta_key']   = 'maintenance_mode_settings';// WPCS: Slow query.
				$maintenance_mode_settings_data['meta_value'] = maybe_serialize( $maintenance_mode_settings );// WPCS: Slow query.
				$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank_restore(), $maintenance_mode_settings_data );
			}
		}

		$obj_dbhelper_install_script_backup_bank = new DbHelper_Install_Script_Backup_Bank();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$backup_bank_version_number = get_option( 'backup-bank-version-number' );

		switch ( $backup_bank_version_number ) {
			case '':
				backup_bank_table();
				backup_bank_meta_table();
				backup_bank_table_restore();
				$backup_bank_admin_notices_array                    = array();
				$bb_start_date                                      = date( 'm/d/Y' );
				$bb_start_date                                      = strtotime( $bb_start_date );
				$bb_start_date                                      = strtotime( '+7 day', $bb_start_date );
				$bb_start_date                                      = date( 'm/d/Y', $bb_start_date );
				$backup_bank_admin_notices_array['two_week_review'] = array( 'start' => $bb_start_date, 'int' => 7, 'dismissed' => 0 ); // @codingStandardsIgnoreLine.
				update_option( 'bb_admin_notice', $backup_bank_admin_notices_array );
				break;

			default:
				if ( $wpdb->query( "SHOW TABLES LIKE '" . $wpdb->prefix . 'backup_details' . "'" ) !== 0 && $wpdb->query( "SHOW TABLES LIKE '" . $wpdb->prefix . 'backup_meta' . "'" ) !== 0 ) {
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'backup_details' );// @codingStandardsIgnoreLine.// WPCS: db call ok, no-cache ok.
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'backup_meta' );// @codingStandardsIgnoreLine.
					backup_bank_table();
					backup_bank_meta_table();
					backup_bank_table_restore();
				}
				if ( $wpdb->query( "SHOW TABLES LIKE '" . $wpdb->prefix . 'backup_bank' . "'" ) !== 0 && $wpdb->query( "SHOW TABLES LIKE '" . $wpdb->prefix . 'backup_bank_meta' . "'" ) !== 0 ) {
					$bb_other_settings_updated_data = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'other_settings'
						)
					);// WPCS: db call ok, no-cache ok.
					$bb_other_settings_array        = maybe_unserialize( $bb_other_settings_updated_data );
					if ( ! array_key_exists( 'automatic_plugin_updates', $bb_other_settings_array ) ) {
						$bb_other_settings_array['automatic_plugin_updates'] = 'disable';
					}
					$where             = array();
					$where['meta_key'] = 'other_settings';// WPCS: Slow query.
					$bb_other_settings_serialized_data['meta_value'] = maybe_serialize( $bb_other_settings_array );// WPCS: Slow query.
					$obj_dbhelper_install_script_backup_bank->update_command( backup_bank_meta(), $bb_other_settings_serialized_data, $where );
				}
				$get_collate_status_data = $wpdb->query(
					$wpdb->prepare(
						'SELECT type FROM ' . $wpdb->prefix . 'backup_bank WHERE type=%s', 'collation_type'
					)
				);// db call ok; no-cache ok.
				$charset_collate         = '';
				if ( ! empty( $wpdb->charset ) ) {
					$charset_collate .= 'CONVERT TO CHARACTER SET ' . $wpdb->charset;
				}
				if ( ! empty( $wpdb->collate ) ) {
					$charset_collate .= ' COLLATE ' . $wpdb->collate;
				}
				if ( 0 === $get_collate_status_data ) {
					if ( ! empty( $charset_collate ) ) {
						$change_collate_main_table         = $wpdb->query(
							'ALTER TABLE ' . $wpdb->prefix . 'backup_bank ' . $charset_collate // @codingStandardsIgnoreLine.
						);// WPCS: db call ok, no-cache ok.
						$change_collate_meta_table         = $wpdb->query(
							'ALTER TABLE ' . $wpdb->prefix . 'backup_bank_meta ' . $charset_collate // @codingStandardsIgnoreLine.
						);// WPCS: db call ok, no-cache ok.
						$change_collate_restore_table      = $wpdb->query(
							'ALTER TABLE ' . $wpdb->prefix . 'backup_bank_restore ' . $charset_collate // @codingStandardsIgnoreLine.
						);// WPCS: db call ok, no-cache ok.
						$collation_data_array              = array();
						$collation_data_array['type']      = 'collation_type';
						$collation_data_array['parent_id'] = '0';
						$obj_dbhelper_install_script_backup_bank->insert_command( backup_bank(), $collation_data_array );
					}
				}
				break;
		}
		update_option( 'backup-bank-version-number', '4.0.1' );
	}
}
