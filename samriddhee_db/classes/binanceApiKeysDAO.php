<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 27/4/18
 * Time: 3:11 AM
 */


require_once (dirname(__FILE__)."/baseDAO.php");

class binanceApiKeysDAO extends DAO
{

    public static function getBinanceApiKeys()
    {
        $pdo = $GLOBALS['pdo'];
        
        $query = $pdo->prepare("SELECT * from binance_api_keys where status = 1");
        
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
}
