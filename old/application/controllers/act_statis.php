<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 活动统计、排榜
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/
class Act_statis extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }
    
    
    /**    
     *  @Purpose:    
     *  显示排行榜    
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
    
}