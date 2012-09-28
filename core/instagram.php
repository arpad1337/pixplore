<?php

/**
 * Instagram API class
 * API Documentation: http://instagram.com/developer/
 * Class Documentation: https://github.com/cosenary/Instagram-PHP-API/blob/master/README.markdown
 * 
 * @author Christian Metz
 * @since 30.10.2011
 * @copyright Christian Metz - MetzWeb Networks 2012
 * @version 1.5
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 */

class Instagram {

  /**
   * The API base URL
   */
  const API_URL = 'https://api.instagram.com/v1/';

  /**
   * The API OAuth URL
   */
  const API_OAUTH_URL = 'https://api.instagram.com/oauth/authorize';

  /**
   * The OAuth token URL
   */
  const API_OAUTH_TOKEN_URL = 'https://api.instagram.com/oauth/access_token';

  /**
   * The Instagram API Key
   * 
   * @var string
   */
  private $_apikey;

  /**
   * The Instagram OAuth API secret
   * 
   * @var string
   */
  private $_apisecret;

  /**
   * The callback URL
   * 
   * @var string
   */
  private $_callbackurl;

  /**
   * The user access token
   * 
   * @var string
   */
  private $_accesstoken;

  /**
   * Available scopes
   * 
   * @var array
   */
  private $_scopes = array('basic', 'likes', 'comments', 'relationships');


  /**
   * Default constructor
   *
   * @param array|string $config          Instagram configuration data
   * @return void
   */
  public function __construct($config) {
    if (true === is_array($config)) {
      // if you want to access user data
      $this->setApiKey($config['apiKey']);
      $this->setApiSecret($config['apiSecret']);
      $this->setApiCallback($config['apiCallback']);
    } else if (true === is_string($config)) {
      // if you only want to access public data
      $this->setApiKey($config);
    } else {
      throw new InstagramException("Error: __construct() - Configuration data is missing.",1001);
    }
  }

  /**
   * Generates the OAuth login URL
   *
   * @param array [optional] $scope       Requesting additional permissions
   * @return string                       Instagram OAuth login URL
   */
  public function getLoginUrl($scope = array('basic')) {
    if (is_array($scope) && count(array_intersect($scope, $this->_scopes)) === count($scope)) {
      return self::API_OAUTH_URL.'?client_id='.$this->getApiKey().'&redirect_uri='.$this->getApiCallback().'&scope='.implode('+', $scope).'&response_type=code';
    } else {
      throw new InstagramException("Error: getLoginUrl() - The parameter isn't an array or invalid scope permissions used.",1002);
    }
  }

  /**
   * Search for a user
   *
   * @param string $name                  Instagram username
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   */
  public function searchUser($name, $limit = 0) {
    return $this->_makeCall('users/search', array('q' => $name, 'count' => $limit));
  }

  /**
   * Get user info
   *
   * @param integer [optional] $id        Instagram user id
   * @return mixed
   */
  public function getUser($id = 0) {
    return $this->_makeCall('users/'.$id);
  }

  /**
   * Get user activity feed
   *
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   */
  public function getUserFeed($limit = 0) {
    return $this->_makeCall('users/self/feed', array('count' => $limit));
  }

  /**
   * Get user recent media
   *
   * @param integer [optional] $id        Instagram user id
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   */
  public function getUserMedia($id = 'self', $limit = 0) {
    return $this->_makeCall('users/'.$id.'/media/recent', array('count' => $limit));
  }

  /**
   * Get the liked photos of a user
   *
   * @param integer [optional] $limit     Limit of returned results
   * @return mixed
   */
  public function getUserLikes($limit = 0) {
    return $this->_makeCall('users/self/media/liked', array('count' => $limit));
  }

  /**
   * Search media by its location
   *
   * @param string $lat                   Latitude of the center search coordinate
   * @param string $lng                   Longitude of the center search coordinate
   * @return mixed
   */
  public function searchMedia($lat, $lng, $distance = 1000, $count = 20, $min_timestamp, $max_timestamp) {
    return $this->_makeCall('media/search', array(
		'lat' => $lat, 
		'lng' => $lng, 
		'distance' => $distance, 
		'count' => $count,
		'min_timestamp' => $min_timestamp,     
		'max_timestamp' => $max_timestamp
		)
	);
  }
  
  public function searchLocation($lat, $lng, $distance = 1000,$foursquare_id = null,$foursquare_v2_id = null){
	  return $this->_makeCall('locations/search', array('lat' => $lat, 'lng' => $lng, 'distance' => $distance, 'count' => 100,'foursquare_id'=>$foursquare_id,'foursquare_v2_id'=>$foursquare_v2_id));
  }

  public function getLocation($id)
  {
    return $this->_makeCall('/locations/'.$id, null);
  }
  
  public function getLocationImages($id,$count,$max_id = null){
	return $this->_makeCall('/locations/'.$id.'/media/recent', array(
	  	'count' => $count,
		  'max_id' => $max_id 
	));
  }

  public function getGeography($id)
  {
    return $this->_makeCall('/geographies/'.$id, false, null);
  }

  public function getGeographyImages($id,$count,$min_id){
    return $this->_makeCall('/geographies/'.$id.'/media/recent', array(
      'count' => $count,
      'min_id'=>$min_id
    ));
  }

  public function like($media_id){

    $apiCall = self::API_URL.'media/'.$media_id.'/likes';

    $params = array("access_token" => $this->_accesstoken);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiCall);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $jsonData = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($statusCode != 200)
    {
      throw new InstagramException("Error thrown at Instagram: ".$jsonData, 1000);
    }
    curl_close($ch);

    $apiCall = self::API_URL.'media/'.$media_id.'/comments';

