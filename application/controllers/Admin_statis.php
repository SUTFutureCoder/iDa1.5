<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 * 用于各种统计
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/
class Admin_statis extends CI_Controller{
    
    function __construct() {
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
        $this->load->library('authorizee');
        $this->load->library('ida');
        
        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'ida_statis')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }
        
        $statis = $this->ida->getPlatfromStatis();
        
        $this->load->view('admin_statis_view', array('statis' => $statis));
    }
}

