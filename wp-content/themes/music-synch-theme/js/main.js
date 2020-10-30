jQuery( document ).ready(function($) {

    if($('#frontendsynchstatus').length > 0) {
        //do_synch();
    }

    function do_synch() {
        if($('#frontendsynchstatus').val('synch-start')){
            let _currentrackrow = $('.synchlist-list table tr.single-track').first();
            let _tid = _currentrackrow.attr('data-track-id');
            _tid
            doAction({
                doaction: 'synch',
                tid: _tid
            },function(msg) {
                console.log(msg);
            });
        }
    }

    /*window.onSpotifyWebPlaybackSDKReady = () => {
        const token = 'BQBn9FsX18xQZRoVlZH_ecF1VYFydmIB6fwnBEkEA_p8Cd-QYuESOCUtJaFxLFgRlyODSfyxWdCNicW1ySaeONgSsCakAP6K-5nwRZrObq01CPuVHSE7qgLpSNV9ZDidREJIT1Fw5OG6HnrCv9H3QQFEMvihC9HcWR-JtN6cHQ';
      
        var player = new Spotify.Player({
            name: 'Carly Rae Jepsen Player',
            getOAuthToken: callback => {
              callback(token);
            },
            volume: 0.5
          });
    };*/
    const play = ({
        spotify_uri,
        playerInstance: {
          _options: {
            getOAuthToken,
            id
          }
        }
      }) => {
        getOAuthToken(access_token => {
          fetch(`https://api.spotify.com/v1/me/player/play?device_id=${id}`, {
            method: 'PUT',
            body: JSON.stringify({ uris: [spotify_uri] }),
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer 'BQBn9FsX18xQZRoVlZH_ecF1VYFydmIB6fwnBEkEA_p8Cd-QYuESOCUtJaFxLFgRlyODSfyxWdCNicW1ySaeONgSsCakAP6K-5nwRZrObq01CPuVHSE7qgLpSNV9ZDidREJIT1Fw5OG6HnrCv9H3QQFEMvihC9HcWR-JtN6cHQ'`
            },
          });
        });
      };
      
      const token = 'BQBn9FsX18xQZRoVlZH_ecF1VYFydmIB6fwnBEkEA_p8Cd-QYuESOCUtJaFxLFgRlyODSfyxWdCNicW1ySaeONgSsCakAP6K-5nwRZrObq01CPuVHSE7qgLpSNV9ZDidREJIT1Fw5OG6HnrCv9H3QQFEMvihC9HcWR-JtN6cHQ';
      
      play({
        playerInstance: new Spotify.Player({ name: "..." }),
        spotify_uri: 'spotify:track:7xGfFoTpQ2E7fRF5lN10tr',
      });
      




    $('#synchlist_genreinput').click(function(){
        $('#synchlist_genreinput_dropdown_wrap').addClass('active');
    }); 
    $('#synchlistgenreupdatefilter').keyup(function(){
        let _val = $(this).val();
        if($(this).val()==''){
            $(this).parent().find('.genre-badge').css('display','initial');
        } else{
            $(this).parent().find('.genre-badge').css('display','none');
        }
        $(this).parent().find('.genre-badge').each(function() {
            if($(this).html().includes(_val)) {
                $(this).css('display','initial');
            }
        }); 
    });
    $('#synchlistgenreupdate').on('.genre-badge','click',function() {
        $(this).toggleClass('active');
        $(this).parent().find('.active');
        
    });

    $('#synchlist_genreinput_dropdown_wrap').on('click','.list-group-item-action',function(){
        let _genreinput = $('#'+$(this).parent().attr('data-inputid'));
        _genreinput.val($(this).html());
        $(this).parent().html('');
    });

    $('#main_search .search-icon').click(function(e) {
        if($(this).hasClass('active')) {
            $(this).closest('form').submit();
        }
        $(this).addClass('active');
    });
    $('.search-wrapper .close').click(function(e) {
        $('#main_search .search-icon').removeClass('active');
    });

    $('.synch-list-sortable').sortable();


    $('.single-track-action-modalyoutube').click(function(e) {
        e.preventDefault();
        let _iframe = $($(this).attr('data-target')).find('iframe');
        _iframe.attr('src',_iframe.attr('data-src'));
    });

    $('.action-fastsynch-start').click(function(e){
        e.preventDefault();        
        $.ajax({
            url: $('#actionFastaddModalForm').attr('action'),
            data: {
                doaction: 'fastadd',
                spotifyid: $('#actionFastaddModalForm #spotifyid').val(),
                datalink: $('#actionFastaddModalForm #datalink').val()
            }
        }).done(function(msg) {
            console.log(msg);
            if(msg.wpterm) {
                location.href="http://localhost/music-synch/album/"+msg.wpterm.slug;
            } else {
                alert('fast add failed!');
            }
        });
    });

    $('.album-action-synch').click(function() {
        $(this).attr('disabled','disabled');
        let _wpalbumid = $(this).attr('data-album-wp-id');
        doAction({
            doaction: 'addalbumsynch',
            wpalbumid: _wpalbumid
        },function(msg) {
        });
    });

    $('#updatetargetfolder').change(function() {
        let _newfolder = $(this).val();
        let _this = $(this);
        doAction({
            doaction: 'updatetargetfolder',
            newfolder: _newfolder
        },function(msg) {
            _this.val(msg);
        });
    });

    function doAction(_data,_fnc) {
        $.ajax({
            url: 'http://localhost/music-synch/',
            data: _data
        }).done(function(msg) {
            _fnc(msg);
        });
    }
});