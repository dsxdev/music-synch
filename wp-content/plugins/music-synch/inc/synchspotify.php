<?php
use Stormiix\EyeD3\EyeD3;
class synchspotify {

    function __construct() {
        require spotifyplugindir.'inc/vendor/autoload.php';
    }

    public function wp_admin_hooks() {
        add_filter('manage_edit-album_columns', array($this,'musy_album_columns'));
        add_filter('handle_bulk_actions-edit-album',array($this,'album_bulk_actions_handle'),10,3);
        add_action('manage_album_custom_column', array($this,'album_columns_content'), 10, 3);
        add_filter('bulk_actions-edit-album', array($this,'album_bulk_actions'));
        
    }
    public function album_bulk_actions_handle($redirect_url, $action, $term_ids) {
        $strpos = '_'.$action;
        if(strpos($strpos,'switchstate')) {
            $newstate = str_replace('switchstate','',$action);
            foreach ($term_ids as $term_id) {
                update_term_meta($term_id,'wpcf-album_state',$newstate);
            }
            $redirect_url = add_query_arg('changed-to-'.$newstate, count($term_ids), $redirect_url);
        }
        return $redirect_url;
    }
    public function album_bulk_actions( $actions ) {
        $actions['switchstatego_synch'] = 'Synchronisieren';
        return $actions;
     }
    public function musy_album_columns( $columns ) {
        unset( $columns['description'] );
        unset( $columns['slug'] );
        $columns['posts'] = 'Tracks';
        return $columns;
    }
    function album_columns_content( $__, $column_name, $termid ) {
        $termmetaname = str_replace('wpcf_field_','wpcf-',$column_name);
        $colvalue = get_term_meta($termid,$termmetaname,true);
        if($column_name == 'wpcf_field_album_state') {
            switch($colvalue) {
                case 'go_synch':
                echo '<div class="dashicons dashicons-update"></div> ';
                break;
            }
            return;
        } else {
           // echo $colvalue;
        }
    }

    public function _getsynchspotifydb() {
        $json = json_decode(file_get_contents(spotifyplugindir.'_temp_albumdownload.json'), true);
        
        foreach($json as $album){
           //print_rr($album['album']);
           // print_rr($this->getSpotifyArtistData($album['album']['artists'][0]['id'])); die(); continue; //die();
            //$this->getSpotifyTrackData($album['album']['tracks']['items'][0]['id']);
			$termid = wp_insert_term($album['album']['name'],'album');
			add_term_meta($termid['term_id'],'wpcf-album-spotify-id',$album['album']['id']);
			add_term_meta($termid['term_id'],'wpcf-album-artists',json_encode($album['album']['artists']));
			add_term_meta($termid['term_id'],'wpcf-album-spotify-data',json_encode($album['album']));
			add_term_meta($termid['term_id'],'wpcf-cover',$album['album']['images'][0]['url']);
		}
		die();
    }

    public function fillTracksByYoutubeplaylist($album, $youtubeplaylist) {
        add_term_meta($album->wpterm->term_id,'wpcf-album-synch-data-link',$youtubeplaylist);
        $youtubePlaylistData = $this->getYoutubeplaylistDataByLink($youtubeplaylist);
        //$youtubePlaylistData = json_decode('[[0,"CrlbNR_AiPk"],[1,"vx5oG06KnvY"],[2,"A7JzBuSn_iM"],[3,"jYl_SIC_G-A"],[4,"mnJ0xDoFhC8"],[5,"zIWm7Gfisyk"],[6,"vRteduP2a1o"],[7,"tvts8On_2kg"],[8,"7LHDg2ME_NI"],[9,"0A2OHm3p_9o"],[10,"emkloGyfdUU"]]');
        $tracksWP = array();
        
        foreach($album->tracks->items as $index => $track) {
            $trackWP = $this->getWPTrackBySpotifyId($track->id);
            if($index != $youtubePlaylistData[$index][0]) {
                continue;
            }
            update_post_meta($trackWP->ID,'wpcf-track-synch-data-link','https://www.youtube.com/watch?v='.$youtubePlaylistData[$index][1]);
            update_post_meta($trackWP->ID,'wpcf-track-youtube-data',$youtubePlaylistData[$index]['youtubedata']);
            $tracksWP[] = $tracksWP;
        }
        return $tracksWP;
    }

