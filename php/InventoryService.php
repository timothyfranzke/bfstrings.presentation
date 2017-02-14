<?php

/**

 * Created by PhpStorm.

 * User: Timothy

 * Date: 8/30/2015

 * Time: 10:54 AM

 */



require_once('utils/DBConnector.php');

require_once('utils/JSONWriter.php');

require_once('utils/Guard.php');



$db = new DBConnector();

$connection = $db->AdminConnection();

$jsonWriter = new JSONWriter();

if( isset( $_GET['load'] ))
{
	$load = $_GET['load'];
	if($load == 'banjos')
	{
		echo "Going to LoadBanjos() dodio\n";
		LoadBanjos();
	}
	if($load == 'guitars')
	{
		LoadGuitars();
	}
	if($load == 'mandos')
	{
		LoadMandos();
	}
}

if( isset( $_GET['type'] ) )

{



    $results = '';

    $type = $_GET['type'];

    if ($type == 'banjo')

    {
		
        $results = GetBanjos();

    }

    else if ($type == 'guitar')

    {
		
        $results = GetGuitars();

    }

    else if ($type == 'mandolin')

    {

        $results = GetMandolins();

    }

    else {
		
        $results = GetAll();

    }

    echo $jsonWriter->RecordSetToJson($results);

}

else if (isset( $_GET['id']))

{

    $results = GetItemById($_GET['id']);

    echo $jsonWriter->RecordSetToJson($results);

}
else if (isset( $_GET['action']))
{
    $results = GetTypes();

    echo $jsonWriter->RecordSetToJson($results);
}



else{

    $contents = file_get_contents("php://input");

    $item = json_decode($contents, true);
    $images = $item['images'];
    $imageCount = count($images);

    if (isset( $item['id'] ))
    {
        UpdateInventory($item);
        $id = $item['id'];
		$currentImageCount = GetImageCount($id);
        for($i = $currentImageCount; $i < ($imageCount + $currentImageCount); $i++)
        {
			$imageIndex = $i - $currentImageCount;
            CreateImage($images[$imageIndex], $id, $i);
        }
    }

    else{
        AddInventory($item);

        $id = GetId();
        CreateDirectory($id);

        for($i = 0; $i < $imageCount; $i++)
        {
            CreateImage($images[$i], $id, $i);
        }
        echo $id;

    }

}



function AddInventory($item)

{

    $name = $item['name'];

    $description = $item['description'];

    $price = $item['price'];

    $type = $item['type'];



    $query = "INSERT INTO Inventory (Name, Description, Price, Type) values ('$name', '$description', $price, $type)";

    mysql_query($query);

}

function CreateDirectory($id)
{
    mkdir('../img/inventory/' . $id . '/', 0755, true);
	mkdir('../img/inventory/' . $id . '/thumbs/', 0755, true);
}

function UpdateInventory($item)

{

    $id = $item['id'];

    $name = $item['name'];

    $description = $item['description'];

    $price = $item['price'];

    $type = $item['type'];

    $sold = $item['sold'];

    $active = $item['active'];

    if ($sold == 'true')

    {

        $sold = 1;

    }

    else{

        $sold = 0;

    }

    if ($active == 'true')

    {

        $active = 1;

    }

    else{

        $active = 0;

    }



    $query = "UPDATE `Inventory` SET `Name`='$name',`Description`='$description',`Price`=$price,`Type`=$type,`Active`=$active,`Sold`=$sold WHERE Id = $id";

    mysql_query($query);

}



function GetGuitars()

{

    $query = "SELECT * FROM Inventory where Type = 2 AND Active = 1 ORDER BY id DESC";

    $results = mysql_query($query);

    return $results;

}



function GetBanjos()

{

    $query = "SELECT * FROM Inventory where Type = 1 AND Active = 1 ORDER BY id DESC";

    $results = mysql_query($query);

    return $results;

}



function GetMandolins()

{

    $query = "SELECT * FROM Inventory where Type = 3 AND Active = 1 ORDER BY id DESC";

    $results = mysql_query($query);

    return $results;

}



function GetAll()

{

    $query = "SELECT * FROM Inventory where Active = 1 ORDER BY id DESC";

    $results = mysql_query($query);

    return $results;

}



function CreateImage($image, $id, $index){

   
    list($type, $image) = explode(';', $image);
    list(, $image)      = explode(',', $image);
    $image = base64_decode($image);
	$imgCount = $index + 1;
    $query = "UPDATE `Inventory` SET `ImageCount`=$imgCount WHERE Id = $id";

    mysql_query($query);

    file_put_contents('../img/inventory/' . $id . '/' . $index . '.png', $image);

}



