<?php
/**
 * This file contains uninstallation code.
 *
 * @author Tech Banker
 * @package wp-backup-bank
 * @version 3.0.1
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}
if ( ! current_user_can( 'manage_options' ) ) {
	return;
} else {
	global $wpdb;
	if ( is_multisite() ) {
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );// WPCS: db call ok, no-cache ok.
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );// @codingStandardsIgnoreLine.
			$backup_bank_version_number = get_option( 'backup-bank-version-number' );
			if ( false !== $backup_bank_version_number ) {
				global $wp_version, $wpdb;

				$bb_other_settings_updated_data = $wpdb->get_var(
					$wpdb->prepare(
						'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta
						WHERE meta_key = %s', 'other_settings'
					)
				);// WPCS: db call ok, no-cache ok.
				$bb_other_settings_array        = maybe_unserialize( $bb_other_settings_updated_data );
				if ( isset( $bb_other_settings_array['remove_tables_at_uninstall'] ) && 'enable' === esc_attr( $bb_other_settings_array['remove_tables_at_uninstall'] ) ) {
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'backup_bank' );// @codingStandardsIgnoreLine.
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'backup_bank_meta' );// @codingStandardsIgnoreLine.
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'backup_bank_restore' );// @codingStandardsIgnoreLine.
					delete_option( 'backup-bank-version-number' );
					delete_option( 'bb_admin_notice' );
					delete_option( 'backup-bank-wizard' );
				}
				$backup_bank_version_number = get_option( 'backup-bank-version-number' );
			}
			restore_current_blog();
		}
	} else {
		$backup_bank_version_number = get_option( 'backup-bank-version-number' );
		if ( false !== $backup_bank_version_number ) {
			global $wp_version, $wpdb;

			$bb_other_settings_updated_data = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'backup_bank_meta
					WHERE meta_key = %s', 'other_settings'
				)
			);// WPCS: db call ok, no-cache ok.
			$bb_other_settings_array        = maybe_unserialize( $bb_other_settings_updated_data );
			if ( isset( $bb_other_settings_array['remove_tables_at_uninstall'] ) && 'enable' === esc_attr( $bb_other_settings_array['remove_tables_at_uninstall'] ) ) {
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'backup_bank' );// @codingStandardsIgnoreLine.
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'backup_bank_meta' );// @codingStandardsIgnoreLine.
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'backup_bank_restore' );// @codingStandardsIgnoreLine.
				delete_option( 'backup-bank-version-number' );
				delete_option( 'bb_admin_notice' );
				delete_option( 'backup-bank-wizard' );
			}
		}
	}
}
