<?php
/**
 * This Template is used for creating new backups.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/views/backups
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
	} elseif ( MANUAL_BACKUP_BANK === '1' ) {

		$backup_bank_manual_backup          = wp_create_nonce( 'backup_bank_manual_backup' );
		$backup_bank_check_cloud_connection = wp_create_nonce( 'backup_bank_check_ftp_dropbox_connection' );
		$local_folder_destination           = str_replace( BACKUP_BANK_CONTENT_DIR, '', BACKUP_BANK_BACKUPS_DATE_DIR );
		$content_folder_destination         = str_replace( '\\', '/', BACKUP_BANK_CONTENT_DIR );
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
					<a href="admin.php?page=bb_manage_backups">
						<?php echo esc_attr( $bb_backups ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<span>
						<?php echo esc_attr( $bb_start_backup ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-note"></i>
							<?php echo esc_attr( $bb_start_backup ); ?>
						</div>
						<p class="premium-editions-backup-bank">
							<?php echo esc_attr( $bb_upgrade_know_about ); ?> <a href="https://tech-banker.com/backup-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_full_features ); ?></a> <?php echo esc_attr( $bb_chek_our ); ?> <a href="https://tech-banker.com/backup-bank/backend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_online_demos ); ?></a>
						</p>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_add_new_backup">
							<input type="hidden" name="ux_txt_archive_name" id="ux_txt_archive_name" value="backup_%Y-%m-%d_%H-%i-%s">
							<input  type="hidden" id="archivename" name="archivename"/>
							<input id="archive_name_hidden" type="hidden" name="archive_name_hidden"/>
							<input type="hidden" name="ux_txt_content_location" id="ux_txt_content_location" value="<?php echo esc_attr( $content_folder_destination ); ?>" />
							<input type="hidden" name="ux_txt_folder_location" id="ux_txt_folder_location"/>
							<div class="form-body">
								<div class="form-actions">
									<div class="pull-right">
										<input type="submit" class="btn vivid-green ux_btn_generate_backup" name="ux_btn_generate_backup" id="ux_btn_generate_backup" value="<?php echo esc_attr( $bb_start_backup ); ?>">
									</div>
								</div>
								<div class="line-separator"></div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">
												<?php echo esc_attr( $bb_backup_type ); ?> :
												<span class="required" aria-required="true">*</span>
											</label>
											<input type="hidden" name="ux_txt_backup_type" id="ux_txt_backup_type" class="form-control" value="<?php echo esc_attr( $local_folder_destination ); ?>">
											<select name="ux_ddl_backup_type" id="ux_ddl_backup_type" class="form-control" onchange="backup_type_backup_bank();">
												<option value="complete_backup"><?php echo esc_attr( $bb_complete_backup ); ?></option>
												<option value="only_database"><?php echo esc_attr( $bb_only_database ); ?></option>
												<option value="only_filesystem"><?php echo esc_attr( $bb_only_filesystem ); ?></option>
												<option value="only_plugins_and_themes"><?php echo esc_attr( $bb_only_plugins_and_themes ); ?></option>
												<option value="only_themes"><?php echo esc_attr( $bb_only_themes ); ?></option>
												<option value="only_plugins"><?php echo esc_attr( $bb_only_plugins ); ?></option>
												<option value="only_wp_content_folder"><?php echo esc_attr( $bb_wp_content_folder ); ?></option>
											</select>
											<i class="controls-description" ><?php echo esc_attr( $bb_backup_type_tooltip ); ?></i>
										</div>
									</div>
								</div>
								<div id="ux_div_exclude_list">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $bb_exclude_list ); ?> :
										</label>
										<input type="text" class="form-control" name="ux_txt_return_email" id="ux_txt_return_email" value=".svn-base, .git, .ds_store" placeholder="<?php echo esc_attr( $bb_exclude_list_placeholder ); ?>">
										<i class="controls-description"><?php echo esc_attr( $bb_exclude_list_tooltip ); ?></i>
									</div>
								</div>
								<div id="ux_div_file_compression_type">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $bb_file_compression ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<select name="ux_ddl_file_compression_type" id="ux_ddl_file_compression_type" class="form-control" onchange="file_compression_backup_bank();">
											<option value=".tar">.tar</option>
											<option value=".tar.gz" <?php echo ! extension_loaded( 'zlib' ) ? 'disabled = disabled style=color:#FF0000' : ''; ?> > <?php echo extension_loaded( 'zlib' ) ? '.tar.gz' : '.tar.gz' . esc_attr( $bb_extention_not_found ); ?></option>
											<option value=".tar.bz2" disabled = "disabled" style="color:#FF0000" > <?php echo extension_loaded( 'bz2' ) ? '.tar.bz2' : '.tar.bz2' . esc_attr( $bb_extention_not_found ); ?> ( <?php echo esc_attr( $bb_premium_editions ); ?> ) </option>
											<option value=".zip">.zip</option>
										</select>
										<i class="controls-description"><?php echo esc_attr( $bb_file_compression_tooltip ); ?></i>
									</div>
								</div>
								<div id="ux_div_db_compression_type">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $bb_db_compression ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<select name="ux_ddl_db_compression_type" id="ux_ddl_db_compression_type" class="form-control" onchange="db_compression_backup_bank();">
											<option value=".sql">.sql</option>
											<option value=".sql.gz" <?php echo ! extension_loaded( 'zlib' ) ? 'disabled = disabled style=color:#FF0000' : ''; ?> > <?php echo extension_loaded( 'zlib' ) ? '.sql.gz' : '.sql.gz' . esc_attr( $bb_extention_not_found ); ?></option>
											<option value=".sql.bz2" disabled = "disabled" style="color:#FF0000" > <?php echo extension_loaded( 'bz2' ) ? '.sql.bz2' : '.sql.bz2' . esc_attr( $bb_extention_not_found ); ?></option>
											<option value=".sql.zip">.sql.zip</option>
										</select>
										<i class="controls-description" ><?php echo esc_attr( $bb_db_compression_tooltip ); ?></i>
									</div>
								</div>
								<div id="ux_div_backup_tables">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $bb_backup_tables ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_database_tables">
											<thead>
												<tr>
													<th style="width: 5%;text-align:center;">
														<input type="checkbox" id="ux_chk_select_all_first" value="0" checked="checked" name="ux_chk_select_all_first">
													</th>
													<th>
														<?php echo esc_attr( $bb_table_names ); ?>
													</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if ( is_array( $result ) ) {
													for ( $flag = 0; $flag < count( $result ); $flag++ ) { // @codingStandardsIgnoreLine
														if ( 0 === $flag % 2 ) {
															?>
															<tr>
																<td style="text-align:center;">
																	<input type="checkbox" class="all_check_backup_tables" id="ux_chk_add_new_backup_db_<?php echo intval( $flag ); ?>" name="ux_chk_add_new_backup_db[]" checked="checked" value="<?php echo esc_attr( $result[ $flag ] ); ?>">
																</td>
																<td class="custom-manual-td">
																	<label style="font-size:13px;"><?php echo esc_attr( $result[ $flag ] ); ?></label>
																</td>
															<?php
														} else {
															?>
																<td style="text-align:center;">
																	<input type="checkbox"  class="all_check_backup_tables" checked="checked" id="ux_chk_add_new_backup_db_<?php echo intval( $flag ); ?>" name="ux_chk_add_new_backup_db[]" value="<?php echo esc_attr( $result[ $flag ] ); ?>">
																</td>
																<td class="custom-manual-td">
																	<label style="font-size:13px;"><?php echo esc_attr( $result[ $flag ] ); ?></label>
																</td>
															</tr>
															<?php
														}
														if ( $flag == count( $result ) - 1 && 0 === $flag % 2 ) { // @codingStandardsIgnoreLine
															?>
														<td style="width: 5%;text-align:center;">
														</td>
														<td class="custom-manual-td">
																<label></label>
														</td>
															<?php
														}
													}
												}
												?>
											</tbody>
										</table>
										<i class="controls-description" ><?php echo esc_attr( $bb_table_names ); ?></i>
									</div>
								</div>
								<div id="ux_div_backup_destination">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $bb_backup_destination ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<div class="row" style="margin-top: 10px;">
											<div class="col-md-4">
												<input type="radio" name="ux_rdl_backup_destination_type" id="ux_rdl_bb_local_folder" class="form-control" checked = checked onclick="backup_destination_backup_bank();" value="local_folder"><?php echo esc_attr( $bb_local_folder ); ?>
											</div>
											<div class="col-md-4">
												<input type="radio" name="ux_rdl_backup_destination_type" class="form-control" id="ux_rdl_bb_amazons3" onclick="backup_destination_backup_bank();" value="amazons3" disabled = "disabled"> <?php echo esc_attr( $bb_amazons3 ) . '<span style="color: red;"> ( ' . esc_attr( $bb_premium_editions ) . ' ) </span>'; ?>
											</div>
											<div class="col-md-4">
												<input type="radio" name="ux_rdl_backup_destination_type" class="form-control" id="ux_rdl_bb_dropbox" onclick="backup_destination_backup_bank();"  value="dropbox" <?php echo 'enable' !== $settings_data_array['backup_to_dropbox'] ? 'disabled = disabled' : ''; ?>> <?php echo esc_attr( $bb_dropbox ); ?><?php echo 'enable' !== $settings_data_array['backup_to_dropbox'] ? ' ( <a href="admin.php?page=bb_dropbox_settings" style="color:#FF0000;"> ' . esc_attr( $bb_configure ) . '</a> )' : ''; ?>
											</div>
										</div>
										<div class="row" style="margin-top: 10px;">
											<div class="col-md-4">
												<input type="radio" name="ux_rdl_backup_destination_type" id="ux_rdl_bb_email" class="form-control" onclick="backup_destination_backup_bank();" value="email" <?php echo 'enable' !== $settings_data_array['backup_to_email'] ? 'disabled = disabled' : ''; ?>> <?php echo 'enable' !== $settings_data_array['backup_to_email'] ? esc_attr( $bb_email ) . ' ( <a href="admin.php?page=bb_email_settings" style="color:#FF0000;"> ' . esc_attr( $bb_configure ) . '</a> )' : esc_attr( $bb_email ); ?>
											</div>
											<div class="col-md-4">
												<input type="radio" name="ux_rdl_backup_destination_type" class="form-control" id="ux_rdl_bb_ftp" onclick="backup_destination_backup_bank();" value="ftp" <?php echo 'enable' !== $settings_data_array['backup_to_ftp'] ? 'disabled = disabled' : ''; ?>> <?php echo 'enable' !== $settings_data_array['backup_to_ftp'] ? esc_attr( $bb_ftp ) . ' ( <a href="admin.php?page=bb_ftp_settings" style="color:#FF0000;">' . esc_attr( $bb_configure ) . '</a> )' : esc_attr( $bb_ftp ); ?>
											</div>
											<div class="col-md-4">
												<input type="radio" name="ux_rdl_backup_destination_type" class="form-control" id="ux_rdl_bb_google_drive" onclick="backup_destination_backup_bank();" value="google_drive" <?php echo 'enable' !== $settings_data_array['backup_to_google_drive'] ? 'disabled = disabled' : ''; ?>> <?php echo 'enable' !== $settings_data_array['backup_to_google_drive'] ? esc_attr( $bb_google_drive_settings ) . ' ( <a href="admin.php?page=bb_google_drive" style="color:#FF0000;">' . esc_attr( $bb_configure ) . '</a> )' : esc_attr( $bb_google_drive_settings ); ?>
											</div>
										</div>
										<div class="row" style="margin-top: 10px;">
											<div class="col-md-4">
												<input type="radio" name="ux_rdl_backup_destination_type" id="ux_rdl_bb_onedrive" class="form-control" onclick="backup_destination_backup_bank();" value= "onedrive" disabled = "disabled" > <?php echo esc_attr( $bb_onedrive ) . '<span style="color: red;"> ( ' . esc_attr( $bb_premium_editions ) . ' ) </span>'; ?>
											</div>
											<div class="col-md-4">
												<input type="radio" name="ux_rdl_backup_destination_type" class="form-control" id="ux_rdl_bb_rackspace" onclick="backup_destination_backup_bank();" value="rackspace" disabled = "disabled" > <?php echo esc_attr( $bb_rackspace ) . '<span style="color: red;"> ( ' . esc_attr( $bb_premium_editions ) . ' ) </span>'; ?>
											</div>
											<div class="col-md-4">
												<input type="radio" name="ux_rdl_backup_destination_type" class="form-control" id="ux_rdl_bb_azure" onclick="backup_destination_backup_bank();" value="azure" disabled = "disabled" > <?php echo esc_attr( $bb_ms_azure ) . '<span style="color: red;"> ( ' . esc_attr( $bb_premium_editions ) . ' ) </span>'; ?>
											</div>
										</div>
									</div>
								</div>
								<i class="controls-description"><?php echo esc_attr( $bb_backup_destination_tooltip ); ?></i>
								<div class="line-separator"></div>
								<div class="form-actions">
									<div class="pull-right">
										<input type="submit" class="btn vivid-green ux_btn_generate_backup" name="ux_btn_generate_backup" id="ux_btn_generate_backup" value="<?php echo esc_attr( $bb_start_backup ); ?>">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="ux_div_portlet_progress" style="display:none;">
			<div id="ux_div_progressbar">
				<div class="progress-bar-position">
					<div class="portlet-progress-bar">
						A Backup is on the way!
						<?php
						if ( ! is_rtl() ) {
							?>
							<span style="float:right;">
								<span id="ux_hrs" class="tech-banker-counter">00</span> <span id="ux_collon" class="tech-banker-counter">:</span>
								<span id="ux_mins">00</span> :
								<span id="ux_secs">00</span>
							</span>
							<?php
						} else {
							?>
						<span style="float:left;">
							<span id="ux_secs">00</span> :
							<span id="ux_mins">00</span> <span id="ux_collon" class="tech-banker-counter">:</span>
							<span id="ux_hrs" class="tech-banker-counter">00</span>
						</span>
							<?php
						}
						?>
					</div>
					<div id="progress" class="progress-bar-width">
						<div id="progress_status" style="width:1%;max-width: 100%;color:#fff;background-color:#a4cd39;text-align: center;">
							1%
						</div>
					</div>
					<div id="uploading_progress" class="tech-banker-counter">
						<div id="upload_progress" class="progress-bar-width">
							<div id="uploaded_status" style="width:1%;max-width: 100%;color:#fff;background-color:#a4cd39;text-align: center;">
								1%
							</div>
						</div>
					</div>
					<div id="information" class="progress-info">
						Starting Backup
					</div>
					<div class="portlet-progress-message">
						<p>
							* Please do not <u>Cancel</u> or <u>Refresh</u> the Page to avoid Termination of the Backup.<br/>
							* Kindly be Patient!
						</p>
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
					<a href="admin.php?page=bb_manage_backups">
						<?php echo esc_attr( $bb_backups ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<span>
						<?php echo esc_attr( $bb_start_backup ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-note"></i>
							<?php echo esc_attr( $bb_start_backup ); ?>
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
