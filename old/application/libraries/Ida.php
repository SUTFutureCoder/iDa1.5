<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 * 关于爱答平台
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/
class Ida{
    static private $_ci;
    static private $_mc;
    static private $_db;
    /**    
     *  @Purpose:    
     *  获取平台统计信息   
     *  @Method Name:
     *  getPlatfromStatis()    
     *  @Parameter:  
     *  @Return: 
     *  array   $ida_info   平台信息
    */ 
    public function getPlatfromStatis(){
        if (!self::$_ci){
            //在自定义类库中初始化CI资源
            self::$_ci =& get_instance();       
        }
        
        //获取活动信息
        $ida_info = array(
            'act_info' => $this->getActInfo(),
            'question_info' => $this->getQuestionInfo(),
            'school_info' => $this->getSchoolInfo(),
        );
        return $ida_info;
    }
    
    /**    
     *  @Purpose:    
     *  获取已过期或未过期活动数量
     *  以及memcache中缓存里列表
     *  @Method Name:
     *  getActInfo()
     *  @Parameter: 
     *  @Return: 
     *  0 无列表
     *  array $act_info 各个活动相关信息
    */ 
    public function getActInfo(){
        if (!self::$_ci){
            self::$_ci =& get_instance();            
        }
        
        if (!self::$_db){
            self::$_ci->load->library('database');
            self::$_db = self::$_ci->database->conn();
        }

        if (!self::$_mc){
            self::$_ci->load->library('cache');        
            self::$_mc = self::$_ci->cache->memcache();
        }
        
        $act_info = array();
                
        //正在进行的活动查询
        $act_info['progress_sum'] = self::$_db->ida->act->find(array('act_start' => array('$lte' => date('Y-m-d H:i:s')), 'act_end' => array('$gte' => date('Y-m-d H:i:s'))))->count();
        $act_info['overdue_sum'] = self::$_db->ida->act->find(array('act_end' => array('$lt' => date('Y-m-d H:i:s'))))->count();
        $act_info['future_sum'] = self::$_db->ida->act->find(array('act_start' => array('$gt' => date('Y-m-d H:i:s'))))->count();
        $act_info['sum'] = array_sum($act_info);
        $act_info['memcache_act_sum'] = count(self::$_mc->get('ida_' . self::$_ci->cache->getNS('act') . '_ing_list'));
        
        $cursor = self::$_db->ida->act->find(array(), array('act_name' => 1, '_id' => 1));
        
        $act_info['join_sum'] = 0;
        foreach ($cursor as $value){
            $act_info['list'][(string)$value['_id']]['name'] = $value['act_name'];            
            $act_info['list'][(string)$value['_id']]['join'] = self::$_db->ida->answer->find(array('act_id' => (string)$value['_id']))->count();
            $act_info['join_sum'] += $act_info['list'][(string)$value['_id']]['join'];
            
            //获取最大、最小值及平均值
            $match = array('$match' => array(
                'act_id' => (string)$value['_id']                
            ));
            
            $group = array('$group' => array(
                '_id' => '$act_id',
                'max_score' => array('$max' => '$answer_score'),
                'min_score' => array('$min' => '$answer_score'),
                'average_score' => array('$avg' => '$answer_score'),
                'average_time' => array('$avg' => '$answer_time')
            ));
            
            $act_info['list'][(string)$value['_id']]['score'] = self::$_db->ida->answer->aggregate(array($match, $group));
        }
        
        return $act_info;
    }
    
    /**    
     *  @Purpose:    
     *  获取题目数量，及其类型分布
     *  以及memcache中缓存里列表
     *  @Method Name:
     *  getQuestionInfo()
     *  @Parameter: 
     * 
     *  @Return: 
     *  0 无列表
     *  array $question_info = (
     *      'sum' => $sum,
     *      'type' => array(
     *          classname => classsum
     *      )
     *  ) 问题数量列表
    */ 
    public function getQuestionInfo(){
        if (!self::$_ci){
            self::$_ci =& get_instance();
        }
        
        if (!self::$_db){
            self::$_ci->load->library('database');
            self::$_db = self::$_ci->database->conn();
        }

        if (!self::$_mc){
            self::$_ci->load->library('cache');        
            self::$_mc = self::$_ci->cache->memcache();
        }
        
        $question_info = array();
        
        $class = self::$_db->ida->command(array('distinct' => 'question', 'key' => 'question_type'));
        
        $question_info['type_sum'] = array();
        foreach ($class['values'] as $value){
            $question_info['type_sum'][$value] = self::$_db->ida->question->find(array('question_type' => $value))->count();            
            
        }
        
        $question_info['sum'] = array_sum($question_info['type_sum']);
        return $question_info;
    }
    
    /**    
     *  @Purpose:    
     *  获取总数及学校分布情况
     *  @Method Name:
     *  getSchoolInfo()
     *  @Parameter: 
     * 
     *  @Return: 
     *  0 无列表
     *  array $school = (
     *      'sum' => $sum,
     *      'class' => array(
     *          classname => classsum
     *      )
     *  ) 问题数量列表
    */ 
    public function getSchoolInfo(){
        if (!self::$_ci){
            self::$_ci =& get_instance();
        }
        
        if (!self::$_db){
            self::$_ci->load->library('database');
            self::$_db = self::$_ci->database->conn();
        }
        
        $school_info = array();
        
        $school = self::$_db->ida->command(array('distinct' => 'user', 'key' => 'user_school'));
        
        $school_info['type_sum'] = array();
        foreach ($school['values'] as $value){
            $school_info['type_sum'][$value] = self::$_db->ida->user->find(array('user_school' => $value))->count();            
        }
        
        arsort($school_info['type_sum']);
        $school_info['sum'] = array_sum($school_info['type_sum']);
        return $school_info;
    }
}