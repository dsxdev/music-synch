<?php
   /**
    * The header for our theme.
    *
    * Displays all of the <head> section and everything up till <div id="content">
    *
    * @package understrap
    */
   
   // Exit if accessed directly.
   defined( 'ABSPATH' ) || exit;
   
   $container = get_theme_mod( 'understrap_container_type' );
   ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
   <head>
      <meta charset="<?php bloginfo( 'charset' ); ?>">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="profile" href="http://gmpg.org/xfn/11">
      <?php wp_head(); ?>
      
   </head>
   <body <?php body_class(); ?>>
      <?php do_action( 'wp_body_open' ); ?>
      <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header closed-sidebar-mobile closed-sidebar">
      <div class="app-header header-shadow">
         <div class="app-header__content">
            <div class="app-header-left">
               <form  action="<?php echo get_home_url();?>/suche/" method="GET">
                  <div class="search-wrapper">
                     <div class="input-holder" id="main_search">
                        <input type="text" name="sstring" class="search-input btn" placeholder="Type to search">
                        <span class="search-icon"><span></span></span>
                        <select id="s_what_spotify" name="s-what" class="s-what mb-2 form-control">
                              <option value="album" selected="selected">Album</option>
                              <option value="artist">Artist</option>
                        </select>
                        <select style="display: none" id="s_what_discogs" class="s-what mb-2 form-control">
                              <option value="album" selected="selected">Label</option>
                        </select>
                        <select name="s-type" class="mb-2 form-control s-type">
                              <option value="spotify" selected="selected">Spotify</option>
                              <option value="discogs">Discogs</option>
                        </select>
                     </div>
                     <span class="close"></span>
                  </div>
                  </form>
                  <ul class="header-menu nav">
                     <li class="nav-item">
                        <a href="<?php echo get_home_url(); ?>/wp-admin" class="nav-link">
                           <i class="nav-link-icon fa fa-database"> </i>
                           WP-Admin
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="<?php echo get_home_url(); ?>/dashboard" class="nav-link">
                           <i class="nav-link-icon fa fa-database"> </i>
                           Dashboard
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="<?php echo get_home_url(); ?>/synch" class="nav-link">
                        <i class="nav-link-icon fa fa-database"> </i>
                        Synch
                        </a>
                     </li>
                  </ul>
            </div>
            <div class="app-header-right">
               <div class="update-synch-target-folder">
                  <a target="_blank" href="https://dashboardpack.com/live-demo-free/?livedemo=329&v=fa868488740a">prototyp-demo</a>
                  <div class="wrap-updatetargetfolder">
                     <label for="targetfolder" class="">Folder URL</label>
                     <input name="targetfolder" id="updatetargetfolder" value="<?php echo get_option('synchtargetfolder'); ?>" placeholder="" type="text" class="form-control">
                  </div>
               </div>
               <button id="actionFastaddHandler" data-toggle="modal" data-target="#actionFastaddModal" class="mb-2 mr-2 btn btn-link header-right-btns"><i class="fas fa-folder-plus"></i></button>
               <button id="actionSynchstarter" class="mb-2 mr-2 btn btn-link header-right-btns"><i class="fas fa-sync-alt"></i></button>
            </div>
         </div>
      </div>
      <div class="app-main">