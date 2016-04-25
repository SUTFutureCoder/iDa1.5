<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 关于页面
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2015 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    
 * @link       https://github.com/SUTFutureCoder/
*/

class About extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    
        /**    
     *  @Purpose:    
     *  显示关于界面    
     *  @Method Name:
     *  Index()    
     *  @Parameter: 
     * 
     * 
     *  @Return: 
     *  
    */
    public function index(){
        $this->load->library('session');
        $this->load->view('about_view');
    }
   
}