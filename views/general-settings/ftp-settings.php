<?php
/**
 * This Template is used for displaying ftp settings.
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
		$backup_bank_ftp_settings = wp_create_nonce( 'backup_bank_ftp_settings' );
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
					<?php echo esc_attr( $bb_ftp_settings ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-share-alt"></i>
						<?php echo esc_attr( $bb_ftp_settings ); ?>
					</div>
					<p class="premium-editions-backup-bank">
						<?php echo esc_attr( $bb_upgrade_know_about ); ?> <a href="https://tech-banker.com/backup-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_full_features ); ?></a> <?php echo esc_attr( $bb_chek_our ); ?> <a href="https://tech-banker.com/backup-bank/backend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_ftp_settings">
						<div class="form-body">
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $bb_ftp_settings_backup_to ); ?> :
								<span class="required" aria-required="true">*</span>
							</label>
							<select name="ux_ddl_ftp_settings_enable_disable" id="ux_ddl_ftp_settings_enable_disable" class="form-control" onchange="ftp_backup_bank();">
								<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
								<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
							</select>
							<i class="controls-description"><?php echo esc_attr( $bb_enable_tooltip ); ?></i>
						</div>
						<div id="ux_div_ftp">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $bb_ftp_settings_protocol ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<select name="ux_ddl_ftp_protocol" id="ux_ddl_ftp_protocol" class="form-control">
										<option value="ftp"><?php echo esc_attr( $bb_ftp ); ?></option>
										<option value="ftps"><?php echo esc_attr( $bb_ftps_settings ); ?></option>
										<option value="sfpt_over_ssh"><?php echo esc_attr( $bb_ftp_settings_sftp_over_ssh ); ?></option>
									</select>
									<i class="controls-description"><?php echo esc_attr( $bb_ftp_settings_protocol_tooltip ); ?></i>
								</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $bb_ftp_settings_host ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<input name="ux_txt_ftp_settings_host" id="ux_txt_ftp_settings_host" type="text" class="form-control" placeholder="<?php echo esc_attr( $bb_ftp_settings_host_placeholder ); ?>" value="<?php echo isset( $ftp_settings_data_array['host'] ) ? esc_html( $ftp_settings_data_array['host'] ) : ''; ?>">
									<i class="controls-description"><?php echo esc_attr( $bb_ftp_settings_host_tooltip ); ?></i>
								</div>
								</div>
								</div>
								<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $bb_ftp_settings_login_type ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_login_type" id="ux_ddl_login_type" class="form-control" onchange="ftp_login_type_backup_bank();">
									<option value="username_password"><?php echo esc_attr( $bb_ftp_settings_username_password ); ?></option>
									<option value="username_only"><?php echo esc_attr( $bb_ftp_settings_username_only ); ?></option>
									<option value="anonymous"><?php echo esc_attr( $bb_ftp_settings_anonymous ); ?></option>
									<option value="no_login"><?php echo esc_attr( $bb_ftp_settings_anonymous_no_login ); ?></option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $bb_ftp_settings_login_type_tooltip ); ?></i>
							</div>
							<div id = "ux_div_ftp_username">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $bb_ftp_settings_ftp_username ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<input type="text" class="form-control" name="ux_txt_ftp_settings_username" id="ux_txt_ftp_settings_username" value="<?php echo isset( $ftp_settings_data_array['ftp_username'] ) ? esc_html( $ftp_settings_data_array['ftp_username'] ) : ''; ?>" placeholder="<?php echo esc_attr( $bb_ftp_settings_ftp_username_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $bb_ftp_settings_ftp_username_tooltip ); ?></i>
								</div>
							</div>
							<div id = "ux_div_ftp_password">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $bb_ftp_settings_password ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<input type="password" class="form-control" name="ux_txt_ftp_settings_password" id="ux_txt_ftp_settings_password" value="<?php echo isset( $ftp_settings_data_array['ftp_password'] ) ? esc_html( $ftp_settings_data_array['ftp_password'] ) : ''; ?>" placeholder="<?php echo esc_attr( $bb_ftp_settings_password_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $bb_ftp_settings_password_tooltip ); ?></i>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $bb_ftp_settings_ftp_mode ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<select name="ux_ddl_ftp_mode" id="ux_ddl_ftp_mode" class="form-control">
										<option value="false"><?php echo esc_attr( $bb_ftp_settings_active_mode ); ?></option>
										<option value="true"><?php echo esc_attr( $bb_ftp_settings_passive_mode ); ?></option>
									</select>
									<i class="controls-description"><?php echo esc_attr( $bb_ftp_settings_ftp_mode_tooltip ); ?></i>
								</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $bb_ftp_settings_ftp_port ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<input type="text" class="form-control" name="ux_txt_ftp_settings_port" id="ux_txt_ftp_settings_port" value="<?php echo isset( $ftp_settings_data_array['port'] ) ? esc_html( $ftp_settings_data_array['port'] ) : ''; ?>" placeholder="<?php echo esc_attr( $bb_ftp_settings_ftp_port_placeholder ); ?>" onfocus="only_digits_when_paste_backup_bank(this.id);">
									<i class="controls-description"><?php echo esc_attr( $bb_ftp_settings_ftp_port_tooltip ); ?></i>
								</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $bb_ftp_settings_remote_path ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<input type="text" class="form-control" name="ux_txt_ftp_settings_remote_path" id="ux_txt_ftp_settings_remote_path" value="<?php echo isset( $ftp_settings_data_array['remote_path'] ) ? esc_html( $ftp_settings_data_array['remote_path'] ) : ''; ?>" placeholder="<?php echo esc_attr( $bb_ftp_settings_remote_path_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $bb_ftp_settings_remote_path_tooltip ); ?></i>
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
					<?php echo esc_attr( $bb_ftp_settings ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-share-alt"></i>
						<?php echo esc_attr( $bb_ftp_settings ); ?>
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
