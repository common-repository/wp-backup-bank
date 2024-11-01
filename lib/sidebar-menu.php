<?php
/**
 * This file is used for creating sidebar menu.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.
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
		$flag = 0;

		$role_capabilities = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value from ' . $wpdb->prefix . 'backup_bank_meta WHERE ' . $wpdb->prefix . 'backup_bank_meta.meta_key = %s', 'roles_and_capabilities'
			)
		);// WPCS: db call ok, no-cache ok.

		$roles_and_capabilities_unserialized_data = maybe_unserialize( $role_capabilities );
		$capabilities                             = explode( ',', $roles_and_capabilities_unserialized_data['roles_and_capabilities'] );

		if ( is_super_admin() ) {
			$bb_role = 'administrator';
		} else {
			$bb_role = check_user_roles_backup_bank();
		}
		switch ( $bb_role ) {
			case 'administrator':
				$privileges = 'administrator_privileges';
				$flag       = $capabilities[0];
				break;

			case 'author':
				$privileges = 'author_privileges';
				$flag       = $capabilities[1];
				break;

			case 'editor':
				$privileges = 'editor_privileges';
				$flag       = $capabilities[2];
				break;

			case 'contributor':
				$privileges = 'contributor_privileges';
				$flag       = $capabilities[3];
				break;

			case 'subscriber':
				$privileges = 'subscriber_privileges';
				$flag       = $capabilities[4];
				break;

			default:
				$privileges = 'other_privileges';
				$flag       = $capabilities[5];
				break;
		}

		if ( isset( $roles_and_capabilities_unserialized_data ) && count( $roles_and_capabilities_unserialized_data ) > 0 ) {
			foreach ( $roles_and_capabilities_unserialized_data as $key => $value ) {
				if ( $key === $privileges ) {
					$privileges_value = $value;
					break;
				}
			}
		}

		$full_control = explode( ',', $privileges_value );
		if ( ! defined( 'FULL_CONTROL' ) ) {
			define( 'FULL_CONTROL', "$full_control[0]" );
		}
		if ( ! defined( 'MANAGE_BACKUPS_BACKUP_BANK' ) ) {
			define( 'MANAGE_BACKUPS_BACKUP_BANK', "$full_control[1]" );
		}
		if ( ! defined( 'MANUAL_BACKUP_BANK' ) ) {
			define( 'MANUAL_BACKUP_BANK', "$full_control[2]" );
		}
		if ( ! defined( 'SCHEDULE_BACKUP_BANK' ) ) {
			define( 'SCHEDULE_BACKUP_BANK', "$full_control[3]" );
		}
		if ( ! defined( 'GENERAL_SETTINGS_BACKUP_BANK' ) ) {
			define( 'GENERAL_SETTINGS_BACKUP_BANK', "$full_control[4]" );
		}
		if ( ! defined( 'EMAIL_TEMPLATES_BACKUP_BANK' ) ) {
			define( 'EMAIL_TEMPLATES_BACKUP_BANK', "$full_control[5]" );
		}
		if ( ! defined( 'ROLES_AND_CAPABILITIES_BACKUP_BANK' ) ) {
			define( 'ROLES_AND_CAPABILITIES_BACKUP_BANK', "$full_control[6]" );
		}
		if ( ! defined( 'SYSTEM_INFORMATION_BACKUP_BANK' ) ) {
			define( 'SYSTEM_INFORMATION_BACKUP_BANK', "$full_control[7]" );
		}

		$backup_bank_wizard_url = get_option( 'backup-bank-wizard' );
		if ( '1' === $flag ) {
			global $wp_version;
			if ( get_option( 'backup-bank-wizard' ) ) {
				add_menu_page( $wp_backup_bank, $wp_backup_bank, 'read', 'bb_start_backup', '', plugins_url( 'assets/global/img/icon.png', dirname( __FILE__ ) ) );
			} else {
				add_menu_page( $wp_backup_bank, $wp_backup_bank, 'read', 'bb_wizard_backup', '', plugins_url( 'assets/global/img/icon.png', dirname( __FILE__ ) ) );
				add_submenu_page( $wp_backup_bank, $wp_backup_bank, '', 'read', 'bb_wizard_backup', 'bb_wizard_backup' );
			}

			add_submenu_page( $bb_manage_backups, $bb_manage_backups, '', 'read', 'bb_manage_backups', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_manage_backups' );
			add_submenu_page( 'bb_start_backup', $bb_start_backup, $bb_backups, 'read', 'bb_start_backup', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_start_backup' );
			add_submenu_page( $bb_schedule_backup, $bb_schedule_backup, '', 'read', 'bb_schedule_backup', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_schedule_backup' );
			add_submenu_page( 'bb_start_backup', $bb_alert_setup, $bb_general_settings, 'read', 'bb_alert_setup', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_alert_setup' );
			add_submenu_page( $bb_other_settings, $bb_other_settings, '', 'read', 'bb_other_settings', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_other_settings' );
			add_submenu_page( $bb_dropbox_settings, $bb_dropbox_settings, '', 'read', 'bb_dropbox_settings', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_dropbox_settings' );
			add_submenu_page( $bb_email_settings, $bb_email_settings, '', 'read', 'bb_email_settings', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_email_settings' );
			add_submenu_page( $bb_ftp_settings, $bb_ftp_settings, '', 'read', 'bb_ftp_settings', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_ftp_settings' );
			add_submenu_page( $bb_amazons3_settings, $bb_amazons3_settings, '', 'read', 'bb_amazons3_settings', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_amazons3_settings' );
			add_submenu_page( $bb_onedrive_settings, $bb_onedrive_settings, '', 'read', 'bb_onedrive_settings', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_onedrive_settings' );
			add_submenu_page( $bb_rackspace_settings, $bb_rackspace_settings, '', 'read', 'bb_rackspace_settings', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_rackspace_settings' );
			add_submenu_page( $bb_ms_azure_settings, $bb_ms_azure_settings, '', 'read', 'bb_ms_azure_settings', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_ms_azure_settings' );
			add_submenu_page( $bb_google_drive, $bb_google_drive, '', 'read', 'bb_google_drive', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_google_drive' );
			add_submenu_page( 'bb_start_backup', $bb_email_templates, $bb_email_templates, 'read', 'bb_email_templates', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_email_templates' );
			add_submenu_page( 'bb_start_backup', $bb_roles_and_capabilities, $bb_roles_and_capabilities, 'read', 'bb_roles_and_capabilities', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_roles_and_capabilities' );
			add_submenu_page( 'bb_start_backup', $bb_support_forum, $bb_support_forum, 'read', 'https://wordpress.org/support/plugin/wp-backup-bank', '' );
			add_submenu_page( 'bb_start_backup', $bb_system_information, $bb_system_information, 'read', 'bb_system_information', false === $backup_bank_wizard_url ? 'bb_wizard_backup' : 'bb_system_information' );
			add_submenu_page( 'bb_start_backup', $bb_premium_editions, $bb_premium_editions, 'read', 'https://tech-banker.com/backup-bank/pricing/', '' );
		}

		if ( ! function_exists( 'bb_wizard_backup' ) ) {
			/**
			 * This function is used to create bb_wizard_backup menu.
			 */
			function bb_wizard_backup() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/wizard/wizard.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/wizard/wizard.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_start_backup' ) ) {
			/**
			 * This function is used to create bb_start_backup menu.
			 */
			function bb_start_backup() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/backups/start-backup.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/backups/start-backup.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_schedule_backup' ) ) {
			/**
			 * This function is used to create bb_schedule_backup menu.
			 */
			function bb_schedule_backup() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/backups/schedule-backup.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/backups/schedule-backup.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_manage_backups' ) ) {
			/**
			 * This function is used to create bb_manage_backups menu.
			 */
			function bb_manage_backups() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/backups/manage-backups.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/backups/manage-backups.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_alert_setup' ) ) {
			/**
			 * This function is used to create bb_alert_setup menu.
			 */
			function bb_alert_setup() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/alert-setup.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/alert-setup.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_other_settings' ) ) {
			/**
			 * This function is used to create bb_other_settings menu.
			 */
			function bb_other_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/other-settings.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/other-settings.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_dropbox_settings' ) ) {
			/**
			 * This function is used to create bb_dropbox_settings menu.
			 */
			function bb_dropbox_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/dropbox-settings.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/dropbox-settings.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_email_settings' ) ) {
			/**
			 * This function is used to create bb_email_settings menu.
			 */
			function bb_email_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/email-settings.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/email-settings.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_ftp_settings' ) ) {
			/**
			 * This function is used to create bb_ftp_settings menu.
			 */
			function bb_ftp_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/ftp-settings.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/ftp-settings.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_amazons3_settings' ) ) {
			/**
			 * This function is used to create bb_amazons3_settings menu.
			 */
			function bb_amazons3_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/amazons3-settings.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/amazons3-settings.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_rackspace_settings' ) ) {
			/**
			 * This function is used to create bb_rackspace_settings menu.
			 */
			function bb_rackspace_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/rackspace-settings.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/rackspace-settings.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_ms_azure_settings' ) ) {
			/**
			 * This function is used to create bb_ms_azure_settings menu.
			 */
			function bb_ms_azure_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/ms-azure-settings.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/ms-azure-settings.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_email_templates' ) ) {
			/**
			 * This function is used to create bb_email_templates menu.
			 */
			function bb_email_templates() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/email-templates/email-templates.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/email-templates/email-templates.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_roles_and_capabilities' ) ) {
			/**
			 * This function is used to create bb_roles_and_capabilities menu.
			 */
			function bb_roles_and_capabilities() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/roles-and-capabilities/roles-and-capabilities.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/roles-and-capabilities/roles-and-capabilities.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_system_information' ) ) {
			/**
			 * This function is used to create bb_system_information menu.
			 */
			function bb_system_information() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/system-information/system-information.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/system-information/system-information.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_onedrive_settings' ) ) {
			/**
			 * This function is used to create bb_onedrive_settings menu.
			 */
			function bb_onedrive_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/onedrive-settings.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/onedrive-settings.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'bb_google_drive' ) ) {
			/**
			 * This function is used to create bb_google_drive menu.
			 */
			function bb_google_drive() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'views/general-settings/google-drive.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'views/general-settings/google-drive.php';
				}
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
	}
}
