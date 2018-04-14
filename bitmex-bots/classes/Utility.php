<?php
namespace ccxt;
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 10/4/18
 * Time: 8:15 PM
 */
date_default_timezone_set ('UTC');
use Exception as Exception; // a common import
use MathPHP\Statistics\Average;
include_once __DIR__.'/../../ccxt.php';
require_once(__DIR__ . '/../../vendor/autoload.php');


////STD Functions imported from somewhere - underscore_case
////Functions written by us - camelCase
////all variables - underscore_case

class Utility
{
    //***Params Value Example***//
    //  $start_range = '2018-04-09 11:30:00'
    //  $end_range = NULL -> $end_range = 'current_timestamp'
    //  $end_range = '2018-04-09 11:30:00'
    //  $exchange = Mandatory Exchange Object
    //  $duration = '1m', '5m', '1h', '1d'
    //  $symbol = 'BTC/USD'
    public static function fetchRangeOHLCVbitmex($exchange, $duration, $symbol, $start_range, $end_range = NULL){
        if(!$end_range)
            $end_range = time();
        else
            $end_range = strtotime($end_range);

        if($duration == '1m')
            $seconds_with_suffix = 60000;
        elseif($duration == '5m')
            $seconds_with_suffix = 60000*5;
        elseif($duration == '1h')
            $seconds_with_suffix = 60000*60;
        elseif($duration == '1d')
            $seconds_with_suffix = 60000*60*24;

        $start_range_for_bitmex = strtotime($start_range).'000';
        $end_range_for_bitmex = $end_range.'000';
        $last_timestamp_plus_next_chunk = 0;

        $full_range_candlestick_data = array();

        $opens = array();
        $highs = array();
        $lows = array();
        $closes = array();
        $volumes = array();
        $average_highs_lows = array();
        $full_range_candlestick_data = array();

        if ($exchange->has['fetchOHLCV']){
            do{
                usleep ($exchange->rateLimit * 1000); // usleep wants microseconds
                $candlestick_data = $exchange->fetch_ohlcv ($symbol, $duration,$start_range_for_bitmex);
                $last_timestamp_plus_next_chunk = end($candlestick_data)[0] + $seconds_with_suffix;
                foreach($candlestick_data as $element){
                    $full_range_candlestick_data[$element[0]] = $element;
                    $opens[$element[0]] = $element[1];
                    $highs[$element[0]] =$element[2];
                    $lows[$element[0]] = $element[3];
                    $closes[$element[0]] = $element[4];
                    $volumes[$element[0]] = $element[5];
                    $average_highs_lows[$element[0]] = round(($element[2]+$element[3])/2);
                }
                $start_range_for_bitmex = $last_timestamp_plus_next_chunk;
            }while($last_timestamp_plus_next_chunk < intval($end_range_for_bitmex));
        }

        $return_data = array();
        $return_data['full_range_candlestick_data'] = $full_range_candlestick_data;
        $return_data['opens'] = $opens;
        $return_data['highs'] = $highs;
        $return_data['lows'] = $lows;
        $return_data['closes'] = $closes;
        $return_data['volumes'] = $volumes;
        $return_data['average_highs_lows'] = $average_highs_lows;

        return $return_data;

    }

    /**
     * This user-land implementation follows the implementation quite strictly;
     * it does not attempt to improve the code or algorithm in any way. It will
     * raise a warning if you have fewer than 2 values in your array, just like
     * the extension does (although as an E_USER_WARNING, not E_WARNING).
     *
     * @param array $a
     * @param bool $sample [optional] Defaults to false
     * @return float|bool The standard deviation or false on error.
     */
    public static function stats_standard_deviation(array $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
            --$n;
        }
        return sqrt($carry / $n);
    }

    public static function frequencyOfRateInDurationBitmex($rate, $exchange, $duration, $symbol, $start_range, $end_range = NULL){
        if(!$end_range)
            $end_range = time();
        else
            $end_range = strtotime($end_range);

        if($duration == '1m')
            $seconds_with_suffix = 60000;
        elseif($duration == '5m')
            $seconds_with_suffix = 60000*5;
        elseif($duration == '1h')
            $seconds_with_suffix = 60000*60;
        elseif($duration == '1d')
            $seconds_with_suffix = 60000*60*24;

        $start_range_for_bitmex = strtotime($start_range).'000';
        $end_range_for_bitmex = $end_range.'000';
        $last_timestamp_plus_next_chunk = 0;
        $counter_rate = 0;

        if ($exchange->has['fetchOHLCV']){
            do{
                usleep ($exchange->rateLimit * 1000); // usleep wants microseconds
                $candlestick_data = $exchange->fetch_ohlcv ($symbol, $duration, $start_range_for_bitmex);
                print_r ($candlestick_data); // one month
                $last_timestamp_plus_next_chunk = end($candlestick_data)[0] + $seconds_with_suffix;
                foreach($candlestick_data as $element){
                    if($element[2] >= $rate && $element[3] <= $rate)
                        $counter_rate++;
                }
                $start_range_for_bitmex = $last_timestamp_plus_next_chunk;
            }while($last_timestamp_plus_next_chunk < intval($end_range_for_bitmex));
        }

        return $counter_rate;
    }

    public static function frequencyOfTwoRatesInDurationBitmex($rate_1,$rate_2, $exchange, $duration, $symbol, $start_range, $end_range = NULL){
        if(!$end_range)
            $end_range = time();
        else
            $end_range = strtotime($end_range);

        if($duration == '1m')
            $seconds_with_suffix = 60000;
        elseif($duration == '5m')
            $seconds_with_suffix = 60000*5;
        elseif($duration == '1h')
            $seconds_with_suffix = 60000*60;
        elseif($duration == '1d')
            $seconds_with_suffix = 60000*60*24;

        $start_range_for_bitmex = strtotime($start_range).'000';
        $end_range_for_bitmex = $end_range.'000';
        $last_timestamp_plus_next_chunk = 0;
        $counter_rate = 0;

            

        if ($exchange->has['fetchOHLCV']){
            do{
                usleep ($exchange->rateLimit * 1000); // usleep wants microseconds
                $candlestick_data = $exchange->fetch_ohlcv ($symbol, $duration, $start_range_for_bitmex);
                print_r ($candlestick_data); // one month
                $last_timestamp_plus_next_chunk = end($candlestick_data)[0] + $seconds_with_suffix;
                foreach($candlestick_data as $element){
                    if($element[2] >= $rate && $element[3] <= $rate)
                        $counter_rate++;
                }
                $start_range_for_bitmex = $last_timestamp_plus_next_chunk;
            }while($last_timestamp_plus_next_chunk < intval($end_range_for_bitmex));
        }

        return $counter_rate;
    }
}