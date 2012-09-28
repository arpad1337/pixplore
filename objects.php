<?php
require_once "core/database.php";

if(isset($_GET['objectId']))
{

	$db = new Database();

	$place = $db->getRow('places',array('id','name','cover_url','location'),"ID = '".$_GET['objectId']."'");

	if(!empty($place))
	{
		echo '
		 <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# fbhack-kriek: http://ogp.me/ns/fb/fbhack-kriek#">
		  <meta property="fb:app_id" content="108479775975719" /> 
		  <meta property="og:type"   content="fbhack-kriek:venue" /> 
		  <meta property="og:url"    content="http://development.kriek.hu/pixplore/objects.php?objectId='.$_GET['objectId'].'" /> 
		  <meta property="og:title"  content="'.$place['name'].'" /> 
		  <meta property="og:description" content="I just dicsovered '.$place['name'].' at this amazing app." />
		  <meta property="place:location:latitude" content="'.$place['location']->latitude.'" />
		  <meta property="place:location:longitude" content="'.$place['location']->longitude.'" />
		  <meta property="og:image"  content="'.$place['cover_url'].'" />';

	}

}
?>