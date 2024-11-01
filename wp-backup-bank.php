<?php // @codingStandardsIgnoreLine
/**
 * Plugin Name: WordPress Backup Plugin - Backup Bank
 * Plugin URI: https://tech-banker.com/backup-bank
 * Description: Backup and Restore Plugin made super easy and real quick. Backup your files, folders and send your backups to Local, Email, FTP, DropBox or Google Drive.
 * Author: Tech Banker
 * Author URI: https://tech-banker.com/backup-bank
 * Version: 4.0.28
 * License: GPLv3
 * Text Domain: wp-backup-bank
 * Domain Path: /languages
 *
 * @package wp-backup-bank
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
$current_year  = date( 'Y' );
$current_month = date( 'm' );
$current_date  = date( 'd' );

/* Constant Declaration */
if ( ! defined( 'BACKUP_BANK_DIR_PATH' ) ) {
	define( 'BACKUP_BANK_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'BACKUP_BANK_CONTENT_DIR' ) ) {
	define( 'BACKUP_BANK_CONTENT_DIR', dirname( dirname( BACKUP_BANK_DIR_PATH ) ) );
}
if ( ! defined( 'BACKUP_BANK_BACKUPS_DIR' ) ) {
	define( 'BACKUP_BANK_BACKUPS_DIR', BACKUP_BANK_CONTENT_DIR . '/wp-backup-bank' );
}
if ( ! defined( 'BACKUP_BANK_BACKUPS_YEAR_DIR' ) ) {
	define( 'BACKUP_BANK_BACKUPS_YEAR_DIR', BACKUP_BANK_BACKUPS_DIR . '/' . $current_year );
}
if ( ! defined( 'BACKUP_BANK_BACKUPS_MONTH_DIR' ) ) {
	define( 'BACKUP_BANK_BACKUPS_MONTH_DIR', BACKUP_BANK_BACKUPS_YEAR_DIR . '/' . $current_month );
}
if ( ! defined( 'BACKUP_BANK_BACKUPS_DATE_DIR' ) ) {
	define( 'BACKUP_BANK_BACKUPS_DATE_DIR', BACKUP_BANK_BACKUPS_MONTH_DIR . '/' . $current_date );
}
if ( ! defined( 'BACKUP_BANK_URL_PATH' ) ) {
	define( 'BACKUP_BANK_URL_PATH', plugins_url( __FILE__ ) );
}
if ( ! defined( 'BACKUP_BANK_PLUGIN_DIRNAME' ) ) {
	define( 'BACKUP_BANK_PLUGIN_DIRNAME', plugin_basename( dirname( __FILE__ ) ) );
}
if ( ! defined( 'WP_BACKUP_BANK' ) ) {
	define( 'WP_BACKUP_BANK', 'wp-backup-bank' );
}
if ( ! defined( 'BACKUP_BANK_FOLDER_DROPBOX' ) ) {
	define( 'BACKUP_BANK_FOLDER_DROPBOX', 'wp-backup-bank/' . $current_year . '/' . $current_month . '/' . $current_date . '/' );
}
if ( ! defined( 'BACKUP_BANK_SET_TIME_LIMIT' ) ) {
	define( 'BACKUP_BANK_SET_TIME_LIMIT', 0 );
}
if ( ! defined( 'BACKUP_BANK_WARN_DB_ROWS' ) ) {
	define( 'BACKUP_BANK_WARN_DB_ROWS', 150000 );
}
if ( ! defined( 'BACKUP_BANK_WARN_FILE_SIZE' ) ) {
	define( 'BACKUP_BANK_WARN_FILE_SIZE', 1024 * 1024 * 250 );
}
if ( ! defined( 'BACKUP_BANK_FILE' ) ) {
	define( 'BACKUP_BANK_FILE', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'TECH_BANKER_URL' ) ) {
	define( 'TECH_BANKER_URL', 'https://tech-banker.com' );
}
if ( ! defined( 'TECH_BANKER_SITE_URL' ) ) {
	define( 'TECH_BANKER_SITE_URL', 'https://tech-banker.com/backup-bank' );
}
if ( ! defined( 'TECH_BANKER_STATS_URL' ) ) {
	define( 'TECH_BANKER_STATS_URL', 'https://stats.tech-banker-services.org' );
}
if ( ! defined( 'LOCAL_TIME_BACKUP_BANK' ) ) {
	define( 'LOCAL_TIME_BACKUP_BANK', strtotime( date_i18n( 'Y-m-d H:i:s' ) ) );
}
if ( ! defined( 'BACKUP_BANK_VERSION_NUMBER' ) ) {
	define( 'BACKUP_BANK_VERSION_NUMBER', '4.0.28' );
}

if ( ! function_exists( 'backup_folders_for_backup_bank' ) ) {
	/**
	 * This Function is used to make a folder.
	 */
	function backup_folders_for_backup_bank() {
		if ( ! is_dir( BACKUP_BANK_BACKUPS_DIR ) ) {
			wp_mkdir_p( BACKUP_BANK_BACKUPS_DIR );
		}
	}
}

$memory_limit_backup_bank = intval( ini_get( 'memory_limit' ) );
if ( ! extension_loaded( 'suhosin' ) && $memory_limit_backup_bank < 512 ) {
	@ini_set( 'memory_limit', '512M' );// @codingStandardsIgnoreLine
}

@ini_set( 'max_execution_time', 6000 );// @codingStandardsIgnoreLine
@ini_set( 'max_input_vars', 10000 );// @codingStandardsIgnoreLine

if ( ! function_exists( 'install_script_for_backup_bank' ) ) {
	/**
	 * This function is used to create tables in database.
	 */
	function install_script_for_backup_bank() {
		global $wpdb;
		if ( is_multisite() ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );// WPCS: db call ok, no-cache ok.
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );// @codingStandardsIgnoreLine
				$version = get_option( 'backup-bank-version-number' );
				if ( $version < '4.0.1' ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/class-dbhelper-install-script-backup-bank.php' ) ) {
						include BACKUP_BANK_DIR_PATH . 'lib/class-dbhelper-install-script-backup-bank.php';
					}
				}
				restore_current_blog();
			}
		} else {
			$version = get_option( 'backup-bank-version-number' );
			if ( $version < '4.0.1' ) {
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/class-dbhelper-install-script-backup-bank.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'lib/class-dbhelper-install-script-backup-bank.php';
				}
			}
		}
	}
}

if ( ! function_exists( 'backup_bank' ) ) {
	/**
	 * This function is used for creating parent table.
	 */
	function backup_bank() {
		global $wpdb;
		return $wpdb->prefix . 'backup_bank';
	}
}

if ( ! function_exists( 'backup_bank_restore' ) ) {
	/**
	 * This function is used for creating backup_bank_restore table.
	 */
	function backup_bank_restore() {
		global $wpdb;
		return $wpdb->prefix . 'backup_bank_restore';
	}
}

if ( ! function_exists( 'backup_bank_meta' ) ) {
	/**
	 * This function is used for creating meta table.
	 */
	function backup_bank_meta() {
		global $wpdb;
		return $wpdb->prefix . 'backup_bank_meta';
	}
}

/**
 * This function is used for checking roles of different users.
 */

if ( ! function_exists( 'check_user_roles_backup_bank' ) ) {
	/**
	 * This function is used for checking roles of different users.
	 *
	 * @param string $user passes parameter as user.
	 */
	function check_user_roles_backup_bank( $user = null ) {
		$user = $user ? new WP_User( $user ) : wp_get_current_user();
		return $user->roles ? $user->roles[0] : false;
	}
}

