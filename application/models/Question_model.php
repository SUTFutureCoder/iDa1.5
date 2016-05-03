<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 * 问题相关
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/
class Question_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    static private $_db = null;

    private static function getDbInstance(){
        if (null === self::$_db){
            $ci =& get_instance();
            $ci->load->library('database');
            self::$_db = $ci->database->conn();
        }
        return self::$_db;
    }


    /**    
     *  @Purpose:    
     *  获取问题类型
     *  @Method Name:
     *  getQuestionType()    
     *  @Parameter: 
     *  
     *  @Return: 
     *  array $type 类型
    */ 
    public function getQuestionType(){
        self::getDbInstance();
        
        $type = array();
//        $type_cursor = self::$_db->ida->command(array('distinct' => 'question', 'key' => 'question_type', 'query' => array('is_delete' => 0)));
        $type_cursor = self::$_db->ida->question->distinct('question_type', array('is_delete' => 0));

        foreach ($type_cursor as $key => $value){
            $type[] = $value;
        }
        return $type;
    }
    
    /**    
     *  @Purpose:    
     *  添加问题
     *  @Method Name:
     *  addQuestion($question)    
     *  @Parameter: 
     *  array $question 问题数组
     *  @Return: 
     *  0 添加失败
     *  $_id 添加成功
    */ 
    public function addQuestion($question){
        self::getDbInstance();
        
        //设置或获取自增
        //[这种设计比较有问题，如果被问题被删除如何处理是个问题，因此以后最好只用_id。本着不要动运行良好代码的原则，所以将全部问题加上is_delete]
        $cursor = self::$_db->ida->question->find(array(), array('question_id' => 1))->sort(array('question_id' => -1))->limit(1);
        foreach ($cursor as $key => $value){
        }
        
        if (!isset($key)){
            $question['question_id'] = 1;
        } else {
            $question['question_id'] = ++$value['question_id'];
        }
        
        self::$_db->ida->question->insert($question);
        if ($question['_id']){
            return $question['question_id'];
        } else {
            return 0;
        }
    }

    /**
     * 更新问题
     *
     *
     * @param $questionId
     * @param $arrData
     * @return bool
     */
    public function updateQuestion($questionId, $arrData){
        self::getDbInstance();
        if (!empty(self::$_db->ida->question->update(array('question_id' => (int)$questionId), array('$set' => $arrData))['ok'])){
            return true;
        } else {
            return false;
        }
    }
    
    /**    
     *  @Purpose:    
     *  添加问题
     *  @Method Name:
     *  dumpQuestion($question_type, $type)
     *  @Parameter: 
     *  string $question_type 问题类型
     *  string $type 类型
     *  @Return: 
     *  0 无数据
     *  array $data id
    */ 
    public function dumpQuestion($question_type, $type){
        self::getDbInstance();
        
        $data = array();
        
        $cursor = self::$_db->ida->question->find(array('type' => $type, 'question_type' => $question_type, 'is_delete' => 0), array('question_id' => 1));
        
        foreach ($cursor as $key => $value){
            $data[] = $value['question_id'];
        }
        if (!isset($key)){
            return 0;
        }
        
        return $data;
    }
    
    /**    
     *  @Purpose:    
     *  通过id获取问题
     *  @Method Name:
     *  getQuestionById($question_id)
     *  @Parameter: 
     *  int $question_id 问题id
     *  @Return: 
     *  0 无数据
     *  array $data 问题数据(单值)
    */ 
    public function getQuestionById($question_id){
        self::getDbInstance();
        
        $cursor = self::$_db->ida->question->find(array('question_id' => (int)$question_id));
        
        foreach ($cursor as $key => $value){
            $data = $value;
        }
        if (!isset($key)){
            return 0;
        }
        return $data;
    }

    /**
     *
     * 通过题库名称获取题目列表以及分页
     *
     * @param $strQuestionBank
     * @param $intPageNo
     * @param $intPerpage
     * @param $strQuestionType
     * @return array
     */
    public function getQuestionListByQuestionBankName($strQuestionBank, $intPageNo = 1, $intPerpage = 20, $strQuestionType = null){
        self::getDbInstance();

        if ($strQuestionType === null){
            $arrConds = array('question_type' => $strQuestionBank);
        } else {
            $arrConds = array('question_type' => $strQuestionBank, 'type' => $strQuestionType);
        }

        $cursor  = self::$_db->ida->question->find($arrConds)->sort(array('question_add_time' => -1))->limit($intPerpage)->skip(($intPageNo - 1) * $intPerpage);
        $data    = array(
            'sum' => $datasum = self::$_db->ida->question->find($arrConds)->count()
        );
        foreach ($cursor as $key => $value){
            $data['data'][] = $value;
        }
        return $data;
    }

    /**
     * 根据关键字搜索问题
     *
     * @param $keyword
     * @param array $arrField
     * @return int
     */
    public function searchQuestion($keyword, $arrField = array()){
        self::getDbInstance();

        $cursor = self::$_db->ida->question->find(array('question_content' => new MongoRegex("/$keyword/"), 'is_delete' => 0), $arrField)->sort(array('question_add_time' => -1));

        $data   = array();
        foreach ($cursor as $key => $value){
            $data[] = $value;
        }
        return $data;
    }

    /**
     *
     * 根据id删除问题
     *
     * @param $intQuestionId
     * @return bool
     */
    public function deleteQuestionById($intQuestionId){
        self::getDbInstance();

        if (!empty(self::$_db->ida->question->update(array('question_id' => (int)$intQuestionId), array('$set' => array('is_delete' => 1)))['ok'])){
            return true;
        } else {
            return false;
        }
    }
}