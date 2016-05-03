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

    /**
     * 搜索问题
     *
     * @return int
     */
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

        if (!($this->input->post('search_text', true))){
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

    /**
     * 通过id获取问题信息
     *
     * @return int
     */
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


    public function getQuestionList(){
        $this->load->model('question_model');
        $this->load->library('session');
        $this->load->library('role');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'question_update')){
            echo json_encode(array('code' => 0, 'error' => '抱歉，您的权限不足'));
            exit;
        }

        if ('' == ($this->input->post('question_bank_name', true))){
            echo json_encode(array('code' => -1, 'error' => '抱歉，题库名称不能为空'));
            exit;
        }
        $strQuestionBankName = $this->input->post('question_bank_name', true);

        if ('' == ($this->input->post('question_type', true))){
            echo json_encode(array('code' => -2, 'error' => '抱歉，题目类型不能为空'));
            exit;
        }

        $strQuestionType = $this->input->post('question_type', true);
        if ('all' == $strQuestionType){
            $strQuestionType = null;
        }

        $arrPage   = $this->input->post('page');

        $intPageNo = !empty($arrPage['page_no']) ? $arrPage['page_no'] : 1;
        $intPerpage= !empty($arrPage['perpage']) ? $arrPage['perpage'] : 20;

        $arrRet = $this->question_model->getQuestionListByQuestionBankName($strQuestionBankName, $intPageNo, $intPerpage, $strQuestionType);
        echo json_encode($arrRet);
        exit;
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
            echo json_encode(array('code' => 0, 'error' => '抱歉，您的权限不足'));
            exit;
        }

        if (!$this->input->post('question_id', true)){
            echo json_encode(array('code' => -1, 'error' => '输入的id不能为空'));
            exit;
        }

        $ret = $this->question_model->deleteQuestionById($this->input->post('question_id', true));
        if (false === $ret){
            echo json_encode(array('code' => -2, 'error' => '删除失败'));
            exit;
        }

        echo json_encode(array('code' => 1));
    }

    /**
     * 修改问题
     *
     * @return int
     */
    public function modifyQuestion(){
        $this->load->model('question_model');
        $this->load->library('session');
        $this->load->library('role');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'question_update')){
            echo json_encode(array('code' => 0, 'error' => '抱歉，您的权限不足'));
            return 0;
        }

        if (!$this->input->post('question_id', true)){
            echo json_encode(array('code' => -1, 'error' => 'id不能为空'));
            exit;
        }

        $intQuestionId = $this->input->post('question_id', true);

        $clean         = array();
        if ($this->input->post('question_type_fill', TRUE)){
            //以填入的内容为准
            if (20 < $this->input->post('question_type_fill', TRUE)) {
                echo json_encode(array('code' => -2, 'error' => '抱歉，问题类型不能为空或大于20个字符'));
                return 0;
            }
            $strQuestionType = $this->input->post('question_type_fill', TRUE);

        } else {
            if (!$this->input->post('question_type_select', TRUE) || 20 < $this->input->post('question_type_select', TRUE)){
                echo json_encode(array('code' => -2, 'error' => '抱歉，问题类型不能为空或大于20个字符'));
                return 0;
            }
            $strQuestionType = $this->input->post('question_type_select', TRUE);

        }

        $clean['question_type'] = $strQuestionType;

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

        $result = $this->question_model->updateQuestion($intQuestionId, $clean);
        if (0 != $result){
            $result = $intQuestionId;
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
            echo json_encode(array('code' => -8, 'error' => '更新数据失败'));
        }

    }
}
