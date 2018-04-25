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
use MathPHP\Statistics\Average;

$bitmex_bot_status = botStatusDAO::getBitmexBotStatus();
if($bitmex_bot_status[0][on_status] == 1){

    $exchange = new \ccxt\bitmexSam();


//NeerajTest

    $bitmex_range_bot_details = botStatusDAO::getBitmexRangeBotDetails();
    $long = $bitmex_range_bot_details[0][long_price];
//$long = 7500;
    $short = $bitmex_range_bot_details[0][short_price];
//$short = 8500;
    $amount = $bitmex_range_bot_details[0][amount];
    $exchange->apiKey = 'Bd1DKQOJ3MuqZPjv5QcznfYW';
    $exchange->secret = 'qBJQyCwboT3nlPZ2lxnHEAOrBJvIm2f1sodXcXgoWFlAdfh7';
//
////AnuragMain
//$exchange->apiKey = 'PeSLxxp7MEZ06ujwf_NPEz4G';
//$exchange->secret = 'n1RAC2Euprc5otU2N0Jlsb8ZBfzaQfLnpRfID2Blm7Oxc5y0';
//
//$balance = $exchange->fetch_balance ();
//var_dump ($balance);
//
//print_r ($exchange->has); // or var_dump
//exit;

//$start_range = strtotime('2018-04-17 00:00:00').'000';
    $symbol_perpetual = 'BTC/USD';
    $symbol_position = array();
    $symbol_position[] = 'XBTUSD';

    $open_orders = $exchange->fetch_open_orders($symbol_perpetual);
    $open_positions = $exchange->fetch_positions();
//$exchange->fe
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
                        }
                    }
                }else if(count($open_orders)==0){
                    $res_buy_pos = $exchange->create_limit_buy_order($symbol_perpetual,2*$amount, $long);
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
                        }
                    }
                }else if(count($open_orders)==0){
                    $res_sell_pos = $exchange->create_limit_sell_order($symbol_perpetual,2*$amount, $short);
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
    }
}else if($bitmex_bot_status[0][on_status] == -1){
   //
}


