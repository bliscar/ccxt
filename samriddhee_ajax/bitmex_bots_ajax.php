<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 27/4/18
 * Time: 3:24 PM
 */
require_once(__DIR__ . '/../samriddhee_db/classes/bitmexApiKeysDAO.php');
require_once(__DIR__ . '/../samriddhee_db/classes/bitmexSymbolsDAO.php');

 // For Debugging
//$_GET['command'] = 'change_api_key';
//$_GET['net_value'] = 2;
//$_GET['user_name'] = 'AnuragMainnet';
//
//print_r($_GET);

if($_GET['command'] == 'change_net') {
    $return_array = bitmexApiKeysDAO::getBitmexApiKeys($_GET);

    if ($return_array['return_code'] == 200) {
        $output = '
        <label for="selectApiKey">Select Api Keys</label>
        <select style= "width:15%" class="form-control"  id="selectApiKey">
        <option value= -1>Select Api Keys</option>  
    ';
        foreach ($return_array[api_keys] as $key => $value) {
            $output .= "
             <option value= $value[user_name]>$value[user_name]</option>
        ";
        }

        $output .= "</select>";
    } else {
        $output = json_encode(500);
    }
}else if($_GET['command'] == 'change_api_key') {

    $updated_apis_with_status_array = bitmexApiKeysDAO::updateBitmexAPiStatus($_GET);
    $return_array = bitmexSymbolsDAO::getBitmexSymbols();

    if ($return_array['return_code'] == 200) {
        $output = '
        <label for="selectBitmexSymbol">Select Symbol</label>
        <select style= "width:15%" class="form-control"  id="selectBitmexSymbol">
        <option value= -1>Select Symbol</option>  
    ';
        foreach ($return_array[bitmex_symbols] as $key => $value) {
            $output .= "
             <option value= $value[symbol]>$value[symbol]</option>
        ";
        }

        $output .= "</select>";
    } else {
        $output = json_encode(500);
    }
}else if($_GET['command'] == 'change_symbol') {

    $updated_symbols_with_status_array = bitmexSymbolsDAO::updateBitmexSymbolsRunningStatus($_GET);


    if ($updated_symbols_with_status_array['return_code'] == 200) {


        $output = "All Done!";
    } else {
        $output = json_encode(500);
    }
}

echo $output;