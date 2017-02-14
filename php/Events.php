<?php
require_once('utils/DBConnector.php');

require_once('utils/JSONWriter.php');

require_once('utils/Guard.php');



$db = new DBConnector();

$connection = $db->AdminConnection();
$jsonWriter = new JSONWriter();

$response = file_get_contents("https://graph.facebook.com/oauth/access_token?%20client_id=1030518733661195&client_secret=3674537faa96a91088f7ad3b9e319ac4&grant_type=client_credentials");
$array = explode("=",$response);

$eventResponse = file_get_contents("https://graph.facebook.com/v2.5/1596300553922503/events?access_token=" . $array[1]);
$eventArray = json_decode($eventResponse, true);
$i = 1;


foreach($eventArray['data'] as $key=>$value){
	$imageRespose = file_get_contents("https://graph.facebook.com/v2.5/1186331501461789/picture?access_token=" . $array[1] ."&redirect=false&type=large");
	$imageArray =  json_decode($imageRespose, true);
		
		$name = mysql_real_escape_string($value['name']);
		$description = mysql_real_escape_string($value['description']);
		$url = $imageArray['data']['url'];
		$fbid = $value['id'];
		$startDate = $value['start_time'];
		$endDate = $value['end_time'];
		
		$query = "INSERT INTO event (Id, Name, Description, Url, Active, FbID, startDate, endDate) values ($fbid, '$name', '$description', '$url', true, '$fbid', '$startDate', '$endDate')";
		mysql_query($query);
		
}
	
function DoesEventExist($id)
{
    $query = "SELECT id FROM Inventory where id = $id";
    $results = mysql_query($query);
	$stuff = mysql_result($results, 0);
	
	return false;
}

$resultsQuery = "SELECT * FROM event WHERE startDate > CURDATE() ORDER BY startDate"; 
$eventResults = mysql_query($resultsQuery);

echo $jsonWriter->RecordSetToJson($eventResults);
	
