<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 25/4/18
 * Time: 5:21 PM
 */
require_once(__DIR__ . '/../samriddhee_db/classes/botStatusDAO.php');

if($_GET['command'] == 'change_status'){
    $return_array = botStatusDAO::toggleBitmexBotStatus();
}else if($_GET['command'] == 'change_range_details'){
    $return_array = botStatusDAO::updateBitmexRangeBotDetails($_GET);
}

if($return_array['return_code'] == 200)
    $output = json_encode($return_array);
else
    $output = json_encode(500);

echo $output;