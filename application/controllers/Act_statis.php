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


    public function dumpStatisToExcel(){
        $this->load->library('session');
        $this->load->library('authorizee');
        $this->load->model('act_model');
        $this->load->model('user_model');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'person_add')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }
        $strActId   = $this->input->get('id');

        $arrActInfo = $this->act_model->getActInfoById($strActId);
        if (empty($arrActInfo)){
            echo json_encode(array('code' => -1, 'error' => '考试名称不存在'));
            return 0;
        }

        $strActId  = $arrActInfo['_id']->{'$id'};
        $arrStatis = $this->act_model->getUserRank($strActId);

        //接合arrInfo输出excel
        $this->load->library('PHPExcel');
        $strTitle = $arrActInfo['act_name'] . '答题数据导出';

        $this->phpexcel->getProperties()->setCreator('*Chen Lin https://github.com/SUTFutureCoder')
            ->setTitle($strTitle);

        $objCurrentSheet = $this->phpexcel->getActiveSheet();
        $objCurrentSheet->setCellValue('A1', '活动名称');
        $objCurrentSheet->setCellValue('B1', $arrActInfo['act_name']);
        $objCurrentSheet->setCellValue('C1', '开始时间');
        $objCurrentSheet->setCellValue('D1', $arrActInfo['act_start']);
        $objCurrentSheet->setCellValue('E1', '结束时间');
        $objCurrentSheet->setCellValue('F1', $arrActInfo['act_end']);
        $objCurrentSheet->setCellValue('G1', '创建单位');
        $objCurrentSheet->setCellValue('H1', $arrActInfo['act_school']);
        $objCurrentSheet->setCellValue('I1', '答题时限');
        $objCurrentSheet->setCellValue('J1', $arrActInfo['act_paper_time']);
        $objCurrentSheet->setCellValue('K1', '活动序列号');
        $objCurrentSheet->setCellValue('L1', $strActId);

        //设置第二行表头
        //获取统计数据
        $arrActStatis  = $this->act_model->getActStatisById($strActId);
        $objCurrentSheet->setCellValue('C2', '参与人数');
        $objCurrentSheet->setCellValue('D2', $arrActStatis['join']);
        $objCurrentSheet->setCellValue('E2', '最高分');
        $objCurrentSheet->setCellValue('F2', $arrActStatis['score']['result'][0]['max_score']);
        $objCurrentSheet->setCellValue('G2', '最低分');
        $objCurrentSheet->setCellValue('H2', $arrActStatis['score']['result'][0]['min_score']);
        $objCurrentSheet->setCellValue('I2', '平均分');
        $objCurrentSheet->setCellValue('J2', $arrActStatis['score']['result'][0]['average_score']);
        $objCurrentSheet->setCellValue('K2', '平均用时');
        $objCurrentSheet->setCellValue('L2', $arrActStatis['score']['result'][0]['average_time']);


        //设置第二行表头
        $objCurrentSheet->setCellValue('A3', '排名');
        $objCurrentSheet->setCellValue('B3', '姓名');
        $objCurrentSheet->setCellValue('C3', '分数');
        $objCurrentSheet->setCellValue('D3', '完成时间[s]');
        $objCurrentSheet->setCellValue('E3', '单位');
        $objCurrentSheet->setCellValue('F3', '班级');
        $objCurrentSheet->setCellValue('G3', '学号');
        $objCurrentSheet->setCellValue('H3', '手机号码');
        $objCurrentSheet->setCellValue('I3', '邮箱');

        //遍历获取用户集
        foreach ($arrStatis as $arrStatisValue){
            $arrUserIdList[] = $arrStatisValue['user_id'];
        }
        $arrUserInfoList = $this->user_model->getUserInfoByIds($arrUserIdList);

        //遍历写入
        //用于出错串行
        $intErrorLine  = 0;
        foreach ($arrStatis as $key => $arrValue){
            $intLine   = $key + 4 - $intErrorLine;

            //注意两种存储方法
            $strUserId     = '';
            if (isset($arrValue['user_id']->{'$id'})){
                $strUserId = $arrValue['user_id']->{'$id'};
            } elseif (is_string($arrValue['user_id'])){
                $strUserId = $arrValue['user_id'];
            }

            if ('' === $strUserId || !isset($arrUserInfoList[$strUserId])){
                ++$intErrorLine;
                continue;
            }
            $objCurrentSheet->setCellValue('A' . $intLine, ($intLine - 3));
            $objCurrentSheet->setCellValue('B' . $intLine, $arrValue['user_name']);
            $objCurrentSheet->setCellValue('C' . $intLine, $arrValue['answer_score']);
            $objCurrentSheet->setCellValue('D' . $intLine, $arrValue['answer_time']);
            $objCurrentSheet->setCellValue('E' . $intLine, implode(',', $arrUserInfoList[$strUserId]['user_school']));
            $objCurrentSheet->setCellValue('F' . $intLine, $arrUserInfoList[$strUserId]['user_major']);
            $objCurrentSheet->setCellValue('G' . $intLine, $arrUserInfoList[$strUserId]['user_number']);
            $objCurrentSheet->setCellValue('H' . $intLine, $arrUserInfoList[$strUserId]['user_telephone']);
            $objCurrentSheet->setCellValue('I' . $intLine, $arrUserInfoList[$strUserId]['user_mail']);
        }

        //样式设置
        $objTitleSheetStyle = $objCurrentSheet->getStyle('A1');
        $objTitleSheetStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objTitleSheetStyle->getFont()->setBold(true);

        //开始复制表头样式
        for ($strTempTitle = 'C'; $strTempTitle <= 'L'; ++$strTempTitle){
            if (ord($strTempTitle) % 2 != 0){
                $objCurrentSheet->duplicateStyle($objTitleSheetStyle, $strTempTitle . '1');
                $objCurrentSheet->getStyle($strTempTitle . '1')->getFont()->setBold(true);
                $objCurrentSheet->duplicateStyle($objTitleSheetStyle, $strTempTitle . '2');
                $objCurrentSheet->getStyle($strTempTitle . '2')->getFont()->setBold(true);
            }
        }
        $objCurrentSheet->duplicateStyle($objTitleSheetStyle, 'A3:I3');

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