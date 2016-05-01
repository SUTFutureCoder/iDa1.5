<?php
/**
 * 用于对问题类型进行管理
 *
 * 提供修改题目、题库删除、复制等
 *
 * Created by PhpStorm.
 * User: lin
 * Date: 16-4-28
 * Time: 下午9:37
 */
class Admin_question_manage extends CI_Controller{

    //用于在界面上显示
    static private $QUESTION_TYPE = array(
        //显示 => value
        '单选' => 'choose',
        '多选' => 'multi_choose',
        '填空' => 'fill',
        '判断' => 'judge',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function Index(){
        $this->load->model('question_model');
        $this->load->library('session');
        $this->load->library('role');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'question_update')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        //获取题库名称列表
        $arrQuestionType = $this->question_model->getQuestionType();

        $this->load->view('admin_question_manage_view', array(
            'question_type'        => $arrQuestionType,
            'question_answer_type' => self::$QUESTION_TYPE,
        ));

        
    }

    public function searchQuestion(){
        $this->load->model('question_model');
        $this->load->library('session');
        $this->load->library('role');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'question_update')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        if (empty($this->input->post('search_text', true))){
            echo json_encode(array('code' => -1, 'error' => '请输入搜索关键字',));
            exit;
        }

        $arrField = array(
            'type'              => 1,
            'question_type'     => 1,
            'question_content'  => 1,
            'question_add_time' => 1,
            'question_id'       => 1,
        );

        $arrData = $this->question_model->searchQuestion($this->input->post('search_text', true), $arrField);

        echo json_encode($arrData);
    }

    public function getQuestionInfoById(){
        $this->load->model('question_model');
        $this->load->library('session');
        $this->load->library('role');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'question_update')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        $strId = $this->input->post('question_id', true);

        if (empty($strId)){
            echo json_encode(array('code' => -1, 'error' => '传输的问题id有误'));
            exit;
        }

        echo json_encode($this->question_model->getQuestionById($strId));
    }

    /**
     * 通过question id删除问题
     *
     * @return int
     */
    public function deleteQuestionById(){
        $this->load->model('question_model');
        $this->load->library('session');
        $this->load->library('role');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'question_dele')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        if (!$this->input->post('question_id', true)){
            echo json_encode(array('code' => -1, '输入的id不能为空'));
            exit;
        }

        $ret = $this->question_model->deleteQuestionById($this->input->post('question_id', true));
        if (false === $ret){
            echo json_encode(array('code' => -2, '删除失败'));
            exit;
        }

        echo json_encode(array('code' => 1));
    }
}