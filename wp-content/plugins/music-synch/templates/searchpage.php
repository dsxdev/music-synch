<div class="app-page-title">
         <div class="page-title-wrapper">
            <div class="page-title-heading">
               <div class="page-title-icon">
                  <i class="pe-7s-cloud-download icon-gradient bg-amy-crisp">
                  </i>
               </div>
               <div><?php echo get_the_title(); ?><br>
                  see demo <a target="_blank" href="https://demo.dashboardpack.com/architectui-html-free/components-accordions.html">DEMO</a>
               </div>
            </div>
            <div class="page-title-actions">

               <div class="d-inline-block dropdown">
                  <button data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-info">
                  <span class="btn-icon-wrapper pr-2 opacity-7">
                  <i class="fa fa-cogs fa-w-20"></i>
                  </span>
                  Suche
                  </button>
                  <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                     <ul class="nav flex-column">
                        <li class="nav-item">
                           <a href="javascript:void(0);" class="synch-action-start nav-link">
                              <span>
                              <i class="fas fa-cloud-download-alt"></i> Start Synch
                              </span>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="javascript:void(0);" class="synch-action-stop nav-link">
                              <span>
                              <i class="fas fa-ban"></i> Stop Synch
                              </span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
         <!-- end page title -->
      </div>
<div id="searchresults" class="searchresults">
    <div id="data-list-albums" class="row musy-datalist">
        <?php foreach($queryresult as $album) {
            include ABSPATH .'wp-content/plugins/music-synch/templates/content-album.php';  
        } ?>
    </div>
</div>