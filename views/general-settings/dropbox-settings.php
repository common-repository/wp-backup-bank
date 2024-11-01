<?php
/**
 * This Template is used for displaying Dropbox settings.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/views/general-settings
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//exit if accessed directly
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
		$backup_bank_dropbox_settings = wp_create_nonce( 'backup_bank_dropbox_settings' );
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
						<?php echo esc_attr( $bb_dropbox_settings ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-social-dropbox"></i>
							<?php echo esc_attr( $bb_dropbox_settings ); ?>
						</div>
						<p class="premium-editions-backup-bank">
							<?php echo esc_attr( $bb_upgrade_know_about ); ?> <a href="https://tech-banker.com/backup-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_full_features ); ?></a> <?php echo esc_attr( $bb_chek_our ); ?> <a href="https://tech-banker.com/backup-bank/backend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_online_demos ); ?></a>
						</p>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_dropbox">
							<div class="form-body">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $bb_dropbox_backup_to ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<select name="ux_ddl_dropbox_settings_enable_disable" id="ux_ddl_dropbox_settings_enable_disable" class="form-control" onchange="dropbox_backup_bank();">
										<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
										<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
									</select>
									<i class="controls-description"><?php echo esc_attr( $bb_enable_tooltip ); ?></i>
								</div>
								<div id="ux_div_dropbox">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													<?php echo esc_attr( $bb_api_key ); ?> (<a class="link-style" href="https://www.dropbox.com/developers/apps" id="ux_dropbox_apps_link"  target="_blank"><?php echo esc_attr( ' ' . $bb_dropbox_get_api_secret_key . ' ' ); ?></a>) :
													<span class="required" aria-required="true">*</span>
												</label>
												<input name="ux_txt_dropbox_api_key" id="ux_txt_dropbox_api_key" type="text" class="form-control" placeholder="<?php echo esc_attr( $bb_dropbox_api_key_placeholder ); ?>" value="<?php echo isset( $bb_dropbox_settings_unserialize_data['api_key'] ) ? esc_html( $bb_dropbox_settings_unserialize_data['api_key'] ) : ''; ?>">
												<i class="controls-description"><?php echo esc_attr( $bb_amazons3_asccess_key_id_tooltip ); ?></i>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													<?php echo esc_attr( $bb_dropbox_secret_key ); ?> :
													<span class="required" aria-required="true">*</span>
												</label>
												<input name="ux_txt_dropbox_secret_key" id="ux_txt_dropbox_secret_key" type="text" class="form-control" placeholder="<?php echo esc_attr( $bb_dropbox_secret_key_placeholder ); ?>" value="<?php echo isset( $bb_dropbox_settings_unserialize_data['secret_key'] ) ? esc_html( $bb_dropbox_settings_unserialize_data['secret_key'] ) : ''; ?>">
												<i class="controls-description"><?php echo esc_attr( $bb_amazons3_asccess_key_id_tooltip ); ?></i>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $bb_redirect_url ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<input name="ux_txt_dropbox_redirect_uri" id="ux_txt_dropbox_redirect_uri" readonly="readonly" type="text" value="<?php echo esc_url( admin_url() ) . 'admin.php?page=bb_dropbox_settings'; ?>" class="form-control">
										<i class="controls-description"><?php echo esc_attr( $bb_redirect_url_tooltip ); ?></i>
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
						<?php echo esc_attr( $bb_dropbox_settings ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-social-dropbox"></i>
							<?php echo esc_attr( $bb_dropbox_settings ); ?>
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
