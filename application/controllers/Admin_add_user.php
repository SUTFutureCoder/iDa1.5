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
            return 0;
        } else {
            if (strlen($this->input->post('user_password', TRUE)) > 20){
                echo json_encode(array('code' => -10, 'error' => '请输入20位以下的密码'));
                return 0;
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
            $clean['user_school'] = array($this->input->post('user_school', TRUE));
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

    /**
     *
     */
    public function addUserBatch(){
        set_time_limit(90);
        ini_set("memory_limit", "1024M");

        $this->load->library('session');
        $this->load->library('authorizee');
        $this->load->model('user_model');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'person_add')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        if (empty($_FILES['upload_user_file']['tmp_name']) || !is_file($_FILES['upload_user_file']['tmp_name'])){
            echo json_encode(array('code' => -1, 'error' => '请上传填写后的excel格式文件'));
            exit;
        }

        $this->load->library('PHPExcel');
        //直接处理
        $objPHPExcel  = PHPExcel_IOFactory::load($_FILES['upload_user_file']['tmp_name']);
        $arrSheetData = $objPHPExcel->getSheet(0)->toArray(null, true, true, true);

        if (empty($arrSheetData) || empty($arrSheetData[2])){
            echo json_encode(array('code' => -2, 'error' => '您上传的表格数据不能为空'));
            exit;
        }

        //清除表头
        array_shift($arrSheetData);

        //准备通过导出EXCEL反馈新用户密码
        foreach ($arrSheetData as $key => $arrSheetValue){
            $line          = $key + 1;
            $arrUserInfo   = array();

            //注意用户一样的情况，保留密码，更新用户单位，返回密码
            //开始验证
            if (empty($arrSheetValue['A']) || !ctype_digit((int)$arrSheetValue['A']) || 11 != strlen($arrSheetValue['A'])){
                echo json_encode(array('code' => -3, 'error' => '您上传文件中第' . $line . '行-A列的数据应该为11位数字'));
                exit;
            }
            $arrUserInfo['user_telephone'] = (string)$arrSheetValue['A'];

            if (empty($arrSheetValue['B']) || !filter_var($arrSheetValue['B'], FILTER_VALIDATE_EMAIL)){
                echo json_encode(array('code' => -4, 'error' => '您上传文件中第' . $line . '行-B列的数据应该为合法的邮箱地址'));
                exit;
            }
            $arrUserInfo['user_mail']      = $arrSheetValue['B'];

            if (empty($arrSheetValue['C']) || 16 <= mb_strlen($arrSheetValue['C'], 'utf-8')){
                echo json_encode(array('code' => -5, 'error' => '您上传文件中第' . $line . '行-C列的数据应该为小于16字'));
                exit;
            }
            $arrUserInfo['user_name']      = $arrSheetValue['C'];

            if (empty($arrSheetValue['D']) || !is_int((int)$arrSheetValue['D']) || 20 <= strlen($arrSheetValue['D'])){
                echo json_encode(array('code' => -6, 'error' => '您上传文件中第' . $line . '行-D列的数据应该为小于20个字符的学号'));
                exit;
            }
            $arrUserInfo['user_number']    = (string)$arrSheetValue['D'];

            if (empty($arrSheetValue['E']) || 20 <= mb_strlen($arrSheetValue['E'])){
                echo json_encode(array('code' => -7, 'error' => '您上传文件中第' . $line . '行-E列的数据应该为小于20个字符的学校名称'));
                exit;
            }
            $arrUserInfo['user_school']    = $arrSheetValue['E'];

            if (empty($arrSheetValue['F']) || 30 <= mb_strlen($arrSheetValue['F'])){
                echo json_encode(array('code' => -8, 'error' => '您上传文件中第' . $line . '行-F列的数据应该为小于30个字符的专业名称'));
                exit;
            }
            $arrUserInfo['user_major']     = $arrSheetValue['F'];

            //验证用户是否已注册
            if ($this->user_model->checkUserExist($arrUserInfo['user_telephone'])){
                //只根据电话信息更新用户其他信息，addToSet学校信息，保留密码
                $this->user_model->updateUserInfoByMobile($arrUserInfo['user_telephone'], $arrUserInfo);
            } else {
                $this->load->library('encrypt');
                //直接新建用户,使用学号后6位新建(也可以改)
                $arrUserInfo['user_password']  = $this->encrypt->encode(substr($arrUserInfo['user_number'], -6));
                $arrUserInfo['user_role']      = '普通用户';
                $arrUserInfo['user_join_time'] = date('Y-m-d H:i:s');
                $arrUserInfo['user_school']    = array($arrUserInfo['user_school']);
                $this->user_model->addUser($arrUserInfo);
            }
        }

        //删除文件
        unlink($_FILES['upload_user_file']['tmp_name']);

        echo json_encode(array('code' => 0, 'error' => 'done'));
        exit;
    }

    /**
     * 用于获取批量添加用户excel模板
     *
     * @return int
     */
    public function getExcelTemplate(){
        $this->load->library('session');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'person_add')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        $strTitle = '答题平台添加用户标准模板' . time();


        $this->load->library('PHPExcel');
        $this->phpexcel->getProperties()->setCreator('*Chen Lin https://github.com/SUTFutureCoder')
            ->setTitle($strTitle);

        $objCurrentSheet = $this->phpexcel->getActiveSheet();
        $objCurrentSheet->setCellValue('A1', '手机号码');
        $objCurrentSheet->setCellValue('B1', '邮箱号码');
        $objCurrentSheet->setCellValue('C1', '姓名');
        $objCurrentSheet->setCellValue('D1', '学号');
        $objCurrentSheet->setCellValue('E1', '就读学校');
        $objCurrentSheet->setCellValue('F1', '专业班级');

        $objTitleSheetStyle = $objCurrentSheet->getStyle('A1');
        $objTitleSheetStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objTitleSheetStyle->getFont()->setBold(true);

        $objCurrentSheet->duplicateStyle($objTitleSheetStyle, 'B1:F1');


        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $strTitle . '.xls"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter = new PHPExcel_Writer_Excel5($this->phpexcel);
        $objWriter->save('php://output');
    }
}