<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 25/4/18
 * Time: 4:31 PM
 */
require_once (dirname(__FILE__)."/baseDAO.php");

class botStatusDAO extends DAO
{

    public static function getBitmexTestnetStatus()
    {
        $pdo = $GLOBALS['pdo'];

        try {
            $query = $pdo->prepare("SELECT testnet_on from bots_status where bot_name  = 'bitmex_range_bot'");
            $query->execute();
        } catch (PDOException $ex) {
            return 808;
        }
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function toggleBitmexTestnetStatus()
    {
        $pdo = $GLOBALS['pdo'];

        try {
            $query = $pdo->prepare("update bots_status set testnet_on = -1*testnet_on where bot_name = 'bitmex_range_bot';");
        } catch (PDOException $ex) {
            return 808;
        }

        $query->execute();

        $bitmex_testnet_status = self::getBitmexTestnetStatus();
        $return_array = array();
        $return_array['return_code'] = 200;
        $return_array['current_testnet_status'] = $bitmex_testnet_status[0][testnet_on];
        return $return_array;
    }

    public static function getBitmexMainnetStatus()
    {
        $pdo = $GLOBALS['pdo'];

        try {
            $query = $pdo->prepare("SELECT mainnet_on from bots_status where bot_name  = 'bitmex_range_bot'");
            $query->execute();
        } catch (PDOException $ex) {
            return 808;
        }
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function toggleBitmexMainnetStatus()
    {
        $pdo = $GLOBALS['pdo'];

        try {
            $query = $pdo->prepare("update bots_status set mainnet_on = -1*mainnet_on where bot_name = 'bitmex_range_bot';");
        } catch (PDOException $ex) {
            return 808;
        }

        $query->execute();

        $bitmex_testnet_status = self::getBitmexMainnetStatus();
        $return_array = array();
        $return_array['return_code'] = 200;
        $return_array['current_mainnet_status'] = $bitmex_testnet_status[0][mainnet_on];
        return $return_array;
    }
    
    public static function getBitmexRangeBotDetails()
    {
        $pdo = $GLOBALS['pdo'];

        try {
            $query = $pdo->prepare("SELECT * from bots_status where bot_name  = 'bitmex_range_bot'");
            $query->execute();
        } catch (PDOException $ex) {
            return 808;
        }
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function updateBitmexRangeBotDetails($data_array)
    {
        $pdo = $GLOBALS['pdo'];

        try {
            $query = $pdo->prepare("update bots_status set long_price = ?, short_price = ?, amount = ? where bot_name = 'bitmex_range_bot';");

            $query->bindParam(1, $data_array['long_price']);
            $query->bindParam(2, $data_array['short_price']);
            $query->bindParam(3, $data_array['amount']);
        } catch (PDOException $ex) {
            return 808;
        }

        $query->execute();

        $bitmex_bot_details = self::getBitmexRangeBotDetails();
        $return_array = array();
        $return_array['return_code'] = 200;
        $return_array['long_price'] = $bitmex_bot_details[0][long_price];
        $return_array['short_price'] = $bitmex_bot_details[0][short_price];
        $return_array['amount'] = $bitmex_bot_details[0][amount];
        return $return_array;
    }
}
