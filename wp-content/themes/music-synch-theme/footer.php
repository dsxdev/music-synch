<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>


<div id="actionFastaddModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="actionFastaddModal" style="display: none;" aria-hidden="true">
               <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                     <form enctype="multipart/form-data" id="actionFastaddModalForm" method="get" action="http://localhost/music-synch/">
                        <input type="hidden" name="doaction" value="fastadd">
                        <div class="modal-header">
                           <h5 class="modal-title" id="exampleModalLongTitle">Spotify Fast Add</h5>
                           <span type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                           </span>
                        </div>
                        <div class="modal-body">
                           <div class="position-relative form-group">
                              <label for="spotifyid" class="">Spotfy URL / ID</label>
                              <input name="spotifyid" id="spotifyid" placeholder="Spotfy URL / ID" type="text" class="form-control">
                           </div>
                           <div class="position-relative form-group">
                              <label for="spotifyid" class="">Data link</label>
                              <input name="datalink" id="datalink" placeholder="youtube / mp3" type="text" class="form-control">
                           </div>
                        </div>
                        <div class="modal-footer">
                           <span type="button" class="btn btn-secondary" data-dismiss="modal">Close</span>
                           <span type="submit" class="btn btn-primary action-fastsynch-start">Start Downloader</span>
                        </div>
                     </form>
                  </div>
               </div>
			</div>
			
<!-- end app main -->
	</div>
<!-- end app container -->
</div>

<?php wp_footer(); ?>

</body>

</html>

