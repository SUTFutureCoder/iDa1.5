<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 * 添加用户
 * 
 *
 * @copyright  版权所有(C) 2015-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    0.1
 * @link       https://github.com/SUTFutureCoder/
*/

class Admin_add_user extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    /**    
     *  @Purpose:    
     *  显示界面    
     *  @Method Name:
     *  Index()    
     *  @Parameter: 
     *     
     *  @Return: 
     *  
    */
    
    public function Index(){
        $this->load->library('session');
        $this->load->library('role');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'person_add')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }
        
        //获取角色列表
        $role_list = array();
        $role_list = $this->role->getRoleList();
        $this->load->view('admin_add_user_view', array(
            'role_list' => $role_list
        ));
    }
    
    /**    
     *  @Purpose:    
     *  添加用户
     *  @Method Name:
     *  addUser()    
     *  @Parameter: 
     *     
     *  @Return: 
     *  
    */
    
    public function addUser(){
        $this->load->library('session');
        $this->load->library('role');
        $this->load->library('authorizee');
        $this->load->model('user_model');
        $this->load->library('encrypt');
        
        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'person_add')){
            echo json_encode(array('code' => -1, 'error' => '抱歉，您的权限不足'));
            return 0;
        }
        
        $clean = array();
        
        if ($this->input->post('user_telephone', TRUE) && ctype_digit($this->input->post('user_telephone', TRUE)) && 11 == strlen($this->input->post('user_telephone', TRUE))){
            $clean['user_telephone'] = $this->input->post('user_telephone', TRUE);
        } else {
            echo json_encode(array('code' => -2, 'error' => '手机号码有误或为空'));
            return 0;
        }
        
        if ($this->input->post('user_mail', TRUE) && filter_var($this->input->post('user_mail', TRUE), FILTER_VALIDATE_EMAIL)){
            $clean['user_mail'] = $this->input->post('user_mail', TRUE);
        } else {
            echo json_encode(array('code' => -3, 'error' => '邮箱地址为空或不合法'));
            return 0;
        }
        
        if ($this->input->post('user_password', TRUE) != $this->input->post('user_password_confirm', TRUE)){
            echo json_encode(array('code' => -9, 'error' => '两次输入的密码不一致'));
        } else {
            if (strlen($this->input->post('user_password', TRUE)) > 20){
                echo json_encode(array('code' => -10, 'error' => '请输入20位以下的密码'));
            } else {
                $clean['user_password'] = $this->encrypt->encode($this->input->post('user_password', TRUE));
            }
        }
        
        if ($this->input->post('user_name', TRUE) && 20 >= mb_strlen($this->input->post('user_name', TRUE), 'utf-8')){
            $clean['user_name'] = $this->input->post('user_name', TRUE);
        } else {
            echo json_encode(array('code' => -4, 'error' => '姓名未填写或超过20个字符'));
            return 0;
        }
        
        
        if ($this->input->post('user_number') && ctype_digit($this->input->post('user_number', TRUE)) && 20 >= strlen($this->input->post('user_number', TRUE))){
            $clean['user_number'] = $this->input->post('user_number', TRUE);
        } else {
            echo json_decode(array('code' => -5, 'error' => '学号不能超过20个数字或为空'));
            return 0;
        }
        
        if ($this->input->post('user_school', TRUE) && 20 >= mb_strlen($this->input->post('user_school', TRUE), 'utf-8')){
            $clean['user_school'] = $this->input->post('user_school', TRUE);
        } else {
            echo json_encode(array('code' => -6, 'error' => '学校名称不能超过20个字符或为空'));
            return 0;
        }
        
        if ($this->input->post('user_major') && 20 >= mb_strlen($this->input->post('user_major', TRUE), 'utf-8')){
            $clean['user_major'] = $this->input->post('user_major', TRUE);
        } else {
            echo json_encode(array('code' => -7, 'error' => '专业班级不能大于20个字符或为空'));
            return 0;
        }
        
        if (!in_array($this->input->post('user_role', TRUE), $this->role->getRoleList())){
            echo json_encode(array('code' => -8, 'error' => '角色名称不合法'));
            return 0;
        } else {
            $clean['user_role'] = $this->input->post('user_role', TRUE);
        }
        
        $clean['user_join_time'] = date('Y-m-d H:i:s');
        
        $result = $this->user_model->addUser($clean);
        
        if (1 != $result){
            echo json_encode(array('code' => -11, 'error' => $result));
            return 0;
        } else {
            echo json_encode(array('code' => 1));
        }
    }
}