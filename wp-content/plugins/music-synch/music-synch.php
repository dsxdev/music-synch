<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Music_Synch
 *
 * @wordpress-plugin
 * Plugin Name:       music-synch
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Daniel
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       music-synch
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
use Stormiix\EyeD3\EyeD3;
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MUSIC_SYNCH_VERSION', '1.0.0' );
define('spotifyplugindir',plugin_dir_path(__FILE__));
define('musicsynchplugin',plugin_dir_path(__FILE__));
define('musicsynchtmpmp3',plugin_dir_path(__FILE__).'mp3tmp/');

function synchronisatoin_register_options_page() {
	add_menu_page('Synchronisation Einstellungen', 'Synchronisation', 'manage_options', 'synchronisation', 'settingspage_master');
}
add_action('admin_menu', 'synchronisatoin_register_options_page');
  
function my_enqueue($hook) {
    wp_enqueue_script('musicsynchadmin', plugin_dir_url(__FILE__) . '/admin.js',array('jquery'));
}

add_action('admin_enqueue_scripts', 'my_enqueue');

function frontend_enqueue_scripts() {
    wp_enqueue_script('jquery-ui-sortable');
}

add_action('wp_enqueue_scripts', 'frontend_enqueue_scripts');

function print_rr($r) {
	echo '<pre>';print_r($r);echo '</pre>';
}
function print_rrd($r) {
	echo '<pre>';print_r($r);echo '</pre>'; die();
}

add_action( 'wp_ajax_loadspotifydata', 'loadspotifydataajax' );
function loadspotifydataajax() {
	
	require_once(plugin_dir_path(__FILE__).'inc/vendor/autoload.php'); 
	require_once(plugin_dir_path(__FILE__).'inc/spotifyhelper.php');
	require_once(plugin_dir_path(__FILE__).'inc/synchspotify.php');
	
	$synchspotify = new synchspotify();
	$api = $synchspotify->setupSpotifyApi();
	$spotifyalbumcollection = json_decode(file_get_contents(spotifyplugindir.'_temp_albumdownload.json'), true);
	//$spotifyalbumcollection = $synchspotify->collectspotity($api);

	foreach($spotifyalbumcollection as $key => $spotifyalbum) {
		if($key > 26) {
		break;
		}
		$synchspotify->saveUploadSpotifyalbumByObject($spotifyalbum); 
	}
	die();
}

function admin_init_fnc() {
	require_once(plugin_dir_path(__FILE__).'inc/vendor/autoload.php'); 
	require_once(plugin_dir_path(__FILE__).'inc/spotifyhelper.php');
	require_once(plugin_dir_path(__FILE__).'inc/synchspotify.php');
	$sphelper = new spotifyhelper();
	$synchspotify = new synchspotify();
	// ADMIN HOKS
	$synchspotify->wp_admin_hooks();
	

	// GET FUNCTIONS
	if(isset($_GET['doaction'])) {
		header('Content-Type: application/json');
		echo json_encode($synchspotify->{$_GET['doaction']}());
		die();
	}

	
}
add_action('admin_init','admin_init_fnc');
function settingspage_master()
{
	include plugin_dir_path(__FILE__).'/templates/settingspage-master.php';
}

add_action('wp_loaded','frontend_init');
function frontend_init() {
	if(is_admin())
		return;
	require_once(plugin_dir_path(__FILE__).'inc/vendor/autoload.php'); 
	require_once(plugin_dir_path(__FILE__).'inc/spotifyhelper.php');
	require_once(plugin_dir_path(__FILE__).'inc/synchspotify.php');
	$strpos = '_'.$_SERVER['REQUEST_URI'];
	if(strpos($strpos,'music-synch/setspotifytoken/') > 0)
		spotifyhelper::get_spotifytoken();
	
	$synchspotify = new synchspotify();
	if(isset($_GET['doaction'])) {
		header('Content-Type: application/json');
		echo json_encode($synchspotify->{$_GET['doaction']}());
		die();
	}
}

add_shortcode('synchpage','pagesynchfnc');
function pagesynchfnc() {
	if(is_admin()) {
		return;
	}
	require_once(plugin_dir_path(__FILE__).'inc/vendor/autoload.php'); 
	require_once(plugin_dir_path(__FILE__).'inc/spotifyhelper.php');
	require_once(plugin_dir_path(__FILE__).'inc/synchspotify.php');
	
	$synchspotify = new synchspotify();
	$synchspotify->pagesynch();
}

add_shortcode('pagesearch','pagesearchfnc');
function pagesearchfnc() {
	require_once(plugin_dir_path(__FILE__).'inc/vendor/autoload.php'); 
	require_once(plugin_dir_path(__FILE__).'inc/spotifyhelper.php');
	require_once(plugin_dir_path(__FILE__).'inc/synchspotify.php');

	if(isset($_GET['sstring'])) {
		$synchspotify = new synchspotify();
		$synchspotify->mainsearch();
	}
}