    $params['text'] = "Nice pics. #pestagram";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiCall);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $jsonData = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($statusCode != 200)
    {
      throw new InstagramException("Error thrown at Instagram: ".$jsonData, 1000);
    }
    curl_close($ch);
    
    return json_decode($jsonData);
  }

  public function subscribe($type,$token,$objectData)
  {
    $params = array(
      'client_id' => $this->_apikey,
      'client_secret' => $this->_apisecret,
      'callback_url' => $this->_callbackurl,
      'aspect' => 'media',
      'verify_token' => $token
      );
    switch ($type) {
      case 'location':
        $params['object'] = 'location';
        $params['object_id'] = $objectData['object_id'];
      break;
      case 'geography':
        $params['object'] = 'geography'; 
        $params['lat'] = $objectData['lat'];
        $params['lng'] = $objectData['lng'];
        $params['radius'] = $objectData['radius'];
      break;
    }

    $apiCall = self::API_URL.'subscriptions';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiCall);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $jsonData = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($statusCode != 200)
    {
      throw new InstagramException("Error thrown at Instagram: ".$jsonData, 1000);
    }
    curl_close($ch);
    
    return json_decode($jsonData);
  }
  
  

  /**
   * Get media by its id
   *
   * @param integer $id                   Instagram media id
   * @return mixed
   */
  public function getMedia($id) {
    return $this->_makeCall('media/'.$id);
  }

  /**
   * Get the most popular media
   *
   * @return mixed
   */
  public function getPopularMedia() {
    return $this->_makeCall('media/popular');
  }

  /**
   * Search for tags by name
   *
   * @param string $name                  Valid tag name
   * @return mixed
   */
  public function searchTags($name) {
    return $this->_makeCall('tags/search', array('q' => $name));
  }

  /**
   * Get info about a tag
   *
   * @param string $name                  Valid tag name
   * @return mixed
   */
  public function getTag($name) {
    return $this->_makeCall('tags/'.$name);
  }

  /**
   * Get a recently tagged media
   *
   * @param string $name                  Valid tag name
   * @return mixed
   */
  public function getTagMedia($name,$next_max_tag_id) {
    return $this->_makeCall('tags/'.$name.'/media/recent',array("max_tag_id"=>$next_max_tag_id));
  }

  /**
   * Get the OAuth data of a user by the returned callback code
   *
   * @param string $code                  OAuth2 code variable (after a successful login)
   * @param boolean [optional] $token     If it's true, only the access token will be returned
   * @return mixed
   */
  public function getOAuthToken($code, $token = false) {
    $apiData = array(
      'grant_type'      => 'authorization_code',
      'client_id'       => $this->getApiKey(),
      'client_secret'   => $this->getApiSecret(),
      'redirect_uri'    => $this->getApiCallback(),
      'code'            => $code
    );
    
    $result = $this->_makeOAuthCall($apiData);
    return (false === $token) ? $result : $result->access_token;
  }

  /**
   * The call operator
   *
   * @param string $function              API resource path
   * @param array [optional] $params      Additional request parameters
   * @param boolean [optional] $auth      Whether the function requires an access token
   * @return mixed
   */
  private function _makeCall($function, $params = null) {
      // if the call needs a authenticated user
    if (true === isset($this->_accesstoken)) {
      $authMethod = '?access_token='.$this->getAccessToken();
    } else {
      $authMethod = '?client_id='.$this->getApiKey();
    }
    
    if (isset($params) && is_array($params)) {
      $params = '&'.http_build_query($params);
    } else {
      $params = null;
    }
    
    $apiCall = self::API_URL.$function.$authMethod.$params;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiCall);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
    $jsonData = curl_exec($ch);

    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($statusCode != 200)
    {
      throw new InstagramException("Error thrown at Instagram: ".$jsonData, 1000);
    }

    curl_close($ch);
    
    return json_decode($jsonData);
  }

  /**
   * The OAuth call operator
   *
   * @param array $apiData                The post API data
   * @return mixed
   */
  private function _makeOAuthCall($apiData) {
    $apiHost = self::API_OAUTH_TOKEN_URL;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiHost);
    curl_setopt($ch, CURLOPT_POST, count($apiData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $jsonData = curl_exec($ch);

    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($statusCode != 200)
    {
      throw new InstagramException("Error thrown at Instagram: ".$jsonData, 1000);
    }

    curl_close($ch);
    
    return json_decode($jsonData);
  }

  /**
   * Access Token Setter
   * 
   * @param object|string $data
   * @return void
   */
  public function setAccessToken($data) {
    (true === is_object($data)) ? $token = $data->access_token : $token = $data;
    $this->_accesstoken = $token;
  }

  /**
   * Access Token Getter
   * 
   * @return string
   */
  public function getAccessToken() {
    return $this->_accesstoken;
  }

  /**
   * API-key Setter
   * 
   * @param string $apiKey
   * @return void
   */
  public function setApiKey($apiKey) {
    $this->_apikey = $apiKey;
  }

  /**
   * API Key Getter
   * 
   * @return string
   */
  public function getApiKey() {
    return $this->_apikey;
  }

  /**
   * API Secret Setter
   * 
   * @param string $apiSecret 
   * @return void
   */
  public function setApiSecret($apiSecret) {
    $this->_apisecret = $apiSecret;
  }

  /**
   * API Secret Getter
   * 
   * @return string
   */
  public function getApiSecret() {
    return $this->_apisecret;
  }
	
  /**
   * API Callback URL Setter
   * 
   * @param string $apiCallback
   * @return void
   */
  public function setApiCallback($apiCallback) {
    $this->_callbackurl = $apiCallback;
  }

  /**
   * API Callback URL Getter
   * 
   * @return string
   */
  public function getApiCallback() {
    return $this->_callbackurl;
  }

}

?>