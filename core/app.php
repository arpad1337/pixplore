<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once "core/map.php";
require_once "core/feed.php";
require_once "core/facebook.php";
require_once "core/database.php";
require_once "core/instagram.php";

class App
{
	private $_appId = '108479775975719';
	private $_appSecret = '384d727031518ab779f74cd4255c773e';
	public $Facebook = null;
	public $Database = null;
	public function __construct(){
		$this->Facebook = new Facebook(array(
			"appId" => $this->_appId,
			"secret" => $this->_appSecret
		));
		$this->Database = new Database();
		return true;
	}
	public function registerUserByAccessToken($access_token)
	{
		$this->Facebook->setAccessToken($access_token);
		$userData = $this->Facebook->api('/me',array('id','name'));
		$this->Database->insertQuery('users',array('id'=>$userData['id'],'name'=>$userData['name']));
		return $userData['id'];
	}
}

class AppException extends Exception
{
	public function getJsonObject()
	{
		return json_decode($this);
	}
}

class DatabaseException extends AppException{}

?>