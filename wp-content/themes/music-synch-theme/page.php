<?php
   /**
    * The template for displaying all pages.
    *
    * This is the template that displays all pages by default.
    * Please note that this is the WordPress construct of pages
    * and that other 'pages' on your WordPress site will use a
    * different template.
    *
    * @package understrap
    */
   
   // Exit if accessed directly.
   defined( 'ABSPATH' ) || exit;
   get_header();
   
   ?>
<div class="app-main__outer">
   <div class="app-main__inner">
      
      <?php the_content(); ?>
      <!--
      <div id="data-list-albums" class="row musy-datalist">
         <div class="col-md-4">
            <div class="main-card mb-3 card ">
               <img width="100%" src="https://i.scdn.co/image/ab67616d0000b273e0dc7a999912d9e66b20c2e3" alt="Card image cap" class="card-img-top">
               <div class="card-body">
                  <h5 class="card-title">The flaming Lips</h5>
                  <h6 class="card-subtitle">American head</h6>
                  <div class="collapse" id="collapseExample123">
                     <table class="mb-0 table table-sm">
                        <thead>
                           <tr>
                              <th>Tracks</th>
                              <th>Tracks Synched</th>
                              <th>Label</th>
                              <th>Genre</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <th scope="row">7</th>
                              <td>2</td>
                              <td><a href="#" target="_blank">superfuzz</a></td>
                              <td>Rock / Psychedelic-Rock / Alternative</td>
                           </tr>
                        </tbody>
                     </table>
                     <table class="mb-0 table table-sm">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Track</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td scope="row">1</td>
                              <td>Titlelkj lsll</td>
                              <td><a href="javascript:void(0);" class="mb-2 mr-2 badge badge-success"><i class="pe-7s-check"> </i></a></td>
                           </tr>
                           <tr>
                              <td scope="row">2</td>
                              <td>Titlelkj lsll</td>
                              <td><a href="javascript:void(0);" class="mb-2 mr-2 badge badge-success"><i class="pe-7s-check"> </i></a></td>
                           </tr>
                           <tr>
                              <td scope="row">3</td>
                              <td>Titlelkj lsll</td>
                              <td>
                                 <a href="javascript:void(0);" class="mb-2 mr-2 badge badge-warning"><i class="pe-7s-less"> </i></a>
                              </td>
                           </tr>
                           <tr>
                              <td scope="row">4</td>
                              <td>Titlelkj lsll</td>
                              <td><a href="javascript:void(0);" class="mb-2 mr-2 badge badge-primary"><i class="pe-7s-download"> </i></a></td>
                           </tr>
                           <tr>
                              <td scope="row">5</td>
                              <td>Titlelkj lsll</td>
                              <td><a href="javascript:void(0);" class="mb-2 mr-2 badge badge-primary"><i class="pe-7s-download"> </i></a></td>
                           </tr>
                           <tr>
                              <td scope="row">6</td>
                              <td>Titlelkj lsll</td>
                              <td><a href="javascript:void(0);" class="mb-2 mr-2 badge badge-primary"><i class="pe-7s-download"> </i></a></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="card-footer">
                  <div>
                     <form class="form-inline">
                        <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group"><label for="exampleEmail22" class="mr-sm-2">Data-URL</label><input name="email" id="exampleEmail22" placeholder="youtube / vimeo / mediathek" type="text" class="form-control"></div>
                        <button class="btn btn-light"><i class="pe-7s-refresh-2"> </i></button>
                     </form>
                  </div>
                  <button type="button" class="item-synch-state mb-2 mr-2 btn-transition btn btn-outline-link" aria-expanded="false"><i class="pe-7s-switch"> </i></button>
                  <button type="button" class="item-synch-state active mb-2 mr-2 btn-transition btn btn-outline-link" aria-expanded="false"><i class="pe-7s-switch"> </i></button>
                  <button type="button" data-toggle="collapse" href="#collapseExample123" class="mb-2 mr-2 btn-transition btn btn-outline-link" aria-expanded="false"><i class="pe-7s-info"> </i></button>
               </div>
            </div>
         </div>
      </div>
      -->
   </div>
</div>
<?php get_footer(); ?>