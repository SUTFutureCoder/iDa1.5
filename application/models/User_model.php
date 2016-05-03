<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 用于关于用户的和数据库交互
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2016 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/

class User_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    static private $_db;
    /**    
     *  @Purpose:    
     *  添加用户    
     *  @Method Name:
     *  addUser($user_info)    
     *  @Parameter: 
     *  array  $user_info 用户信息
     * 
     *  @Return: 
     *  
    */
    public function addUser($user_info){
        $this->load->library('database');
        if (!isset(self::$_db)){
            self::$_db = $this->database->conn();
        }
        
        try{
            self::$_db->ida->user->insert($user_info);
            $result[0] = 1;
            $result[1] = $user_info['_id'];
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
        return $result;
    }

    /**
     * 通过手机号进行修改信息
     *
     * @param $intUserMobile
     * @param $arrUserInfo
     * @return mixed
     */
    public function updateUserInfoByMobile($intUserMobile, $arrUserInfo){
        $this->load->library('database');
        if (!isset(self::$_db)){
            self::$_db = $this->database->conn();
        }

        $arrUserSchool = $arrUserInfo['user_school'];
        unset($arrUserInfo['user_school']);

        return self::$_db->ida->user->update(array('user_telephone' => $intUserMobile,), array('$set' => $arrUserInfo, '$addToSet' => array('user_school' => $arrUserSchool)));
    }
    
    /**    
     *  @Purpose:    
     *  验证用户登录密码    
     *  @Method Name:
     *  checkPassword($user_telephone, $user_password)    
     *  @Parameter: 
     *  array $user_telephone 用户手机号
     *  array $user_password 密码
     * 
     *  @Return: 
     *  array $data 用户信息
     *  bool false 无此用户或密码错误
    */
    public function checkPassword($user_telephone, $user_password){
        $this->load->library('database');
        $this->load->library('encrypt');
        if (!isset(self::$_db)){
            self::$_db = $this->database->conn();
        }
        
        $cursor = self::$_db->ida->user->find(array('user_telephone' => $user_telephone));
        foreach ($cursor as $key => $value){
            //只匹配一个
            $data = $value;
            if (!$key){
                return FALSE;
            } else {
                $data['_id'] = $key;
            }
        }

        if (empty($data)){
            return FALSE;
        }
        
        if ($user_password != $this->encrypt->decode($data['user_password'])){
            return FALSE;
        } else {
            return $data;
        }
    }
    
    /**    
     *  @Purpose:    
     *  检查用户是否已经注册    
     *  @Method Name:
     *  checkUserExist($user_telephone)    
     *  @Parameter: 
     *  string $user_telephone 用户手机号
     * 
     *  @Return: 
     *  bool 0 此用户未注册
     *  bool 1 此用户已经注册
    */
    public function checkUserExist($user_telephone){
        $this->load->library('database');
        if (!isset(self::$_db)){
            self::$_db = $this->database->conn();
        }
        
        $cursor = self::$_db->ida->user->find(array('user_telephone' => $user_telephone));
        foreach ($cursor as $key => $value){
            
        }
        if (!isset($key)){
            return 0;
        } else {
            return 1;
        }
        
    }

    /**
     * 根据用户id列表获取用户信息
     *
     * @param $arrUserIds
     * @return array
     */
    public function getUserInfoByIds($arrUserIds){
        $this->load->library('database');
        if (!isset(self::$_db)){
            self::$_db = $this->database->conn();
        }

        $cursor = self::$_db->ida->user->find(array('_id' => array('$in' => $arrUserIds)),
            array('user_name' => 1, 'user_telephone' => 1, 'user_mail' => 1, 'user_number' => 1, 'user_school' => 1, 'user_major' => 1, ));
        $arrUserInfoList = array();
        foreach ($cursor as $value){
            $arrUserInfoList[$value['_id']->{'$id'}] = $value;
        }
        return $arrUserInfoList;
    }
}
