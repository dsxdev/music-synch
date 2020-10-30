<?php
$synchlist = get_posts(array(
   'posts_per_page' => -1,
   'post_type' => 'synch-list',
   'order' => 'asc',
));
if(!isset($synchspotify))
$synchspotify = new synchspotify();
$genrelist = $synchspotify->getAvailableGenrelist();
?>
<input type="hidden" id="frontendsynchstatus" name="frontendsynchstatus" value="synch-start" />
<div class="main-card mb-3 card">
   <div id="synchlist">
   <div class="modal fade" id="synchlistgenreupdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
                                    <div class="modal-dialog" role="document">
                                       <div class="modal-content">
                                             <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">×</span>
                                                </button>
                                             </div>
                                             <div class="modal-body scroll-area-md">
                                             <div class="modal-genre-list scrollbar-container ps--active-y ps">
                                          <input type="text" class="form-controll" id="synchlistgenreupdatefilter">
                                             <?php foreach($genrelist as $genre): ?>
                                                <div class="genre-badge mb-2 mr-2 badge badge-pill badge-secondary"><?php echo $genre; ?></div>
                                             <?php endforeach; ?>
                                       </div>      
                                          </div>
                                             <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                             </div>
                                       </div>
                                    </div>
                                 </div>
      <div class="synchlist-list">
         <div class="card-body">
            <h5 class="card-title">Synch Liste</h5>
            <ul class="list-group">
               <li class="list-group-item" data-synch-id="" data-track-id="">
                  <span class="icon"></span>
                  <table class="mb-0 table">
                        <thead>
                           <tr>
                              <th>Album</th>
                              <th>Track Number</th>
                              <th>Track Title</th>
                              <th>Genre</th>
                              <th>Kommentar</th>
                              <th>Cover</th>
                           </tr>
                        </thead>
                        <tbody class="synch-list-sortable">
                           <?php foreach($synchlist as $synchItem):  ?>
                           <?php
                           $synchTrackid = get_post_meta($synchItem->ID,'wpcf-track-id',true);
                           
                           
                           $trackIDE3Data = $synchspotify->collectTrackIDE3data($synchTrackid);
                          // print_rrd($trackIDE3Data);
                           ?>
                           <tr data-track-id="<?php echo $synchTrackid; ?>" class="single-track status-failed">
                              <td><?php echo $trackIDE3Data['album'][0]; ?></td>
                              <td><?php echo $trackIDE3Data['track_number'];?></td>
                              <td><?php echo $trackIDE3Data['title'][0];?></td>
                              <td>
                                 <div id="synchlist_genre_wrap">
                                 <?php if(!empty($trackIDE3Data['genre'])):
                                    foreach($trackIDE3Data['genre'] as $genre): ?>
                                    <button data-toggle="modal" data-target="#synchlistgenreupdate" class="genre-badge mb-2 mr-2 badge badge-pill badge-secondary"><?php echo $genre; ?></button>
                                    <?php endforeach; ?>
                                 <?php endif; ?>
                                 </div>
                              </td>
                              <td><?php echo $trackIDE3Data['comment'][0];?></td>
                              <td><img width="100" src="<?php echo $trackIDE3Data['attached_picture'][0]['data']; ?>" /></td>
                           </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
               </li>
            </ul>
         </div>
      </div>
   </div>
</div>