<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/12/13
 * Time: 10:48
 */
class potentialCustomersAction extends adminBaseAction
{
    public function __init()
    {
        $this->db = M('public:common')->model('potential_customers');
        $this->assign('is_status',L('is_status')); //资源库抓取电话是否已打状态
    }

    public function init(){
        $action=sget('action');
        if($action=='grid') {
            $page = sget("pageIndex",'i',0); //页码
            $size = sget("pageSize",'i',20); //每页数
            $list = $this->db->model('potential_customers')->select('*')->where("status=0")->page($page, $size)->getPage();
            $result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
            $this->json_output($result);
        }
        $this->display('potential_customers.html');
    }

    //更新电话状态
    public function editInfo(){
        $this->is_ajax=true; //指定为Ajax输出
        $id=sget('id','i',0);
        $data['status']=1;
        if(!id){
             $this->error('信息不存在');
        }else{
            $result=$this->db->wherePk($id)->update($data);
        }
        if(!$result) $this->error('操作失败');
        $this->success('操作成功');
    }

    //下载当前数据
    public function downLoad(){
        $action=sget('action');
        if($action=='grid'){
            $list = $this->db->model('potential_customers')->select('*')->getAll();
            if(!count($list)) $this->error('没有可供下载的数据');
            $resultPHPExcel = E('PHPExcel',APP_LIB.'extend');

//            p($match);die;
//            $resultPHPExcel->getActiveSheet()->setCellValue('A1', '编号');
//            $resultPHPExcel->getActiveSheet()->setCellValue('B1', '电话号码');
//            $resultPHPExcel->getActiveSheet()->setCellValue('C1', '状态');
            $i =1;
            $arr[0][0]='编号';
            $arr[0][1]='电话号码';
            $arr[0][2]='状态';
            $arrs=array();
            foreach($list as $k=>$v){
                $arrs[$k][0]=$v['id'];
                $arrs[$k][1]=$v['phone_number'];
                $arrs[$k][2]=$v['status'];
            }
            $arrs=array_merge($arr+$arrs);
            foreach($arrs as $item){
                //赋值
                $resultPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i,$item[0],PHPExcel_Cell_DataType::TYPE_STRING);
                $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item[1]);
                $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item[2]);

                $i++;
            }
            //设置列宽
            $resultPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);


            $outputFileName = '来自我的塑料网'.'.xlsx';
            $xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);  //创建文件格式写入对象实例
            header("Content-Type: application/octet-stream");
            header('Content-Disposition:inline;filename="'.$outputFileName.'"');
            header("Content-Transfer-Encoding: binary");
//		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache");
            $xlsWriter->save( "php://output" );

        }
    }
}