<?php
/**
 * This Template is used for managing plugin settings.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/views/general-settings
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
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
	} elseif ( GENERAL_SETTINGS_BACKUP_BANK === '1' ) {
		$backup_bank_other_settings = wp_create_nonce( 'backup_bank_other_settings' );
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
			<li>
				<i class="icon-custom-home"></i>
				<a href="admin.php?page=bb_manage_backups">
					<?php echo esc_attr( $wp_backup_bank ); ?>
					</a>
					<span>></span>
			</li>
			<li>
				<a href="admin.php?page=bb_alert_setup">
					<?php echo esc_attr( $bb_general_settings ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $bb_other_settings ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-settings"></i>
						<?php echo esc_attr( $bb_other_settings ); ?>
					</div>
					<p class="premium-editions-backup-bank">
						<?php echo esc_attr( $bb_upgrade_know_about ); ?> <a href="https://tech-banker.com/backup-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_full_features ); ?></a> <?php echo esc_attr( $bb_chek_our ); ?> <a href="https://tech-banker.com/backup-bank/backend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_other_settings">
						<div class="form-body">
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $bb_other_setting_remove_tables ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_remove_tables" id="ux_ddl_remove_tables" class="form-control" >
									<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $bb_other_setting_remove_tables_tootltip ); ?></i>
							</div>
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $bb_other_setting_maintenance_mode ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_maintenance_mode" id="ux_ddl_maintenance_mode" class="form-control" onchange="enable_and_disable_backup_bank('#ux_ddl_maintenance_mode', '#ux_txt_maintenance_mode');">
									<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $bb_other_setting_maintenance_mode_tooltip ); ?></i>
							</div>
							<div id="ux_txt_maintenance_mode">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $bb_other_setting_maintenance_mode_message ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<textarea name="ux_txt_maintenance_mode_message" id="ux_txt_maintenance_mode_message" class="form-control" placeholder="<?php echo esc_attr( $bb_other_setting_maintenance_mode_message_placeholder ); ?>"><?php echo isset( $bb_other_settings_maintenance_mode_data['message_when_restore'] ) ? esc_html( $bb_other_settings_maintenance_mode_data['message_when_restore'] ) : ''; ?> </textarea>
									<i class="controls-description"><?php echo esc_attr( $bb_other_setting_maintenance_mode_message_tooltip ); ?></i>
								</div>
							</div>
							<div class="line-separator"></div>
							<div class="form-actions">
								<div class="pull-right">
									<input type="submit" class="btn vivid-green" name="ux_btn_save_changes" id="ux_btn_save_changes" value="<?php echo esc_attr( $bb_save_changes ); ?>">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
		<?php
	} else {
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
			<li>
				<i class="icon-custom-home"></i>
					<a href="admin.php?page=bb_manage_backups">
					<?php echo esc_attr( $wp_backup_bank ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<a href="admin.php?page=bb_alert_setup">
					<?php echo esc_attr( $bb_general_settings ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $bb_other_settings ); ?>
					</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-settings"></i>
						<?php echo esc_attr( $bb_other_settings ); ?>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<strong><?php echo esc_attr( $bb_user_access_message ); ?></strong>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?php
	}
}
