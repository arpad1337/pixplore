<?php 

class Map
{
	private $_fsqUrl = "https://api.foursquare.com/v2/venues/search";
	private $_fsqConfig = array(
		"CLIENT_ID" => "BZ24IDBJAH5GQGOR3VPAJUS3BDQQZK20VR4WSNXU23K2EXYJ",
		"CLIENT_SECRET" => "5RYKHSE34P5YQTK4JRGDS1OFCECJM33PI0F5B3J2WPM21TYC"
	);
	public $Instagram = null;
	private $_InstaConfig = array(
		'apiKey'      => '4e25a35028e142ce82b385cf40d61783',
		'apiSecret'   => '9f72071fdcdd48a6ab83ee73fa21fcb9',
		'apiCallback' => 'http://development.kriek.hu/pixplore/'
	);
	public function __construct(){
		$this->Instagram = new Instagram($this->_InstaConfig);
	}

	public function search($lat,$lng,$near,$query,$radius=1000)
	{
		/*$v = date("Ymd",time());
		$ch = curl_init();
	
		$params = array(
			"ll"=>!empty($lat)?$lat.','.$lng:null,
			"near"=>$near,
			"query"=>$query,
			"radius"=>$radius,
			"limit"=>50,
			"client_id" => $this->_fsqConfig['CLIENT_ID'],
			"client_secret" => $this->_fsqConfig['CLIENT_SECRET'],
			"v" => $v,
		);
		
		$url = $this->_fsqUrl.'?'.http_build_query($params);
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		
		$jsonResponse = curl_exec($ch);
		curl_close($ch);
		
		$fsqData = json_decode($jsonResponse);*/
		
		return $this->Instagram->searchLocation($lat,$lng,$radius);
		
		//print_r($instaData);print_r($fsqData);
		/*
		$count = count($instaData->data);
		
		foreach($fsqData->response->venues as $key => $venue)
		{
			$i = 0;
			while($i<$count && !(round($venue->location->lat,9) == $instaData->data[$i]->latitude
								 && round($venue->location->lng,9) == $instaData->data[$i]->longitude
								 && $venue->name == $instaData->data[$i]->name)){$i++;}
			if($i == $count){
				unset($fsqData->response->venues[$key]);
			}
			else
			{
				$fsqData->response->venues[$key]->instagram_id = $instaData->data[$i]->id;
			}
		}
		
		$fsqData->response->venues = array_values($fsqData->response->venues);*/
		
		return $fsqData;
	}

}

?>