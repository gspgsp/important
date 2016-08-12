<?php
/**
 * 报价发布--黎贤
 */
class myoffersAction extends userBaseAction{

	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}
	//报价发布列表
	public function init()
	{
		$this->act="myoffers";
		$this->name="报价发布";
		$this->type=2;
		
		$this->product_type=L('product_type');//产品类型
		$this->period=L('period');//期货周期
		$this->process_level=L('process_level');//加工级别
		$this->area=M('system:region')->get_regions(1);//地区
		$this->display('mypurchase');
	}

	public function msgToList()
	{
		$id=sget('id','i',0);
		$type=sget('type','i',1);
		$url=$type==1?'mypurchase':'myoffers';
		$_SESSION['msg_pid']=$id;
		$this->forward("/user/{$url}/lists");
	}

	//报价管理
	public function lists()
	{
		$this->act="offerlist";
		$this->type=2;
		$this->name="报价管理";

		if($id=$_SESSION['msg_pid']){
			$_SESSION['msg_pid']=null;
			$size=10;
			$this->db->query('set @mytemp = 0');
			$result=$this->db->query("select nu from (select (@mytemp:=@mytemp+1) as nu,id from p2p_purchase where user_id=$this->user_id and type=$this->type order by input_time desc) as A where A.id=$id");
			$row=mysql_fetch_assoc($result);
			$this->page=ceil($row['nu']/$size);
			if(empty($row)) $id=0;
			$this->assign('id',$id);
		}

		$this->product_type=L('product_type');//产品类型
		$this->shelve_type=L('shelve_type');//上下架状态
		$this->cargo_type=L('cargo_type');//现货期货
		$this->display('mypurchase.list');
	}


	/**
	 * 批量导入报价excel表页面
	 *
     */
	public function offers_excels(){

		$this->act="myoffers";
		$this->assign('type',$_GET['type']);
		$this->display('offers_excels');
	}

	/**
	 * 处理excel文件
	 *
     */
	public function doExcel()
	{
		if($_POST)
		{
			$this->is_ajax=true;
			$saveName=sget('saveName','s','');                     //上传文件名
			$savePath=C('upload_local.path');                      //文件保存路径
			$path=$savePath.$saveName;                             //文件路径
			if(!is_file($path)) $this->error('文件不存在');

			E('PHPExcel',APP_LIB.'extend');                        //引入phpexcel类
			$purModel=M('product:purchase');
			$productModel=M('product:product');                    //产品
			$factoryModel=M('product:factory');                    //厂家
			$regionModel=M('system:region');                       //地区

			static $times=1;//计数器

			$c_id=$_SESSION['uinfo']['c_id'];//客户id
			$customer_manager=$_SESSION['uinfo']['customer_manager'];//交易员id

			$objPHPExcel = PHPExcel_IOFactory::load($path);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			if(empty($sheetData)) $this->error('上传文件不正确，请重新上传');
			foreach ($sheetData as $key => $value) {
				if($times>=20) break;//一次导入最多20条
				if($key==1) continue;//跳过第一条

				//厂家
				if(!$_factory=$factoryModel->where("f_name='{$value['B']}'")->select('fid')->getOne()){
					$f_data=array(
						'f_name'=>$value['B'],
						'input_time'=>CORE_TIME,
					);
					$factoryModel->add($f_data);
					$_factory=$factoryModel->getLastID();
				}
				$product_type=array_flip(L('product_type'));//产品类型
				if(!$product_type=$product_type[$value['D']]) continue;

				$process_type=array_flip(L('process_level'));
				$process_type=isset($process_type[$value['C']])?$process_type[$value['C']]:11;//加工级别
				//产品

				if(!$_model=$productModel->where("model='{$value['A']}' and f_id=$_factory and product_type=$product_type")->select('id')->getOne()){

					$_product=array(
						'model'=>$value['A'],              //牌号
						'product_type'=>$product_type,     //产品类型
						'process_type'=>$process_type,     //加工级别
						'f_id'=>$_factory,                 //厂家id
						'input_time'=>CORE_TIME,           //创建时间
						'status'=>3,                       //审核状态
					);
					$productModel->add($_product);
					$_model=$productModel->getLastID();
				}
				if(!$_provinces=$regionModel->where("name='{$value['G']}' and pid=1")->select('id')->getOne()) continue;//判断地区
				//报价发布
				$_data=array(
					'user_id'=>$this->user_id,//用户id
					'c_id'=>$c_id,//客户id
					'customer_manager'=>$customer_manager,//交易员
					'p_id'=>$_model,//产品
					'number'=>$value['E'],//吨数
					'unit_price'=>$value['F'],//单价
					'provinces'=>$_provinces,//省份id
					'store_house'=>$value['H'],//仓库
					'cargo_type'=>$value['J']=='现货'?1:2,//现货期货
					'bargain'=>$value['I']=='是'?2:1,//是否实价
					'period'=>$value['J']=='现货'?0:1,//报价周期
					'type'=>2,//报价
					'status'=>2,//状态，报价不需要审核
					'input_time'=>CORE_TIME,//创建时间
				);
				$purModel->add($_data);
				$times++;
			}
			$this->success('导入成功');
		}
	}

	/**
	 * 我的供货(报价)
	 *
     */
	public function supply(){
		$this->act='supply';

		$this->display('supply');
	}

	/**
	 *(我的供货)报价列表
     */
	public function subblyTable(){
		$type=2;//报价
		$where="un.sale_user_id=$this->user_id and pur.type=$type  ";

		//收索条件
		if($status=sget('status','s','')){
			$where.=" and sb.status='{$status}' ";
		}
		$page=sget('page','i',1);
		$size=10;
		$list=M('product:salebuy')->getPurPage($where,$page,$size);
		$list=array_unique($list);
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->assign('count',ceil($list['count']/$size));

		$this->display('supply_table');

	}


}