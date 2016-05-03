<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 * 用于活动的数据操作
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/

class Act_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    static private $_db = null;

    private static function getDbInstance(){
        $ci =& get_instance();
        $ci->load->library('database');

        if (self::$_db === null){
            self::$_db = $ci->database->conn();
        }
    }


    /**    
     *  @Purpose:    
     *  设置活动
     *  @Method Name:
     *  setAct($act)    
     *  @Parameter: 
     *  array $act 活动数据
     *  @Return: 
     *  0 添加失败
     *  _id 添加成功
    */ 
    public function setAct($act){
        self::getDbInstance();
        
        self::$_db->ida->act->insert($act);
        if ($act['_id']){
            return $act['_id'];
        } else {
            return 0;
        }
    }
    
    /**    
     *  @Purpose:    
     *  获取活动列表
     *  @Method Name:
     *  getActList()    
     *  @Parameter: 
     *  @Return: 
     *  0 无列表
     *  array $act_list 活动列表
    */ 
    public function getActList($arrUserSchool = null){
        self::getDbInstance();

        if (null === $arrUserSchool){
            $cursor = self::$_db->ida->act->find(array('act_start' => array('$lt' => date('Y-m-d H:i:s')), 'act_end' => array('$gt' => date('Y-m-d H:i:s')), 'act_private' => 0,), array('act_name' => 1, 'act_comment' => 1, 'act_start' => 1, 'act_end' => 1, 'act_img' => 1));
        } else {
            $cursor = self::$_db->ida->act->find(array('act_start' => array('$lt' => date('Y-m-d H:i:s')), 'act_end' => array('$gt' => date('Y-m-d H:i:s')), '$or' => array(array('act_private' => 0), array('act_school' => array('$in' => $arrUserSchool)))), array('act_name' => 1, 'act_comment' => 1, 'act_start' => 1, 'act_end' => 1, 'act_img' => 1));
        }


        $act_list = array();
        foreach ($cursor as $key => $value){
            $act_list[$key][] = $value;
        }
        
        if (!isset($key)){
            return 0;
        }
        return $act_list;
    }
    
    /**    
     *  @Purpose:    
     *  通过活动id获取活动信息
     *  @Method Name:
     *  getActInfoById($id)    
     *  @Parameter: 
     *  string objectId($id) 活动标识ID
     *  @Return: 
     *  0 无列表
     *  array $act_info 活动详情
    */ 
    public function getActInfoById($id){
        self::getDbInstance();
        
        try{
            $cursor = self::$_db->ida->act->find(array('_id' => new MongoId("$id")));
        } catch (Exception $ex) {
            return 0;
        }
        
        
        foreach ($cursor as $key => $value){
            
            $act_info = $value;
        }
        if (!isset($key)){
            return 0;
        }
    
        return $act_info;
    }
    
    /**    
     *  @Purpose:    
     *  通过用户id和活动id获取排行榜和用户排名
     *  [user_id, answer_score, end_time, start_time, user_name]
     *  @Method Name:
     *  getActInfoById($id)    
     *  @Parameter: 
     *  string objectId($id) 活动标识ID
     *  @Return: 
     *  0 无列表
     *  array $act_list 活动列表
    */ 
    public function getUserRank($act_id){
        self::getDbInstance();
        
        try{
            $cursor = self::$_db->ida->answer->find(array('act_id' => $act_id), array('answer_score' => 1, 'user_name' => 1, 'user_school' => 1, 'user_class' => 1, 'answer_time' => 1, 'user_id' => 1))->sort(array('answer_score' => -1, 'answer_time' => 1))->limit(100);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return 0;
        }
        
        $rankdata = array();
        foreach ($cursor as $key => $value){
            $rankdata[] = $value;
        }
        
        if (!isset($key)){
            return 0;
        }
        
        return $rankdata;
    }
    
    /**    
     *  @Purpose:    
     *  获取已过期或未过期活动数量
     *  以及memcache中缓存里列表
     *  @Method Name:
     *  getActStatisById($act_id)
     *  @Parameter: 
     *  $act_id 活动id
     *  @Return: 
     *  0 无列表
     *  array $act_statis
    */ 
    public function getActStatisById($act_id){
        self::getDbInstance();

        $act_statis = array();
        $act_statis['join'] = self::$_db->ida->answer->find(array('act_id' => $act_id))->count();

        //获取最大、最小值及平均值
        $match = array('$match' => array(
            'act_id' => $act_id                
        ));

        $group = array('$group' => array(
            '_id' => '$act_id',
            'max_score' => array('$max' => '$answer_score'),
            'min_score' => array('$min' => '$answer_score'),
            'average_score' => array('$avg' => '$answer_score'),
            'average_time' => array('$avg' => '$answer_time')
        ));
            
        $act_statis['score'] = self::$_db->ida->answer->aggregate(array($match, $group));
        
        return $act_statis;
    }

    /**
     *
     * 获取按照添加时间倒序排列的所有活动基础信息列表
     *
     * @return array
     */
    public function getAllActBasicList(){
        self::getDbInstance();

        $arrRet     = self::$_db->ida->act->find(array(), array('act_name' => 1, 'act_school' => 1, 'act_start' => 1, 'act_end' => 1,))
            ->sort(array('act_add_time' => -1))->limit(50);
        $arrActData = array();
        foreach ($arrRet as $_id => $arrValue){
            $arrActData[] = $arrValue;
        }
        return $arrActData;
    }

    /**
     * 修改活动信息
     *
     * @param $actId
     * @param $actInfo
     * @return mixed
     */
    public function updateAct($actId, $actInfo){
        self::getDbInstance();
        if (!empty(self::$_db->ida->act->update(array('_id' => new MongoId($actId)), array('$set' => $actInfo))['ok'])){
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除活动
     *
     * @param $actId
     * @return bool
     */
    public function deleteAct($actId){
        self::getDbInstance();
        if (!empty(self::$_db->ida->act->remove(array('_id' => new MongoId($actId)))['ok'])){
            return true;
        } else {
            return false;
        }
    }
    
}
