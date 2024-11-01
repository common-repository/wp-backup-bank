<?php
/**
 * This file is used for maintance mode.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/includes
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
global $wpdb;
$enable_maintenance_mode      = $wpdb->get_var(
	$wpdb->prepare(
		'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_restore WHERE meta_key = %s', 'maintenance_mode_settings'
	)
);// WPCS: db call ok, no-cache ok.
$enable_maintenance_mode_data = maybe_unserialize( $enable_maintenance_mode );
?>
<h1> <?php echo isset( $enable_maintenance_mode_data['message_when_restore'] ) ? esc_attr( $enable_maintenance_mode_data['message_when_restore'] ) : 'Site in Maintenance Mode '; ?> </h1>
<?php
exit();
