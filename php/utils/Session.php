<?php
/**
 * Created by PhpStorm.
 * User: Timothy
 * Date: 2/4/2015
 * Time: 8:58 PM
 */


class FranzkeSession
{

    function AddSessionVariable($key, $value)
    {
        session_start();
        require_once('DBConnector.php');
        $db = new DBConnector();
        $connection = $db->SessionConnection();
        $sessionId = session_id();
        $query = "INSERT INTO SESSION VALUES('$sessionId', '$key', '$value')";
        mysql_query($query);
        $db->CloseConnection($connection);
    }
}