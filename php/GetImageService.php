<?php



require_once('utils/DBConnector.php');



require_once('utils/JSONWriter.php');



require_once('utils/Guard.php');



$db = new DBConnector();



$connection = $db->AdminConnection();



$jsonWriter = new JSONWriter();



$id = $_GET['id'];



if (isset($_GET['admin']))

{

	$results = GetAdminImages($id);

}

else

{

	$results = GetImages($id);

}



echo $jsonWriter->RecordSetToJson($results);



function GetImages($id)

{

    $query = "SELECT * FROM Image where Active = 1 AND folderId = " .$id;

    $results = mysql_query($query);



    return $results;

}

	

function GetAdminImages($id)

{

    $query = "SELECT * FROM Image where Active = 1 AND folderId = " . $id;

    $results = mysql_query($query);


    return $results;

}