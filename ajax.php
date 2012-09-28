<?php 
ob_start();
header("Content-Type: application/json; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once "core/app.php";

try
{
	switch($_GET['action'])
	{
		case "loginCallback":
			if(!isset($_GET['access_token']))
			{
				throw new AppException("Access token missing",1);
			}
			$app = new App();
			die('{"userId":'.$app->registerUserByAccessToken($_GET['access_token']).'}');
			break;

		case "registerPlace":
			$db = new Database();

			$c = (empty($_REQUEST['cover_url']))?'http://www.thecompliancecenter.com/store/media/catalog/product/cache/1/image/325x/5e06319eda06f020e43594a9c230972d/l/b/lbbl4ps_hi_3.gif':$_REQUEST['cover_url'];

			$db->insertQuery('places',array('id'=>$_REQUEST['id'],'name'=>$_REQUEST['name'],'cover_url'=>$c,'location'=>json_encode($_REQUEST['location'])));

			die('{"id":'.$_REQUEST['id'].'}');
		break;

		case "getPlaces" : 
			$db = new Database();
			echo json_encode($db->getRows('places',array('id','name','cover_url')));
		break;

		case "addViewCount" : 
			$db->insertQuery('views',array(
				'place_id' => $_REQUEST['place_id'],
				'user_id' => $_REQUEST['user_id'] 
			));
		break;

		case "search":
		
			$map = new Map();
			die(json_encode($map->search(@$_REQUEST['lat'],@$_REQUEST['lng'],@$_REQUEST['near'],@$_REQUEST['query'],@$_REQUEST['radius'])));

		break;
	}
}
catch(AppException $ex)
{
	die(print_r($ex));
}

?>