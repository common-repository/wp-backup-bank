<?php
/**
 * This file is used for displaying sidebar menus.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/includes
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
	} else {
		?>
		<div class="page-sidebar-wrapper-tech-banker">
			<div class="page-sidebar-tech-banker navbar-collapse collapse">
				<div class="sidebar-menu-tech-banker">
					<ul class="page-sidebar-menu-tech-banker" data-slide-speed="200">
						<div class="sidebar-search-wrapper" style="padding:20px;text-align:center">
							<a class="plugin-logo" href="<?php echo esc_attr( TECH_BANKER_SITE_URL ); ?>" target="_blank">
								<img src="<?php echo esc_attr( plugins_url( 'assets/global/img/backup-bank-logo.png', dirname( __FILE__ ) ) ); ?>">
							</a>
						</div>
						<li id="ux_bb_li_backups">
							<a href="javascript:;">
								<i class="icon-custom-folder-alt"></i>
								<span class="title">
									<?php echo esc_attr( $bb_backups ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_bb_li_manage_backups">
									<a href="admin.php?page=bb_manage_backups">
										<i class="icon-custom-folder-alt"></i>
										<span class="title">
											<?php echo esc_attr( $bb_manage_backups ); ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_generate_manual_backup">
									<a href="admin.php?page=bb_start_backup">
										<i class="icon-custom-note"></i>
										<span class="title">
											<?php echo esc_attr( $bb_start_backup ); ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_schedule_backup">
									<a href="admin.php?page=bb_schedule_backup">
										<i class="icon-custom-hourglass"></i>
										<span class="title">
											<?php echo esc_attr( $bb_schedule_backup ); ?>
										</span>
										<span class="badge">Pro</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_bb_li_general_settings">
							<a href="javascript:;">
								<i class="icon-custom-paper-clip"></i>
								<span class="title">
									<?php echo esc_attr( $bb_general_settings ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_bb_li_alert_setup_backup">
									<a href="admin.php?page=bb_alert_setup">
										<i class="icon-custom-bell"></i>
										<span class="title">
											<?php echo esc_attr( $bb_alert_setup ); ?>
										</span>
										<span class="badge">Pro</span>
									</a>
								</li>
								<li id="ux_bb_li_amazons3_settings">
									<a href="admin.php?page=bb_amazons3_settings">
										<i class="icon-custom-action-undo"></i>
										<span class="title">
											<?php echo esc_attr( $bb_amazons3_settings ); ?>
										</span>
										<span class="badge">Pro</span>
									</a>
								</li>
								<li id="ux_bb_li_dropbox_settings">
									<a href="admin.php?page=bb_dropbox_settings">
										<i class="icon-custom-social-dropbox"></i>
										<span class="title">
											<?php echo esc_attr( $bb_dropbox_settings ); ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_email_settings">
									<a href="admin.php?page=bb_email_settings">
										<i class="icon-custom-envelope"></i>
										<span class="title">
											<?php echo esc_attr( $bb_email_settings ); ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_ftp_settings">
									<a href="admin.php?page=bb_ftp_settings">
										<i class="icon-custom-share-alt"></i>
										<span class="title">
											<?php echo esc_attr( $bb_ftp_settings ); ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_google_drive_backup">
									<a href="admin.php?page=bb_google_drive">
										<i class="icon-custom-social-dribbble"></i>
										<span class="title">
											<?php echo esc_attr( $bb_google_drive ); ?>
										</span>
									</a>
								</li>
								<li id="ux_bb_li_onedrive_settings">
									<a href="admin.php?page=bb_onedrive_settings">
										<i class="icon-custom-cloud-upload"></i>
										<span class="title">
											<?php echo esc_attr( $bb_onedrive_settings ); ?>
										</span>
										<span class="badge">Pro</span>
									</a>
								</li>
								<li id="ux_bb_li_rackspace_settings">
									<a href="admin.php?page=bb_rackspace_settings">
										<i class="icon-custom-rocket"></i>
										<span class="title">
											<?php echo esc_attr( $bb_rackspace_settings ); ?>
										</span>
										<span class="badge">Pro</span>
									</a>
								</li>
								<li id="ux_bb_li_ms_azure_settings">
									<a href="admin.php?page=bb_ms_azure_settings">
										<i class="icon-custom-energy"></i>
										<span class="title">
											<?php echo esc_attr( $bb_ms_azure_settings ); ?>
										</span>
										<span class="badge">Pro</span>
									</a>
								</li>
								<li id="ux_bb_li_other_settings_backup">
									<a href="admin.php?page=bb_other_settings">
										<i class="icon-custom-settings"></i>
										<span class="title">
											<?php echo esc_attr( $bb_other_settings ); ?>
										</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_bb_li_email_template">
							<a href="admin.php?page=bb_email_templates">
								<i class="icon-custom-layers"></i>
								<span class="title">
									<?php echo esc_attr( $bb_email_templates ); ?>
								</span>
								<span class="badge">Pro</span>
							</a>
						</li>
						<li id="ux_bb_li_roles_capabilities">
							<a href="admin.php?page=bb_roles_and_capabilities">
								<i class="icon-custom-user"></i>
								<span class="title">
									<?php echo esc_attr( $bb_roles_and_capabilities ); ?>
								</span>
								<span class="badge">Pro</span>
							</a>
						</li>
						<li id="ux_bb_li_support_forum">
							<a href="https://wordpress.org/support/plugin/wp-backup-bank" target="_blank">
								<i class="icon-custom-star"></i>
								<span class="title">
									<?php echo esc_attr( $bb_support_forum ); ?>
								</span>
							</a>
						</li>
						<li id="ux_bb_li_system_information">
							<a href="admin.php?page=bb_system_information">
								<i class="icon-custom-screen-desktop"></i>
								<span class="title">
									<?php echo esc_attr( $bb_system_information ); ?>
								</span>
							</a>
						</li>
						<li id="ux_bb_li_premium_editions">
							<a href="https://tech-banker.com/backup-bank/pricing/" target="_blank">
								<i class="icon-custom-lock-open"></i>
								<strong><span class="title" style="color:yellow;">
									<?php echo esc_attr( $bb_premium_editions ); ?>
								</span></strong>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="page-content-wrapper">
		<div class="page-content">
		<?php
	}
}
