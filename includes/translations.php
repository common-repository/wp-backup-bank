<?php
/**
 * This file is used for translation strings.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/includes
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly.

$bb_upgrade_know_about      = __( 'Know about', 'wp-backup-bank' );
$bb_full_features           = __( 'Full Features', 'wp-backup-bank' );
$bb_chek_our                = __( 'or check our', 'wp-backup-bank' );
$bb_online_demos            = __( 'Online Demos', 'wp-backup-bank' );
$bb_support_forum           = __( 'Ask For Help', 'wp-backup-bank' );
$bb_premium_editions        = __( 'Premium Edition', 'wp-backup-bank' );
$bb_message_premium_edition = __( 'This feature is available only in Premium Editions! <br> Kindly Purchase to unlock it!', 'wp-backup-bank' );

// Footer.
$bb_success                              = __( 'Success!', 'wp-backup-bank' );
$bb_update_settings                      = __( 'Saved Successfully!', 'wp-backup-bank' );
$bb_backup_generated_successfully        = __( 'Successfully Generated!', 'wp-backup-bank' );
$bb_add_schedule_backup                  = __( 'Scheduled Successfully!', 'wp-backup-bank' );
$bb_choose_tables                        = __( 'Choose particular to continue!', 'wp-backup-bank' );
$bb_confirm_single_delete                = __( 'Are you sure?', 'wp-backup-bank' );
$bb_delete_backups                       = __( 'Data Deleted!', 'wp-backup-bank' );
$bb_ftp_conn                             = __( 'Incorrect Server Address!', 'wp-backup-bank' );
$bb_could_not_connect                    = __( 'Error Connecting FTP with details mentioned below. Please rectify and try again!', 'wp-backup-bank' );
$bb_invalid_dropbox_api_or_secret_key    = __( 'Error Validating DropBox Api Key or App Secret. Please rectify and try again!', 'wp-backup-bank' );
$bb_error_file_upload                    = __( 'Error uploading file. Please rectify and try again!', 'wp-backup-bank' );
$bb_cancel_backup_to_dropbox             = __( 'Error! Access to your Folder has been Cancelled', 'wp-backup-bank' );
$bb_backup_terminated                    = __( 'Unfortunately, your Backup has been Terminated!', 'wp-backup-bank' );
$bb_dropbox_upload                       = __( 'Error establishing a DropBox connection or Access Token got Expired!', 'wp-backup-bank' );
$bb_ftp_connect                          = __( 'Incorrect Server Address!', 'wp-backup-bank' );
$bb_file_does_not_exist                  = __( 'Error! Unfortunately, Restore of the Backup has Failed!', 'wp-backup-bank' );
$bb_restore_backup_success               = __( 'Restoring of Backup has been Completed Successfully', 'wp-backup-bank' );
$bb_choose_backup                        = __( 'Please choose a Backup File', 'wp-backup-bank' );
$bb_choose_log_file                      = __( 'Please choose a Log File', 'wp-backup-bank' );
$bb_choose_backup_to_download_tooltip    = __( 'Choose a Backup File to download', 'wp-backup-bank' );
$bb_choose_backup_to_restore_tooltip     = __( 'Choose a Backup File to restore', 'wp-backup-bank' );
$bb_choose_log_file_to_download_tooltip  = __( 'Choose a Log File to download', 'wp-backup-bank' );
$bb_purge_backup                         = __( 'Purged Successfully!', 'wp-backup-bank' );
$bb_backup_email                         = __( 'Email couldn\'t be sent as Backup Size exceeds 20MB!', 'wp-backup-bank' );
$bb_ftp_not_configured_message           = __( 'FTP Settings are not configured!', 'wp-backup-bank' );
$bb_dropbox_not_configured_message       = __( 'Dropbox Settings are not configured!', 'wp-backup-bank' );
$bb_email_not_configured_message         = __( 'Email Settings are not configured!', 'wp-backup-bank' );
$bb_authenticating_google_drive_settings = __( 'Please wait till Google Drive Settings gets validated!', 'wp-backup-bank' );
$bb_validate_onedrive_settings           = __( 'Please wait till OneDrive Settings gets validated!', 'wp-backup-bank' );
$bb_invalid_onedrive_client_id           = __( 'Invalid Application Id. Please rectify and try again!', 'wp-backup-bank' );
$bb_invalid_onedrive_client_secret       = __( 'Invalid Client Secret. Please rectify and try again!', 'wp-backup-bank' );
$bb_onedrive_upload                      = __( 'Error establishing a OneDrive connection or Access Token got Expired!', 'wp-backup-bank' );
$bb_onedrive_backup_not_send             = __( 'Backup couldn\'t be uploaded to OneDrive. Please re-authenticate and try again!', 'wp-backup-bank' );
$bb_google_drive_not_configured_message  = __( 'Google Drive Settings are not configured!', 'wp-backup-bank' );
$bb_google_drive_token_expired           = __( 'Error establishing a Google Drive connection or Access Token got Expired!', 'wp-backup-bank' );
$bb_invalid_secret_key_google_drive      = __( 'Invalid Client Secret. Please rectify and try again!', 'wp-backup-bank' );
$bb_amazons3_backup_not_send             = __( 'Backup couldn\'t be uploaded to amazons3. Please re-authenticate and try again!', 'wp-backup-bank' );
$bb_amazons3_upload                      = __( 'Error establishing a Amazon S3 connection or Access Token got Expired!', 'wp-backup-bank' );
$bb_amazons3_not_configured_message      = __( 'Amazon S3 Settings are not configured!', 'wp-backup-bank' );
$bb_invalid_rackspace_client             = __( 'Invalid Username or API Key. Please rectify and try again!', 'wp-backup-bank' );
$bb_rackspace_not_configured_message     = __( 'Rackspace Settings are not configured!', 'wp-backup-bank' );
$bb_invalid_ms_azure_client              = __( 'Invalid Account Name or Access Key. Please rectify and try again!', 'wp-backup-bank' );
$bb_invalid_ms_azure_client_container    = __( 'Invalid Container Name. Please rectify and try again!', 'wp-backup-bank' );
$bb_azure_not_configured_message         = __( 'Microsoft Azure Settings are not configured!', 'wp-backup-bank' );
$bb_azure_backup_not_send                = __( 'Backup couldn\'t be uploaded to Microsoft Azure. Please re-authenticate and try again!', 'wp-backup-bank' );
$bb_rackspace_backup_not_send            = __( 'Backup couldn\'t be uploaded to Rackspace. Please re-authenticate and try again!', 'wp-backup-bank' );
$bb_rackspace_upload                     = __( 'Error establishing a Rackspace connection or Access Token got Expired!', 'wp-backup-bank' );
$bb_azure_upload                         = __( 'Error establishing a Microsoft Azure connection or Access Token got Expired!', 'wp-backup-bank' );
$bb_dropbox_backup_not_send              = __( 'Backup couldn\'t be uploaded to Dropbox. Please re-authenticate and try again!', 'wp-backup-bank' );

// Menus.
$wp_backup_bank            = __( 'Backup Bank', 'wp-backup-bank' );
$bb_manage_backups         = __( 'Existing Backups', 'wp-backup-bank' );
$bb_schedule_backup        = __( 'Schedule Backup', 'wp-backup-bank' );
$bb_general_settings       = __( 'General Settings', 'wp-backup-bank' );
$bb_alert_setup            = __( 'Alert Setup', 'wp-backup-bank' );
$bb_other_settings         = __( 'Other Settings', 'wp-backup-bank' );
$bb_dropbox_settings       = __( 'Dropbox Settings', 'wp-backup-bank' );
$bb_email_settings         = __( 'Email Settings', 'wp-backup-bank' );
$bb_ftp_settings           = __( 'FTP Settings', 'wp-backup-bank' );
$bb_email_templates        = __( 'Email Templates', 'wp-backup-bank' );
$bb_roles_and_capabilities = __( 'Roles & Capabilities', 'wp-backup-bank' );
$bb_system_information     = __( 'System Information', 'wp-backup-bank' );
$bb_backups                = __( 'Backups / Restore', 'wp-backup-bank' );
$bb_onedrive_settings      = __( 'OneDrive Settings', 'wp-backup-bank' );
$bb_google_drive           = __( 'Google Drive Settings', 'wp-backup-bank' );
$bb_amazons3_settings      = __( 'Amazon S3 Settings', 'wp-backup-bank' );
$bb_rackspace_settings     = __( 'Rackspace Settings', 'wp-backup-bank' );
$bb_ms_azure_settings      = __( 'Microsoft Azure Settings', 'wp-backup-bank' );


// Common Variables.
$bb_configure                       = __( 'Configure!', 'wp-backup-bank' );
$bb_save_changes                    = __( 'Save Changes', 'wp-backup-bank' );
$bb_enable                          = __( 'Enable', 'wp-backup-bank' );
$bb_disable                         = __( 'Disable', 'wp-backup-bank' );
$bb_user_access_message             = __( 'You don\'t have Sufficient Access to this Page. Kindly contact the Administrator for more Privileges', 'wp-backup-bank' );
$bb_backup_name                     = __( 'Backup Name', 'wp-backup-bank' );
$bb_backup_type                     = __( 'Backup Type', 'wp-backup-bank' );
$bb_api_key                         = __( 'App Key', 'wp-backup-bank' );
$bb_subject                         = __( 'Subject', 'wp-backup-bank' );
$bb_backup_name_tooltip             = __( 'Provide a title to your backup', 'wp-backup-bank' );
$bb_backup_name_placeholder         = __( 'Please provide Backup Title', 'wp-backup-bank' );
$bb_backup_type_tooltip             = __( 'Choose what type of Backup you want to create?', 'wp-backup-bank' );
$bb_complete_backup                 = __( 'Complete Backup', 'wp-backup-bank' );
$bb_only_database                   = __( 'Only Database', 'wp-backup-bank' );
$bb_only_filesystem                 = __( 'Only Filesystem', 'wp-backup-bank' );
$bb_only_plugins_and_themes         = __( 'Only Plugins and Themes Folder', 'wp-backup-bank' );
$bb_only_themes                     = __( 'Only Themes Folder', 'wp-backup-bank' );
$bb_only_plugins                    = __( 'Only Plugins Folder', 'wp-backup-bank' );
$bb_wp_content_folder               = __( 'Only WP Content Folder', 'wp-backup-bank' );
$bb_exclude_list                    = __( 'Exclude List', 'wp-backup-bank' );
$bb_exclude_list_tooltip            = __( 'Provide Files extensions you want to exclude', 'wp-backup-bank' );
$bb_exclude_list_placeholder        = __( 'Please provide file extensions', 'wp-backup-bank' );
$bb_file_compression                = __( 'File Compression Type', 'wp-backup-bank' );
$bb_file_compression_tooltip        = __( 'Choose Compression Type for your Backup', 'wp-backup-bank' );
$bb_db_compression                  = __( 'DB Compression Type', 'wp-backup-bank' );
$bb_db_compression_tooltip          = __( 'Choose Compression Type for your Database Backup File', 'wp-backup-bank' );
$bb_backup_destination              = __( 'Backup Destination', 'wp-backup-bank' );
$bb_backup_destination_tooltip      = __( 'Choose where you want to store your Backup', 'wp-backup-bank' );
$bb_local_folder                    = __( 'Local Folder', 'wp-backup-bank' );
$bb_dropbox                         = __( 'Dropbox', 'wp-backup-bank' );
$bb_onedrive                        = __( 'OneDrive', 'wp-backup-bank' );
$bb_email                           = __( 'Email', 'wp-backup-bank' );
$bb_ftp                             = __( 'FTP', 'wp-backup-bank' );
$bb_dropbox_not_configured          = __( 'Dropbox ( Configure ! )', 'wp-backup-bank' );
$bb_email_not_configured            = __( 'Email ( Configure ! )', 'wp-backup-bank' );
$bb_ftp_not_configured              = __( 'FTP ( Configure ! )', 'wp-backup-bank' );
$bb_table_names                     = __( 'Choose Tables for Backup', 'wp-backup-bank' );
$bb_backup_tables                   = __( 'Backup Tables', 'wp-backup-bank' );
$bb_preview                         = __( 'Preview', 'wp-backup-bank' );
$bb_start_backup                    = __( 'Start Backup', 'wp-backup-bank' );
$bb_cc_email                        = __( 'CC', 'wp-backup-bank' );
$bb_bcc_email                       = __( 'BCC', 'wp-backup-bank' );
$bb_cc_placeholder                  = __( 'Please provide CC Email Address', 'wp-backup-bank' );
$bb_bcc_placeholder                 = __( 'Please provide BCC Email Address', 'wp-backup-bank' );
$bb_extention_not_found             = __( '( Extension not supported )', 'wp-backup-bank' );
$bb_email_message                   = __( 'Message', 'wp-backup-bank' );
$bb_email_message_tooltip           = __( 'Content for your Email', 'wp-backup-bank' );
$bb_email_cc_tooltip                = __( 'A valid Email Address used in the "CC" field. Use "," to separate multiple email addresses', 'wp-backup-bank' );
$bb_email_bcc_tooltip               = __( 'A valid Email Address used in the "BCC" field. Use "," to separate multiple email addresses', 'wp-backup-bank' );
$bb_database                        = __( 'Database Backup', 'wp-backup-bank' );
$bb_plugins                         = __( 'Plugins Backup', 'wp-backup-bank' );
$bb_plugins_themes                  = __( 'Plugins and Themes Backup', 'wp-backup-bank' );
$bb_themes                          = __( 'Themes Backup', 'wp-backup-bank' );
$bb_contents                        = __( 'Contents Backup', 'wp-backup-bank' );
$bb_filesystem                      = __( 'Filesystem Backup', 'wp-backup-bank' );
$bb_never                           = __( 'Never', 'wp-backup-bank' );
$bb_na                              = __( 'N/A', 'wp-backup-bank' );
$bb_onedrive_not_configured         = __( 'OneDrive ( Configure ! )', 'wp-backup-bank' );
$bb_onedrive_not_configured_message = __( 'OneDrive Settings are not configured!', 'wp-backup-bank' );
$bb_google_drive_not_configured     = __( 'Google Drive ( Configure ! )', 'wp-backup-bank' );
$bb_google_drive_settings           = __( 'Google Drive', 'wp-backup-bank' );
$bb_redirect_url                    = __( 'Redirect URI', 'wp-backup-bank' );
$bb_redirect_url_tooltip            = __( 'Please Copy and Paste this URI into Redirect URI field', 'wp-backup-bank' );
$bb_amazons3_not_configured         = __( 'Amazon S3 ( Configure ! )', 'wp-backup-bank' );
$bb_amazons3                        = __( 'Amazon S3', 'wp-backup-bank' );
$bb_rackspace_not_configured        = __( 'Rackspace ( Configure ! )', 'wp-backup-bank' );
$bb_rackspace                       = __( 'Rackspace', 'wp-backup-bank' );
$bb_ms_azure                        = __( 'Microsoft Azure', 'wp-backup-bank' );

// Roles and Capabilities.
$bb_roles_capabilities_show_menu                            = __( 'Show Backup Bank Menu', 'wp-backup-bank' );
$bb_roles_capabilities_show_menu_tooltip                    = __( 'Choose who would be able to see the Backup Bank Menu?', 'wp-backup-bank' );
$bb_roles_capabilities_administrator                        = __( 'Administrator', 'wp-backup-bank' );
$bb_roles_capabilities_author                               = __( 'Author', 'wp-backup-bank' );
$bb_roles_capabilities_editor                               = __( 'Editor', 'wp-backup-bank' );
$bb_roles_capabilities_contributor                          = __( 'Contributor', 'wp-backup-bank' );
$bb_roles_capabilities_subscriber                           = __( 'Subscriber', 'wp-backup-bank' );
$bb_roles_capabilities_topbar_menu                          = __( 'Show Backup Bank Top Bar Menu', 'wp-backup-bank' );
$bb_roles_capabilities_topbar_menu_tooltip                  = __( 'Do you want to show Backup Bank menu in Top Bar?', 'wp-backup-bank' );
$bb_roles_capabilities_administrator_role                   = __( 'An Administrator Role can do the following', 'wp-backup-bank' );
$bb_roles_capabilities_administrator_role_tooltip           = __( 'Choose pages for users having Administrator Access', 'wp-backup-bank' );
$bb_roles_capabilities_full_control                         = __( 'Full Control', 'wp-backup-bank' );
$bb_roles_capabilities_author_role                          = __( 'An Author Role can do the following', 'wp-backup-bank' );
$bb_roles_capabilities_author_role_tooltip                  = __( 'Choose pages for users having Author Access', 'wp-backup-bank' );
$bb_roles_capabilities_editor_role                          = __( 'An Editor Role can do the following', 'wp-backup-bank' );
$bb_roles_capabilities_editor_role_tooltip                  = __( 'Choose pages for users having Editor Access', 'wp-backup-bank' );
$bb_roles_capabilities_contributor_role                     = __( 'A Contributor Role can do the following', 'wp-backup-bank' );
$bb_roles_capabilities_contributor_role_tooltip             = __( 'Choose pages for users having Contributor Access', 'wp-backup-bank' );
$bb_roles_capabilities_subscriber_role                      = __( 'A Subscriber Role can do the following', 'wp-backup-bank' );
$bb_roles_capabilities_subscriber_role_tooltip              = __( 'Choose pages for users having Subscriber Access', 'wp-backup-bank' );
$bb_roles_capabilities_other                                = __( 'Others', 'wp-backup-bank' );
$bb_roles_capabilities_other_role                           = __( 'Other Roles can do the following', 'wp-backup-bank' );
$bb_roles_capabilities_other_role_tooltip                   = __( 'Please choose specific page available for Others Role Access', 'wp-backup-bank' );
$bb_roles_and_capabilities_other_roles_capabilities         = __( 'Please tick the appropriate capabilities for security purposes', 'wp-backup-bank' );
$bb_roles_and_capabilities_other_roles_capabilities_tooltip = __( 'Only users with these capabilities can access Backup Bank', 'wp-backup-bank' );

// Onedrive Settings.
$bb_onedrive_get_client_id             = __( 'Get Application Id & Application Secrets', 'wp-backup-bank' );
$bb_onedrive_backup_to                 = __( 'Backup to OneDrive', 'wp-backup-bank' );
$bb_onedrive_client_id_placeholder     = __( 'Please provide valid Application Id', 'wp-backup-bank' );
$bb_onedrive_client_secret             = __( 'Application Secrets', 'wp-backup-bank' );
$bb_onedrive_client_secret_placeholder = __( 'Please provide valid Application Secrets', 'wp-backup-bank' );
$bb_client_id                          = __( 'Application Id', 'wp-backup-bank' );
$bb_onedrive_skd_application           = __( 'Create new OneDrive Live SDK Application', 'wp-backup-bank' );

// Amazon S3 Settings.
$bb_amazons3_backup_to                    = __( 'Backup to Amazon S3', 'wp-backup-bank' );
$bb_enable_tooltip                        = __( 'Choose Enable to Configure Settings?', 'wp-backup-bank' );
$bb_amazons3_asccess_key_id               = __( 'Access Key Id', 'wp-backup-bank' );
$bb_amazons3_asccess_key_id_tooltip       = __( 'Provide Credentials to Configure the Settings', 'wp-backup-bank' );
$bb_amazons3_asccess_key_id_placeholder   = __( 'Please provide valid Access Key Id', 'wp-backup-bank' );
$bb_amazons3_secret_key                   = __( 'Secret Access Key', 'wp-backup-bank' );
$bb_amazons3_secret_key_placeholder       = __( 'Please provide valid Secret Access Key', 'wp-backup-bank' );
$bb_amazons3_bucket_name                  = __( 'Bucket Name', 'wp-backup-bank' );
$bb_amazons3_bucket_tooltip               = __( 'Provide Bucket Name which will you get from your Amazon S3 Account', 'wp-backup-bank' );
$bb_amazons3_bucket_placeholder           = __( 'Please provide valid Bucket Name', 'wp-backup-bank' );
$bb_amazons3_get_access_key_id_secret_key = __( 'Access Key Id & Secret Access Key', 'wp-backup-bank' );

// Rackspace Settings.
$bb_rackspace_backup_to             = __( 'Backup to Rackspace', 'wp-backup-bank' );
$bb_rackspace_username              = __( 'Username', 'wp-backup-bank' );
$bb_rackspace_username_placeholder  = __( 'Please provide valid Username', 'wp-backup-bank' );
$bb_rackspace_api_key               = __( 'Api Key', 'wp-backup-bank' );
$bb_rackspace_api_key_placeholder   = __( 'Please provide valid Api Key', 'wp-backup-bank' );
$bb_rackspace_container             = __( 'Container Name', 'wp-backup-bank' );
$bb_rackspace_container_tooltip     = __( 'Provide Rackspace account Container Name', 'wp-backup-bank' );
$bb_rackspace_container_placeholder = __( 'Please provide a valid container name', 'wp-backup-bank' );
$bb_rackspace_region                = __( 'Container Region', 'wp-backup-bank' );
$bb_rackspace_region_tooltip        = __( 'Choose where your Rackspace container is Created', 'wp-backup-bank' );
$bb_rackspace_region_dfw            = __( 'Dallas (DFW)', 'wp-backup-bank' );
$bb_rackspace_region_iad            = __( 'Northern Virginia (IAD)', 'wp-backup-bank' );
$bb_rackspace_region_ord            = __( 'Chicago (ORD)', 'wp-backup-bank' );
$bb_rackspace_region_lon            = __( 'London (LON)', 'wp-backup-bank' );
$bb_rackspace_region_syd            = __( 'Sydney (SYD)', 'wp-backup-bank' );
$bb_rackspace_region_hkg            = __( 'Hong Kong (HKG)', 'wp-backup-bank' );
$bb_rackspace_get_credentials       = __( 'Get Username & Api key', 'wp-backup-bank' );

// MS Azure Settings.
$bb_ms_azure_backup_to                  = __( 'Backup to Microsoft Azure', 'wp-backup-bank' );
$bb_ms_azure_account_name               = __( 'Account Name', 'wp-backup-bank' );
$bb_ms_azure_account_name_placeholder   = __( 'Please provide valid Account Name', 'wp-backup-bank' );
$bb_ms_azure_access_key                 = __( 'Access Key', 'wp-backup-bank' );
$bb_ms_azure_access_key_placeholder     = __( 'Please provide valid Access Key', 'wp-backup-bank' );
$bb_ms_azure_container                  = __( 'Container Name', 'wp-backup-bank' );
$bb_ms_azure_container_tooltip          = __( 'Provide Microsoft Azure account Container Name', 'wp-backup-bank' );
$bb_ms_azure_container_placeholder      = __( 'Please provide a valid container name', 'wp-backup-bank' );
$bb_ms_azure_get_client_account_details = __( 'Get Account Name & Access Key', 'wp-backup-bank' );

// Manage Backup.
$bb_manage_backups_execution                 = __( 'Execution', 'wp-backup-bank' );
$bb_manage_backups_status                    = __( 'Status', 'wp-backup-bank' );
$bb_manage_backups_schedule_backup_btn       = __( 'Schedule Backup', 'wp-backup-bank' );
$bb_manage_backups_action                    = __( 'Action', 'wp-backup-bank' );
$bb_manage_backups_execution_manual          = __( 'Manual', 'wp-backup-bank' );
$bb_manage_backups_execution_scheduled       = __( 'Scheduled', 'wp-backup-bank' );
$bb_manage_backups_status_not_yet            = __( 'Not Yet Executed', 'wp-backup-bank' );
$bb_manage_backups_status_running            = __( 'Backup is Running', 'wp-backup-bank' );
$bb_manage_backups_delete                    = __( 'Delete', 'wp-backup-bank' );
$bb_manage_backups_download                  = __( 'Download', 'wp-backup-bank' );
$bb_manage_backups_tooltip                   = __( 'Restore Backup', 'wp-backup-bank' );
$bb_manage_backups_bulk_action               = __( 'Bulk Action', 'wp-backup-bank' );
$bb_manage_backups_apply                     = __( 'Apply', 'wp-backup-bank' );
$bb_manage_backups_terminated                = __( 'Backup Terminated', 'wp-backup-bank' );
$bb_manage_backups_completed_successfully    = __( 'Backup Completed Successfully', 'wp-backup-bank' );
$bb_backup_executed_in                       = __( 'Executed In', 'wp-backup-bank' );
$bb_backup_total_size                        = __( 'Total Size', 'wp-backup-bank' );
$bb_backup_details                           = __( 'Backup Details', 'wp-backup-bank' );
$bb_last_execution                           = __( 'Last Execution', 'wp-backup-bank' );
$bb_next_execution                           = __( 'Next Execution', 'wp-backup-bank' );
$bb_manage_backups_last_status               = __( 'Last Status', 'wp-backup-bank' );
$bb_manage_backups_log                       = __( 'Log', 'wp-backup-bank' );
$bb_manage_backups_download_backup           = __( 'Download Backup', 'wp-backup-bank' );
$bb_manage_backups_close                     = __( 'Close', 'wp-backup-bank' );
$bb_manage_download_backup                   = __( 'Download Backup', 'wp-backup-bank' );
$bb_manage_download_log_file                 = __( 'Download Log File', 'wp-backup-bank' );
$bb_manage_download_restore_backup           = __( 'Restore Backup', 'wp-backup-bank' );
$bb_manage_select_backup                     = __( 'Select Backup', 'wp-backup-bank' );
$bb_manage_select_log_backup                 = __( 'Select Log File', 'wp-backup-bank' );
$bb_manage_backups_restored_successfully     = __( 'Backup Restored Successfully', 'wp-backup-bank' );
$bb_manage_backups_restore_terminated        = __( 'Backup Restore Terminated', 'wp-backup-bank' );
$bb_manage_backups_purge_backups             = __( 'Purge Backups', 'wp-backup-bank' );
$bb_compression_type                         = __( 'Compression Type', 'wp-backup-bank' );
$bb_manage_rerun_backup                      = __( 'Re-run', 'wp-backup-bank' );
$bb_manage_backup_on                         = __( 'Backup Taken On ', 'wp-backup-bank' );
$bb_manage_backup_restore_on                 = __( 'Backup Restore On ', 'wp-backup-bank' );
$bb_manage_backups_uploading_to_ftp          = __( 'Uploading Backup to Ftp', 'wp-backup-bank' );
$bb_manage_backups_uploading_to_email        = __( 'Uploading Backup to Email', 'wp-backup-bank' );
$bb_manage_backups_uploading_to_dropbox      = __( 'Uploading Backup to Dropbox', 'wp-backup-bank' );
$bb_manage_backups_uploading_to_onedrive     = __( 'Uploading Backup to OneDrive', 'wp-backup-bank' );
$bb_manage_backups_uploading_to_google_drive = __( 'Uploading Backup to Google Drive', 'wp-backup-bank' );
$bb_manage_backups_uploading_to_amazons3     = __( 'Uploading Backup to Amazon s3', 'wp-backup-bank' );
$bb_manage_backups_uploading_to_rackspace    = __( 'Uploading Backup to Rackspace', 'wp-backup-bank' );
$bb_manage_backups_uploading_to_azure        = __( 'Uploading Backup to Microsoft Azure', 'wp-backup-bank' );

// Email Templates.
$bb_choose_email_template                   = __( 'Choose Email Template', 'wp-backup-bank' );
$bb_choose_email_template_tooltip           = __( 'Choose an Email Template to Configure Settings', 'wp-backup-bank' );
$bb_email_template_send_to                  = __( 'Send To', 'wp-backup-bank' );
$bb_email_template_send_to_tooltip          = __( 'A valid Email Address to which you would like to send a Email', 'wp-backup-bank' );
$bb_email_template_send_to_placeholder      = __( 'Please provide Email Address', 'wp-backup-bank' );
$bb_email_template_subject_tooltip          = __( 'Subject Line for your Email', 'wp-backup-bank' );
$bb_email_template_subject_placeholder      = __( 'Please provide Subject', 'wp-backup-bank' );
$bb_email_template_for_backup_schedule      = __( 'When Backup is Successfully Scheduled', 'wp-backup-bank' );
$bb_email_template_for_generated_backup     = __( 'When Backup is Successfully Generated', 'wp-backup-bank' );
$bb_email_template_for_backup_failure       = __( 'When Backup is Failed', 'wp-backup-bank' );
$bb_email_template_for_restore_successfully = __( 'When Restore is Successfully Completed', 'wp-backup-bank' );
$bb_email_template_for_restore_failure      = __( 'When Restore is Failed', 'wp-backup-bank' );

// Schedule Backup.
$bb_schedule_backup_start_on             = __( 'Start On', 'wp-backup-bank' );
$bb_schedule_backup_start_on_tooltip     = __( 'Choose Start Date for Scheduler to Run', 'wp-backup-bank' );
$bb_schedule_backup_start_on_placeholder = __( 'Please provide Start Date', 'wp-backup-bank' );
$bb_schedule_backup_start_time           = __( 'Start Time', 'wp-backup-bank' );
$bb_schedule_backup_start_time_tooltip   = __( 'Choose Start Time for Scheduler to Run at', 'wp-backup-bank' );
$bb_schedule_backup_hrs                  = 'hrs';
$bb_schedule_backup_mins                 = 'mins';
$bb_schedule_backup_duration             = __( 'Duration', 'wp-backup-bank' );
$bb_schedule_backup_duration_tooltip     = __( 'Set when Scheduler have to Repeat', 'wp-backup-bank' );
$bb_schedule_backup_hourly               = __( 'Hourly', 'wp-backup-bank' );
$bb_schedule_backup_daily                = __( 'Daily', 'wp-backup-bank' );
$bb_schedule_backup_repeat_every         = __( 'Repeat Every', 'wp-backup-bank' );
$bb_schedule_backup_repeat_every_tooltip = __( 'Set time duration for Scheduler to Repeat', 'wp-backup-bank' );

// Alert Setup.
$bb_email_for_backup_schedule      = __( 'Email when a backup is Successfully Scheduled', 'wp-backup-bank' );
$bb_email_for_generated_backup     = __( 'Email when a backup is Successfully Generated', 'wp-backup-bank' );
$bb_email_for_backup_failure       = __( 'Email when a backup is Failed', 'wp-backup-bank' );
$bb_email_for_restore_successfully = __( 'Email when Restore is Successfully Completed', 'wp-backup-bank' );
$bb_email_for_restore_failure      = __( 'Email when Restore is Failed', 'wp-backup-bank' );

// Other Settings.
$bb_other_setting_automatic_plugin_update              = __( 'Automatic Plugin Updates', 'wp-backup-bank' );
$bb_other_setting_automatic_plugin_update_tooltip      = __( 'Choose a specific option whether to allow Automatic Plugin Updates', 'wp-backup-bank' );
$bb_other_setting_maintenance_mode                     = __( 'Maintenance Mode', 'wp-backup-bank' );
$bb_other_setting_maintenance_mode_tooltip             = __( 'Do you want to show maintenance mode message?', 'wp-backup-bank' );
$bb_other_setting_maintenance_mode_message             = __( 'Maintenance Mode Message', 'wp-backup-bank' );
$bb_other_setting_maintenance_mode_message_tooltip     = __( 'Provide message that would be shown when Maintenance Mode is enabled.', 'wp-backup-bank' );
$bb_other_setting_maintenance_mode_message_placeholder = __( 'Please provide Message', 'wp-backup-bank' );
$bb_other_setting_remove_tables                        = __( 'Remove Database at Uninstall', 'wp-backup-bank' );
$bb_other_setting_remove_tables_tootltip               = __( 'Do you want to Remove Database at Uninstall of the Plugin?', 'wp-backup-bank' );

// Dropbox Settings.
$bb_dropbox_backup_to              = __( 'Backup to Dropbox', 'wp-backup-bank' );
$bb_dropbox_api_key_placeholder    = __( 'Please provide valid App key', 'wp-backup-bank' );
$bb_dropbox_secret_key             = __( 'App Secret', 'wp-backup-bank' );
$bb_dropbox_secret_key_placeholder = __( 'Please provide valid App Secret', 'wp-backup-bank' );
$bb_dropbox_get_api_secret_key     = __( 'Get App key & App Secret', 'wp-backup-bank' );

// Email Settings.
$bb_email_backup_to                    = __( 'Backup to Email', 'wp-backup-bank' );
$bb_email_settings_email_address       = __( 'Email Address', 'wp-backup-bank' );
$bb_email_address_placeholder          = __( 'Please provide Email Address', 'wp-backup-bank' );
$bb_email_settings_subject_placeholder = __( 'Please provide Subject', 'wp-backup-bank' );

// FTP Settings.
$bb_ftp_settings_backup_to                = __( 'Backup to FTP', 'wp-backup-bank' );
$bb_ftp_settings_host                     = __( 'Host', 'wp-backup-bank' );
$bb_ftp_settings_host_tooltip             = __( 'You would need to provide a valid FTP host', 'wp-backup-bank' );
$bb_ftp_settings_host_placeholder         = __( 'Please provide your FTP Host', 'wp-backup-bank' );
$bb_ftp_settings_ftp_username             = __( 'Username', 'wp-backup-bank' );
$bb_ftp_settings_ftp_username_tooltip     = __( 'You would need to provide valid FTP Username', 'wp-backup-bank' );
$bb_ftp_settings_ftp_username_placeholder = __( 'Please provide your FTP Username', 'wp-backup-bank' );
$bb_ftp_settings_password                 = __( 'Password', 'wp-backup-bank' );
$bb_ftp_settings_password_tooltip         = __( 'You would need to provide valid FTP Password', 'wp-backup-bank' );
$bb_ftp_settings_password_placeholder     = __( 'Please provide your FTP Password', 'wp-backup-bank' );
$bb_ftp_settings_remote_path              = __( 'Remote Path', 'wp-backup-bank' );
$bb_ftp_settings_remote_path_tooltip      = __( 'You would need to provide the Storage path for your Backup', 'wp-backup-bank' );
$bb_ftp_settings_remote_path_placeholder  = __( 'Please provide your Remote Path', 'wp-backup-bank' );
$bb_ftp_settings_protocol                 = __( 'Protocol', 'wp-backup-bank' );
$bb_ftp_settings_protocol_tooltip         = __( 'You would need to choose a Protocol according to your Server', 'wp-backup-bank' );
$bb_ftps_settings                         = __( 'FTPS', 'wp-backup-bank' );
$bb_ftp_settings_sftp_over_ssh            = __( 'SFTP over SSH', 'wp-backup-bank' );
$bb_ftp_settings_login_type               = __( 'Login Type', 'wp-backup-bank' );
$bb_ftp_settings_login_type_tooltip       = __( 'You would need to choose Login Type', 'wp-backup-bank' );
$bb_ftp_settings_username_password        = __( 'Username & Password', 'wp-backup-bank' );
$bb_ftp_settings_username_only            = __( 'Username Only', 'wp-backup-bank' );
$bb_ftp_settings_anonymous                = __( 'Anonymous', 'wp-backup-bank' );
$bb_ftp_settings_anonymous_no_login       = __( 'No Login', 'wp-backup-bank' );
$bb_ftp_settings_ftp_port                 = __( 'Port', 'wp-backup-bank' );
$bb_ftp_settings_ftp_port_tooltip         = __( 'You would need to provide the Port Number', 'wp-backup-bank' );
$bb_ftp_settings_ftp_port_placeholder     = __( 'Please provide Port Number', 'wp-backup-bank' );
$bb_ftp_settings_ftp_mode                 = __( 'FTP Mode', 'wp-backup-bank' );
$bb_ftp_settings_ftp_mode_tooltip         = __( 'You would need to choose the Mode of FTP', 'wp-backup-bank' );
$bb_ftp_settings_active_mode              = __( 'Active', 'wp-backup-bank' );
$bb_ftp_settings_passive_mode             = __( 'Passive', 'wp-backup-bank' );

// Mailer.
$bb_scheduler    = __( 'Scheduler', 'wp-backup-bank' );
$bb_mailer_hours = __( 'Hours', 'wp-backup-bank' );
$bb_mailer_hour  = __( 'Hour', 'wp-backup-bank' );

// Google Drive.
$bb_google_drive_backup_to              = __( 'Backup to Google Drive', 'wp-backup-bank' );
$bb_google_drive_client_id              = __( 'Client ID', 'wp-backup-bank' );
$bb_google_drive_client_id_placeholder  = __( 'Please provide Client Id', 'wp-backup-bank' );
$bb_google_drive_secret_key             = __( 'Client Secret', 'wp-backup-bank' );
$bb_google_drive_secret_key_placeholder = __( 'Please provide Client Secret', 'wp-backup-bank' );
$bb_google_drive_get_client_secret_key  = __( 'Get Client Id & Client Secret', 'wp-backup-bank' );
