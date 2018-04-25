<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 25/4/18
 * Time: 2:43 PM
 */


require_once (dirname(__FILE__)."/../samriddhee_constants/db_constants.php");
require_once (dirname(__FILE__)."/../samriddhee_constants/path_constants.php");
//Enter your database connection details here.
global $host,$db_name ,$db_username ,$db_password ,$pdo, $deletepdo;

$host = DB_SERVER; //HOST NAME.
$db_name = DB_APP; //Database Name
$db_username = DB_USER; //Database Username
$db_password = DB_PASSWORD; //Database Password
try
{
    $pdo = new PDO('mysql:host='. $host .';dbname='.$db_name, $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e)
{
    echo $e->getMessage();
    exit('Error Connecting To DataBase');
}

try
{
    $deletepdo = new PDO('mysql:host='. $host .';dbname='.$db_name, DB_DELETE_USER, DB_DELETE_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $deletepdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e)
{
    echo $e->getMessage();
    exit('Error Connecting To DataBase');
}
