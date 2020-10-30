<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @param UNDERSTRAP
 */
require_once('inc/understrap.php');

/**
 * ******************************
 *  ******************************
 * @param WPSTARSCODE
 *  *******************************
 * ******************************
 */



/**
 * @param TEXTDOMAIN
 */
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );
function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}

function wps_get_text_domain() {
    return 'wp-stars';
}



/**
 * @param INCLUDES
 */

/** THS Bootstrap Navwalker hier laden, da änderungen ab Zeile 109 wegen Klickbarkeit des Primärmenüitems */
require_once('inc/bootstrap-wp-navwalker.php');



/**
 * @param EDITPOSTLINK
 */

function wps_remove_edit_post_link( $link ) {
    return '';
}
add_filter('edit_post_link', 'wps_remove_edit_post_link');


/**
 * @param AUTHOR-API-EXPOSING deaktivieren
 */

add_filter( 'rest_endpoints', function( $endpoints ){
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
});


/**
 * @param SCSSSetup
 */
require_once('wps-modules/scssphp/scss.inc.php');
require_once('wps-modules/scssphp/example/Server.php');
use Leafo\ScssPhp\Server;
use Leafo\ScssPhp\Compiler;

// Check ob ein Scss File neu gerendert werden muss
function check_for_recompile($filename_scss,$import = false){

    $fullPath = __DIR__ . '/scss';
    $cachePath = $fullPath.'/scss_cache';
    $filename_css = 'theme-style.min.css';

    if (!file_exists($cachePath)) {
        mkdir($cachePath, 0644, true);
    }

    if( filemtime($fullPath.'/'.$filename_scss) >  filemtime($fullPath.'/../'.$filename_css) || filesize($fullPath.'/../'.$filename_css) == 0) {
        $directoryMain = $fullPath;
        
        $scss = new Compiler();
        $scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
        $serverMain = new Server($directoryMain,null,$scss);

        // Wenn es ein Importiertes File ist soll das Main File aktualisiert werden, ansonsten das angegebene File
        $serverMain->compileFile($fullPath.'/'.$filename_scss, $fullPath.'/../'.$filename_css);

        return true;
    }
    return false;
}

// Generiertes Stylesheet einfügen und veränderungen überwachen
add_action( 'wp_enqueue_scripts', 'wps_enqueue_styles' );
function wps_enqueue_styles() {

   check_for_recompile('_project.scss',true);
   wp_enqueue_style( 'wps-styles-protoytp', get_stylesheet_directory_uri() . '/' . 'prototyp.min.css' , array(), filemtime(get_stylesheet_directory() . '/prototyp.min.css') );
    wp_enqueue_style( 'wps-styles', get_stylesheet_directory_uri() . '/' . 'theme-style.min.css' , array('child-understrap-styles'), filemtime(get_stylesheet_directory() . '/theme-style.min.css') );
}



add_action( 'wp_enqueue_scripts', 'wps_enqueue_scripts' );
function wps_enqueue_scripts() {
    wp_enqueue_script( 'spotify-sdk', 'https://sdk.scdn.co/spotify-player.js');
    wp_enqueue_script( 'wps-scripts-prototyp', get_stylesheet_directory_uri() . '/prototyp-assets/scripts/main.js', array(), filemtime(get_stylesheet_directory() . '/prototyp-assets/scripts/main.js'));
    wp_enqueue_script( 'wps-scripts', get_stylesheet_directory_uri() . '/js/main.js', array('spotify-sdk','wps-scripts-prototyp'), filemtime(get_stylesheet_directory() . '/js/main.js'));

}




/**
 * @param Sidebars
 */

add_action( 'widgets_init', 'wps_theme_slug_widgets_init' );

function wps_theme_slug_widgets_init() {

    register_sidebar( array(
        'name' => __( 'Zweiter Footer', wps_get_text_domain() ),
        'id' => 'footer-second',
        'description' => __( 'Zweiter Footer unter Fußbereich.', wps_get_text_domain() ),
        'before_widget' => '<div id="%1$s" class="footer-secondary-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widgettitle" style="display: none;">',
        'after_title'   => '</span>',
    ) );

    register_sidebar( array(
        'name' => __( 'Navigation Right', wps_get_text_domain() ),
        'id' => 'nav-right',
        'description' => __( 'Widget neben der Navigation.', wps_get_text_domain() ),
        'before_widget' => '<div id="%1$s" class="nav-right-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widgettitle" style="display: none;">',
        'after_title'   => '</span>',
    ) );


    register_sidebar( array(
        'name' => __( 'Top Bar', wps_get_text_domain() ),
        'id' => 'top-bar',
        'description' => __( 'Top-Bar über dem Header.', wps_get_text_domain() ),
        'before_widget' => '<div id="%1$s" class="top-bar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widgettitle" style="display: none;">',
        'after_title'   => '</span>',
    ) );

    register_sidebar( array(
        'name' => __( 'Top Bar Mobile', wps_get_text_domain() ),
        'id' => 'top-bar-mobile',
        'description' => __( 'Top-Bar Mobile über dem Header.', wps_get_text_domain() ),
        'before_widget' => '<div id="%1$s" class="top-bar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widgettitle" style="display: none;">',
        'after_title'   => '</span>',
    ) );


}


/** 
 * @param BODYOPENING Hook
 */

 add_action('wp_body_open', 'wps_directlink_acc', 0);

 function wps_directlink_acc() {
    ?>
    <div id="skiplinks" role="navigation" aria-label="Direktlinks" class="skip-link sr-only sr-only-focusable">
        <a href="#content">Direkt zum Inhalt</a> 
        <a href="#main-menu">Zur Navigation</a> 
        <a href="#footer">Zum Footer</a> 
    </div>
    
    <?php
 }



add_action( 'vc_after_init', 'vc_remove_frontend_links' );
function vc_remove_frontend_links() {

    vc_disable_frontend(); // this will disable frontend editor

}


// require WP-Stars Widget for wp-admin Home Dashboard
require_once('inc/widget-desktop.php');

// Add defer to script tags
add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10 );
function defer_parsing_of_js( $url ) {
    if ( is_user_logged_in() ) return $url; //don't break WP Admin
    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    return str_replace( ' src', ' defer src', $url );
}