/**
 * This function is used to create link for Pro Editions.
 *
 * @param string $plugin_link .
 */
function backup_bank_action_links( $plugin_link ) {
	$plugin_link[] = '<a href="https://tech-banker.com/backup-bank/pricing/" style="color: red; font-weight: bold;" target="_blank">Go Pro!</a>';
	return $plugin_link;
}

if ( ! function_exists( 'get_others_capabilities_backup_bank' ) ) {
	/**
	 * This function is used to get all the roles available in WordPress.
	 */
	function get_others_capabilities_backup_bank() {
		$user_capabilities = array();
		if ( function_exists( 'get_editable_roles' ) ) {
			foreach ( get_editable_roles() as $role_name => $role_info ) {
				foreach ( $role_info['capabilities'] as $capability => $values ) {
					if ( ! in_array( $capability, $user_capabilities, true ) ) {
						array_push( $user_capabilities, $capability );
					}
				}
			}
		} else {
			$user_capabilities = array(
				'manage_options',
				'edit_plugins',
				'edit_posts',
				'publish_posts',
				'publish_pages',
				'edit_pages',
				'read',
			);
		}
		return $user_capabilities;
	}
}

$backup_bank_version_number = get_option( 'backup-bank-version-number' );
if ( '4.0.1' === $backup_bank_version_number ) {

	if ( is_admin() ) {
		if ( ! function_exists( 'backend_js_css_for_backup_bank' ) ) {
			/**
			 * This function is used for including backend js and css.
			 */
			function backend_js_css_for_backup_bank() {
				$pages_backup_bank = array(
					'bb_wizard_backup',
					'bb_manage_backups',
					'bb_start_backup',
					'bb_schedule_backup',
					'bb_alert_setup',
					'bb_other_settings',
					'bb_dropbox_settings',
					'bb_email_settings',
					'bb_ftp_settings',
					'bb_amazons3_settings',
					'bb_onedrive_settings',
					'bb_rackspace_settings',
					'bb_ms_azure_settings',
					'bb_google_drive',
					'bb_email_templates',
					'bb_roles_and_capabilities',
					'bb_system_information',
				);
				if ( in_array( isset( $_REQUEST['page'] ) ? esc_attr( wp_unslash( $_REQUEST['page'] ) ) : '', $pages_backup_bank, true ) ) {// WPCS: input var ok, CSRF ok, sanitization ok.
					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'jquery-ui-datepicker' );
					wp_enqueue_script( 'bootstrap.js', plugins_url( 'assets/global/plugins/custom/js/custom.js', __FILE__ ) );
					wp_enqueue_script( 'bootstrap-tabdrop.js', plugins_url( 'assets/global/plugins/tabdrop/js/tabdrop.js', __FILE__ ) );
					wp_enqueue_script( 'jquery.validate.js', plugins_url( 'assets/global/plugins/validation/jquery.validate.js', __FILE__ ) );
					wp_enqueue_script( 'jquery.datatables.js', plugins_url( 'assets/global/plugins/datatables/media/js/jquery.datatables.js', __FILE__ ) );
					wp_enqueue_script( 'jquery.fngetfilterednodes.js', plugins_url( 'assets/global/plugins/datatables/media/js/fngetfilterednodes.js', __FILE__ ) );
					wp_enqueue_script( 'toastr.js', plugins_url( 'assets/global/plugins/toastr/toastr.js', __FILE__ ) );
					wp_enqueue_style( 'simple-line-icons.css', plugins_url( 'assets/global/plugins/icons/icons.css', __FILE__ ) );
					wp_enqueue_style( 'components.css', plugins_url( 'assets/global/css/components.css', __FILE__ ) );
					wp_enqueue_style( 'wp-backup-bank-custom.css', plugins_url( 'assets/admin/layout/css/wp-backup-bank-custom.css', __FILE__ ) );
					if ( is_rtl() ) {
						wp_enqueue_style( 'backup-bank-bootstrap.css', plugins_url( 'assets/global/plugins/custom/css/custom-rtl.css', __FILE__ ) );
						wp_enqueue_style( 'backup-bank-layout.css', plugins_url( 'assets/admin/layout/css/layout-rtl.css', __FILE__ ) );
						wp_enqueue_style( 'wp-backup-bank-tech-banker-custom.css', plugins_url( 'assets/admin/layout/css/tech-banker-custom-rtl.css', __FILE__ ) );
					} else {
						wp_enqueue_style( 'backup-bank-bootstrap.css', plugins_url( 'assets/global/plugins/custom/css/custom.css', __FILE__ ) );
						wp_enqueue_style( 'backup-bank-layout.css', plugins_url( 'assets/admin/layout/css/layout.css', __FILE__ ) );
						wp_enqueue_style( 'wp-backup-bank-tech-banker-custom.css', plugins_url( 'assets/admin/layout/css/tech-banker-custom.css', __FILE__ ) );
					}
					wp_enqueue_style( 'backup-bank-plugins.css', plugins_url( 'assets/global/css/plugins.css', __FILE__ ) );
					wp_enqueue_style( 'backup-bank-default.css', plugins_url( 'assets/admin/layout/css/themes/default.css', __FILE__ ) );
					wp_enqueue_style( 'backup-bank-toastr.min.css', plugins_url( 'assets/global/plugins/toastr/toastr.css', __FILE__ ) );
					wp_enqueue_style( 'backup-bank-jquery-ui.css', plugins_url( 'assets/global/plugins/datepicker/jquery-ui.css', __FILE__ ), false, '2.0', false );
					wp_enqueue_style( 'backup-bank-datatables.foundation.css', plugins_url( 'assets/global/plugins/datatables/media/css/datatables.foundation.css', __FILE__ ) );
				}
			}
		}
		add_action( 'admin_enqueue_scripts', 'backend_js_css_for_backup_bank' );
	}

	if ( ! function_exists( 'get_users_capabilities_backup_bank' ) ) {
		/**
		 * This function is used to get users capabilities.
		 */
		function get_users_capabilities_backup_bank() {
			global $wpdb;
			$capabilities              = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'roles_and_capabilities'
				)
			);// WPCS: db call ok, no-cache ok.
			$core_roles                = array(
				'manage_options',
				'edit_plugins',
				'edit_posts',
				'publish_posts',
				'publish_pages',
				'edit_pages',
				'read',
			);
			$unserialized_capabilities = maybe_unserialize( $capabilities );
			return isset( $unserialized_capabilities['capabilities'] ) ? $unserialized_capabilities['capabilities'] : $core_roles;
		}
	}

	if ( ! function_exists( 'helper_file_for_backup_bank' ) ) {
		/**
		 * This function is used for helper file.
		 */
		function helper_file_for_backup_bank() {
			global $wpdb;
			$user_role_permission = get_users_capabilities_backup_bank();
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/class-dbhelper-backup-bank.php' ) ) {
				include_once BACKUP_BANK_DIR_PATH . 'lib/class-dbhelper-backup-bank.php';
			}
		}
	}

	if ( ! function_exists( 'sidebar_menu_for_backup_bank' ) ) {
		/**
		 * This function is used for sidebar menu.
		 */
		function sidebar_menu_for_backup_bank() {
			global $wpdb, $current_user;
			$user_role_permission = get_users_capabilities_backup_bank();
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
				include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
			}
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/sidebar-menu.php' ) ) {
				include_once BACKUP_BANK_DIR_PATH . 'lib/sidebar-menu.php';
			}
		}
	}

	if ( ! function_exists( 'topbar_menu_for_backup_bank' ) ) {
		/**
		 * This function is used for topbar menu.
		 */
		function topbar_menu_for_backup_bank() {
			global $wpdb, $current_user, $wp_admin_bar;
			$role_capabilities                        = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value from ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'roles_and_capabilities'
				)
			);// WPCS: db call ok, no-cache ok.
			$roles_and_capabilities_unserialized_data = maybe_unserialize( $role_capabilities );
			$top_bar_menu                             = $roles_and_capabilities_unserialized_data['show_backup_bank_top_bar_menu'];

			if ( 'enable' === $top_bar_menu ) {
				$user_role_permission = get_users_capabilities_backup_bank();
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( get_option( 'backup-bank-wizard' ) ) {
					if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/admin-bar-menu.php' ) ) {
						include_once BACKUP_BANK_DIR_PATH . 'lib/admin-bar-menu.php';
					}
				}
			}
		}
	}

	if ( ! function_exists( 'ajax_register_for_backup_bank' ) ) {
		/**
		 * This function is used for register ajax.
		 */
		function ajax_register_for_backup_bank() {
			global $wpdb;
			$user_role_permission = get_users_capabilities_backup_bank();
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/translations.php' ) ) {
				include BACKUP_BANK_DIR_PATH . 'includes/translations.php';
			}
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/action-library.php' ) ) {
				include_once BACKUP_BANK_DIR_PATH . 'lib/action-library.php';
			}
		}
	}

	if ( ! function_exists( 'plugin_load_textdomain_backup_bank' ) ) {
		/**
		 * This function is used to load languages.
		 */
		function plugin_load_textdomain_backup_bank() {
			if ( function_exists( 'load_plugin_textdomain' ) ) {
				load_plugin_textdomain( 'wp-backup-bank', false, BACKUP_BANK_PLUGIN_DIRNAME . '/languages' );
			}
		}
	}

	if ( ! function_exists( 'mailer_file_backup_bank' ) ) {
		/**
		 * This function is used for include mailer class.
		 */
		function mailer_file_backup_bank() {
			if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/class-dbmailer-backup-bank.php' ) ) {
				include_once BACKUP_BANK_DIR_PATH . 'lib/class-dbmailer-backup-bank.php';
			}
		}
	}

	if ( ! function_exists( 'scheduler_for_backup_bank' ) ) {
		/**
		 * This function is used for creating a scheduler for backup.
		 *
		 * @param string $cron_name passes parameter as cron name.
		 * @param string $time_interval passes parameter as time interval.
		 * @param string $timestamp passes parameter as time stamp.
		 */
		function scheduler_for_backup_bank( $cron_name, $time_interval, $timestamp ) {
			if ( ! wp_next_scheduled( $cron_name ) ) {
				$current_offset = get_option( 'gmt_offset' ) * 60 * 60;

				wp_schedule_event( $timestamp - $current_offset, $time_interval, $cron_name );
			}
		}
	}

	if ( ! function_exists( 'cron_scheduler_for_intervals_backup_bank' ) ) {
		/**
		 * This function is used to cron scheduler for intervals.
		 *
		 * @param string $schedules passes parameter as schedules.
		 */
		function cron_scheduler_for_intervals_backup_bank( $schedules ) {
			$schedules['1Hour']  = array(
				'interval' => 60 * 60,
				'display'  => 'Every 1 Hour',
			);
			$schedules['2Hour']  = array(
				'interval' => 60 * 60 * 2,
				'display'  => 'Every 2 Hours',
			);
			$schedules['3Hour']  = array(
				'interval' => 60 * 60 * 3,
				'display'  => 'Every 3 Hours',
			);
			$schedules['4Hour']  = array(
				'interval' => 60 * 60 * 4,
				'display'  => 'Every 4 Hours',
			);
			$schedules['5Hour']  = array(
				'interval' => 60 * 60 * 5,
				'display'  => 'Every 5 Hours',
			);
			$schedules['6Hour']  = array(
				'interval' => 60 * 60 * 6,
				'display'  => 'Every 6 Hours',
			);
			$schedules['7Hour']  = array(
				'interval' => 60 * 60 * 7,
				'display'  => 'Every 7 Hours',
			);
			$schedules['8Hour']  = array(
				'interval' => 60 * 60 * 8,
				'display'  => 'Every 8 Hours',
			);
			$schedules['9Hour']  = array(
				'interval' => 60 * 60 * 9,
				'display'  => 'Every 9 Hours',
			);
			$schedules['10Hour'] = array(
				'interval' => 60 * 60 * 10,
				'display'  => 'Every 10 Hours',
			);
			$schedules['11Hour'] = array(
				'interval' => 60 * 60 * 11,
				'display'  => 'Every 11 Hours',
			);
			$schedules['12Hour'] = array(
				'interval' => 60 * 60 * 12,
				'display'  => 'Every 12 Hours',
			);
			$schedules['13Hour'] = array(
				'interval' => 60 * 60 * 13,
				'display'  => 'Every 13 Hours',
			);
			$schedules['14Hour'] = array(
				'interval' => 60 * 60 * 14,
				'display'  => 'Every 14 Hours',
			);
			$schedules['15Hour'] = array(
				'interval' => 60 * 60 * 15,
				'display'  => 'Every 15 Hours',
			);
			$schedules['16Hour'] = array(
				'interval' => 60 * 60 * 16,
				'display'  => 'Every 16 Hours',
			);
			$schedules['17Hour'] = array(
				'interval' => 60 * 60 * 17,
				'display'  => 'Every 17 Hours',
			);
			$schedules['18Hour'] = array(
				'interval' => 60 * 60 * 18,
				'display'  => 'Every 18 Hours',
			);
			$schedules['19Hour'] = array(
				'interval' => 60 * 60 * 19,
				'display'  => 'Every 19 Hours',
			);
			$schedules['20Hour'] = array(
				'interval' => 60 * 60 * 20,
				'display'  => 'Every 20 Hours',
			);
			$schedules['21Hour'] = array(
				'interval' => 60 * 60 * 21,
				'display'  => 'Every 21 Hours',
			);
			$schedules['22Hour'] = array(
				'interval' => 60 * 60 * 22,
				'display'  => 'Every 22 Hours',
			);
			$schedules['23Hour'] = array(
				'interval' => 60 * 60 * 23,
				'display'  => 'Every 23 Hours',
			);
			$schedules['Daily']  = array(
				'interval' => 60 * 60 * 24,
				'display'  => 'Daily',
			);
			return $schedules;
		}
	}

	if ( ! function_exists( 'unschedule_events_backup_bank' ) ) {
		/**
		 * This function is used to unscheduling the events.
		 *
		 * @param string $cron_name passes parameter as cron name.
		 */
		function unschedule_events_backup_bank( $cron_name ) {
			if ( wp_next_scheduled( $cron_name ) ) {
				$db_cron = wp_next_scheduled( $cron_name );
				wp_unschedule_event( $db_cron, $cron_name );
			}
		}
	}

	if ( ! function_exists( 'admin_functions_for_backup_bank' ) ) {
		/**
		 * This function is used for admin functions.
		 */
		function admin_functions_for_backup_bank() {
			install_script_for_backup_bank();
			helper_file_for_backup_bank();
			backup_folders_for_backup_bank();
		}
	}

	if ( ! function_exists( 'user_function_for_backup_bank' ) ) {
		/**
		 * This function is used for user functions.
		 */
		function user_function_for_backup_bank() {
			plugin_load_textdomain_backup_bank();
			backup_folders_for_backup_bank();
			global $wpdb;
			$meta_values      = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'other_settings'
				)
			);// WPCS: db call ok, no-cache ok.
			$meta_data_array  = array();
			$unserialize_data = maybe_unserialize( $meta_values );
			if ( 'enable' === $unserialize_data['automatic_plugin_updates'] ) {
				plugin_auto_update_backup_bank();
			} else {
				wp_clear_scheduled_hook( 'automatic_updates_backup_bank' );
			}
			mailer_file_backup_bank();
		}
	}

	if ( ! function_exists( 'maintenance_mode_backup_bank' ) ) {
		/**
		 * This function is used to including backup file on maintenance mode.
		 */
		function maintenance_mode_backup_bank() {
			global $wpdb;
			$enable_maintenance_mode      = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_restore WHERE meta_key = %s', 'maintenance_mode_settings'
				)
			);// WPCS: db call ok, cache ok.
			$enable_maintenance_mode_data = maybe_unserialize( $enable_maintenance_mode );

			if ( 'enable' === $enable_maintenance_mode_data['restoring'] ) {
				if ( file_exists( BACKUP_BANK_DIR_PATH . 'includes/maintenance.php' ) ) {
					include_once BACKUP_BANK_DIR_PATH . 'includes/maintenance.php';
				}
			}
		}
	}

	if ( ! function_exists( 'plugin_auto_update_backup_bank' ) ) {
		/**
		 * This function is used to Update Plugin Edition.
		 */
		function plugin_auto_update_backup_bank() {
			if ( ! wp_next_scheduled( 'automatic_updates_backup_bank' ) ) {
				wp_schedule_event( LOCAL_TIME_BACKUP_BANK, 'Daily', 'automatic_updates_backup_bank' );
			}
			add_action( 'automatic_updates_backup_bank', 'backup_bank_plugin_auto_update' );
		}
	}

	if ( ! function_exists( 'backup_bank_plugin_auto_update' ) ) {
		/**
		 * This function is used to Update Plugin Automatically.
		 */
		function backup_bank_plugin_auto_update() {
			try {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				require_once ABSPATH . 'wp-admin/includes/misc.php';
				define( 'FS_METHOD', 'direct' );
				require_once ABSPATH . 'wp-includes/update.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				wp_update_plugins();
				ob_start();
				$plugin_upgrader = new Plugin_Upgrader();
				$plugin_upgrader->upgrade( BACKUP_BANK_FILE );
				$output = @ob_get_contents();// @codingStandardsIgnoreLine
				@ob_end_clean();// @codingStandardsIgnoreLine
			} catch ( Exception $e ) {// @codingStandardsIgnoreLine

			}
		}
	}

	if ( ! function_exists( 'backup_data_backup_bank' ) ) {
		/**
		 * This function is used to Running Backup.
		 *
		 * @param string $backup_array passes parameter as backup array.
		 */
		function backup_data_backup_bank( $backup_array ) {
			global $wpdb;
			$obj_backup_data_backup_bank = new Backup_Data_Backup_Bank( $backup_array );

			if ( 'only_database' === $backup_array['backup_type'] ) {
				$backup_status = $obj_backup_data_backup_bank->database_backup_bank();
			} else {
				$backup_status = $obj_backup_data_backup_bank->get_directories_backup_bank();
			}

			$backup_bank_data     = $backup_array;
			$file_name            = untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive_name'] ) ) . '.json';
			$backup_size          = $obj_backup_data_backup_bank->kbsize;
			$backup_data_time     = $obj_backup_data_backup_bank->timetaken;
			$backup_log_timetaken = $obj_backup_data_backup_bank->log_timetaken;

			$backup_bank_data['execution_time'] = maybe_serialize( array( LOCAL_TIME_BACKUP_BANK ) );
			$backup_bank_data['executed_in']    = $backup_data_time;
			$backup_bank_data['total_size']     = $backup_size . 'Mb';
			$backup_bank_data['executed_time']  = LOCAL_TIME_BACKUP_BANK;

			switch ( $backup_bank_data['backup_type'] ) {
				case 'only_themes':
					$backup_filename = 'Themes';
					break;

				case 'only_plugins':
					$backup_filename = 'Plugins';
					break;

				case 'only_wp_content_folder':
					$backup_filename = 'Contents';
					break;

				case 'complete_backup':
					$backup_filename = 'Complete';
					break;

				case 'only_filesystem':
					$backup_filename = 'Filesystem';
					break;

				case 'only_plugins_and_themes':
					$backup_filename = 'Plugins_Themes';
					break;

				case 'only_database':
					$backup_filename = 'Database';
					break;
			}

			$dbmailer_backup_bank_obj = new Dbmailer_Backup_Bank();

			if ( 'terminated' !== $backup_status && 'file_exists' !== $backup_status ) {
				if ( 'local_folder' !== $backup_bank_data['backup_destination'] ) {
					$upload_time_start = microtime( true );
					switch ( $backup_bank_data['backup_destination'] ) {
						case 'ftp':
							$upload_status = 'uploading_to_ftp';
							break;

						case 'email':
							$upload_status = 'uploading_to_email';
							break;

						case 'dropbox':
							$upload_status = 'uploading_to_dropbox';
							break;

						case 'google_drive':
							$upload_status = 'uploading_to_google_drive';
							break;
					}

					$backup_bank_data['status'] = $upload_status;

					$backup_bank_update_data               = array();
					$where                                 = array();
					$where['meta_key']                     = 'manual_backup_meta';// WPCS: db sql slow query.
					$backup_bank_update_data['meta_value'] = maybe_serialize( $backup_bank_data );// WPCS: db sql slow query.
					$where['meta_id']                      = $backup_bank_data['meta_id'];
					$obj_dbhelper_backup_bank              = new Dbhelper_Backup_Bank();
					$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $backup_bank_update_data, $where );
					switch ( $backup_bank_data['backup_destination'] ) {
						case 'ftp':
							$ftp_settings_data       = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'ftp_settings'
								)
							);// WPCS: db call ok, cache ok.
							$ftp_settings_data_array = maybe_unserialize( $ftp_settings_data );
							$obj_ftp_connect         = new Ftp_Connection_Backup_Bank();
							$ftp_connection          = $obj_ftp_connect->ftp_connect( $ftp_settings_data_array['host'], $ftp_settings_data_array['protocol'], $ftp_settings_data_array['port'] );

							if ( false !== $ftp_connection ) {
								$ftp_login  = $obj_ftp_connect->login_ftp( $ftp_connection, $ftp_settings_data_array['login_type'], $ftp_settings_data_array['ftp_username'], $ftp_settings_data_array['ftp_password'] );
								$ftp_result = $obj_ftp_connect->ftp_mkdir_recusive( $ftp_connection, trailingslashit( $ftp_settings_data_array['remote_path'] ) . BACKUP_BANK_FOLDER_DROPBOX . basename( $backup_bank_data['folder_location'] ) );

								if ( false !== $ftp_result ) {
									$backup_array = array( untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive'] ) ), untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive_name'] ) ) . '.txt' );
									if ( isset( $backup_array ) && count( $backup_array ) > 0 ) {
										foreach ( $backup_array as $backup_file ) {
											$backup_name = basename( $backup_file );
											@$obj_ftp_connect->custom_ftp_put( $ftp_connection, $backup_file, $backup_name, $file_name, $backup_bank_data );// @codingStandardsIgnoreLine
										}
									}
									$cloud         = 2;
									$log           = "<b>$backup_filename Backup</b> has been Uploaded to <b>FTP</b> Successfully.";
									$backup_status = 'completed_successfully';
								}
							}
							break;

						case 'email':
							$email_setting_data   = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key=%s', 'email_settings'
								)
							);// WPCS: db call ok, cache ok.
							$email_settings_array = maybe_unserialize( $email_setting_data );
							$file_size            = filesize( untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive'] ) ) );

							if ( $file_size <= 20971520 ) {
								$dbmailer_backup_bank_obj->sending_backup_to_email( $email_settings_array, $backup_bank_data );
								$log           = "<b>$backup_filename Backup</b> has been sent to <b>Email</b> Successfully.";
								$backup_status = 'completed_successfully';
							} else {
								$log           = "<b>$backup_filename Backup</b> could not be Sent to <b>Email</b> as Backup Size is more than <b>20MB</b>.";
								$backup_status = 'email_not_sent';
							}
							$cloud = 1;
							break;

						case 'dropbox':
							$bb_dropbox_settings_data             = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'dropbox_settings'
								)
							);// WPCS: db call ok, cache ok.
							$bb_dropbox_settings_unserialize_data = maybe_unserialize( $bb_dropbox_settings_data );
							$obj_dropbox_backup_bank              = new Dropbox_Backup_Bank();
							$obj_dropbox                          = $obj_dropbox_backup_bank->dropbox_client( $bb_dropbox_settings_unserialize_data['api_key'], $bb_dropbox_settings_unserialize_data['secret_key'] );
							try {
								$obj_dropbox_backup_bank->create_folder( $obj_dropbox, BACKUP_BANK_FOLDER_DROPBOX . basename( $backup_bank_data['folder_location'] ) );
							} catch ( DropboxException $e ) {// @codingStandardsIgnoreLine

							}
							$logfile_name = untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive_name'] ) ) . '.txt';
							$backup_array = array( untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive'] ) ), untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive_name'] ) ) . '.txt' );
							try {
								$obj_dropbox_backup_bank->handle_dropbox_auth_upload( $obj_dropbox, $backup_array, BACKUP_BANK_FOLDER_DROPBOX . basename( $backup_bank_data['folder_location'] ), $file_name, $logfile_name, $backup_bank_data );
							} catch ( Exception $e ) {
								$dropbox_upload_error = $e->getMessage();
							}

							$cloud = 2;
							if ( isset( $dropbox_upload_error ) ) {
								$log           = "<b>$backup_filename Backup</b> could not be Uploaded to <b>Dropbox</b> as " . $dropbox_upload_error;
								$backup_status = 'dropbox_backup_not_sent';
							} else {
								$log           = "<b>$backup_filename Backup</b> has been Uploaded to <b>Dropbox</b> Successfully.";
								$backup_status = 'completed_successfully';
							}
							break;

						case 'google_drive':
							$bb_google_drive_data             = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'google_drive'
								)
							);// WPCS: db call ok, cache ok.
							$bb_google_drive_unserialize_data = maybe_unserialize( $bb_google_drive_data );
							$file_size                        = filesize( untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive'] ) ) );
							$obj_google_drive_backup_bank     = new Google_Drive_Backup_Bank();
							$backup_array                     = array( untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive'] ) ), untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive_name'] ) ) . '.txt' );
							$folderid                         = $obj_google_drive_backup_bank->google_drive_create_folder( $bb_google_drive_unserialize_data['client_id'], $bb_google_drive_unserialize_data['secret_key'], $backup_array, $backup_filename, $bb_google_drive_unserialize_data['redirect_uri'] );
							if ( isset( $backup_array ) && count( $backup_array ) ) {
								foreach ( $backup_array as $backup_file ) {
									$obj_google_drive_backup_bank->upload_file( $bb_google_drive_unserialize_data['client_id'], $bb_google_drive_unserialize_data['secret_key'], $backup_file, $folderid, $bb_google_drive_unserialize_data['redirect_uri'], $file_name, $backup_bank_data );
								}
							}
							$cloud         = 2;
							$log           = "<b>$backup_filename Backup</b> has been Uploaded to <b>Google Drive</b> Successfully.";
							$backup_status = 'completed_successfully';
							break;
					}

					$uploaded_microtime = microtime( true ) - $upload_time_start;
					$uploaded_time      = max( microtime( true ) - $upload_time_start, 0.000001 );
					$logfile_path       = untrailingslashit( $backup_bank_data['folder_location'] ) . '/' . implode( '', maybe_unserialize( $backup_bank_data['archive_name'] ) ) . '.txt';
					$result             = 100;
					$rtime              = $backup_log_timetaken + $uploaded_microtime;
					$message            = '{' . "\r\n";
					$message           .= '"log": "' . $log . '" ,' . "\r\n";
					$message           .= '"perc": ' . $result . ',' . "\r\n";
					$message           .= '"status": "' . $backup_status . '" ,' . "\r\n";
					$message           .= '"cloud": ' . $cloud . "\r\n";
					$message           .= '}';
					file_put_contents( $file_name, $message );// @codingStandardsIgnoreLine
					file_put_contents( $logfile_path, sprintf( '%08.03f', round( $rtime, 3 ) ) . ' ' . strip_tags( $log ), FILE_APPEND );// @codingStandardsIgnoreLine
				}
			}

			$backup_bank_data['status'] = $backup_status;
			if ( 'local_folder' !== $backup_bank_data['backup_destination'] && 'terminated' !== $backup_status && 'file_exists' !== $backup_status ) {
				$backup_bank_data['executed_in'] = $backup_data_time + $uploaded_time;
			}
			$backup_bank_update_data               = array();
			$where                                 = array();
			$where['meta_key']                     = 'manual_backup_meta';// WPCS: db sql slow query.
			$backup_bank_update_data['meta_value'] = maybe_serialize( $backup_bank_data );// WPCS: db sql slow query.
			$where['meta_id']                      = $backup_bank_data['meta_id'];
			$obj_dbhelper_backup_bank              = new Dbhelper_Backup_Bank();
			$obj_dbhelper_backup_bank->update_command( backup_bank_meta(), $backup_bank_update_data, $where );

			$alert_setup_data = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'alert_setup'
				)
			);// WPCS: db call ok, cache ok.

			$alert_setup_data_array = maybe_unserialize( $alert_setup_data );

			if ( 'terminated' !== $backup_status && 'file_exists' !== $backup_status ) {
				if ( 'enable' === $alert_setup_data_array['email_when_backup_generated_successfully'] ) {
					$backup_generated_data       = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta WHERE meta_key = %s', 'template_for_backup_successful_generated'
						)
					);// WPCS: db call ok, cache ok.
					$backup_generated_data_array = maybe_unserialize( $backup_generated_data );
					$dbmailer_backup_bank_obj->email_when_backup_generated_successfully( $backup_generated_data_array, $backup_bank_data );
				}
			} else {
				if ( 'enable' === $alert_setup_data_array['email_when_backup_failed'] ) {
					$email_backup_data       = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . ' backup_bank_meta WHERE meta_key =%s', 'template_for_backup_failure'
						)
					);// WPCS: db call ok, cache ok.
					$email_backup_data_array = maybe_unserialize( $email_backup_data );
					$dbmailer_backup_bank_obj->email_when_backup_failed( $email_backup_data_array, $backup_bank_data );
				}
			}
		}
	}

	if ( ! function_exists( 'deactivation_function_for_backup_bank' ) ) {
		/**
		 * This function is used to get Plugin Stats.
		 */
		function deactivation_function_for_backup_bank() {
			delete_option( 'backup-bank-wizard' );
		}
	}
	/* hooks */

	/**
	 *This hook is used for calling the function of admin functions.
	 */

	add_action( 'admin_init', 'admin_functions_for_backup_bank' );

	/**
	 * This hook is used for calling the function of register ajax.
	 */

	add_action( 'wp_ajax_backup_bank_action', 'ajax_register_for_backup_bank' );

	/*
	 * This hook is used for calling the function of user function.
	 */

	add_action( 'init', 'user_function_for_backup_bank' );

	/**
	 * This hook is used for calling the function of sidebar menu.
	 */

	add_action( 'admin_menu', 'sidebar_menu_for_backup_bank' );

	/**
	 * This hook is used for calling the function of sidebar menuin multisite case.
	 */
	add_action( 'network_admin_menu', 'sidebar_menu_for_backup_bank' );

	/**
	 * This hook is used for calling the function of topbar menu.
	 */

	add_action( 'admin_bar_menu', 'topbar_menu_for_backup_bank', 100 );

	/**
	 * This hook is used for calling the function of cron schedulers jobs for WordPress data and database.
	 */

	add_filter( 'cron_schedules', 'cron_scheduler_for_intervals_backup_bank' );

	/**
	 * This hook is used for maintenance_mode_backup_bank.
	 */

	add_action( 'template_redirect', 'maintenance_mode_backup_bank' );

	/**
	 * This hook is used for start_backup.
	 */

	add_action( 'start_backup', 'backup_data_backup_bank' );

	/**
	 * This hook is used for create link for premium Edition.
	 */
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'backup_bank_action_links' );

	/**
	 * This hook is used for Plugin Stats.
	 */

	register_deactivation_hook( __FILE__, 'deactivation_function_for_backup_bank' );
}

