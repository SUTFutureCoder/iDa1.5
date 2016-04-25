<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 连接memcache
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/

class Cache{
    static private $_mc;
    
    static public function memcache(){
        if (self::$_mc){
            return self::$_mc;
        } else {
            self::$_mc = new Memcached();  
            self::$_mc->addServer('127.0.0.1', 11211);
            return self::$_mc;
        }
    }
    
    
    /**    
     *  @Purpose:    
     *  建立命名空间
     *  @Method Name:
     *  setNS($type)
     *  @Parameter: 
     *  string $type 命名空间类型
     *  @Return: 
     *  string $seed 命名空间唯一标识符
    */
    static public function setNS($type){
        if (!self::$_mc){
            self::$_mc = new Memcached(); 
            self::$_mc->addServer('127.0.0.1', 11211);
        }
        
        switch ($type){
            case 'act':
                $tem_seed = 'ac4';
                $seed = 'ac1' . time();
                break;
            
            case 'answer':
                $tem_seed = 'an';
                $seed = 'an' . time();
                break;
            
            case 'role':
                $tem_seed = 'ro';
                $seed = 'ro1' . time();
                break;
            
            case 'authorizee':
                $tem_seed = 'au';
                $seed = 'au' . time();
                break;
            
            case 'question':
                $tem_seed = 'qu';
                $seed = 'qu' . time();
                break;
            
            case 'question_type':
                $tem_seed = 'qu_t';
                $seed = 'qu_t' . time();
                break;
        }
        
        self::$_mc->set('ida_' . $type . '_seed_' . $tem_seed, $seed);
        return $seed;
    }
    
    /**    
     *  @Purpose:    
     *  获取命名空间
     *  @Method Name:
     *  getNS($type)
     *  @Parameter: 
     *  string $type 命名空间类型
     *  @Return: 
     *  string $seed 命名空间唯一标识符
    */
    static public function getNS($type){
        if (!self::$_mc){
            self::$_mc = new Memcached();  
            self::$_mc->addServer('127.0.0.1', 11211);
        }
        
        switch ($type){
            case 'act':
                $tem_seed = 'ac4';
                break;
            
            case 'answer':
                $tem_seed = 'an';
                break;
            
            case 'role':
                $tem_seed = 'ro';
                break;
            
            case 'authorizee':
                $tem_seed = 'au';
                break;
            
            case 'question':
                $tem_seed = 'qu';
                break;
            
            case 'question_type':
                $tem_seed = 'qu_t';
                break;
        }
        
        if ($seed = self::$_mc->get('ida_' . $type . '_seed_' . $tem_seed)){            
            return $seed;
        } else {
            $seed = self::setNS($type);
            return $seed;
        }
    }
}
