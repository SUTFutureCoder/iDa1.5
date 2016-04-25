<?php
/**
 * 用于执行临时脚本文件
 *
 * Created by PhpStorm.
 * User: lin
 * Date: 16-4-25
 * Time: 上午11:01
 */

class Script extends CI_Controller{

    private static $db = null;

    private static function getDbInstance(){
        $ci =& get_instance();
        $ci->load->library('database');
        if (null === self::$db){
            self::$db = $ci->database->conn();
        }
        return self::$db;
    }

    public function updateUserSchoolStringToArray(){
        $objDb       = self::getDbInstance();
        $arrUserList = $objDb->ida->user->find();
        foreach ($arrUserList as $_id => $value){
            $strUserSchool = $value['user_school'];
            $objDb->ida->user->update(array('_id' => new MongoId($_id)), array('$set' => array('user_school' => array($strUserSchool,))), array('upsert' => false, 'multiple' => true));
        }
        echo 'done';
    }
}