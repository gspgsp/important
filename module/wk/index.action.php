<?php

class indexAction extends adminBaseAction{

	protected $model,$uname,$today,$db,$adminid;
	public function __init(){
		$this->adminid=$_SESSION['adminid'];
		$this->uname=$_SESSION['username'];
		$this->db=M("public:common")->model('offers_msg');
		$this->today=strtotime(date('Y-m-d',time()));

	}
	public function init()
	{
		//最近发布时间
		$offerList = $this->db->getAll("SELECT *,(SELECT COUNT(id) FROM p2p_log_sms WHERE FIND_IN_SET(msg.`id`, offers_ids_str)) AS `count`
		FROM p2p_offers_msg AS msg
		WHERE `msg`.`input_time`> ".$this->today." ORDER BY msg.`input_time` DESC");
		$this->assign('offerList', $offerList);
		$this->display('index');
	}
	public function check(){
		$id = sget('id','i');
		$status = sget('status','i');
		if(empty($id) || empty($status)){
			$this->error('操作有误');
		}
		$res = $this->db->where('id='.$id)->update(array('status'=>$status,'update_time'=>time(),'update_admin'=>$this->uname));
		if($res){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}
	public function sendOffersMsg(){
		$id = sget('id','i');
		if(empty($id)){
			$this->error('操作有误');
		}
		$res = $this->db->model('offers_msg')->where('id = '.$id)->getRow();
		if($res['unlock_time'] > CORE_TIME){
			$this->error('该条记录，您已发送，有效期1小时');
		}
		if($res['status'] != 2){
			$this->error('审核通过的牌号才可发送短信');
		}
		// p($res);die;
		$data['grade'] = $res['grade'];
		$data['sale_price'] = $res['sale_price'];
		$data['input_time'] = time();
		$data['offers_id'] = $res['id'];
		$data['status'] = 0;
		$add_res = $this->db->model('offers_cron')->add($data);
		$update_res = $this->db->model('offers_msg')->where('id = '.$id)->update(array('unlock_time'=>CORE_TIME + 3600));
		if($add_res){
			$this->success('发送成功');
		}else{
			$this->error('发送失败');
		}
	}
	public function select(){
		$data = trim($_POST['keyword']);
		$offerList = $this->db->getAll("SELECT *,(SELECT COUNT(id) FROM p2p_log_sms WHERE FIND_IN_SET(msg.`id`, offers_ids_str)) AS `count`
		FROM p2p_offers_msg AS msg
		WHERE `msg`.`input_time`> ".$this->today." and msg.grade = '".$data."' ORDER BY msg.`input_time` DESC");
		// showtrace();
		$this->assign('offerList', $offerList);
		$this->assign('select', 'select');
		$this->display('index');
	}
	//发布
	public function addInfo()
	{
		if($_POST){
			$_data=$_POST;
			//规范牌号厂家输入，拦截基础数据库中不存在的牌号厂家
			$grade=strtolower($_data['grade']);
			$factory=$_data['factory'];
			if(!M('product:product')->where("model='{$grade}'")->select('model')->getOne()) $this->error('添加失败，基础数据库中不存在此牌号');
			if(!M('product:factory')->where("f_name='{$factory}'")->select('f_name')->getOne()) $this->error('添加失败，基础数据库中不存在此厂家');

			$_data['input_time']=time();
			$_data['uid']=$this->adminid;
			$_data['uname']=$this->uname;
			$_data['person_phone']=M('rbac:adm')->getPhoneByAdminId($this->adminid);
			$_data['status']=1;//1.待审核   2审核通过  3.不通过
			if(!$this->db->model('offers_msg')->add($_data)) exit(json_encode(array('err'=>1,'msg'=>'系统错误，发布失败。code:101')));
			exit(json_encode(array('err'=>0,'data'=>$_data)));
		}
	}
	// 报价上传
	public function offerUpload()
	{

		$this->is_ajax=true;
		$url=sget('url','s','');//上传文件名
		$savePath=C('upload_local.path');//文件保存路径
		$path=$savePath.$url;//文件路径
		if(!is_file($path)) $this->error('文件不存在');
		$excelData = $this->read($path);
		foreach ($excelData as $key => $value) {
			$_data['grade']=$value['牌号'];
			$_data['factory']=$value['厂家'];
			$_data['num']=$value['数量'];
			$_data['sale_price']=$value['销售价格'];
			$_data['price']=$value['成本价'];
			$_data['store']=$value['仓库'];
			$_data['stock']=$value['期货'];
			$_data['china_area']=$value['区域'];
			$_data['remark']=$value['备注'];
			$_data['supplier']=$value['供应商'];
			$_data['person']=$value['责任人'];
			$_data['person_phone']=$value['责任人手机'];
			$_data['uname']=$this->uname;
			$_data['uid']=$this->adminid;
			$_data['input_time']=time();
			$this->db->model('offers_msg')->add($_data);
		}
		$this->success('导入成功');
	}

	//库存导入
	public function stockUpload()
	{
		$this->is_ajax=true;
		$url=sget('url','s','');//上传文件名
		$savePath=C('upload_local.path');//文件保存路径
		$path=$savePath.$url;//文件路径
		if(!is_file($path)) $this->error('文件不存在');
		$excelData = $this->read($path);

		$this->model->where("is_stock=1")->delete();
		foreach ($excelData as $key => $value) {
			$_data['pay']=$value['付款'];
			$_data['grade']=$value['牌号'];
			$_data['factory']=$value['厂家'];
			$_data['num']=$value['吨数'];
			$_data['cost']=$value['成本'];
			$_data['price']=$value['售价'];
			$_data['true_price']='实价';
			$_data['ship_type']='自提';
			$_data['store']=$value['仓库'];
			$_data['stock']=$value['现货/期货'];
			$_data['remark']=$value['备注'];
			$_data['uname']=$value['业务员'];
			$_data['input_time']=time();
			$_data['is_stock']=1;
			$this->model->add($_data);
		}
		$this->success('导入成功');

	}

	//excel 导入 --lixian
	protected function read($filename,$encode='utf-8'){
		E('PHPExcel',APP_LIB.'extend');//引入phpexcel类

		$ext = substr(strrchr($filename, '.'), 1);
		if($ext == 'xls'){
			//xls
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		}else{
			//xlsx
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		}
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($filename);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		for ($row = 1; $row <= $highestRow; $row++) {
			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			}
		}
		$field = array_shift($excelData);
		foreach ($excelData as $key => $value) {
			$excelData[$key] = array_combine($field, $value);
		}
		return $excelData;
	}


	// 报价单导出
	public function excelOutput()
	{
		$start=sget('start','s','');
		$end=sget('end','s','');

		if($start){
			$start=strtotime($start);
		}else{
			$start=strtotime(date('Y-m-d',time()));
		}
		if($end){
			$end=strtotime($end)+60*60*24;
		}else{
			$end=$start+60*60*24;
		}

		$list=$this->model->where("input_time between $start and $end and is_stock=0")->order("input_time asc,uname desc")->select("type,grade,factory,num,price,store,ship_type,true_price,uname,stock,FROM_UNIXTIME(input_time,'%Y/%m/%d') as time")->getAll();
		$excle=array('类型','牌号','厂家','数量','价格','仓库','配送','是否实价','发布者','期/现','发布时间');
		$this->exportexcel($list,$excle,"offers-".date("md",$start)."-".date("md",$end-60*60*24));
	}


	/**
	    * 导出数据为excel表格
	    *@param $data    一个二维数组,结构如同从数据库查出来的数组
	    *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
	    *@param $filename 下载的文件名
	    *@examlpe 
	    *$stu = M ('User');
	    *$arr = $stu -> select();
	    *exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
	*/
	protected function exportexcel($data=array(),$title=array(),$filename='report')
	{
	    header("Content-type:application/octet-stream");
	    header("Accept-Ranges:bytes");
	    header("Content-type:application/vnd.ms-excel");  
	    header("Content-Disposition:attachment;filename=".$filename.".xls");
	    header("Pragma: no-cache");
	    header("Expires: 0");
	    //导出xls 开始
	    if (!empty($title)){
	        foreach ($title as $k => $v) {
	            $title[$k]=iconv("UTF-8", "GB2312",$v);
	        }
	        $title= implode("\t", $title);
	        echo "$title\n";
	    }
	    if (!empty($data)){
	        foreach($data as $key=>$val){
	            foreach ($val as $ck => $cv) {
	                $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
	            }
	            $data[$key]=implode("\t", $data[$key]);
	            
	        }
	        echo implode("\n",$data);
	    }
	}

	// 牌号搜索
	public function searchs()
	{
		$where=1;
		$timeline=sget('timeline','i','0');
		$keyword=sget('keyword','s','');

		$p=sget('p','i',1);
		$size=20;
		if($keyword==''||!$timeline) return;

		$where.=" and is_stock=0 and status='上架' and grade like '%{$keyword}%'";
		$timeline=$timeline==1?null:($timeline==2?'供应':'求购');
		if($timeline){
			$where.=" and type='{$timeline}'";
		}
		$list=$this->model->where($where)->page($p,$size)->order("input_time desc")->getPage();
		if($list['data']){
			foreach ($list['data'] as $key => $value) {
				$list['data'][$key]['date']=date('m-d H:i',$value['input_time']);
			}
		}else{
			$list=array();
		}
		$allpage=ceil($list['count']/$size);
		exit(json_encode(array('list'=>$list['data'],'count'=>$allpage,'p'=>$p)));
	}
}