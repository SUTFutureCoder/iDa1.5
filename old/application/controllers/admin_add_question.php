<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 添加问题
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/
class Admin_add_question extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    
    /**    
     *  @Purpose:    
     *  显示添加题目界面    
     *  @Method Name:
     *  Index()    
     *  @Parameter: 
     *     
     *  @Return: 
     *  
    */
    public function Index(){
        $this->load->view('admin_add_question_view');
    }
    
    
    /**    
     *  @Purpose:    
     *  添加题目    
     *  @Method Name:
     *  setQuestion()    
     *  @Parameter: 
     *     
     *  @Return: 
     *  
    */
    public function setQuestion(){
        $this->load->model('question_model');
        $this->load->library('authorizee');
        $this->load->library('session');
        $this->load->library('cache');
        
        
        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'question_add')){
            echo json_encode(array('code' => -1, 'error' => '抱歉，您的权限不足'));
            return 0;
        }
        
        $clean = array();
        if (!$this->input->post('question_type', TRUE) || 20 < $this->input->post('question_type', TRUE)){
            echo json_encode(array('code' => -2, 'error' => '抱歉，问题类型不能为空或大于20个字符'));
            return 0;
        } else {
            $clean['question_type'] = $this->input->post('question_type', TRUE);
        }
        
        if (!$this->input->post('question_content', TRUE) || 300 < mb_strlen($this->input->post('question_content', TRUE))){
            echo json_encode(array('code' => -3, 'error' => '抱歉，题干不能为空或超过300个字符'));
            return 0;
        } else {
            $clean['question_content'] = $this->input->post('question_content', TRUE);
        }
        
        if (!$this->input->post('question_score', TRUE) || !ctype_digit($this->input->post('question_score', TRUE))){
            echo json_encode(array('code' => -12, 'error' => '抱歉，请输入有效的分值'));
            return 0;
        } else {
            $clean['question_score'] = $this->input->post('question_score', TRUE);
        }
        
        
        //选择或填空或判断
        if ($this->input->post('question_choose', TRUE)){
            if (!$this->input->post('question_choose')){
                echo json_encode(array('code' => -4, 'error' => '选项不能为空'));
                return 0;
            }
            $clean['question_choose'] = $this->input->post('question_choose', TRUE);
            
            if (!$this->input->post('question_choose_answer', TRUE)){
                echo json_decode(array('code' => -7, 'error' => '选项答案不能为空或非字符'));
                return 0;
            } else {
                $question_choose_ansert = explode(' ', strtoupper($this->input->post('question_choose_answer', TRUE)));
                $clean['question_answer'] = $question_choose_ansert;
                
                //判定类型多选还是单选
                if (1 == count($question_choose_ansert)){
                    $clean['type'] = 'choose';
                } else if (1 < count($question_choose_ansert)) {
                    $clean['type'] = 'multi_choose';
                } else {
                    echo json_encode(array('code' => -9, 'error' => '正确答案数量有误'));
                    return 0;
                }
            }
        } else if ($this->input->post('question_fill_answer', TRUE)) {
            //填空题            
            if (!$this->input->post('question_fill_answer', TRUE) || 300 < mb_strlen($this->input->post('question_fill_answer',TRUE))){
                echo json_encode(array('code' => -6, 'error' => '填空题答案不能为空'));
                return 0;
            } else {
                $clean['type'] = 'fill';
                $clean['question_answer'] = $this->input->post('question_fill_answer', TRUE);
            }
            
        } else if ('on' == $this->input->post('question_judge', TRUE)){
            $clean['type'] = 'judge';

            if ('on' == $this->input->post('question_judge_true', TRUE)){
                $clean['question_answer'] = 1;
            } else {
                $clean['question_answer'] = 0;
            }
        } else {
            echo json_encode(array('code' => -10, 'error' => '请在添加题目处填写正确的数据'));
            return 0;
        }
        
        if ($this->input->post('question_private', TRUE) && 'on' == $this->input->post('question_private', TRUE)){
            $clean['question_private'] = 1;
        }  
        
        
        if ($this->input->post('question_hint', TRUE)){
            $clean['question_hint'] = $this->input->post('question_hint', TRUE);            
        }
        
        $clean['question_add_time'] = date('Y-m-d H:i:s');
        $clean['question_add_user_id'] = $this->session->userdata('user_id');
        $clean['question_add_user_name'] = $this->session->userdata('user_name');
        
        $result = $this->question_model->addQuestion($clean);
        
        if (0 != $result){
            //dump至memcache
            $mc = $this->cache->memcache();
            $clean['question_id'] = $result;
            $mc->set('ida_' . $this->cache->getNS('question') . '_' . $result, $clean);
            
            //id插入到分类中
            if (!$data = $mc->get('ida_' . $this->cache->getNS('question_type') . '_' . $clean['question_type'] . '_' . $clean['type'])){
                //从数据库中dump到分类
                if ($question_id_set = $this->question_model->dumpQuestion($clean['question_type'], $clean['type'])){
                    $mc->set('ida_' . $this->cache->getNS('question_type') . '_' . $clean['question_type'] . '_' . $clean['type'], $question_id_set);
                } else {
                    //之前没有数据
                    $question_id[0] = $clean['question_id'];
                    $mc->set('ida_' . $this->cache->getNS('question_type') . '_' . $clean['question_type'] . '_' . $clean['type'], $question_id);
                }
            } else {
                //之前有数据
                $data[] = $clean['question_id'];
                $mc->set('ida_' . $this->cache->getNS('question_type') . '_' . $clean['question_type'] . '_' . $clean['type'], $data);
            }
            echo json_encode(array('code' => 1));
        } else {
            echo json_encode(array('code' => -8, 'error' => '插入数据失败'));
        }
    }    
}