function GetItemById($id)

{

    $query = "SELECT * FROM Inventory where id = $id";

    $results = mysql_query($query);

    return $results;

}



function GetId()

{

    $query = "SELECT MAX(id) FROM Inventory";

    $results = mysql_query($query);

    $id = mysql_fetch_row($results);

    return $id[0];

}

function GetImageCount($id)

{

    $query = "SELECT ImageCount FROM Inventory WHERE id = $id";

    $results = mysql_query($query);

    $id = mysql_fetch_row($results);

    return $id[0];

}


function GetTypes()
{
    $query = "SELECT * FROM Type";
    $result = mysql_query($query);
    return $result;
}

function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
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

function LoadBanjos()
{
	$item = json_decode(file_get_contents("../inventory/banjos.json", true));
	echo $item;
	foreach($item as $banjo)
	{
		echo "reading banjo " . $banjo->name . "\n";
		$name = $banjo->name;
		$description = $banjo->description;
		$price = $banjo->price;
		$type = 1;
		$imageCount = $banjo->imageCount;

		$query = "INSERT INTO Inventory (Name, Description, Price, Type, ImageCount) values ('$name', '$description', $price, $type, $imageCount)";
		mysql_query($query);
		$id = GetId();
		CreateDirectory($id);
		
		for($i = 1; $i <= $imageCount; $i++)
		{
			$query = "INSERT INTO Image (folderId, itemId, active) values ($id, $i, true)";
			mysql_query($query);
			$oldId = $i;
			echo "copying the images : Image Number : " . $i . " Total : " . $imageCount;
			$filename = "../img/inventory/banjos/" . $banjo->id . "/" . $oldId . ".jpg";
			$image = imagecreatefromstring(file_get_contents($filename));
			imagepng($image, '../img/inventory/' . $id . '/' . $i . ".png");
			make_thumb($image,'../img/inventory/' . $id . '/thumbs/' . $index . '.png', 135);
		}	
	}
}

function LoadGuitars()
{
	$item = json_decode(file_get_contents("../inventory/guitars.json", true));
	echo $item;
	foreach($item as $guitar)
	{
		echo "reading guitar " . $guitar->name . "\n";
		$name = $guitar->name;
		$description = $guitar->description;
		$price = $guitar->price;
		$type = 2;
		$imageCount = $guitar->imageCount;

		$query = "INSERT INTO Inventory (Name, Description, Price, Type, ImageCount) values ('$name', '$description', $price, $type, $imageCount)";
		mysql_query($query);
		$id = GetId();
		CreateDirectory($id);
		
		for($i = 1; $i <= $imageCount; $i++)
		{
			$query = "INSERT INTO Image (folderId, itemId, active) values ($id, $i, true)";
			mysql_query($query);
			$oldId = $i;
			echo "copying the images : Image Number : " . $i . " Total : " . $imageCount;
			$filename = "../img/inventory/guitars/" . $guitar->id . "/" . $oldId . ".jpg";
			$image = imagecreatefromstring(file_get_contents($filename));
			imagepng($image, '../img/inventory/' . $id . '/' . $i . ".png");
			make_thumb($image,'../img/inventory/' . $id . '/thumbs/' . $index . '.png', 135);
		}	
	}
}

function LoadMandos()
{
	$item = json_decode(file_get_contents("../inventory/mandos.json", true));
	echo $item;
	foreach($item as $mando)
	{
		echo "reading mando " . $mando->name . "\n";
		$name = $mando->name;
		$description = $mando->description;
		$price = $mando->price;
		$type = 3;
		$imageCount = $mando->imageCount;

		$query = "INSERT INTO Inventory (Name, Description, Price, Type, ImageCount) values ('$name', '$description', $price, $type, $imageCount)";
		mysql_query($query);
		$id = GetId();
		CreateDirectory($id);
		
		for($i = 1; $i <= $imageCount; $i++)
		{
			$query = "INSERT INTO Image (folderId, itemId, active) values ($id, $i, true)";
			mysql_query($query);
			$oldId = $i;
			echo "copying the images : Image Number : " . $i . " Total : " . $imageCount;
			$filename = "../img/inventory/mandos/" . $mando->id . "/" . $oldId . ".jpg";
			$image = imagecreatefromstring(file_get_contents($filename));
			imagepng($image, '../img/inventory/' . $id . '/' . $i . ".png");
			make_thumb($image,'../img/inventory/' . $id . '/thumbs/' . $index . '.png', 135);
		}	
	}
}