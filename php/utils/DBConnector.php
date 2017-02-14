<?php

/**

 * Created by PhpStorm.

 * User: Timothy

 * Date: 1/19/2015

 * Time: 9:49 AM

 */



class DBConnector {

    function AdminConnection()

    {

        $db = DBConnection();

        mysql_select_db("bfstrings");

        return $db;

    }

    function SessionConnection()

    {

        $db = DBConnection();

        mysql_select_db("session");

        return $db;

    }



    function CloseConnection($db)

    {

        mysql_close($db);

    }

}



function DBConnection()

{
    $server = 'localhost';
    //$server = 'mfranzke.powwebmysql.com';

//        $dbusername = 'franzke_race';





    $dbusername = 'bfstrings';

    $dbpassword = 'Neqm3dBkmKVDkZTq5rdB';



    $db = mysql_connect($server, $dbusername, $dbpassword);



    if (!$db)

    {

        echo "{status:'unable to make connection'}";

        die;

    }



    return $db;

}

