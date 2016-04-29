<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 后台入口
 * 
 * 
 *
 * @copyright  版权所有(C) 2015-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    0.1
 * @link       https://github.com/SUTFutureCoder/
*/

class Admin extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    
    /**    
     *  @Purpose:    
     *  显示后台管理界面    
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
        
        if (!$this->session->userdata('user_role') || !$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'control_center')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }
        
        $this->load->view('control_center_view');
    }
}
