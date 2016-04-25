<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 爱答入口
 * 
 *
 * @copyright  版权所有(C) 2015-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    0.1
 * @link       https://github.com/SUTFutureCoder 
*/
         
class Index extends CI_Controller{
    function __construct() {
        parent::__construct();
    }
    
    /**    
     *  @Purpose:    
     *  显示主页    
     *  @Method Name:
     *  Index()    
     *  @Parameter: 
     *     
     *  @Return: 
     *  
     * :WARNING: 请不要地址末尾加上index.php打开 :WARNING:
    */
    public function Index()
    {          
        $this->load->library('session');
        $this->load->library('cache');
        $this->load->model('act_model');
        
        $mc = $this->cache->memcache();
        
        //获取活动列表
        if (!$data = $mc->get('ida_' . $this->cache->getNS('act') . '_ing_list')){
            $data = $this->act_model->getActList();
            //保存一天
//            $mc->set('ida_' . $this->cache->getNS('act') . '_ing_list', $data, 86400);
            $mc->set('ida_' . $this->cache->getNS('act') . '_ing_list', $data, 86400);
        }
        
        $this->load->view('index_view', array(
            'act_list' => $data   
        ));
    }
    
    /**    
     *  @Purpose:    
     *  显示验证码    
     *  @Method Name:
     *  setValidateCode()    
     *  @Parameter: 
     *     
     *  @Return: 
     *  
    */
    public function setValidateCode(){
        //准备注册/登录
        $this->load->library('session');
        $this->load->library('ValidateCode');
        $_vc = new ValidateCode();            
        $_vc->doimg();
        $this->session->set_userdata('authnum_session', $_vc->getCode());
    }
    
    
    /**    
     *  @Purpose:    
     *  验证用户登陆    
     *  @Method Name:
     *  checkUserLogin()    
     *  @Parameter: 
     *  
     *  @Return: 
     *  
    */
    public function checkUserLogin(){
        $this->load->library('session');
        $this->load->model('user_model');
        
        $clean = array();
        
        if ($this->session->userdata('authnum_session') != $this->input->post('loginValidateCode', TRUE)){
            echo json_encode(array('code' => -1, 'error' => '验证码不正确'));
            return 0;
        }
        
        if (!$this->input->post('loginMobile', TRUE) || 11 != strlen($this->input->post('loginMobile', TRUE))){
            echo json_encode(array('code' => -2, 'error' => '抱歉，您的手机号不合法'));
            return 0;
        } else {
            $clean['user_telephone'] = $this->input->post('loginMobile', TRUE);
        }
        
        if (!$this->input->post('loginPassword', TRUE) || 20 < strlen($this->input->post('loginPassword', TRUE))){
            echo json_encode(array('code' => -3, 'error' => '抱歉，您的密码不能超过20个字符或为空'));
            return 0;
        } else {
            $clean['user_password'] = $this->input->post('loginPassword', TRUE);
        }
        
        $result = $this->user_model->checkPassword($clean['user_telephone'], $clean['user_password']);
        
        if ($result == FALSE){
            echo json_encode(array('code' => -4, 'error' => '抱歉，查无此人或密码错误'));
            return 0;
        }
        
        //写入session
        $this->session->set_userdata('user_name', $result['user_name']);
        $this->session->set_userdata('user_role', $result['user_role']);
        $this->session->set_userdata('user_id', $result['_id']);
        $this->session->set_userdata('user_telephone', $result['user_telephone']);
        $this->session->set_userdata('user_school', $result['user_school']);
        
        echo json_encode(array('code' => 1, 'user_name' => $result['user_name']));
    }
    
    
    
