<?php// print_rrd($album); ?>
<?php $artiststring = '';
foreach($album->artists as $key => $artist){
    if($key+1 < count($album->artists) && $key > 0){
        $artiststring .= ', ';
    }
    $artiststring .= $artist->name; 
}
?>
<article class="col-md-2">
    <div class="main-card mb-3 card ">
    <img width="100%" src="<?php echo $album->images[0]->url; ?>" alt="Card image cap" class="card-img-top">
    <div class="card-body">
        <h5 class="card-title"><?php echo $album->name; ?></h5>
        <h6 class="card-subtitle"><?php echo $artiststring; ?></h6>
        <div class="collapse" id="albumdata<?php echo $album->id; ?>">
            <!--<table class="mb-0 table table-sm">
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
            </table>-->
            <table class="mb-0 table table-sm">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Track</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($album->tracks->items as $track): ?>
                    <tr data-spotify-id="<?php echo $track->id; ?>">
                        <td scope="row"><?php echo $track->track_number; ?> <?php if($track->disc_number > 1):?>(DISC <?php echo $track->disc_number; ?>)<?php endif; ?></td>
                        <td><?php echo $track->name; ?></td>
                    <td><a href="javascript:void(0);" class="mb-2 mr-2 badge badge-success"><i class="pe-7s-check"> </i></a></td>
                </tr>
                <?php endforeach; ?>
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
        <button type="button" data-toggle="collapse" href="#albumdata<?php echo $album->id; ?>" class="mb-2 mr-2 btn-transition btn btn-outline-link" aria-expanded="false"><i class="pe-7s-info"> </i></button>
    </div>
    </div>
</article>