    public function getAlbumTracksByWPAlbumId($WPAlbumId, $order='asc') {
        $albumTracks = get_posts(array(
            'post_type' => 'track',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                'taxonomy' => 'album',
                'field' => 'term_id',
                'terms' =>  $WPAlbumId
                )
                ),
             'meta_key'  => 'wpcf-track-spotify-number',
             'orderby'   => 'meta_value_num',
             'order'=> $order
        ));
        return $albumTracks;
    }

    public function updatetargetfolder() {
        update_option('synchtargetfolder',$_GET['newfolder']);
        return $_GET['newfolder'];
    }

    public function fastadd() {
        $spotifyAlbumId = spotifyhelper::get_spotifyid_bypar($_GET['spotifyid']);
        $spotifyAlbum = $this->getSpotifyAlbumData($spotifyAlbumId);

        // Alle song data urls mit youtube playlist videos befüllen
        $this->fillTracksByYoutubeplaylist($spotifyAlbum,$_GET['datalink']);
        // array mit song und data urls returnen
        return $spotifyAlbum;
    }

    public function getAvailableGenrelist() {
        include(musicsynchplugin . 'inc/genrelist.php');
        return $genrelist;
    }

    public function collectTrackIDE3data($synchTrackid) { $this->getAvailableGenrelist();
        $wpTrack = $synchTrackid;
        if(!is_object($synchTrackid)) {
            $wpTrack = get_post((int)$synchTrackid);
        }
        $synchTrackid = $wpTrack->ID;
        $trackgenre = get_post_meta((int)$synchTrackid,'wpcf-track-genre',true);
        $tracknumber = get_post_meta((int)$synchTrackid,'wpcf-track-spotify-number',true);

        $trackSpotifyAlbumdata = json_decode(get_post_meta((int)$synchTrackid,'wpcf-track-spotify-data',true));
        $wpTrackAlbum = $this->getWPAlbumBySpotifyid($trackSpotifyAlbumdata->id);
        $trackSpotifydata = $trackSpotifyAlbumdata->tracks->items[(int)$tracknumber-1];
        
        $trackcover = get_term_meta((int)$wpTrackAlbum->term_id,'wpcf-album-cover',true);

        $artistcollection = array();
        $_artists = $trackSpotifyAlbumdata->tracks->items[0]->artists;
        foreach($_artists as $_artist) {
            if(in_array($_artist->name,$artistcollection)) {
                continue;
            }
            $artistcollection[] = $_artist->name;
        }

        $_yeardate = new DateTime($trackSpotifyAlbumdata->release_date);
        $_trackgenre = array();
        if(!empty($trackgenre)) {
            $_trackgenre = explode(';',$trackgenre);
        }
        
        $TagData = array(
            'title' => array($trackSpotifydata->name),
            'artist' => $artistcollection,
            'genre' => $_trackgenre,
            'track_number' => $tracknumber,
            'album' => array($trackSpotifyAlbumdata->name),
            'comment' => array('Label: '.$trackSpotifyAlbumdata->label),
            'year' => array($_yeardate->format('Y')),
            'attached_picture' => array(
                array (
                    'data'=> $trackcover,
                    'picturetypeid'=> 3,
                    'mime'=> 'image/jpeg',
                    'description' => 'My Picture'
                )
            )
        );
        return $TagData;
    }

    public function addalbumsynch() {
        $WPAlbum = get_term_by('id', (int)$_GET['wpalbumid'], 'album');
        $WPAlbumTracks = $this->getAlbumTracksByWPAlbumId((int)$_GET['wpalbumid'], 'desc');
        foreach($WPAlbumTracks as $albumTrack) {
            $newsynch = wp_insert_post( array( 
                'meta_input'  => array(
                    'wpcf-track-id' => $albumTrack->ID,
                ), 'post_status' => 'publish', 'post_type' => 'synch-list','post_title' => '('.$WPAlbum->name.') '.$albumTrack->post_title ) );
            update_post_meta($albumTrack->ID,'wpcf-track-status',2);
        }
        update_term_meta($WPAlbum->term_id,'wpcf-album-status',2);
        return true;
    }

    public function setupSpotifyApi() {
        $synchspotify = new synchspotify();
        $spotifytoken = spotifyhelper::get_spotifytoken();
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($spotifytoken);
        return $api;
    }

    public function getSpotifyTrackData($spotifyTrackId) {
        $api = $this->setupSpotifyApi();
        $_res = $api->getTrack($spotifyTrackId);
        return $_res;
    }

    public function getSpotifyAlbumData($spotifyAlbumId) {
        $_res = $this->getSpotifyAlbumDataWP($spotifyAlbumId);
        if($_res) {
            return $_res;
        } else {
            $api = $this->setupSpotifyApi();
            $_res = $api->getAlbum($spotifyAlbumId);
            $_resWPTerm = $this->saveUploadSpotifyalbumByObject($_res,$api);
            $_res->wpterm = $_resWPTerm;
            return $_res;
        }
    }

    public function getSpotifyAlbumDataWP($spotifyAlbumId) {
        if(is_string($spotifyAlbumId)) {
            $args = array(
                'hide_empty' => false, // also retrieve terms which are not used yet
                'meta_query' => array(
                    array(
                       'key'       => 'wpcf-album-spotify-data',
                       'value'     => $spotifyAlbumId,
                       'compare'   => '='
                    )
                ),
                'taxonomy'  => 'album',
                );
            $spotifyAlbumWP = get_terms( $args );
            if(empty($spotifyAlbumWP)) {
                return false;
            }
            $spotifyAlbumWP = $spotifyAlbumWP[0];
        } else {
            $spotifyAlbumWP = get_term( $spotifyAlbumId );
        }
        
        $spotifyAlbumDataWPJson = get_term_meta($spotifyAlbumWP->term_id,'wpcf-album-spotify-data',true);
        $spotifyAlbumDataWP = json_decode($spotifyAlbumDataWPJson);
        $spotifyAlbumDataWP->wpterm = $spotifyAlbumWP;
        return $spotifyAlbumDataWP;
    }

    public function getWPTrackBySpotifyId($spotifyTrackid) {
        $args = array(
            'post_type' => 'track',
            'meta_query' => array(
                array(
                    'key' => 'wpcf-track-spotify-id',
                    'value' => $spotifyTrackid,
                    'compare' => '=',
                )
            )
         );
        $query = new WP_Query($args);
        $return = array();
        if(!empty($query->posts)) {
            $return = $query->posts[0];
        }
        return $return;
    }

    public function getSpotifyArtistData($spotifyArtistId) {
        $api = $this->setupSpotifyApi();
        $_res = $api->getArtist($spotifyArtistId);
        return $_res;
    }

    public function searchSpotifyAlbum($sstring) {
        $api = $this->setupSpotifyApi();
        $_res = $api->search($sstring, 'album');
        $return = array();
        foreach($_res->albums->items as $album) {
            $return[] = $this->getSpotifyAlbumData($album->id);
        }
        return $return;
    }

    public function synch() {
        var_dump($this->setIDE3Tags('C:\xampp\htdocs\music-synch\wp-content\plugins\music-synch/mp3tmp/triggerhappy.mp3',1517)); die();
        //die();
        /*
        $synchItem = get_posts(array(
            'posts_per_page' => 1,
            'post_type' => 'synch-list',
            'order' => 'asc',
            'offset' => $_GET['page']-1
        ));
        */
        $WPTrack = get_post((int)$_GET['tid']); 
        $targetFile = $this->downloadyoutubeToTmpFolder($WPTrack);
        echo $WPTrack->ID;
        print_rrd($targetFile);

        $this->setIDE3Tags($targetFile,$WPTrack->ID);
        return [
            'trackid' => $WPTrack->ID,
            'trackfile' => $targetFile
        ];
    }

    public function setupgoogleclient() {
        $client = new Google_Client();
        $client->setDeveloperKey('AIzaSyCMs25RMd4ASVh_PiTYbzBEdeCd7FO6ytA');
        $youtube = new Google_Service_YouTube($client);
        return $youtube;
    }

    public function getYoutubeplaylistDataByLink($playlistpar) {
        preg_match('/.*list=(.+)/', $playlistpar, $playlist);
        $playlist = $playlist[1];
        $youtube = $this->setupgoogleclient();
        $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
            'playlistId' => $playlist,
            'maxResults' => 50));
        $return = array();
            foreach($playlistItemsResponse['items'] as $playlistitem) {
                $return[] =  array(
                    $playlistitem['snippet']['position'],
                    $playlistitem['snippet']['resourceId']['videoId'],
                    'youtubedata' => json_encode($playlistitem['snippet'])
                );
            }
        return $return;
    }

    public function collectspotity($api) {
        $sphelper = new spotifyhelper();
        $sphelper->items = array();
        $sphelper->offset = 0;
        $importjson = 
        $sphelper->helpcollectspotity($api,$importjson);
        return $sphelper->items;
    }

    public function saveUploadSpotifyalbumByObject($spotifyalbum,$api) {
        // wenn album existiert exit
        if($this->getWPAlbumBySpotifyid($spotifyalbum->id)) {
            return true;
        }
        
        $genrestring = '';
        $genrecollection = array();
        $_artists = $spotifyalbum->tracks->items[0]->artists;
        foreach($_artists as $_artist) {
            $spotifyArtist = $api->getArtist($_artist->id);
            foreach($spotifyArtist->genres as $keygenre => $genre) {
                if(in_array($genre,$genrecollection)) {
                    continue;
                }
                $genrecollection[] = $genre;
            }
        }
        foreach($genrecollection as $_keygenre => $_genre) {
            if($_keygenre > 0) {
                $genrestring .= ';';
            }
            $genrestring .= $_genre;
        }

        $newWPAlbum = wp_insert_term(
            $spotifyalbum->name,   // the term 
            'album'
        );
        if(!$newWPAlbum['term_id']) {
            return false;
        }
        $newWPAlbum = (int)$newWPAlbum['term_id'];
        add_term_meta($newWPAlbum,'wpcf-album-spotify-id',$spotifyalbum->id);
        add_term_meta($newWPAlbum,'wpcf-album-spotify-data',json_encode($spotifyalbum));
        add_term_meta($newWPAlbum,'wpcf-album-spotify-label',json_encode($spotifyalbum->label));
        add_term_meta($newWPAlbum,'wpcf-album-cover',$spotifyalbum->images[0]->url);
        add_term_meta($newWPAlbum,'wpcf-album-status',1);  

        
        // SAVE ALBUM TRACKS
        foreach($spotifyalbum->tracks->items as $spotifyAlbumTrack) {
            // Insert the post into the database
            $newWPTrack = wp_insert_post( array(
                'post_title'    => $spotifyAlbumTrack->name,
                'post_type' => 'track',
                'post_status'   => 'publish',
            ) );
            wp_set_post_terms($newWPTrack,array($newWPAlbum),'album');
            add_post_meta($newWPTrack,'wpcf-track-spotify-id',$spotifyAlbumTrack->id);
            add_post_meta($newWPTrack,'wpcf-track-spotify-data',json_encode($spotifyalbum));
            add_post_meta($newWPTrack,'wpcf-track-spotify-number',$spotifyAlbumTrack->track_number);
            add_post_meta($newWPTrack,'wpcf-track-genre',$genrestring); 
            add_post_meta($newWPTrack,'wpcf-track-status',1); 
        }
        return get_term_by('id', $newWPAlbum, 'album');
    }

    public function setIDE3Tags($trackpath,$wptrackid) {
        $wpTrack = get_post((int)$wptrackid);
        $TagData = $this->collectTrackIDE3data($wpTrack);
        $albumcover = musicsynchtmpmp3.'cover.jpg';
        $spotifyAlbumdata = json_decode(get_post_meta($wpTrack->ID,'wpcf-track-spotify-data',true));
        file_put_contents($albumcover, file_get_contents($spotifyAlbumdata->images[1]->url));
        $pictureFile = file_get_contents($albumcover);
        $TagData['attached_picture'][0]['data'] = $pictureFile;
        $getID3 = new getID3;
        $tagwriter = new getid3_writetags;
        $tagwriter->filename = $trackpath;
        $tagwriter->tagformats = array('id3v2.4');
        $tagwriter->overwrite_tags    = true;
        $tagwriter->remove_other_tags = true;
        $tagwriter->tag_encoding      = 'UTF-8';
        
        $tagwriter->tag_data = $TagData;
        //print_rrd($TagData);
        if ($tagwriter->WriteTags()){ die();
            return true;
        }else{
            throw new \Exception(implode(' : ', $tagwriter->errors)); die();
        }
    }
    /*
    public function saveUploadSpotifytrackByObject($spotifytrack) {
        // wenn album existiert exit
        if($this->getWPTrackBySpotifyid($spotifytrack['track']['id'])) {
            return true;
        }

        $newWPAlbum = wp_insert_term(
            $spotifyalbum['album']['name'],   // the term 
            'album'
        );
        if(!$newWPAlbum['term_id']) {
            return false;
        }
        $newWPAlbum = $newWPAlbum['term_id'];
        add_term_meta($newWPAlbum,'wpcf-album-spotify-data',$spotifyalbum['album']['name']);
        add_term_meta($newWPAlbum,'wpcf-album-spotify-data',json_encode($spotifyalbum['album']));
        add_term_meta($newWPAlbum,'wpcf-spotify-album-label',json_encode($spotifyalbum['album']['label']));
        add_term_meta($newWPAlbum,'wpcf-album-cover',$spotifyalbum['album']['images'][0]['url']);
        add_term_meta($newWPAlbum,'wpcf-album-status',1);  
        return true;
    }*/

    public function getWPAlbumBySpotifyid($spotifyAlbumId) {
        $term_args = array( 'taxonomy' => 'album','hide_empty' => false );
        $WPAlbums = get_tags( $term_args );
        $return = false;
        foreach($WPAlbums as $WPAlbum) {
            $key = get_term_meta( $WPAlbum->term_id, 'wpcf-album-spotify-id', true );
            if($key == $spotifyAlbumId) {
                $return = $WPAlbum;
            }
        }
        return $return;
    }    

    public function downloadyoutubeToTmpFolder($WPTrack) {
        $WPTrackMeta = get_post_meta($WPTrack->ID);
        $tracktitle = preg_replace("/[^a-z0-9\.]/", "", strtolower($WPTrack->post_title));
        $url = $WPTrackMeta['wpcf-track-synch-data-link'][0];
        $tmpfilepath = musicsynchtmpmp3.$tracktitle;

        //$tmpfilepathcmd = str_replace('/','\\',$tmpfilepath);

        $cmdyoutubedl = '/usr/local/Cellar/ffmpeg/4.3.1_1/bin/ffmpeg';
        $cmdyoutubedl = 'youtube-dl';
        
        $cmdffmpeg = '/usr/local/Cellar/youtube-dl/2020.09.20/bin/youtube-dl';
        $cmdffmpeg = 'ffmpeg';

        $cmd = $cmdyoutubedl.' --extract-audio --audio-format mp3 -o "'.str_replace('/','\\',$tmpfilepath).'.%(ext)s" "'.$url.'"';
        $exec = exec($cmd);
       // $cmd = $cmdffmpeg.' -y -i \''.$tmpfilepath.'.webm\' -vn -acodec libmp3lame \'-q:a\' 5 \''.str_replace('/','\\',$tmpfilepath).'.mp3\'';
       // $exec = exec($cmd);
        $cmd = 'rm \''.$tmpfilepath.'.webm\'';
        $exec = exec($cmd);
        $file = $tmpfilepath.'.mp3';
        return $file;
        //$WPMp3MediaFile = $this->uploadMp3toWPMedia($file);
        $targetFolder = get_option('synchtargetfolder');
        $targetfile = $targetFolder . $tracktitle . '.mp3';
        $cmd = 'mv \''.$file.'\' \''.$targetfile.'\'';
        $exec = shell_exec($cmd);
        return $targetfile;
    }

    public function trackFromTmpToTarget($file,$tracktitle) {
        $targetFolder = get_option('synchtargetfolder');
        $targetfile = $targetFolder . $tracktitle . '.mp3';
        $cmd = 'mv \''.$file.'\' \''.$targetfile.'\'';
        $exec = shell_exec($cmd);
        return $targetfile;
    }

    public function uploadMp3toWPMedia($file) {
        $filename = basename($file);

        $upload_file = wp_upload_bits($filename, null, file_get_contents($file));
        if (!$upload_file['error']) {
            print_r($upload_file); die();
            $wp_filetype = wp_check_filetype($filename, null );
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                wp_update_attachment_metadata( $attachment_id,  $attachment_data );
            }
            return $attachment_id;
        } else {
            return false;
        }
    }

    public function getTrackStatusLabel($status) {
        switch($status) {
            case 1:
                return 'Track angelegt';
            break;
            case 2:
                return 'Track synch';
            break;
            case 3:
                return 'Track synch finished';
        }
        return 'ERROR';
    }

    public function loadtemplate($file) {
        include ABSPATH .'wp-content/plugins/music-synch/templates/'.$file; 
    }

    public function collectsynch() {
        $synchlist = array();

        return $synchlist;
    }
    public function jssynch() {
        $this->loadtemplate('synchlist.php');
    }

    public function pagealbum() {
        $album = null;
        $this->loadtemplate('album.php');
    }
    
    public function pagesynch() {
        $this->loadtemplate('synchpage.php');
    }

    public function mainsearch() {
        switch($_GET['s-what']) {
            case 'album':
                $queryresult = $this->searchSpotifyAlbum($_GET['sstring']);
            break;

            case 'track':
                $queryresult = $this->searchSpotifyTrack($_GET['sstring']);
            break;
        }
        include ABSPATH .'wp-content/plugins/music-synch/templates/searchpage.php'; 
    }
}

?>