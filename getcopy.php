<?php
/**
 * Plugin Name: gEtCoPy
 * Plugin URI:  https://getcopy.io/
 * Description: Connect any of your social media accounts and get them converted to a website instantly. 
 * Version:     1.0
 * Author:      Webguruz
 * Author URI:  https://getcopy.io/
 * License:     GPLv2 or later
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'wgtgEtCoPy_ver' ) ) {
	define( 'wgtgEtCoPy_ver', '1.0' );
}
// Db version.
if ( ! defined( 'wgtgEtCoPy_dbv' ) ) {
	define( 'wgtgEtCoPy_dbv', '1.9' );
}

if ( ! defined( 'getcopy_plugin_slug' ) ) {
	$plugin_slug = plugin_basename( __DIR__ );
	define( 'getcopy_plugin_slug', $plugin_slug );
}
if ( ! defined( 'getcopycache_key' ) ) {
	define( 'getcopycache_key', 'getcopycache_key' );
}
if ( ! defined( 'cache_allowed' ) ) {
	define( 'cache_allowed', false );
}

define('PLUGINPATH', plugin_dir_path(__FILE__));
define('PLUGINURL', plugin_dir_url(__FILE__));

require_once __DIR__.'/inc/functions.php';
require_once __DIR__.'/inc/hooks.php';
require_once __DIR__.'/inc/setting-fields.php';
require_once __DIR__.'/inc/shortcodes.php';

register_activation_hook( __FILE__, 'wgt_insta_activate' );
register_deactivation_hook( __FILE__, 'wgt_insta_deactivate' );

add_filter( 'plugins_api', 'wgt_insta_info', 20, 3 );
add_filter( 'site_transient_update_plugins', 'wgt_plugin_update' );

function wgt_insta_activate() {
	/* activation code here */
	ob_start();
	add_option( 'Activated_wgt_insta', 'Plugin-wgt_insta' );
	add_option( 'wgtinsta_ver', wgtgEtCoPy_ver );
	wgt_create_database_table();
	wgt_check_token();
}

function wgt_insta_deactivate() {
	/* deactivation code here */
	delete_option( 'Activated_wgt_insta' );
	$timestamp = wp_next_scheduled( 'wgt_insta_cron_schedule_token_refresh' );
	wp_unschedule_event( $timestamp, 'wgt_insta_cron_schedule_token_refresh' );
	
}

/**
 * Add scripts forntend.
*/
function wgt_insta_registration_scripts() {  

    wp_register_style('wgt-getcopy', plugins_url('css/style.css', __FILE__), array(), '1.0.1', 'all');  
	wp_register_style('wgt-getcopy-font', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css', array(), wgtgEtCoPy_ver, 'all');
    wp_enqueue_style('wgt-getcopy');
    wp_enqueue_style('wgt-getcopy-font');
	//$wgt_setting_css = wgt_get_customizer_css();
	//wp_add_inline_style( 'wgt-getcopy', $wgt_setting_css );
	wp_enqueue_script( 'wgt-script', plugins_url( 'js/script.js', __FILE__ ), array('jquery'), '1.0.1', );
	wp_enqueue_script( 'wgt-script-des', plugins_url( 'js/masnory-effect.js', __FILE__ ), array('jquery'), '1.0.1', );
	wp_localize_script( 'wgt-script', 'wgt_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}

add_action( 'wp_enqueue_scripts', 'wgt_insta_registration_scripts' );

/*
* Add admin scripts
*/

function add_media_script($hook) { 
	if( $hook != 'toplevel_page_wgt-getcopy' ) {
		 return;
	}
    wp_enqueue_media();
	wp_enqueue_style( 'wgt-style', plugins_url('css/admin/wgt-style.css', __FILE__) );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wgt-script', plugins_url('js/admin/wgt-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
  }
  
add_action( 'admin_enqueue_scripts', 'add_media_script' );

function wgt_insta_info($res, $action, $args){
	$plugin_slug = getcopy_plugin_slug;
	
	if( 'plugin_information' !== $action ) {
		return false;
	}
	
	// do nothing if it is not our plugin
	if( $plugin_slug !== $args->slug ) {
		return false;
	}
	
	// get updates
	$remote = wgt_request();

	if( empty($remote) ) {
		
		return false;
	}

	$res = new stdClass();

	$res->name = $remote->name;
	$res->slug = $remote->slug;
	$res->version = $remote->version;
	$res->tested = $remote->tested;
	$res->requires = $remote->requires;
	$res->author = $remote->author;
	$res->author_profile = $remote->author_profile;
	$res->download_link = $remote->download_url;
	$res->trunk = $remote->download_url;
	$res->requires_php = $remote->requires_php;
	$res->last_updated = $remote->last_updated;

	$res->sections = array(
		'description' => $remote->sections->description,
		'installation' => $remote->sections->installation,
		'changelog' => $remote->sections->changelog
	);

	if( ! empty( $remote->banners ) ) {
		$res->banners = array(
			'low' => $remote->banners->low,
			'high' => $remote->banners->high
		);
	}

	return $res;
}

function wgt_plugin_update( $transient ) {
	if ( empty($transient->checked ) ) {
		return $transient;
	}
	$plugin_slug = getcopy_plugin_slug;
    $plugin_ver = wgtgEtCoPy_ver;
	$remote = wgt_request();
	if( empty($remote) ) {
		return false;
	}
	if( empty($plugin_ver) ) {
		return false;
	}
	if(version_compare( $plugin_ver, $remote->version, '<' )) {
		$res = new stdClass();
		$res->slug = $plugin_slug;
		$res->plugin = plugin_basename( __FILE__ ); 
		$res->new_version = $remote->version;
		$res->tested = $remote->tested;
		$res->package = $remote->download_url;

		$transient->response[ $res->plugin ] = $res;

	}

	return $transient;

}
		
?>