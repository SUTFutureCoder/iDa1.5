<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 用于各种统计事项
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/
class Status{
    static private $_ci;
    
    /**    
     *  @Purpose:    
     *  获取已过期或未过期活动数量信息
     *  以及memcache中缓存里列表
     *  @Method Name:
     *  getActSum()
     *  @Parameter: 
     *  @Return: 
     *  0 无列表
     *  array $act_sum = (
     *      'sum' => $sum,
     *      'progress_sum' => $progress_sum,
     *      'overdue_sum' => $overdue_sum
     *  ) 活动数量列表
    */ 
    public function getActSum(){
        if (!self::$_ci){
            self::$_ci =& get_instance();
        }
        self::$_ci->load->library('cache');        
        $mc = self::$_ci->cache->memcache();
        
        
        self::$_ci->load->library('database');
        $db = self::$_ci->database->conn();
        
        //正在进行的活动查询
        $cursor = self::$_ci->ida->act->count();
        
        
        
        return ;
    }
    
}