<?php
/**
 * This Template is used for displaying generated backups and restore them.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/views/backups
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
	} elseif ( MANAGE_BACKUPS_BACKUP_BANK === '1' ) {
		$backup_bank_manage_backups_delete  = wp_create_nonce( 'backup_bank_manage_backups_delete' );
		$backup_bank_manage_backups         = wp_create_nonce( 'backup_bank_manage_backups' );
		$backup_bank_manage_rerun_backups   = wp_create_nonce( 'backup_bank_manage_rerun_backups' );
		$backup_bank_restore_message        = wp_create_nonce( 'backup_bank_restore_message' );
		$backup_bank_check_cloud_connection = wp_create_nonce( 'backup_bank_check_ftp_dropbox_connection_rerun' );
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
						<?php echo esc_attr( $bb_manage_backups ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-folder-alt"></i>
							<?php echo esc_attr( $bb_manage_backups ); ?>
						</div>
						<p class="premium-editions-backup-bank">
							<?php echo esc_attr( $bb_upgrade_know_about ); ?> <a href="https://tech-banker.com/backup-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_full_features ); ?></a> <?php echo esc_attr( $bb_chek_our ); ?> <a href="https://tech-banker.com/backup-bank/backend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $bb_online_demos ); ?></a>
						</p>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_manage_backups">
							<div class="form-body">
								<div class="table-top-margin">
									<select name="ux_ddl_manage_backups" id="ux_ddl_manage_backups" class="custom-bulk-width">
										<option value=""><?php echo esc_attr( $bb_manage_backups_bulk_action ); ?></option>
										<option value="delete" style="color: red;"><?php echo esc_attr( $bb_manage_backups_delete ) . ' ( ' . esc_attr( $bb_premium_editions ) . ' )'; ?></option>
									</select>
									<input type="button" class="btn vivid-green" name="ux_btn_apply" id="ux_btn_apply" value="<?php echo esc_attr( $bb_manage_backups_apply ); ?>" onclick="premium_edition_notification_backup_bank()">
									<a href="admin.php?page=bb_start_backup" class="btn vivid-green" name="ux_btn_manual_backup" id="ux_btn_manual_backup"> <?php echo esc_attr( $bb_start_backup ); ?></a>
									<a href="admin.php?page=bb_schedule_backup" class="btn vivid-green" name="ux_btn_schedule_backup" id="ux_btn_schedule_backup"> <?php echo esc_attr( $bb_manage_backups_schedule_backup_btn ); ?></a>
									<input type="button" class="btn btn-danger" name="ux_btn_purge_backups" id="ux_btn_purge_backups" value="<?php echo esc_attr( $bb_manage_backups_purge_backups ); ?>" onclick="premium_edition_notification_backup_bank();">
								</div>
								<div class="line-separator"></div>
								<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_manage_backups">
									<thead>
										<tr>
											<th style="text-align: center;" class="chk-action">
												<input type="checkbox" name="ux_chk_all_manage_backups" id="ux_chk_all_manage_backups">
											</th>
											<th style="width:33%">
												<label>
													<?php echo esc_attr( $bb_backup_details ); ?>
												</label>
											</th>
											<th style="width:23%">
												<label>
													<?php echo esc_attr( $bb_last_execution ); ?>
												</label>
											</th>
											<th style="width:24%">
												<label>
													<?php echo esc_attr( $bb_next_execution ); ?>
												</label>
											</th>
											<th style="width:20%">
												<label>
													<?php echo esc_attr( $bb_manage_backups_last_status ); ?>
												</label>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ( isset( $bb_backups_unserialized_data ) && count( $bb_backups_unserialized_data ) > 0 ) {
											foreach ( $bb_backups_unserialized_data as $value ) {
												$backup_archive_time = '';
												$backup_log_time     = '';
												if ( 'scheduled' === $value['execution'] ) {
													$archives_array    = maybe_unserialize( $value['archive'] );
													$pop_archive_array = array_pop( $archives_array );
													$archive_array     = array_reverse( $archives_array );
													$logs_array        = maybe_unserialize( $value['log_filename'] );
													$pop_log_array     = array_pop( $logs_array );
													$log_array         = array_reverse( $logs_array );
													$count_array       = count( $archives_array );
													if ( isset( $value['execution_time'] ) ) {
														$backup_time_array   = maybe_unserialize( $value['execution_time'] );
														$backup_time         = array_reverse( $backup_time_array );
														$backup_archive_time = @array_combine( $archive_array, $backup_time ); // @codingStandardsIgnoreLine.
														$backup_log_time     = @array_combine( $log_array, $backup_time ); // @codingStandardsIgnoreLine.
													}
												} else {
													$log_array     = maybe_unserialize( $value['log_filename'] );
													$archive_array = maybe_unserialize( $value['archive'] );
													$count_array   = count( $archive_array );
													if ( isset( $value['execution_time'] ) ) {
														$backup_time         = maybe_unserialize( $value['execution_time'] );
														$backup_archive_time = array_combine( $archive_array, $backup_time );
														$backup_log_time     = array_combine( $log_array, $backup_time );
													}
												}
												$count_log_array  = count( $log_array );
												$restore_log_time = '';
												if ( isset( $value['restore_log_urlpath'] ) ) {
													$restore_logs_array = maybe_unserialize( $value['restore_log_urlpath'] );
													$restore_log_array  = array_reverse( $restore_logs_array );
													$restore_time_array = maybe_unserialize( $value['restore_execution_time'] );
													$restore_time       = array_reverse( $restore_time_array );
													$restore_log_time   = array_combine( $restore_log_array, $restore_time );
												}
												$upload_status = 0;
												if ( 'uploading_to_ftp' === $value['status'] || 'uploading_to_email' === $value['status'] || 'uploading_to_dropbox' === $value['status'] ||
												'uploading_to_onedrive' === $value['status'] || 'uploading_to_rackspace' === $value['status'] || 'uploading_to_azure' === $value['status'] || 'uploading_to_google_drive' === $value['status'] ) {
													$upload_status = 1;
												}
												?>
												<tr>
													<td style="text-align: center;">
														<input type="checkbox" name="ux_chk_manage_backups_<?php echo intval( $value['meta_id'] ); ?>" id="ux_chk_manage_backups_<?php echo intval( $value['meta_id'] ); ?>" onclick="check_all_manage_backups(<?php echo intval( $value['meta_id'] ); ?>)" value="<?php echo intval( $value['meta_id'] ); ?>">
													</td>
													<td>
														<label class="control-label">
															<strong>
																<?php echo esc_attr( $bb_backup_name ); ?> :
															</strong>
															<?php echo esc_html( $value['backup_name'] ); ?>
														</label><br>
														<label class="control-label">
															<strong>
																<?php echo esc_attr( $bb_backup_type ); ?> :
															</strong>
															<?php
															switch ( $value['backup_type'] ) {
																case 'complete_backup':
																	echo esc_attr( $bb_complete_backup );
																	break;

																case 'only_database':
																	echo esc_attr( $bb_database );
																	break;

																case 'only_filesystem':
																	echo esc_attr( $bb_filesystem );
																	break;

																case 'only_plugins_and_themes':
																	echo esc_attr( $bb_plugins_themes );
																	break;

																case 'only_themes':
																	echo esc_attr( $bb_themes );
																	break;

																case 'only_plugins':
																	echo esc_attr( $bb_plugins );
																	break;

																case 'only_wp_content_folder':
																	echo esc_attr( $bb_contents );
																	break;
															}
															?>
														</label><br>
														<label class="control-label">
															<strong>
																<?php echo esc_attr( $bb_backup_destination ); ?> :
															</strong>
															<?php
															switch ( $value['backup_destination'] ) {
																case 'local_folder':
																	echo esc_attr( $bb_local_folder );
																	break;

																case 'dropbox':
																	echo esc_attr( $bb_dropbox );
																	break;

																case 'email':
																	echo esc_attr( $bb_email );
																	break;

																case 'ftp':
																	echo esc_attr( $bb_ftp );
																	break;

																case 'onedrive':
																	echo esc_attr( $bb_onedrive );
																	break;

																case 'google_drive':
																	echo esc_attr( $bb_google_drive_settings );
																	break;

																case 'amazons3':
																	echo esc_attr( $bb_amazons3 );
																	break;

																case 'rackspace':
																	echo esc_attr( $bb_rackspace );
																	break;

																case 'azure':
																	echo esc_attr( $bb_ms_azure );
																	break;
															}
															?>
														</label><br>
														<label class="control-label">
															<strong>
																<?php echo esc_attr( $bb_compression_type ); ?> :
															</strong>
															<?php
															if ( 'only_database' === $value['backup_type'] ) {
																echo esc_html( $value['db_compression_type'] );
															} else {
																echo esc_html( $value['file_compression_type'] );
															}
															?>
														</label><br>
														<label class="control-label">
															<strong>
																<?php echo esc_attr( $bb_manage_backups_execution ); ?> :
															</strong>
															<?php
															switch ( $value['execution'] ) {
																case 'manual':
																	echo esc_attr( $bb_manage_backups_execution_manual );
																	break;

																case 'scheduled':
																	echo esc_attr( $bb_manage_backups_execution_scheduled );
																	break;
															}
															?>
														</label><br>
														<label class="control-label">
															<strong>
																<?php echo esc_attr( $bb_backup_executed_in ); ?> :
															</strong>
															<?php
															if ( 'completed_successfully' === $value['status'] || 'amazons3_upload_failed' === $value['status'] || 'dropbox_backup_not_sent' === $value['status'] || 'azure_backup_not_sent' === $value['status'] || 'rackspace_backup_not_sent' === $value['status'] || 'onedrive_backup_not_sent' === $value['status'] || 'email_not_sent' === $value['status'] ||
															'google_upload_failed' === $value['status'] || 'restored_successfully' === $value['status'] || ( 'scheduled' === $value['execution'] && $count_array >= 1 ) || $upload_status > 0 ) {
																echo date( 'H:i:s', esc_html( $value['executed_in'] ) ); // WPCS:XSS ok.
															} else {
																echo $bb_na; // WPCS:XSS ok.
															}
															?>
														</label><br>
														<label class="control-label">
															<strong>
																<?php echo esc_attr( $bb_backup_total_size ); ?> :
															</strong>
															<?php
															if ( 'completed_successfully' === $value['status'] || 'amazons3_upload_failed' === $value['status'] || 'dropbox_backup_not_sent' === $value['status'] || 'azure_backup_not_sent' === $value['status'] || 'rackspace_backup_not_sent' === $value['status'] ||
															'onedrive_backup_not_sent' === $value['status'] || 'email_not_sent' === $value['status'] || 'google_upload_failed' === $value['status'] || 'restored_successfully' === $value['status'] ||
															( 'scheduled' === $value['execution'] && $count_array >= 1 ) || $upload_status > 0 ) {
																echo esc_html( $value['total_size'] );
															} else {
																echo $bb_na; // WPCS:XSS ok.
															}
															?>
														</label><br>
														<label class="custom-alternative">
															<?php
															if ( 'manual' === $value['execution'] && 'running' !== $value['status'] ) {
																$file_name = implode( '', maybe_unserialize( $value['archive_name'] ) );
																?>
																<a href="javascript:void(0);">
																<i class="icon-custom-reload tooltips" data-original-title="<?php echo esc_attr( $bb_manage_rerun_backup ); ?>" onclick="rerun_backup_bank(<?php echo intval( $value['meta_id'] ); ?>, '<?php echo esc_attr( $value['backup_destination'] ); ?>', '<?php echo esc_attr( $file_name ); ?>', '<?php echo esc_attr( $value['folder_location'] ); ?>')" data-placement="top"></i>
																</a> |
																<?php
															}
															if ( 'completed_successfully' === $value['status'] || 'amazons3_upload_failed' === $value['status'] || 'dropbox_backup_not_sent' === $value['status'] || 'azure_backup_not_sent' === $value['status'] || 'onedrive_backup_not_sent' === $value['status'] || 'restored_successfully' === $value['status'] || 'email_not_sent' === $value['status'] ||
															'rackspace_backup_not_sent' === $value['status'] || 'google_upload_failed' === $value['status'] || 'restore_terminated' === $value['status'] || isset( $value['old_backup'] ) || ( 'scheduled' === $value['execution'] && $count_array >= 1 ) || $upload_status > 0 ) {
													?>
																<a href="javascript:void(0);"  data-popup-open="ux_open_popup" onclick="show_download_backup_bank(<?php echo intval( $value['meta_id'] ); ?>);">
																	<i class="icon-custom-arrow-down tooltips" data-original-title="<?php echo esc_attr( $bb_manage_download_backup ); ?>" data-placement="top"></i>
																</a> |
																<?php
																if ( ! isset( $value['old_backup'] ) ) {
																	?>
																	<a href="javascript:void(0);" data-popup-open="ux_open_popup" onclick="show_restore_backup_bank(<?php echo intval( $value['meta_id'] ); ?>);">
																		<i class="icon-custom-share-alt tooltips" data-original-title="<?php echo esc_attr( $bb_manage_backups_tooltip ); ?>" data-placement="top"></i>
																	</a> |
																	<?php
																}
															}
															if ( ( ( 'not_yet_executed' !== $value['status'] && 'running' !== $value['status'] || isset( $value['old_backup'] ) || ( 'scheduled' === $value['execution'] && $count_array >= 1 ) ) ) && $count_log_array > 0 ) {
																?>
																<a href="javascript:void(0);" data-popup-open="ux_open_popup" onclick="show_download_log_backup_bank(<?php echo intval( $value['meta_id'] ); ?>);">
																<i class="icon-custom-login tooltips" data-original-title="<?php echo esc_attr( $bb_manage_download_log_file ); ?>" data-placement="top"></i>
																</a> |
																<?php
															}
															?>
															<a href="javascript:void(0);">
																<i class="icon-custom-trash tooltips" data-original-title="<?php echo esc_attr( $bb_manage_backups_delete ); ?>" onclick="delete_backup_logs(<?php echo intval( $value['meta_id'] ); ?>)" data-placement="top"></i>
															</a>
														</label><br>
														<select name="ux_ddl_download_type_<?php echo intval( $value['meta_id'] ); ?>" id="ux_ddl_download_type_<?php echo intval( $value['meta_id'] ); ?>" class="form-control" style="display:none;">
															<option value=""><?php echo esc_attr( $bb_choose_backup ); ?></option>
															<?php
															if ( '' !== $backup_archive_time ) {
																foreach ( $backup_archive_time as $key => $data ) {
																	?>
																	<option value="<?php echo trailingslashit( $value['backup_urlpath'] ) . $key; // WPCS:XSS ok. ?>"><?php echo $bb_manage_backup_on . date( 'd M Y h:i A', $data ) . ' (' . $key . ')'; // WPCS:XSS ok. ?></option>
																	<?php
																}
															}
															?>
														</select>
														<select name="ux_ddl_download_log_<?php echo intval( $value['meta_id'] ); ?>" id="ux_ddl_download_log_<?php echo intval( $value['meta_id'] ); ?>" class="form-control" style="display:none;">
															<option value=""><?php echo esc_attr( $bb_choose_log_file ); ?></option>
															<?php
															if ( is_array( $restore_log_time ) ) {
																foreach ( $restore_log_time as $key => $data ) {
																	?>
																	<option value="<?php echo esc_attr( $key ); ?>"><?php echo $bb_manage_backup_restore_on . date( 'd M Y h:i A', $data ) . ' (' . basename( $key ) . ')'; // WPCS:XSS ok. ?></option>
																	<?php
																}
															}
															if ( isset( $value['old_backup_logfile'] ) ) {
																if ( isset( $backup_time ) && count( $backup_time ) > 0 ) {
																	foreach ( $backup_time as $time ) {
																		?>
																		<option value="<?php echo esc_attr( $value['old_backup_logfile'] ); ?>"><?php echo $bb_manage_backup_on . date( 'd M Y h:i A', $time ) . ' (' . basename( $value['old_backup_logfile'] ) . ')'; // WPCS:XSS ok. ?></option>
																		<?php
																	}
																}
															} else {
																if ( '' !== $backup_log_time ) {
																	foreach ( $backup_log_time as $key => $data ) {
																		?>
																		<option value="<?php echo trailingslashit( $value['backup_urlpath'] ) . $key; // WPCS:XSS ok. ?>"><?php echo $bb_manage_backup_on . date( 'd M Y h:i A', $data ) . ' (' . $key . ')'; // WPCS:XSS ok. ?></option>
																		<?php
																	}
																}
															}
															?>
														</select>
													</td>
													<td>
														<label class="control-label">
															<?php
															switch ( $value['status'] ) {
																case 'not_yet_executed':
																	echo esc_attr( $bb_manage_backups_status_not_yet );
																	break;

																default:
																	echo date_i18n( 'd M, Y h:i A', $value['executed_time'] ); // WPCS:XSS ok.
															}
															?>
														</label><br>
													</td>
													<td>
														<?php
														if ( 'manual' === $value['execution'] ) {
															echo $bb_na; // WPCS:XSS ok.
														} else {
															$schedule_name  = 'backup_scheduler_' . $value['meta_id'];
															$next_execution = get_backup_bank_schedule_time( $schedule_name );

															switch ( $next_execution ) {
																case '':
																	echo $bb_na; // WPCS:XSS ok.
																	break;

																default:
																	$current_offset = get_option( 'gmt_offset' ) * 60 * 60;
																	echo date_i18n( 'd M, Y h:i A', $next_execution + $current_offset ) . '<br/> In About ' . human_time_diff( $next_execution ); // WPSC:XSS ok.
																	break;
															}
														}
														?>
													</td>
													<td>
														<?php
														switch ( $value['status'] ) {
															case 'not_yet_executed':
																echo esc_attr( $bb_manage_backups_status_not_yet );
																break;

															case 'file_exists':
															case 'terminated':
																echo esc_attr( $bb_manage_backups_terminated );
																break;

															case 'google_upload_failed':
															case 'rackspace_backup_not_sent':
															case 'onedrive_backup_not_sent':
															case 'amazons3_upload_failed':
															case 'dropbox_backup_not_sent':
															case 'azure_backup_not_sent':
															case 'email_not_sent':
															case 'completed_successfully':
																echo esc_attr( $bb_manage_backups_completed_successfully );
																break;

															case 'restored_successfully':
																echo esc_attr( $bb_manage_backups_restored_successfully );
																break;

															case 'restore_terminated':
																echo esc_attr( $bb_manage_backups_restore_terminated );
																break;

															case 'completed':
															case 'running':
																echo esc_attr( $bb_manage_backups_status_running );
																break;

															case 'uploading_to_email':
																echo esc_attr( $bb_manage_backups_uploading_to_email );
																break;

															case 'uploading_to_ftp':
																echo esc_attr( $bb_manage_backups_uploading_to_ftp );
																break;

															case 'uploading_to_dropbox':
																echo esc_attr( $bb_manage_backups_uploading_to_dropbox );
																break;

															case 'uploading_to_onedrive':
																echo esc_attr( $bb_manage_backups_uploading_to_onedrive );
																break;

															case 'uploading_to_google_drive':
																echo esc_attr( $bb_manage_backups_uploading_to_google_drive );
																break;

															case 'uploading_to_amazons3':
																echo esc_attr( $bb_manage_backups_uploading_to_amazons3 );
																break;

															case 'uploading_to_rackspace':
																echo esc_attr( $bb_manage_backups_uploading_to_rackspace );
																break;

															case 'uploading_to_azure':
																echo esc_attr( $bb_manage_backups_uploading_to_azure );
																break;
														}
														?>
													</td>
												</tr>
												<?php
											}
										}
										?>
									</tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="popup" data-popup="ux_open_popup">
			<div class="popup-inner">
				<div class="portlet box vivid-green" style="margin-bottom: 0px !important">
					<div class="portlet-title">
						<div class="caption" id="ux_div_action">
							<?php echo esc_attr( $bb_manage_backups_download_backup ); ?>
						</div>
					</div>
					<div class="modal-body">
						<form id="ux_frm_download_backups">
							<div class="form-group">
								<label class="control-label">
									<span id="ux_span_download">
										<?php echo esc_attr( $bb_manage_select_backup ); ?>
									</span> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_download_type" id="ux_ddl_download_type" class="form-control">
									<option value=""><?php echo esc_attr( $bb_choose_backup ); ?></option>
								</select>
								<i class="controls-description" id="ux_pop_up_tooltip"><?php echo esc_attr( $bb_choose_backup_to_download_tooltip ); ?></i>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn vivid-green" name="ux_btn_backup" id="ux_btn_backup" onclick="download_backup_bank();" value="<?php echo esc_attr( $bb_manage_download_backup ); ?>">
						<input type="button" data-popup-close="ux_open_popup" class="btn vivid-green" name="ux_btn_close" id="ux_btn_close" value="<?php echo esc_attr( $bb_manage_backups_close ); ?>">
					</div>
				</div>
			</div>
		</div>
		<div id="ux_div_portlet_progress" style="display:none;">
			<div id="ux_div_progressbar">
				<div class="progress-bar-position ">
					<div class="portlet-progress-bar">
						<span id="progress_bar_heading">
							Restore Backup
						</span>
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
						Restoring Backup
					</div>
					<div class="portlet-progress-message">
						<p>
							<span id="cancel_message">* Please do not <u>Cancel</u> or <u>Refresh</u> the Page until the Restore process is Completed.</span><br/>
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
						<?php echo esc_attr( $bb_manage_backups ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-folder-alt"></i>
							<?php echo esc_attr( $bb_manage_backups ); ?>
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
