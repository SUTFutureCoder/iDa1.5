<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 * 角色操作
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    0.1
 * @link       https://github.com/SUTFutureCoder/
*/

class Role{
    static private $_ci;
        
    /**    
     *  @Purpose:    
     *  获取角色列表
     *  @Method Name:
     *  getRoleList()
     *  @Parameter: 
     *  
     *  @Return: 
     *  array $data 角色列表
    */
    public function getRoleList(){
        if (!self::$_ci){
            self::$_ci =& get_instance();
        }
        
        self::$_ci->load->library('cache');
        $mc = self::$_ci->cache->memcache();
       
        if (!($data = $mc->get('ida_' . self::$_ci->cache->getNS('role') . '_role_name_list'))){
            self::$_ci->load->library('database');
            $db = self::$_ci->database->conn();
            $cursor = $db->ida->role->find(array(), array('role_name' => 1));
            
            foreach ($cursor as $id => $value){
                $data[] = $value['role_name'];
            }
            
            $mc->set('ida_' . self::$_ci->cache->getNS('role') . '_role_name_list', $data);
        }
        
        return $data;
    }
}