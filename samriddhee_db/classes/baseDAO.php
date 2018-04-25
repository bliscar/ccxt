<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 25/4/18
 * Time: 2:42 PM
 */

require_once (dirname(__FILE__)."/../../samriddhee_connectors/db_connector.php");

Class DAO{

    public $Pdo;

    function __construct(){
        $this->Pdo = $GLOBALS['pdo'];
    }

}