/**
 * This hook is used for calling the function of install script.
 */

register_activation_hook( __FILE__, 'install_script_for_backup_bank' );

/**
 * This hook is used for calling the function of install script.
 */

add_action( 'admin_init', 'install_script_for_backup_bank' );

if ( ! function_exists( 'plugin_activate_backup_bank' ) ) {
	/**
	 * This function is used to add option.
	 */
	function plugin_activate_backup_bank() {
		add_option( 'backup_bank_do_activation_redirect', true );
	}
}

if ( ! function_exists( 'backup_bank_redirect' ) ) {
	/**
	 * This function is used to redirect page.
	 */
	function backup_bank_redirect() {
		if ( get_option( 'backup_bank_do_activation_redirect', false ) ) {
			delete_option( 'backup_bank_do_activation_redirect' );
			wp_redirect( admin_url( 'admin.php?page=bb_manage_backups' ) );// @codingStandardsIgnoreLine
			exit;
		}
	}
}

/**
 * This hook is used for calling the function plugin_activate_backup_bank
 */

register_activation_hook( __FILE__, 'plugin_activate_backup_bank' );

/**
 * This hook is used for calling the function backup_bank_redirect
 */

add_action( 'admin_init', 'backup_bank_redirect' );

/**
 * This function is used to create the object of admin notices.
 */
