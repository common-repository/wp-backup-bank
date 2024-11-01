<?php
/**
 * This Template is used for managing email settings.
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
					<?php echo esc_attr( $bb_alert_setup ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-bell"></i>
						<?php echo esc_attr( $bb_alert_setup ); ?>
					</div>
					<p class="premium-editions-backup-bank">
						<?php echo esc_attr( $bb_upgrade_know_about ); ?> <a href="https://tech-banker.com/backup-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_full_features ); ?></a> <?php echo esc_attr( $bb_chek_our ); ?> <a href="https://tech-banker.com/backup-bank/backend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_alert_setup">
						<div class="form-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $bb_email_for_generated_backup ); ?> :
									<span class="required" aria-required="true">* ( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
								</label>
								<select name="ux_ddl_backup_generated_successfull" id="ux_ddl_backup_generated_successfull" class="form-control" >
									<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
								</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $bb_email_for_backup_schedule ); ?> :
									<span class="required" aria-required="true">* ( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
								</label>
								<select name="ux_ddl_backup_scheduled_successfull" id="ux_ddl_backup_scheduled_successfull" class="form-control" >
									<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
								</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $bb_email_for_restore_successfully ); ?> :
									<span class="required" aria-required="true">* ( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
								</label>
								<select name="ux_ddl_restore_completed_successfull" id="ux_ddl_restore_completed_successfull" class="form-control" >
									<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
								</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $bb_email_for_backup_failure ); ?> :
									<span class="required" aria-required="true">* ( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
								</label>
								<select name="ux_ddl_backup_failed" id="ux_ddl_backup_failed" class="form-control" >
									<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
								</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $bb_email_for_restore_failure ); ?> :
								<span class="required" aria-required="true">* ( <?php echo esc_attr( $bb_premium_editions ); ?> )</span>
							</label>
							<select name="ux_ddl_restore_failed" id="ux_ddl_restore_failed" class="form-control" >
								<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
								<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
							</select>
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
					<?php echo esc_attr( $bb_alert_setup ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
					<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-bell"></i>
						<?php echo esc_attr( $bb_alert_setup ); ?>
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
