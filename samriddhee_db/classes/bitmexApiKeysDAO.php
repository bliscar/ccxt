<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 27/4/18
 * Time: 3:11 AM
 */


require_once (dirname(__FILE__)."/baseDAO.php");

class bitmexApiKeysDAO extends DAO
{
    //*************************************//
    //                  Input              //
    //$data_array[net_value] == 1 //Testnet//
    //$data_array[net_value] == 2 //Mainnet//
    //*************************************//
    public static function getBitmexApiKeys($data_array)
    {
        $pdo = $GLOBALS['pdo'];
        
        $query = $pdo->prepare("SELECT * from bitmex_api_keys where testnet_keys = ?");

        $query->bindParam(1, $data_array['net_value']);
        try {
            $query->execute();
        } catch (PDOException $ex) {
            return 808;
        }
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $return_array['return_code'] = 200;
        $return_array['api_keys'] = $result;
        return $return_array;
    }

    //*******************************************************************************//
    //                  Input                                                        //
    //$data_array[net_value] == 1 //Testnet                                          //
    //$data_array[net_value] == 2 //Mainnet                                          //
    //$data_array[user_name] == 'Neeraj' //Users of bitmex, check api credentials doc//
    //*******************************************************************************//
    public static function updateBitmexAPiStatus($data_array)
    {
        $pdo = $GLOBALS['pdo'];

        $query = $pdo->prepare("update bitmex_api_keys set status = 0 where testnet_keys = ?");

        $query->bindParam(1, $data_array['net_value']);
        try {
            $query->execute();
        } catch (PDOException $ex) {
            return 808;
        }

        $query = $pdo->prepare("update bitmex_api_keys set status = 1 where user_name = ? and testnet_keys = ?;");

        
        try {
            $query->bindParam(1, $data_array['user_name']);
            $query->bindParam(2, $data_array['net_value']);
            $query->execute();
        } catch (PDOException $ex) {
            return 808;
        }

        $return_array = self::getBitmexApiKeys($data_array);
        return $return_array;
    }
    


}
