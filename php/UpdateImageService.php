<?php

require_once('utils/DBConnector.php');

require_once('utils/JSONWriter.php');

require_once('utils/Guard.php');



$db = new DBConnector();

$connection = $db->AdminConnection();

$jsonWriter = new JSONWriter();

$contents = file_get_contents("php://input");
$item = json_decode($contents, true);
$imageCount = count($item);
//echo $imageCount;

for($i = 0; $i < $imageCount; $i++)
{
	$id = $item[$i]['itemId'];
	$folderId = $item[$i]['folderId'];
	$active = $item[$i]['active'];
	//echo "id" . $id  . "folder" . $folderId . "active" . $active;

	UpdateImage($folderId, $id, $active);
}

function UpdateImage($folderId, $id, $active)
{
if ($active == 1 || $active)
{
$query = "UPDATE Image SET active=" . $active . " WHERE itemId = ". $id  . " AND folderId = " .$folderId;
}
else
{
$query = "UPDATE Image SET active=0 WHERE itemId = ". $id  . " AND folderId = " .$folderId;
}
    ///$query = "UPDATE `Inventory` SET `ImageCount`=$imgCount WHERE Id = $id";
    mysql_query($query);
}

function GetImageCount($id, $folderId)
{
    $query = "SELECT Count(*) FROM Images WHERE id = $id AND folderId = $folderId";

    $results = mysql_query($query);
    $id = mysql_fetch_row($results);

    return $id[0];
}