<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 添加活动
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/
class Admin_add_act extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    
    
    /**    
     *  @Purpose:    
     *  显示添加活动界面    
     *  @Method Name:
     *  Index()    
     *  @Parameter: 
     *     
     *  @Return: 
     *  
    */
    public function Index(){
        $this->load->library('session');
        $this->load->library('authorizee');
        $this->load->model('question_model');
        
        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'person_add')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }
        
        $type = $this->question_model->getQuestionType();
        
        $this->load->view('admin_add_act_view', array(
                    'type' => $type
                ));
    }
    
    
    
    
    /**    
     *  @Purpose:    
     *  添加活动    
     *  @Method Name:
     *  addAct()    
     *  @Parameter: 
     *     
     *  @Return: 
     *  
    */
    public function addAct(){
        $this->load->model('act_model');
        $this->load->library('authorizee');
        $this->load->library('session');
        $this->load->library('cache');
        $this->load->library('secure');
        
        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'question_add')){
            echo json_encode(array('code' => -1, 'error' => '抱歉，您的权限不足'));
            return 0;
        }
        
        $clean = array();
        
        if (!$this->input->post('act_name', TRUE) || 50 < mb_strlen($this->input->post('act_name', TRUE))){
            echo json_encode(array('code' => -2, 'error' => '活动名称不能为空或超过50个字符'));
            return 0;
        } else {
            $clean['act_name'] = $this->input->post('act_name', TRUE);
        }
        
        
        if (!$this->input->post('act_comment', TRUE) || 300 < mb_strlen($this->input->post('act_comment', TRUE))){
            echo json_encode(array('code' => -3, 'error' => '抱歉，活动说明不能为空或超过300个字符'));
            return 0;
        } else {
            $clean['act_comment'] = $this->input->post('act_comment', TRUE);
        }
        
        if (!$this->input->post('act_rule', TRUE)){
            echo json_encode(array('code' => -16, 'error' => '抱歉，请输入活动规则说明'));
            return 0;     
        } else {
            $clean['act_rule'] = $this->input->post('act_rule', TRUE);
        }
        
        if ('on' == $this->input->post('act_private')){
            $clean['act_private'] = 1;            
        }
        
        if ($this->input->post('act_school', TRUE)){
            $clean['act_school'] = $this->input->post('act_school', TRUE);
        }
        
        if (!$this->input->post('act_start', TRUE) || !$this->secure->CheckDateTime($this->input->post('act_start', TRUE))){
            echo json_encode(array('code' => -4, 'error' => '抱歉，开始时间不合法，请尝试关闭输入法。例如2014-05-29 10:10:00'));
            return 0;
        } else {
            $clean['act_start'] = $this->input->post('act_start');
        }
        
        if (!$this->input->post('act_end', TRUE) || !$this->secure->CheckDateTime($this->input->post('act_end', TRUE))){
            echo json_encode(array('code' => -5, 'error' => '抱歉，结束时间不合法，请尝试关闭输入法。例如2014-05-29 10:10:00'));
            return 0;
        } else {
            $clean['act_end'] = $this->input->post('act_end', TRUE);
        }
        
        if (!$this->input->post('act_question_type', TRUE)){
            echo json_encode(array('code' => -6, 'error' => '抱歉，问题类型不能为空'));
            return 0;
        } else {
            $clean['act_question_type'] = $this->input->post('act_question_type', TRUE);
        }
        
        if (!ctype_digit($this->input->post('act_question_choose_sum', TRUE))){
            echo json_encode(array('code' => -9, 'error' => '抱歉，单选数量不能为空且为数字'));
            return 0;
        } else {
            $clean['act_question_choose_sum'] = $this->input->post('act_question_choose_sum', TRUE);
        }
        

        if (!ctype_digit($this->input->post('act_question_multi_choose_sum', TRUE))){
            echo json_encode(array('code' => -10, 'error' => '抱歉，多选数量不能为空且为数字'));
            return 0;
        } else {
            $clean['act_question_multi_choose_sum'] = $this->input->post('act_question_multi_choose_sum', TRUE);
        }
        
        if (!ctype_digit($this->input->post('act_question_judge_sum', TRUE))){
            echo json_encode(array('code' => -7, 'error' => '抱歉，判断数量不能为空且为数字'));
            return 0;
        } else {
            $clean['act_question_judge_sum'] = $this->input->post('act_question_judge_sum', TRUE);
        }
        
        if (!ctype_digit($this->input->post('act_question_fill_sum', TRUE))){
            echo json_encode(array('code' => -7, 'error' => '抱歉，填空数量不能为空且为数字'));
            return 0;
        } else {
            $clean['act_question_fill_sum'] = $this->input->post('act_question_fill_sum', TRUE);
        }
        
        if (!$this->input->post('act_paper_time', TRUE) || !ctype_digit($this->input->post('act_paper_time', TRUE))){
            echo json_encode(array('code' => -8, 'error' => '抱歉，答题时间限制需要为数字'));
            return 0;
        } else {
            $clean['act_paper_time'] = $this->input->post('act_paper_time', TRUE);
        }
        
        //预览图
        if (!in_array($_FILES['upload_img']['type'], array('image/jpeg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/x-png', 'image/png  '))){
            echo json_encode(array('code' => -11, 'error' => '您上传的图片类型错误'));
            return 0;
        } else {
            if (100000 < $_FILES['upload_img']['size']){
                echo json_encode(array('code' => -12, 'error' => '您上传的图片过大，请勿超过100kb'));
                return 0;
            } else {
                if ($_FILES['upload_img']['error'] > 0){
                    echo json_encode(array('code' => -13, 'error' => $_FILES["upload_img"]["error"]));
                    return 0;
                } else {
                    if (file_exists('upload/act_img/' . $_FILES['upload_img']['name'])){
                        echo json_encode(array('code' => -14, 'error' => '此文件已存在，请更名上传'));
                        return 0;
                    } else {
                        $clean['act_img'] = time() . $_FILES['upload_img']['name'];
                        move_uploaded_file($_FILES['upload_img']["tmp_name"], 'upload/act_img/' . $clean['act_img']);
                    }
                }
            }
        }
        
        $clean['act_add_user_name'] = $this->session->userdata('user_name');
        $clean['act_add_user_id'] = $this->session->userdata('user_id');
        $clean['act_add_time'] = date('Y-m-d H:i:s');
        
        if (!$id = $this->act_model->setAct($clean)){
            echo json_encode(array('code' => -15, 'error' => '插入失败'));
            return 0;
        } else {
            $mc = $this->cache->memcache();
            $mc->set('ida_' . $this->cache->getNS('act') . '_act_' . $id, $clean);
                
            echo json_encode(array('code' => 1));
            return 0;
        }
    }   
}
