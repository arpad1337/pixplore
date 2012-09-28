<?php 

header("Content-Type: application/json; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once "core/app.php";

try
{
	switch($_GET['action'])
	{
		case "loginCallback":
			if(isset($_GET['access_token']))
			{
				throw new AppException("Access token missing",1);
			}
			$app = new App();
			//$app->registerUserByAccessToken($_GET['access_token']);
		break;
	}
}
catch(AppException $ex)
{
	die($ex->getJsonObject());
}

?>