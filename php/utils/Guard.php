<?php
/**
 * Created by PhpStorm.
 * User: Timothy
 * Date: 1/31/2015
 * Time: 9:52 AM
 */


class Guard
{
    function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            return $uuid;
        }
    }
    function GuardExchange(){
        if( isset( $_SESSION['userId'] ) && isset( $_SESSION['tempPassword'] )  )
        {
            $userId = $_SESSION['userId'];
            $tempPassword = $_SESSION['tempPassword'];
            if( $userId < -1 || $tempPassword )
            {
                header("Location:". $_SESSION["DOCUMENT_ROOT"] . "/php/Login.php");
                exit;
            }
        }
        else
        {
            header("Location:". $_SESSION["DOCUMENT_ROOT"] . "/php/Login.php");
            exit;
        }
    }

    function GuardUpdatePassword(){
        if( isset( $_SESSION['userId'] ) && isset( $_SESSION['tempPassword'] )  )
        {
            $userId = $_SESSION['userId'];
            $tempPassword = $_SESSION['tempPassword'];
            if( $userId <= -1 || !$tempPassword )
            {
                header("Location:". $_SESSION["DOCUMENT_ROOT"] . "/php/Login.php");
                exit;
            }
        }
        else
        {
            header("Location:Login.php");
            exit;
        }
    }

    function GuardPage($pageName)
    {
        if (!isset($_SESSION['lastActivity']) || (time() - $_SESSION['lastActivity'] > 1800)){
            LogOut();
        }
        if( isset( $_SESSION['userId'] ) )
        {
            $groups = $_SESSION['userEntitlements'];
            $_SESSION['lastActivity'] = time();
            if (!in_array($pageName, $groups))
            {
                header("Location:" . $_SESSION["DOCUMENT_ROOT"] . "/php/Login.php?action=1");
                exit;
            }
        }
        else{
            header("Location:" . $_SESSION["DOCUMENT_ROOT"] . "/php/Login.php");
            exit;
        }
    }


}
function LogOut()
{
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
    header("Location:". $_SESSION["DOCUMENT_ROOT"] . "/php/Login.php");
}