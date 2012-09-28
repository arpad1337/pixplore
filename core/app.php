<?php
require_once "map.php";
require_once "feed.php";
require_once "facebook.php";
require_once "database.php";


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
}

class AppException extends Exception
{
	public function getJsonObject()
	{
		return json_encode($this);
	}
}

?>