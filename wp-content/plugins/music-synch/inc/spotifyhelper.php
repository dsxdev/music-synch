<?php
function print_header_json($obj) {
    header('Content-Type: application/json');
    echo json_encode($obj);
    die();
}

class spotifyhelper {
    public $items;
    public $offset;
    public function helpcollectspotity($api) {
        $res_ = $api->getMySavedAlbums(array('limit'=>50,'offset'=>$this->$offset));
        if(!$res_) {
            return false;
        }
		foreach($res_->items as $it_) {
			$this->items[] = $it_;
		}
		if(count($res_->items) < 50){
			return $this->items;
		} else {
            $this->$offset+=50;
			$this->helpcollectspotity($api);
		}
	}

	public static function get_spotifyid_bypar($parameter) {
		return str_replace('https://open.spotify.com/album/','',$parameter);
		//preg_match('/https:\/\/open.spotify.com\/album\/(.+)\?.*/', $parameter, $id);
		//return $id[1];
	}

	public static function save_spotifytoken($token) {
		setcookie('spotifytoken', $token, time()+55*60, "/");
	}
	public static function get_spotifytoken($returnurl = 'http://localhost/music-synch/wp-admin/admin.php?page=synchronisation') {
		$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$returnurl = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
		$token = '';
		if(!isset($_COOKIE['spotifytoken'])) {
            $crediantials = array(
                array(
                    'e6fdc4431a3b4881b0fccf8819b3f872',
                    '90e46f1e0e8944928d178b4865b766ee',
                    'http://localhost/music-synch/setspotifytoken'
                ),
                array(
                    '13025116161544aaaa445678e6a01da5',
                    '04e51538d26e4e3790bfaabb255b254e',
                    'http://localhost/music-synch/setspotifytoken'
                ),
            );
            $crediantial = $crediantials[1];
			$session = new SpotifyWebAPI\Session($crediantial[0],$crediantial[1],$crediantial[2]);

			if (isset($_GET['code'])) {
				$api = new SpotifyWebAPI\SpotifyWebAPI();
				$session->requestAccessToken($_GET['code']);
				$token = $session->getAccessToken();
				spotifyhelper::save_spotifytoken($token);
				return $token;
			} else {
				$options = [
					'scope' => [
						'user-read-email',
						'user-library-read'
					],
				];
				echo '<script type="text/javascript">
					window.location = "'.$session->getAuthorizeUrl($options).'"
				</script>';
				die();
			}
		} else {
			return $_COOKIE['spotifytoken'];
		}
		return $token;
	}
} 

?>