    /**    
     *  @Purpose:    
     *  验证用户注册    
     *  @Method Name:
     *  checkUserRegister()    
     *  @Parameter: 
     * 
     * 
     *  @Return: 
     *  
    */
    public function checkUserRegister(){
        $this->load->model('user_model');
        $this->load->library('encrypt');
        $this->load->library('session');
        
        $clean = array();
        
        if ($this->session->userdata('authnum_session') != $this->input->post('registerValidateCode', TRUE)){
            echo json_encode(array('code' => -1, 'error' => '抱歉，您的验证码输入有误'));
            return 0;
        }
        
        if (!$this->input->post('registerTele', TRUE) || !ctype_digit($this->input->post('registerTele', TRUE)) || 11 != strlen($this->input->post('registerTele', TRUE))){
            echo json_encode(array('code' => -2, 'error' => '您的手机号需要是11位数字'));
            return 0;
        } else {
            $clean['user_telephone'] = $this->input->post('registerTele', TRUE);
        }
        
        if ($this->user_model->checkUserExist($this->input->post('registerTele', TRUE))){
            echo json_encode(array('code' => -10, 'error' => '此用户已注册'));
            return 0;
        }
        
        if (!$this->input->post('registerMail', TRUE) || !filter_var($this->input->post('registerMail', TRUE), FILTER_VALIDATE_EMAIL)){
            echo json_encode(array('code' => -3, 'error' => '您的邮箱为空或不合法'));
            return 0;
        } else {
            $clean['user_mail'] = $this->input->post('registerMail', TRUE);
        }
        
        if ($this->input->post('registerPassword', TRUE) != $this->input->post('registerPasswordConfirm', TRUE)){
            echo json_encode(array('code' => -4, 'error' => '两次的密码不正确'));
            return 0;
        } else {
            if (!$this->input->post('registerPassword', TRUE) || 20 < strlen($this->input->post('registerPassword', TRUE))){
                echo json_encode(array('code' => -5, 'error' => '密码不能为空或超过20个字符'));
                return 0;
            } else {
                $clean['user_password'] = $this->encrypt->encode($this->input->post('registerPassword', TRUE));
            }
        }
        
        if (!$this->input->post('registerName', TRUE) || 20 < mb_strlen($this->input->post('registerName', TRUE))){
            echo json_encode(array('code' => -6, 'error' => '您的姓名不能为空或超过20个字符'));
            return 0;
        } else {
            $clean['user_name'] = $this->input->post('registerName', TRUE);
        }
        
        if (!$this->input->post('registerNumber', TRUE) || !ctype_digit($this->input->post('registerNumber', TRUE)) || 20 < strlen($this->input->post('registerNumber', TRUE))){
            echo json_encode(array('code' => -7, 'error' => '您的学号不能为空或超过20位'));
            return 0;
        } else {
            $clean['user_number'] = $this->input->post('registerNumber', TRUE);
        }
        
        if (!$this->input->post('registerSchool', TRUE) || 20 < mb_strlen($this->input->post('registerSchool', TRUE))){
            echo json_encode(array('code' => -8, 'error' => '您的学校不能为空或超过20个字符'));
            return 0;
        } else {
            $clean['user_school'] = $this->input->post('registerSchool', TRUE);
        }
        
        
        if (!$this->input->post('registerMajor', TRUE) || 30 < mb_strlen($this->input->post('registerMajor', TRUE))){
            echo json_encode(array('code' => -9, 'error' => '您的注册专业班级不能为空或超过30个字符'));
            return 0;
        } else {
            $clean['user_major']  = $this->input->post('registerMajor', TRUE);
        }
        
        $clean['user_role'] = '普通用户';
        $clean['user_join_time'] = date('Y-m-d H:i:s');
                
        //完成数据验证，写入数据库
        if (is_string($result = $this->user_model->addUser($clean))){
            echo json_encode(array('code' => -11, 'error' => $result));
            return 0;
        } else {
            //插入成功
            $this->session->set_userdata('user_role', '普通用户');
            $this->session->set_userdata('user_telephone', $this->input->post('registerTele', TRUE));
            $this->session->set_userdata('user_id', $result[1]);
            $this->session->set_userdata('user_name', $this->input->post('registerName', TRUE));
            
            echo json_encode(array('code' => 1));
            return 0;
        }
        
    }
    
    
    /**    
     *  @Purpose:    
     *  用户注销    
     *  @Method Name:
     *  logout()    
     *  @Parameter: 
     * 
     * 
     *  @Return: 
     *  
    */
    public function logout(){
        $this->load->library('session');
        
        if ('1' == $this->input->post('logout', TRUE)){
            $this->session->sess_destroy();
            echo 'success';
        }
    }
    
    
    
    /**    
     *  @Purpose:    
     *  获取活动信息    
     *  @Method Name:
     *  getActInfo()    
     *  @Parameter: 
     * 
     * 
     *  @Return: 
     *  
    */
    public function getActInfo(){
        $this->load->library('session');
        $this->load->library('cache');
        $this->load->model('act_model');
        
        if (!$this->session->userdata('user_id')){
            echo json_encode(array('code' => -1, 'error' => '请您登录后查看'));
            return 0;
        }
        
        $mc = $this->cache->memcache();
        
        if (!$data = $mc->get('ida_' . $this->cache->getNS('act') . '_act_' . $this->input->post('act_id', TRUE))){
            if ($result = $this->act_model->getActInfoById($this->input->post('act_id', TRUE))){
                $mc->set('ida_' . $this->cache->getNS('act') . '_act_' . $this->input->post('act_id', TRUE), $result);
                echo json_encode(array('code' => 1, 'act_info' => $result));
            } else {
                echo json_encode(array('code' => -2, 'error' => '未检索到您的活动'));
                return 0;
            }
        } else {
            echo json_encode(array('code' => 1, 'act_info' => $data));
        }
    }
}