function backup_bank_admin_notice_class() {
	global $wpdb;
	/**
	 * This class is used to create the object of admin notices.
	 */
	class Backup_Bank_Admin_Notices {
		/**
		 * The version of this plugin.
		 *
		 * @access   protected
		 * @var      string    $promo_link.
		 */
		protected $promo_link = '';
		/**
		 * The version of this plugin.
		 *
		 * @access   public
		 * @var      string    $config.
		 */
		public $config;
		/**
		 * The version of this plugin.
		 *
		 * @access   public
		 * @var      integer    $notice_spam.
		 */
		public $notice_spam = 0;
		/**
		 * The version of this plugin.
		 *
		 * @access   public
		 * @var      integer    $notice_spam_max.
		 */
		public $notice_spam_max = 2;
		/**
		 * Public Constructor.
		 *
		 * @param array $config .
		 */
		public function __construct( $config = array() ) {
			// Runs the admin notice ignore function incase a dismiss button has been clicked.
			add_action( 'admin_init', array( $this, 'bb_admin_notice_ignore' ) );
			// Runs the admin notice temp ignore function incase a temp dismiss link has been clicked.
			add_action( 'admin_init', array( $this, 'bb_admin_notice_temp_ignore' ) );
			add_action( 'admin_notices', array( $this, 'bb_display_admin_notices' ) );
		}

		/**
		 * Checks to ensure notices aren't disabled and the user has the correct permissions.
		 */
		public function bb_admin_notices() {
			$settings = get_option( 'bb_admin_notice' );
			if ( ! isset( $settings['disable_admin_notices'] ) || ( isset( $settings['disable_admin_notices'] ) && 0 === $settings['disable_admin_notices'] ) ) {
				if ( current_user_can( 'manage_options' ) ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Primary notice function that can be called from an outside function sending necessary variables.
		 *
		 * @param string $admin_notices .
		 */
		public function change_admin_notice_backup_bank( $admin_notices ) {
			// Check options.
			if ( ! $this->bb_admin_notices() ) {
				return false;
			}
			foreach ( $admin_notices as $slug => $admin_notice ) {
				// Call for spam protection.
				if ( $this->bb_anti_notice_spam() ) {
					return false;
				}

				// Check for proper page to display on.
				if ( isset( $admin_notices[ $slug ]['pages'] ) && is_array( $admin_notices[ $slug ]['pages'] ) ) {
					if ( ! $this->bb_admin_notice_pages( $admin_notices[ $slug ]['pages'] ) ) {
						return false;
					}
				}

				// Check for required fields.
				if ( ! $this->bb_required_fields( $admin_notices[ $slug ] ) ) {

					// Get the current date then set start date to either passed value or current date value and add interval.
					$current_date = current_time( 'm/d/Y' );
					$start        = ( isset( $admin_notices[ $slug ]['start'] ) ? $admin_notices[ $slug ]['start'] : $current_date );
					$start        = date( 'm/d/Y' );
					$interval     = ( isset( $admin_notices[ $slug ]['int'] ) ? $admin_notices[ $slug ]['int'] : 0 );
					$date         = strtotime( '+' . $interval . ' days', strtotime( $start ) );
					$start        = date( 'm/d/Y', $date );

					// This is the main notices storage option.
					$admin_notices_option = get_option( 'bb_admin_notice', array() );
					// Check if the message is already stored and if so just grab the key otherwise store the message and its associated date information.
					if ( ! array_key_exists( $slug, $admin_notices_option ) ) {
						$admin_notices_option[ $slug ]['start'] = date( 'm/d/Y' );
						$admin_notices_option[ $slug ]['int']   = $interval;
						update_option( 'bb_admin_notice', $admin_notices_option );
					}

					// Sanity check to ensure we have accurate information
					// New date information will not overwrite old date information.
					$admin_display_check    = ( isset( $admin_notices_option[ $slug ]['dismissed'] ) ? $admin_notices_option[ $slug ]['dismissed'] : 0 );
					$admin_display_start    = ( isset( $admin_notices_option[ $slug ]['start'] ) ? $admin_notices_option[ $slug ]['start'] : $start );
					$admin_display_interval = ( isset( $admin_notices_option[ $slug ]['int'] ) ? $admin_notices_option[ $slug ]['int'] : $interval );
					$admin_display_msg      = ( isset( $admin_notices[ $slug ]['msg'] ) ? $admin_notices[ $slug ]['msg'] : '' );
					$admin_display_title    = ( isset( $admin_notices[ $slug ]['title'] ) ? $admin_notices[ $slug ]['title'] : '' );
					$admin_display_link     = ( isset( $admin_notices[ $slug ]['link'] ) ? $admin_notices[ $slug ]['link'] : '' );
					$output_css             = false;

					// Ensure the notice hasn't been hidden and that the current date is after the start date.
					if ( 0 === $admin_display_check && strtotime( $admin_display_start ) <= strtotime( $current_date ) ) {

						// Get remaining query string.
						$query_str = ( isset( $admin_notices[ $slug ]['later_link'] ) ? $admin_notices[ $slug ]['later_link'] : esc_url( add_query_arg( 'bb_admin_notice_ignore', $slug ) ) );
						if ( strpos( $slug, 'promo' ) === false ) {
							// Admin notice display output.
							echo '<div class="update-nag bb-admin-notice" style="width:95%!important;">
															 <div></div>
																<strong><p>' . $admin_display_title . '</p></strong>
																<strong><p style="font-size:14px !important">' . $admin_display_msg . '</p></strong>
																<strong><ul>' . $admin_display_link . '</ul></strong>
															</div>';// WPCS: XSS ok.
						} else {
							echo '<div class="admin-notice-promo">';
							echo $admin_display_msg;// WPCS: XSS ok.
							echo '<ul class="notice-body-promo blue">
																		' . $admin_display_link . '
																	</ul>';// WPCS: XSS ok.
							echo '</div>';
						}
						$this->notice_spam += 1;
						$output_css         = true;
					}
				}
			}
		}

		/**
		 * Spam protection check
		 */
		public function bb_anti_notice_spam() {
			if ( $this->notice_spam >= $this->notice_spam_max ) {
				return true;
			}
			return false;
		}

		/**
		 * Ignore function that gets ran at admin init to ensure any messages that were dismissed get marked
		 */
		public function bb_admin_notice_ignore() {
			// If user clicks to ignore the notice, update the option to not show it again.
			if ( isset( $_GET['bb_admin_notice_ignore'] ) ) {// WPCS: CSRF ok, input var ok.
				$admin_notices_option = get_option( 'bb_admin_notice', array() );
				$admin_notices_option[ $_GET['bb_admin_notice_ignore'] ]['dismissed'] = 1;// WPCS: CSRF ok, input var ok, sanitization ok.
				update_option( 'bb_admin_notice', $admin_notices_option );
				$query_str = remove_query_arg( 'bb_admin_notice_ignore' );
				wp_safe_redirect( $query_str );
				exit;
			}
		}

		/**
		 * Temp Ignore function that gets ran at admin init to ensure any messages that were temp dismissed get their start date changed.
		 */
		public function bb_admin_notice_temp_ignore() {
			// If user clicks to temp ignore the notice, update the option to change the start date - default interval of 14 days.
			if ( isset( $_GET['bb_admin_notice_temp_ignore'] ) ) {// WPCS: CSRF ok, input var ok.
				$admin_notices_option = get_option( 'cbo_admin_notice', array() );
				$current_date         = current_time( 'm/d/Y' );
				$interval             = ( isset( $_GET['int'] ) ? wp_unslash( $_GET['int'] ) : 7 );// WPCS: input var ok, CSRF ok, sanitization ok.
				$date                 = strtotime( '+' . $interval . ' days', strtotime( $current_date ) );
				$new_start            = date( 'm/d/Y', $date );

				$admin_notices_option[ $_GET['bb_admin_notice_temp_ignore'] ]['start']     = $new_start;// WPCS: CSRF ok, input var ok, sanitization ok.
				$admin_notices_option[ $_GET['bb_admin_notice_temp_ignore'] ]['dismissed'] = 0;// WPCS: CSRF ok, input var ok, sanitization ok.
				update_option( 'bb_admin_notice', $admin_notices_option );
				$query_str = remove_query_arg( array( 'bb_admin_notice_temp_ignore', 'bb_int' ) );
				wp_safe_redirect( $query_str );
				exit;
			}
		}
		/**
		 * Display admin notice on pages.
		 *
		 * @param array $pages .
		 */
		public function bb_admin_notice_pages( $pages ) {
			foreach ( $pages as $key => $page ) {
				if ( is_array( $page ) ) {
					if ( isset( $_GET['page'] ) && $_GET['page'] === $page[0] && isset( $_GET['tab'] ) && $_GET['tab'] === $page[1] ) {// WPCS: CSRF ok, input var ok.
						return true;
					}
				} else {
					if ( 'all' === $page ) {
						return true;
					}
					if ( get_current_screen()->id === $page ) {
						return true;
					}
					if ( isset( $_GET['page'] ) && $_GET['page'] === $page ) {// WPCS: CSRF ok, input var ok.
						return true;
					}
				}
				return false;
			}
		}

		/**
		 * Required fields check.
		 *
		 * @param array $fields .
		 */
		public function bb_required_fields( $fields ) {
			if ( ! isset( $fields['msg'] ) || ( isset( $fields['msg'] ) && empty( $fields['msg'] ) ) ) {
				return true;
			}
			if ( ! isset( $fields['title'] ) || ( isset( $fields['title'] ) && empty( $fields['title'] ) ) ) {
				return true;
			}
			return false;
		}
		/**
		 * Display Content in admin notice.
		 */
		public function bb_display_admin_notices() {
			$two_week_review_ignore = add_query_arg( array( 'bb_admin_notice_ignore' => 'two_week_review' ) );
			$two_week_review_temp   = add_query_arg(
				array(
					'bb_admin_notice_temp_ignore' => 'two_week_review',
					'int'                         => 7,
				)
			);

			$notices['two_week_review'] = array(
				'title'      => __( 'Leave A Backup Bank Review?', 'wp-backup-bank' ),
				'msg'        => __( 'We love and care about you. Backup Bank Team is putting our maximum efforts to provide you the best functionalities.<br> We would really appreciate if you could spend a couple of seconds to give a Nice Review to the plugin for motivating us!', 'wp-backup-bank' ),
				'link'       => '<span class="dashicons dashicons-external backup-bank-admin-notice"></span><span class="backup-bank-admin-notice"><a href="https://wordpress.org/support/plugin/wp-backup-bank/reviews/?filter=5" target="_blank" class="backup-bank-admin-notice-link">' . __( 'Sure! I\'d love to!', 'wp-backup-bank' ) . '</a></span>
												<span class="dashicons dashicons-smiley backup-bank-admin-notice"></span><span class="backup-bank-admin-notice"><a href="' . $two_week_review_ignore . '" class="backup-bank-admin-notice-link"> ' . __( 'I\'ve already left a review', 'wp-backup-bank' ) . '</a></span>
												<span class="dashicons dashicons-calendar-alt backup-bank-admin-notice"></span><span class="backup-bank-admin-notice"><a href="' . $two_week_review_temp . '" class="backup-bank-admin-notice-link">' . __( 'Maybe Later', 'wp-backup-bank' ) . '</a></span>',
				'later_link' => $two_week_review_temp,
				'int'        => 7,
			);

			$this->change_admin_notice_backup_bank( $notices );
		}

	}

	$plugin_info_backup_bank = new Backup_Bank_Admin_Notices();
}

add_action( 'init', 'backup_bank_admin_notice_class' );
/**
 * Add Pop on deactivation.
 */
function add_popup_on_deactivation_backup_bank() {
	global $wpdb;
	class Backup_Bank_Deactivation_Form {// @codingStandardsIgnoreLine
		/**
		 * Public Constructor.
		 */
		function __construct() {
			add_action( 'wp_ajax_post_user_feedback_backup_bank', array( $this, 'post_user_feedback_backup_bank' ) );
			global $pagenow;
			if ( 'plugins.php' === $pagenow ) {
					add_action( 'admin_enqueue_scripts', array( $this, 'feedback_form_js_backup_bank' ) );
					add_action( 'admin_head', array( $this, 'add_form_layout_backup_bank' ) );
					add_action( 'admin_footer', array( $this, 'add_deactivation_dialog_form_backup_bank' ) );
			}
		}
		/**
		 * Add css and js files.
		 */
		function feedback_form_js_backup_bank() {
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_register_script( 'wp-backup-bank-post-feedback', plugins_url( 'assets/global/plugins/deactivation/deactivate-popup.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' ), false, true );
			wp_localize_script( 'wp-backup-bank-post-feedback', 'post_feedback', array( 'admin_ajax' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'wp-backup-bank-post-feedback' );
		}
		/**
		 * Post user Fedback.
		 */
		function post_user_feedback_backup_bank() {
			$backup_bank_deactivation_reason = isset( $_POST['reason'] ) ? wp_unslash( $_POST['reason'] ) : '';// WPCS: CSRF ok, input var ok, sanitization ok.
			$type                            = get_option( 'backup-bank-wizard' );
			$user_admin_email                = get_option( 'backup-bank-admin-email' );
			$class_plugin_info               = new class_plugin_info();
			global $wp_version, $wpdb;
			$theme_details = array();
			if ( $wp_version >= 3.4 ) {
				$active_theme                   = wp_get_theme();
				$theme_details['theme_name']    = strip_tags( $active_theme->name );
				$theme_details['theme_version'] = strip_tags( $active_theme->version );
				$theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
			}
			$plugin_stat_data                     = array();
			$plugin_stat_data['plugin_slug']      = 'wp-backup-bank';
			$plugin_stat_data['reason']           = $backup_bank_deactivation_reason;
			$plugin_stat_data['type']             = 'standard_edition';
			$plugin_stat_data['version_number']   = BACKUP_BANK_VERSION_NUMBER;
			$plugin_stat_data['status']           = $type;
			$plugin_stat_data['event']            = 'de-activate';
			$plugin_stat_data['domain_url']       = site_url();
			$plugin_stat_data['wp_language']      = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
			$plugin_stat_data['email']            = false !== $user_admin_email ? $user_admin_email : get_option( 'admin_email' );
			$plugin_stat_data['wp_version']       = $wp_version;
			$plugin_stat_data['php_version']      = esc_html( phpversion() );
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
				die( 'success' );
		}
		/**
		 * Add form layout of deactivation form.
		 */
		function add_form_layout_backup_bank() {
			?>
			<style type="text/css">
					.backup-bank-feedback-form .ui-dialog-buttonset {
						float: none !important;
					}
					#backup-bank-feedback-dialog-continue,#backup-bank-feedback-dialog-skip {
						float: right;
					}
					#backup-bank-feedback-cancel{
						float: left;
					}
					#backup-bank-feedback-content p {
						font-size: 1.1em;
					}
					.backup-bank-feedback-form .ui-icon {
						display: none;
					}
					#backup-bank-feedback-dialog-continue.backup-bank-ajax-progress .ui-icon {
						text-indent: inherit;
						display: inline-block !important;
						vertical-align: middle;
						animation: rotate 2s infinite linear;
					}
					#backup-bank-feedback-dialog-continue.backup-bank-ajax-progress .ui-button-text {
						vertical-align: middle;
					}
					@keyframes rotate {
						0%    { transform: rotate(0deg); }
						100%  { transform: rotate(360deg); }
					}
			</style>
			<?php
		}
		/**
		 * Add deactivation dialog form.
		 */
		function add_deactivation_dialog_form_backup_bank() {
			?>
			<div id="backup-bank-feedback-content" style="display: none;">
			<p style="margin-top:-5px"><?php echo esc_attr( __( 'We feel guilty when anyone stop using Backup Bank.', 'wp-backup-bank' ) ); ?></p>
						<p><?php echo esc_attr( __( 'If Backup Bank isn\'t working for you, others also may not.', 'wp-backup-bank' ) ); ?></p>
						<p><?php echo esc_attr( __( 'We would love to hear your feedback about what went wrong.', 'wp-backup-bank' ) ); ?></p>
						<p><?php echo esc_attr( __( 'We would like to help you in fixing the issue.', 'wp-backup-bank' ) ); ?></p>
						<p><?php echo esc_attr( __( 'If you click Continue, some data would be sent to our servers for Compatiblity Testing Purposes.', 'wp-backup-bank' ) ); ?></p>
						<p><?php echo esc_attr( __( 'If you Skip, no data would be shared with our servers.', 'wp-backup-bank' ) ); ?></p>
			<form>
				<?php wp_nonce_field(); ?>
				<ul id="backup-bank-deactivate-reasons">
					<li class="backup-bank-reason backup-bank-custom-input">
						<label>
							<span><input value="0" type="radio" name="reason" /></span>
							<span><?php echo esc_attr( __( 'The Plugin didn\'t work', 'wp-backup-bank' ) ); ?></span>
						</label>
					</li>
					<li class="backup-bank-reason backup-bank-custom-input">
						<label>
							<span><input value="1" type="radio" name="reason" /></span>
							<span><?php echo esc_attr( __( 'I found a better Plugin', 'wp-backup-bank' ) ); ?></span>
						</label>
					</li>
					<li class="backup-bank-reason">
						<label>
							<span><input value="2" type="radio" name="reason" checked/></span>
							<span><?php echo esc_attr( __( 'It\'s a temporary deactivation. I\'m just debugging an issue.', 'wp-backup-bank' ) ); ?></span>
						</label>
					</li>
					<li class="backup-bank-reason backup-bank-custom-input">
						<label>
							<span><input value="3" type="radio" name="reason" /></span>
							<span><a href="https://wordpress.org/support/plugin/wp-backup-bank" target="_blank"><?php echo esc_attr( __( 'Open a Support Ticket for me.' ) ); ?></a></span>
						</label>
					</li>
				</ul>
			</form>
		</div>
			<?php
		}
	}
	$plugin_deactivation_details = new Backup_Bank_Deactivation_Form();
}
add_action( 'plugins_loaded', 'add_popup_on_deactivation_backup_bank' );
/**
 * Insert deactivation link.
 *
 * @param array $links .
 */
function insert_deactivate_link_id_backup_bank( $links ) {
	if ( ! is_multisite() ) {
		$links['deactivate'] = str_replace( '<a', '<a id="backup-bank-plugin-disable-link"', $links['deactivate'] );
	}
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'insert_deactivate_link_id_backup_bank', 10, 2 );
