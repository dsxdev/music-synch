<?php
   /**
    * The template for displaying archive pages.
    *
    * Learn more: http://codex.wordpress.org/Template_Hierarchy
    *
    * @package understrap
    */
   
   // Exit if accessed directly.
   defined( 'ABSPATH' ) || exit;
   if(!is_archive('album')) {
       die();
   }
   require_once(musicsynchplugin.'inc/vendor/autoload.php'); 
   require_once(musicsynchplugin.'inc/spotifyhelper.php');
   require_once(musicsynchplugin.'inc/synchspotify.php');
   $synchspotify = new synchspotify();
   $termId = (int)get_queried_object()->term_id;
   $spotifyAlbumData = $synchspotify->getSpotifyAlbumDataWP($termId);
   $spotifyAlbumDataWPMeta = get_term_meta($termId);

   $albumTracks = get_posts(array(
       'post_type' => 'track',
       'posts_per_page' => -1,
       'tax_query' => array(
           array(
           'taxonomy' => 'album',
           'field' => 'term_id',
           'terms' =>  $termId
           )
           ),
        'meta_key'  => 'wpcf-track-spotify-number',
        'orderby'   => 'meta_value_num',
        'order'=> 'asc'
   ));
   get_header();
   ?>
<div class="app-main__outer" id="albumarchive">
    <div class="app-main__inner" id="content" tabindex="-1">
      <?php if ( have_posts() ) : ?>
      <div class="single-album-wrap">
         <div class="single-album-data">
            <div class="single-album-data-content">
               <div class="btn album-img" data-toggle="modal" data-target="#albumimagemodal">
                  <img src="<?php echo $spotifyAlbumData->images[1]->url; ?>" alt="">
               </div>
                <div class="modal fade albummodal" id="albumimagemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                            <img src="<?php echo $spotifyAlbumData->images[0]->url; ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
               <table class="mb-0 table">
                  <thead>
                     <tr>
                        <th>Album</th>
                        <th>Artists</th>
                        <th>Label</th>
                        <th>Tracks</th>
                        <th>Data URL</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>
                           <?php echo $spotifyAlbumData->name; ?>
                        </td>
                        <td>
                           <?php foreach($spotifyAlbumData->artists as $artist): ?>
                           <a href="<?php echo get_home_url(); ?>/artist/<?php echo $artist->id; ?>"><?php echo $artist->name; ?></a>
                           <?php endforeach; ?>
                        </td>
                        <td>
                           <a href="<?php echo get_home_url(); ?>/label/?label=<?php echo urlencode($spotifyAlbumData->label); ?>"><?php echo $spotifyAlbumData->label; ?></a>
                        </td>
                        <td><?php echo count($albumTracks); ?></td>
                        <td>
                            <?php echo $spotifyAlbumDataWPMeta['wpcf-album-synch-data-link'][0]; ?>
                        </td>
                        <tr>
                            <td><button data-album-spotify-id="<?php echo $spotifyAlbumData->id; ?>" data-album-wp-id="<?php echo $spotifyAlbumData->wpterm->term_id; ?>" class="mb-2 mr-2 btn btn-success album-action-synch">Synch</button></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="single-album-tracks">
            <table class="mb-0 table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Track</th>
                        <th>Data Link</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($albumTracks as $index => $track): ?>
                <?php 
                $trackmeta = get_post_meta($track->ID);
                $trackspotifydata = $spotifyAlbumData->tracks->items[($trackmeta['wpcf-track-spotify-number'][0])-1];
                $youtubedata = json_decode($trackmeta['wpcf-track-youtube-data'][0]);
                
                ?>
                <tr>
                    <td>
                        <?php echo $trackspotifydata->track_number; if($trackspotifydata->disc_number > 1 ): ?> (DISK <?php echo $trackspotifydata->disc_number;?>)<?php endif; ?>
                    </td>
                    <td>
                        <?php echo $trackspotifydata->name; ?>
                    </td>
                    <td>
                        <button class="mb-2 mr-2 btn btn-light single-track-action-modalyoutube" data-toggle="modal" data-target="#trackyoutubemodal<?php echo $track->ID; ?>"><?php echo $youtubedata->title; ?></button>
                        <div class="modal fade trackyoutubemodal" id="trackyoutubemodal<?php echo $track->ID; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                    <iframe data-src="https://www.youtube.com/embed/<?php echo str_replace('https://www.youtube.com/watch?v=','',$trackmeta['wpcf-track-synch-data-link'][0]); ?>" width="560" height="315" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" class="single-track-albumid" value="<?php echo $track->ID; ?>">
                                        <input type="text" name="single_track_datalink" class="form-control single_track_datalink" value="<?php echo $trackmeta['wpcf-track-synch-data-link'][0]; ?>" />
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Update Data-URL</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </td>
                    <td>
                        <?php echo $synchspotify->getTrackStatusLabel($trackmeta['wpcf-track-status'][0]); ?>
                    </td>   
                    <td>
                        <button data-toggle="tooltip" data-placement="top" title="" data-original-title="Play track" type="button" data-spotifytrackid="<?php echo $trackspotifydata->id; ?>" data-trackwpid="<?php echo $track->ID; ?>" class="single-track-action-play btn-shadow p-1 btn btn-secondary btn-sm">
                            <i class="fa text-white fa-play pr-1 pl-1"></i>
                        </button>
                        <button data-toggle="tooltip" data-placement="top" title="" data-original-title="Fetch Youtubelink via API" type="button" data-spotifytrackid="<?php echo $trackspotifydata->id; ?>" data-trackwpid="<?php echo $track->ID; ?>" class="single-track-action-fetchyoutube btn-shadow p-1 btn btn-secondary btn-sm">
                            <i class="fas fa-file-download text-white pr-1 pl-1"></i>
                        </button>
                        <button data-toggle="tooltip" data-placement="top" title="" data-original-title="Add to Synch" type="button" data-spotifytrackid="<?php echo $trackspotifydata->id; ?>" data-trackwpid="<?php echo $track->ID; ?>" class="single-track-action-addsynch btn-shadow p-1 btn btn-secondary btn-sm">
                            <i class="fas fa-cloud-download-alt text-white pr-1 pl-1"></i>
                        </button>
                    </td>
                </tr>

                <?php endforeach; ?>
                </tbody>
            </table>

            
         </div>
      </div>
      <!-- end page title -->
    </div>
    <?php /* Start the Loop */ ?>
    <?php else : ?>
        ALBUM EXISTIERT NICHT 
    <?php endif; ?>
</div>
<!-- #content -->
</div><!-- #archive-wrapper -->
<?php get_footer(); ?>