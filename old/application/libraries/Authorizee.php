<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 * 权限操作
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/
class Authorizee{
    static private $_ci;


    /**    
     *  @Purpose:    
     *  检查权限   
     *  @Method Name:
     *  CheckAuthorizee($user_role)    
     *  @Parameter: 
     *  $user_role 用户角色 
     *  @Return: 
     *  1 成功
     *  0 失败
    */ 
    public function CheckAuthorizee($user_role, $role_authorizee){
        if (!self::$_ci){
            //在自定义类库中初始化CI资源
            self::$_ci =& get_instance();       
        }
        
        self::$_ci->load->library('cache');
        
        $mc = self::$_ci->cache->memcache();
        
        if (!($data = $mc->get('ida_' . self::$_ci->cache->getNS('authorizee') . '_role_name_' . $user_role))){
            self::$_ci->load->library('database');
            $db = self::$_ci->database->conn();
            $cursor = $db->ida->role->find(array('role_name' => $user_role), array('role_right' => 1));
            
            foreach ($cursor as $id => $value){
                $data = $value;
            }
            
            if (isset($user_role['role_right'])){
                //设置memcache,防止污染memcache
                $mc->set('ida_' . self::$_ci->cache->getNS('authorizee') . '_role_name_' . $user_role, $data['role_right']);
            }
                    
            if (in_array($role_authorizee, $data['role_right'])){
                return 1;
            } else {
                return 0;
            }
            
        } else {
            if (in_array($role_authorizee, $data)){
                return 1;
            } else {
                return 0;
            }
        }
    }
}