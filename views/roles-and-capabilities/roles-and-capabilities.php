<?php
/**
 * This Template is used for managing roles and capabilities.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/views/roles-and-capabilities
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
	} elseif ( ROLES_AND_CAPABILITIES_BACKUP_BANK === '1' ) {
		$roles_and_capabilities = explode( ',', isset( $details_roles_capabilities['roles_and_capabilities'] ) ? $details_roles_capabilities['roles_and_capabilities'] : '' );
		$author                 = explode( ',', isset( $details_roles_capabilities['author_privileges'] ) ? $details_roles_capabilities['author_privileges'] : '' );
		$editor                 = explode( ',', isset( $details_roles_capabilities['editor_privileges'] ) ? $details_roles_capabilities['editor_privileges'] : '' );
		$contributor            = explode( ',', isset( $details_roles_capabilities['contributor_privileges'] ) ? $details_roles_capabilities['contributor_privileges'] : '' );
		$subscriber             = explode( ',', isset( $details_roles_capabilities['subscriber_privileges'] ) ? $details_roles_capabilities['subscriber_privileges'] : '' );
		$others                 = explode( ',', isset( $details_roles_capabilities['other_privileges'] ) ? $details_roles_capabilities['other_privileges'] : '' );
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
					<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-user"></i>
						<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
					</div>
					<p class="premium-editions-backup-bank">
						<?php echo esc_attr( $bb_upgrade_know_about ); ?> <a href="https://tech-banker.com/backup-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_full_features ); ?></a> <?php echo esc_attr( $bb_chek_our ); ?> <a href="https://tech-banker.com/backup-bank/backend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_roles_and_capabilities">
						<div class="form-body">
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $bb_roles_capabilities_show_menu ); ?> :
								<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></span>
							</label>
							<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_backup_bank_roles">
								<thead>
									<tr>
									<th>
										<input type="checkbox"  name="ux_chk_administrator" id="ux_chk_administrator" value="1" checked="checked" disabled="disabled" <?php echo '1' === $roles_and_capabilities[0] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $bb_roles_capabilities_administrator ); ?>
									</th>
									<th>
										<input type="checkbox"  name="ux_chk_author" id="ux_chk_author"  value="1" onclick="show_roles_capabilities_backup_bank(this, 'ux_div_author_roles');" <?php echo '1' === $roles_and_capabilities[1] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $bb_roles_capabilities_author ); ?>
									</th>
									<th>
										<input type="checkbox"  name="ux_chk_editor" id="ux_chk_editor" value="1" onclick="show_roles_capabilities_backup_bank(this, 'ux_div_editor_roles');" <?php echo '1' === $roles_and_capabilities[2] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $bb_roles_capabilities_editor ); ?>
									</th>
									<th>
										<input type="checkbox"  name="ux_chk_contributor" id="ux_chk_contributor"  value="1" onclick="show_roles_capabilities_backup_bank(this, 'ux_div_contributor_roles');" <?php echo '1' === $roles_and_capabilities[3] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $bb_roles_capabilities_contributor ); ?>
									</th>
									<th>
										<input type="checkbox"  name="ux_chk_subscriber" id="ux_chk_subscriber" value="1" onclick="show_roles_capabilities_backup_bank(this, 'ux_div_subscriber_roles');" <?php echo '1' === $roles_and_capabilities[4] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $bb_roles_capabilities_subscriber ); ?>
									</th>
									<th>
										<input type="checkbox" name="ux_chk_other" id="ux_chk_other" value="1" onclick="show_roles_capabilities_backup_bank(this, 'ux_div_other_roles');" <?php echo '1' === $roles_and_capabilities[5] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $bb_roles_capabilities_other ); ?>
									</th>
								</tr>
								</thead>
							</table>
							<i class="controls-description" ><?php echo esc_attr( $bb_roles_capabilities_show_menu_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $bb_roles_capabilities_topbar_menu ); ?> :
								<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></span>
								</label>
								<select name="ux_ddl_backup_bank_menu" id="ux_ddl_backup_bank_menu" class="form-control">
								<option value="enable"><?php echo esc_attr( $bb_enable ); ?></option>
								<option value="disable"><?php echo esc_attr( $bb_disable ); ?></option>
							</select>
							<i class="controls-description"><?php echo esc_attr( $bb_roles_capabilities_topbar_menu_tooltip ); ?></i>
						</div>
						<div class="line-separator"></div>
						<div class="form-group">
							<div id="ux_div_administrator_roles">
								<label class="control-label">
									<?php echo esc_attr( $bb_roles_capabilities_administrator_role ); ?> :
									<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_administrator">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_administrator" id="ux_chk_full_control_administrator" checked="checked" disabled="disabled" value="1">
												<?php echo esc_attr( $bb_roles_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_manage_backups_admin" disabled="disabled" checked="checked" id="ux_chk_manage_backups_admin" value="1">
												<?php echo esc_attr( $bb_manage_backups ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_add_new_backup_admin" disabled="disabled" checked="checked" id="ux_chk_add_new_backup_admin" value="1">
												<?php echo esc_attr( $bb_start_backup ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_schedule_backup_admin" disabled="disabled" checked="checked" id="ux_chk_schedule_backup_admin" value="1">
												<?php echo esc_attr( $bb_schedule_backup ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_general_settings_admin" disabled="disabled" checked="checked" id="ux_chk_general_settings_admin" value="1">
												<?php echo esc_attr( $bb_general_settings ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_template_admin" disabled="disabled" checked="checked" id="ux_chk_template_admin" value="1">
												<?php echo esc_attr( $bb_email_templates ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_roles_admin" disabled="disabled" checked="checked" id="ux_chk_roles_admin" value="1">
												<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_system_information_admin" disabled="disabled" checked="checked" id="ux_chk_system_information_admin" value="1">
												<?php echo esc_attr( $bb_system_information ); ?>
											</td>
											<td>
											</td>
											<td>
											</td>
										</tr>
									</tbody>
								</table>
								<i class="controls-description"><?php echo esc_attr( $bb_roles_capabilities_administrator_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_author_roles">
								<label class="control-label">
									<?php echo esc_attr( $bb_roles_capabilities_author_role ); ?> :
									<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_author">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_author" id="ux_chk_full_control_author" value="1" onclick="full_control_function_backup_bank(this, 'ux_div_author_roles');" <?php echo isset( $author ) && '1' === $author[0] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_manage_backups_author" id="ux_chk_manage_backups_author" value="1" <?php echo isset( $author ) && '1' === $author[1] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_manage_backups ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_add_new_backup_author" id="ux_chk_add_new_backup_author" value="1" <?php echo isset( $author ) && '1' === $author[2] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_start_backup ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_schedule_backup_author" id="ux_chk_schedule_backup_author" value="1" <?php echo isset( $author ) && '1' === $author[3] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_schedule_backup ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_general_settings_author" id="ux_chk_general_settings_author" value="1" <?php echo isset( $author ) && '1' === $author[4] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_general_settings ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_templates_author" id="ux_chk_templates_author" value="1" <?php echo isset( $author ) && '1' === $author[5] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_email_templates ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_roles_author" id="ux_chk_roles_author" value="1" <?php echo isset( $author ) && '1' === $author[6] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_system_information_author" id="ux_chk_system_information_author" value="1" <?php echo isset( $author ) && '1' === $author[7] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_system_information ); ?>
											</td>
											<td>
											</td>
											<td>
											</td>
										</tr>
									</tbody>
								</table>
								<i class="controls-description"><?php echo esc_attr( $bb_roles_capabilities_author_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_editor_roles">
								<label class="control-label">
									<?php echo esc_attr( $bb_roles_capabilities_editor_role ); ?> :
									<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_editor">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_editor" id="ux_chk_full_control_editor" value="1" onclick="full_control_function_backup_bank(this, 'ux_div_editor_roles');" <?php echo isset( $editor ) && '1' === $editor[0] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_manage_backups_editor" id="ux_chk_manage_backups_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[1] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_manage_backups ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_add_new_backup_editor" id="ux_chk_add_new_backup_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[2] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_start_backup ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_schedule_backup_editor" id="ux_chk_schedule_backup_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[3] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_schedule_backup ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_general_settings_editor" id="ux_chk_general_settings_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[4] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_general_settings ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_templates_editor" id="ux_chk_templates_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[5] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_email_templates ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_roles_editor" id="ux_chk_roles_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[6] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_system_information_editor" id="ux_chk_system_information_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[7] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_system_information ); ?>
											</td>
											<td>
											</td>
											<td>
											</td>
										</tr>
									</tbody>
								</table>
								<i class="controls-description"><?php echo esc_attr( $bb_roles_capabilities_editor_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_contributor_roles">
								<label class="control-label">
									<?php echo esc_attr( $bb_roles_capabilities_contributor_role ); ?> :
									<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_contributor">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_contributor" id="ux_chk_full_control_contributor" value="1" onclick="full_control_function_backup_bank(this, 'ux_div_contributor_roles');" <?php echo isset( $contributor ) && '1' === $contributor[0] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_manage_backups_contributor" id="ux_chk_manage_backups_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[1] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_manage_backups ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_add_new_backup_contributor" id="ux_chk_add_new_backup_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[2] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_start_backup ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_schedule_backup_contributor" id="ux_chk_schedule_backup_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[3] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_schedule_backup ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_general_settings_contributor" id="ux_chk_general_settings_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[4] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_general_settings ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_templates_contributor" id="ux_chk_templates_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[5] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_email_templates ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_roles_contributor" id="ux_chk_roles_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[6] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_system_information_contributor" id="ux_chk_system_information_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[7] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_system_information ); ?>
											</td>
											<td>
											</td>
											<td>
											</td>
										</tr>
									</tbody>
								</table>
								<i class="controls-description" ><?php echo esc_attr( $bb_roles_capabilities_contributor_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_subscriber_roles">
								<label class="control-label">
									<?php echo esc_attr( $bb_roles_capabilities_subscriber_role ); ?> :
									<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_subscriber">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_subscriber" id="ux_chk_full_control_subscriber" value="1" onclick="full_control_function_backup_bank(this, 'ux_div_subscriber_roles');" <?php echo isset( $subscriber ) && '1' === $subscriber[0] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_manage_backups_subscriber" id="ux_chk_manage_backups_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[1] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_manage_backups ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_add_new_backup_subscriber" id="ux_chk_add_new_backup_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[2] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_start_backup ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_schedule_backup_subscriber" id="ux_chk_schedule_backup_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[3] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_schedule_backup ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_general_settings_subscriber" id="ux_chk_general_settings_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[4] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_general_settings ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_templates_subscriber" id="ux_chk_templates_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[5] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_email_templates ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_roles_subscriber" id="ux_chk_roles_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[6] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_system_information_subscriber" id="ux_chk_system_information_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[7] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_system_information ); ?>
											</td>
											<td>
											</td>
											<td>
											</td>
										</tr>
									</tbody>
								</table>
								<i class="controls-description"><?php echo esc_attr( $bb_roles_capabilities_subscriber_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_other_roles">
								<label class="control-label">
									<?php echo esc_attr( $bb_roles_capabilities_other_role ); ?> :
									<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_other">
									<thead>
											<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_other" id="ux_chk_full_control_other" value="1" onclick="full_control_function_backup_bank(this, 'ux_div_other_roles');" <?php echo isset( $others ) && '1' === $others[0] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_manage_backups_other" id="ux_chk_manage_backups_other" value="1" <?php echo isset( $others ) && '1' === $others[1] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_manage_backups ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_add_new_backup_other" id="ux_chk_add_new_backup_other" value="1" <?php echo isset( $others ) && '1' === $others[2] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_start_backup ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_schedule_backup_other" id="ux_chk_schedule_backup_other" value="1" <?php echo isset( $others ) && '1' === $others[3] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_schedule_backup ); ?>
											</td>
										</tr>
											<tr>
											<td>
												<input type="checkbox" name="ux_chk_general_settings_other" id="ux_chk_general_settings_other" value="1" <?php echo isset( $others ) && '1' === $others[4] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_general_settings ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_templates_other" id="ux_chk_templates_other" value="1" <?php echo isset( $others ) && '1' === $others[5] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_email_templates ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_roles_other" id="ux_chk_roles_other" value="1" <?php echo isset( $others ) && '1' === $others[6] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_system_information_other" id="ux_chk_system_information_other" value="1" <?php echo isset( $others ) && '1' === $others[7] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_system_information ); ?>
											</td>
											<td>
											</td>
											<td>
											</td>
										</tr>
									</tbody>
								</table>
								<i class="controls-description" ><?php echo esc_attr( $bb_roles_capabilities_other_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_other_roles_capabilities">
								<label class="control-label">
									<?php echo esc_attr( $bb_roles_and_capabilities_other_roles_capabilities ); ?> :
									<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_other_roles">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_other_roles" id="ux_chk_full_control_other_roles" value="1" onclick="full_control_function_backup_bank(this, 'ux_div_other_roles_capabilities');" <?php echo '1' === $details_roles_capabilities['others_full_control_capability'] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $bb_roles_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$flag              = 0;
										$user_capabilities = get_others_capabilities_backup_bank();
										if ( isset( $user_capabilities ) && count( $user_capabilities ) > 0 ) {
											foreach ( $user_capabilities as $key => $value ) {
												$other_roles = in_array( $value, $other_roles_array, true ) ? 'checked=checked' : '';
												$flag++;
												if ( 0 === $key % 3 ) {
													?>
													<tr>
													<?php
												}
												?>
												<td>
														<input type="checkbox" name="ux_chk_other_capabilities_<?php echo esc_attr( $value ); ?>" id="ux_chk_other_capabilities_<?php echo esc_attr( $value ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo esc_attr( $other_roles ); ?>>
													<?php echo esc_attr( $value ); ?>
												</td>
												<?php
												if ( count( $user_capabilities ) === $flag && 1 === $flag % 3 ) {
													?>
													<td>
													</td>
													<td>
													</td>
													<?php
												}
												?>
												<?php
												if ( count( $user_capabilities ) === $flag && 2 === $flag % 3 ) {
													?>
													<td>
													</td>
													<?php
												}
												?>
												<?php
												if ( 0 === $flag % 3 ) {
													?>
												</tr>
													<?php
												}
											}
										}
										?>
									</tbody>
									</table>
									<i class="controls-description"><?php echo esc_attr( $bb_roles_and_capabilities_other_roles_capabilities_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
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
				<span>
					<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-user"></i>
						<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
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
