<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 17/4/18
 * Time: 6:20 PM
 */


//***STRATEGY***///
//      TO FIND OUT - Number of bets that can be taken, statistical stop loss.
//
//      1. Find out the range to on which the current system will work
//              ->For the time being take a spread of 200$ from the current price and if prices go beyond that, cut the time limit till then
//      2. For the above range find current mean and SD.
//      3. Create Longs and Shorts at both the extremes(or @SD1, @SD2, etc.) if no open position is there.
//      4. After 5 Minutes Run the same script and check if any position is taken, if not adjust the Opens, if yes then place a short @ the opposite points of double amount and
//              A SL for the current position.
//      5. Keep running the script for every 5 minutes.
//***STRATEGY***//

date_default_timezone_set ('UTC');
include_once __DIR__.'/../../ccxt.php';
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../classes/Utility.php');
require_once(__DIR__ . '/../../samriddhee_db/classes/botStatusDAO.php');
require_once(__DIR__ . '/../../samriddhee_db/classes/bitmexSymbolsDAO.php');
require_once(__DIR__ . '/../../samriddhee_db/classes/bitmexApiKeysDAO.php');
require_once(__DIR__ . '/../../../sam-tech/constants/bmx_apis.php');
use MathPHP\Statistics\Average;

$bitmex_testnet_status = botStatusDAO::getBitmexTestnetStatus();
if($bitmex_testnet_status[0][testnet_on] == 1){


    $data_array = array();
    $data_array['net_value'] = 1;

    $bitmex_symbols = bitmexSymbolsDAO::getBitmexSymbols();
    foreach ($bitmex_symbols['bitmex_symbols'] as $key => $value){
        if($value[running_status_test] == 1){
            $net_symbol = $value[symbol];
            break;
        }
    }

    $bitmex_APIKey = bitmexApiKeysDAO::getBitmexApiKeys($data_array);
    foreach ($bitmex_APIKey['api_keys'] as $key1 => $value1){
        if($value1[status] == 1){
            $net_APIKey = $value1[user_name];
            break;
        }
    }


    $exchange = new \ccxt\bitmexSam(); //Testnet
    $exchange->apiKey = $apisapisapis[$net_APIKey][Key];
    $exchange->secret = $apisapisapis[$net_APIKey][Secret];



    $bitmex_range_bot_details = botStatusDAO::getBitmexRangeBotDetails();
    $long = $bitmex_range_bot_details[0][long_price];
//$long = 7500;
    $short = $bitmex_range_bot_details[0][short_price];
//$short = 8500;
    $amount = $bitmex_range_bot_details[0][amount];

    $balance = $exchange->fetch_markets();
    $symbole_array = array();
    foreach ($balance as $value){
        $symbole_array[] = $value[symbol];
    }

    $symbol_perpetual = $net_symbol;
    $symbol_position = array();

    if($symbol_perpetual == 'BTC/USD'){
        $symbol_position[] = 'XBTUSD';
    }else{
        $symbol_position[] = $net_symbol;
    }

    $open_orders = $exchange->fetch_open_orders($symbol_perpetual);
    $open_positions = $exchange->fetch_positions();

    foreach ($open_positions as $key => $position){
        if(!in_array($position['symbol'],$symbol_position)){
            unset($open_positions[$key]);
        }

        else if($position['currentQty'] == 0){
            unset($open_positions[$key]);
        }
    }

    if(count($open_positions) > 0){
        foreach($open_positions as $position){
            if($position['currentQty'] < 0){
                //Short is open
                if(count($open_orders)>0){
                    echo " only 1 order ";
                    foreach ($open_orders as $order) {
                        echo " in ";
                        if((abs($order['price'] - $long) < 1) && $order['side'] == 'buy' && (abs($order['amount'] - $amount)<1)){
                            $can_order = $exchange->cancel_order($order['id']);
                            $res_buy_pos = $exchange->create_limit_buy_order($symbol_perpetual,2*$amount, $long);
                            echo " 1 ";
                        }
                    }
                }else if(count($open_orders)==0){
                    $res_buy_pos = $exchange->create_limit_buy_order($symbol_perpetual,2*$amount, $long);
                    echo " 2 ";
                }
            }else if($position['currentQty'] > 0){
                //Long is open
                if(count($open_orders)>0){
                    echo " only 1 order ";
                    foreach ($open_orders as $order) {
                        echo " in ";
                        if((abs($order['price'] - $short) < 1) && $order['side'] == 'sell' && (abs($order['amount'] - $amount)<1)){
                            $can_order = $exchange->cancel_order($order['id']);
                            $res_sell_pos = $exchange->create_limit_sell_order($symbol_perpetual,2*$amount, $short);
                            echo " 3 ";
                        }
                    }
                }else if(count($open_orders)==0){
                    $res_sell_pos = $exchange->create_limit_sell_order($symbol_perpetual,2*$amount, $short);
                    echo " 4 ";
                }
            }else{
                //**//
                //This should not happen
                //**//
            }

        }
    }else if(count($open_orders) > 0){
        // Do nothing
    }
    else{
        $res_buy = $exchange->create_limit_buy_order($symbol_perpetual, $amount, $long);
        $res_sell = $exchange->create_limit_sell_order($symbol_perpetual, $amount, $short);
        echo " 5 ";
    }
}else if($bitmex_testnet_status[0][testnet_on] == -1){
   //
    echo " 6 ";
}

echo " 7 ";
echo " done ";
