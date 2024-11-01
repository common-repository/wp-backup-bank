<?php
/**
 * This Template is used for displaying email settings.
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
		$backup_bank_email_settings = wp_create_nonce( 'backup_bank_email_settings' );
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
					<?php echo esc_attr( $bb_email_settings ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
					<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-envelope"></i>
						<?php echo esc_attr( $bb_email_settings ); ?>
					</div>
					<p class="premium-editions-backup-bank">
						<?php echo esc_attr( $bb_upgrade_know_about ); ?> <a href="https://tech-banker.com/backup-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_full_features ); ?></a> <?php echo esc_attr( $bb_chek_our ); ?> <a href="https://tech-banker.com/backup-bank/backend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_email_settings">
						<div class="form-body">
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $bb_email_backup_to ); ?> :
								<span class="required" aria-required="true">*</span>
							</label>
							<select name="ux_ddl_email_settings_enable_disable" id="ux_ddl_email_settings_enable_disable" class="form-control" onchange="email_backup_bank();">
								<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
								<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
							</select>
							<i class="controls-description"><?php echo esc_attr( $bb_enable_tooltip ); ?></i>
						</div>
						<div id="ux_div_email">
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $bb_email_settings_email_address ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<input name="ux_txt_email_address" id="ux_txt_email_address" type="text" class="form-control" placeholder="<?php echo esc_attr( $bb_email_address_placeholder ); ?>" value="<?php echo isset( $email_setting_data_array['email_address'] ) ? esc_html( $email_setting_data_array['email_address'] ) : ''; ?>">
								<i class="controls-description"><?php echo esc_attr( $bb_email_template_send_to_tooltip ); ?></i>
							</div>
								<div class="row">
								<div class="col-md-6">
									<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $bb_cc_email ); ?> :
									</label>
									<input type="text" class="form-control" name="ux_txt_email_cc" id="ux_txt_email_cc" value="<?php echo isset( $email_setting_data_array['cc_email'] ) ? esc_html( $email_setting_data_array['cc_email'] ) : ''; ?>" placeholder="<?php echo esc_attr( $bb_cc_placeholder ); ?>">
									<i class="controls-description"><?php echo esc_attr( $bb_email_cc_tooltip ); ?></i>
								</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $bb_bcc_email ); ?> :
									</label>
									<input type="text" class="form-control" name="ux_txt_email_bcc" id="ux_txt_email_bcc" value="<?php echo isset( $email_setting_data_array['bcc_email'] ) ? esc_html( $email_setting_data_array['bcc_email'] ) : ''; ?>" placeholder="<?php echo esc_attr( $bb_bcc_placeholder ); ?>">
									<i class="controls-description"><?php echo esc_attr( $bb_email_bcc_tooltip ); ?></i>
								</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $bb_subject ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<input type="text" class="form-control" name="ux_txt_email_subject" id="ux_txt_email_subject" value="<?php echo isset( $email_setting_data_array['email_subject'] ) ? esc_html( $email_setting_data_array['email_subject'] ) : ''; ?>" placeholder="<?php echo esc_attr( $bb_email_settings_subject_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $bb_email_template_subject_tooltip ); ?></i>
							</div>
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $bb_email_message ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<?php
								$bb_email_settings_distribution = isset( $email_setting_data_array['email_message'] ) ? $email_setting_data_array['email_message'] : '';
								wp_editor(
									$bb_email_settings_distribution, 'ux_heading_content', array(
										'media_buttons' => false,
										'textarea_rows' => 8,
										'tabindex'      => 4,
									)
								);
								?>
								<textarea id="ux_txt_email_settings_message" name="ux_txt_email_settings_message" style="display:none"></textarea>
								<i class="controls-description"><?php echo esc_attr( $bb_email_message_tooltip ); ?></i>
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
					<?php echo esc_attr( $bb_email_settings ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-envelope"></i>
						<?php echo esc_attr( $bb_email_settings ); ?>
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
