<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 * mongodb数据库连接
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    0.1
 * @link       https://github.com/SUTFutureCoder/
*/
class Database{
    
    static private $_db;
    
    /**    
     *  @Purpose:    
     *  连接数据库
     *  @Method Name:
     *  conn($db_name)    
     *  @Parameter: 
     *  string $db_name 数据库名
     *  @Return: 
     *  
    */
    static public function conn(){
        if (self::$_db){
            return self::$_db;
        } else {
            self::$_db = new MongoClient();            
            return self::$_db;
        }
    }
}
