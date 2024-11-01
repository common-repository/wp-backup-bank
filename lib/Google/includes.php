<?php
/**
 * This file is used to include the files .
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/google
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/autoload.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/autoload.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Logger/Abstract.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Logger/Abstract.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Logger/Null.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Logger/Null.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Http/CacheParser.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Http/CacheParser.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Task/Retryable.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Task/Retryable.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/IO/Abstract.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/IO/Abstract.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/IO/Curl.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/IO/Curl.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/google/Cache/Abstract.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/google/Cache/Abstract.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Cache/File.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Cache/File.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Client.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Client.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Utils.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Utils.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Model.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Model.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Http/REST.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Http/REST.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Http/Request.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Http/Request.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Service/Resource.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Service/Resource.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Collection.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Collection.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Config.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Config.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Auth/Abstract.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Auth/Abstract.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Auth/OAuth2.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Auth/OAuth2.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Service.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Service.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/Service/Drive.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/Service/Drive.php';
}
if ( file_exists( BACKUP_BANK_DIR_PATH . 'lib/Google/google-extensions.php' ) ) {
	include_once BACKUP_BANK_DIR_PATH . 'lib/Google/google-extensions.php';
}
