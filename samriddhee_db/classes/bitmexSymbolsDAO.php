<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 27/4/18
 * Time: 5:53 PM
 */
require_once (dirname(__FILE__)."/baseDAO.php");

class bitmexSymbolsDAO extends DAO
{
    public static function getBitmexSymbols()
    {
        $pdo = $GLOBALS['pdo'];

        try {
            $query = $pdo->prepare("SELECT * from bitmex_symbols where secure_acc_to_price = 1");
            $query->execute();
        } catch (PDOException $ex) {
            return 808;
        }
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $return_array = array();
        $return_array['return_code'] = 200;
        $return_array['bitmex_symbols'] = $result;
        
        return $return_array;
    }
    
    public static function updateBitmexSymbolsRunningStatus($data_array)
    {
        $pdo = $GLOBALS['pdo'];

        if($data_array['net_value'] == 1){//**//Testnet
            $query = $pdo->prepare("update bitmex_symbols set running_status_test = 0 ");
        }else if($data_array['net_value'] == 2){//**//Mainnet
            $query = $pdo->prepare("update bitmex_symbols set running_status_main = 0 ");
        }
       
        
        try {
            $query->execute();
        } catch (PDOException $ex) {
            return 808;
        }

        if($data_array['net_value'] == 1){//**//Testnet
            $query = $pdo->prepare("update bitmex_symbols set running_status_test = 1 where symbol = ? ");
        }else if($data_array['net_value'] == 2){//**//Mainnet
            $query = $pdo->prepare("update bitmex_symbols set running_status_main = 1 where symbol = ? ");
        }

        try {
            $query->bindParam(1, $data_array['symbol']);
            $query->execute();
        } catch (PDOException $ex) {
            return 808;
        }

        $return_array = self::getBitmexSymbols($data_array);
        return $return_array;
    }
}
