<?php
/**
 * 管理活动列表
 *
 * 先不分页，按照创建时间倒序
 *
 * Created by PhpStorm.
 * User: lin
 * Date: 16-4-25
 * Time: 下午4:52
 */
class Admin_act_manage extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function Index(){
        $this->load->library('session');
        $this->load->library('authorizee');
        $this->load->model('act_model');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'person_add')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        $arrActList = $this->act_model->getActList();

        $this->load->view('admin_add_act_view', array(
            'type' => $arrActList,
        ));
    }
}