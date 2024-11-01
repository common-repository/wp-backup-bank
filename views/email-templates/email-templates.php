<?php
/**
 * This Template is used for saving email templates.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/views/email-templates
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
	} elseif ( EMAIL_TEMPLATES_BACKUP_BANK === '1' ) {
		$backup_bank_change_template       = wp_create_nonce( 'backup_bank_change_template' );
		$backup_bank_update_email_template = wp_create_nonce( 'backup_bank_update_email_template' );
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
				<span>
					<?php echo esc_attr( $bb_email_templates ); ?>
				</span>
			</li>
		</ul>
		</div>
		<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
					<i class="icon-custom-layers"></i>
						<?php echo esc_attr( $bb_email_templates ); ?>
					</div>
					<p class="premium-editions-backup-bank">
						<?php echo esc_attr( $bb_upgrade_know_about ); ?> <a href="https://tech-banker.com/backup-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_full_features ); ?></a> <?php echo esc_attr( $bb_chek_our ); ?> <a href="https://tech-banker.com/backup-bank/backend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_email_template">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $bb_choose_email_template ); ?> :
								<span class="required" aria-required="true">* ( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
							</label>
							<select name="ux_ddl_email_template" id="ux_ddl_email_template" class="form-control" onchange="template_change_data_backup_bank();">
								<option value="template_for_backup_successful_generated"><?php echo esc_attr( $bb_email_template_for_generated_backup ); ?></option>
								<option value="template_for_scheduled_backup"><?php echo esc_attr( $bb_email_template_for_backup_schedule ); ?></option>
								<option value="template_for_restore_successfully"><?php echo esc_attr( $bb_email_template_for_restore_successfully ); ?></option>
								<option value="template_for_backup_failure"><?php echo esc_attr( $bb_email_template_for_backup_failure ); ?></option>
								<option value="template_for_restore_failure"><?php echo esc_attr( $bb_email_template_for_restore_failure ); ?></option>
							</select>
							<i class="controls-description"><?php echo esc_attr( $bb_choose_email_template_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $bb_email_template_send_to ); ?> :
								<span class="required" aria-required="true">* ( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
							</label>
							<input type="text" class="form-control" name="ux_txt_email_send_to" id="ux_txt_email_send_to" value="" placeholder="<?php echo esc_attr( $bb_email_template_send_to_placeholder ); ?>">
							<i class="controls-description"><?php echo esc_attr( $bb_email_template_send_to_tooltip ); ?></i>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $bb_cc_email ); ?> :
									<span style="color:red;">( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
								</label>
								<input type="text" class="form-control" name="ux_txt_email_template_cc" id="ux_txt_email_template_cc" value="" placeholder="<?php echo esc_attr( $bb_cc_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $bb_email_cc_tooltip ); ?></i>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $bb_bcc_email ); ?> :
									<span style="color:red;">( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
								</label>
								<input type="text" class="form-control" name="ux_txt_email_template_bcc" id="ux_txt_email_template_bcc" value="" placeholder="<?php echo esc_attr( $bb_bcc_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $bb_email_bcc_tooltip ); ?></i>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $bb_subject ); ?> :
								<span class="required" aria-required="true">* ( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
							</label>
							<input type="text" class="form-control" name="ux_txt_email_subject" id="ux_txt_email_subject" value="" placeholder="<?php echo esc_attr( $bb_email_template_subject_placeholder ); ?>">
							<i class="controls-description"><?php echo esc_attr( $bb_email_template_subject_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $bb_email_message ); ?> :
								<span class="required" aria-required="true">* <style="color:red;">( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
							</label>
							<?php
							$bb_email_template_distribution = '';
							wp_editor(
								$bb_email_template_distribution, 'ux_heading_content', array(
									'media_buttons' => false,
									'textarea_rows' => 8,
									'tabindex'      => 4,
								)
							);
							?>
							<textarea id="ux_txt_email_template_message" name="ux_txt_email_template_message" style="display:none"><?php echo esc_attr( $bb_email_template_distribution ); ?></textarea>
							<i class="controls-description" ><?php echo esc_attr( $bb_email_message_tooltip ); ?></i>
						</div>
						<div class="line-separator"></div>
						<div class="form-actions">
							<div class="pull-right">
								<input type="hidden" id="ux_email_template_meta_id" value=""/>
								<input type="submit" class="btn vivid-green" name="ux_btn_save_email_template" id="ux_btn_save_email_template" value="<?php echo esc_attr( $bb_save_changes ); ?>">
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
				<span>
					<?php echo esc_attr( $bb_email_templates ); ?>
				</span>
			</li>
		</ul>
		</div>
		<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
					<i class="icon-custom-layers"></i>
						<?php echo esc_attr( $bb_email_templates ); ?>
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
