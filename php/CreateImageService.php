<?php

require_once('utils/DBConnector.php');

require_once('utils/JSONWriter.php');

require_once('utils/Guard.php');

$db = new DBConnector();

$connection = $db->AdminConnection();

$jsonWriter = new JSONWriter();

if (isset($_GET['id']))
{
	echo GetImageIndex($_GET['id']);
}
else
{
	$contents = file_get_contents("php://input");
	$item = json_decode($contents, true);
	$image = $item['image'];
	$id = $item['id'];
	$index = GetImageIndex($id);
	CreateImage($image, $id, $index);

	echo $index;

}


function CreateImage($image, $id, $index){
    list($type, $image) = explode(';', $image);
    list(, $image)      = explode(',', $image);
    $image = base64_decode($image);
	$imgCount = $index + 1;
	$query = "INSERT INTO `Image` (itemId, folderId, active) VALUES ($index, $id, true);";
    ///$query = "UPDATE `Inventory` SET `ImageCount`=$imgCount WHERE Id = $id";

    mysql_query($query);

    file_put_contents('../img/inventory/' . $id . '/' . $index . '.png', $image);
	make_thumb($image,'../img/inventory/' . $id . '/thumbs/' . $index . '.png', 135);

}

function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
	error_log("image source:" . $src);
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	imagepng($virtual_image, $dest);
}

function GetImageIndex($id){
	error_log("the id is: " + $id);
	$query = "SELECT MAX(itemId) maxId FROM `Image` WHERE `folderId` = " . $id;
	$results = mysql_query($query);
	$id = mysql_fetch_row($results);

	return $id[0] + 1;

}



