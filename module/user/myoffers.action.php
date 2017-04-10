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
			//只处理整条空行
			foreach($sheetData as $k=>$v){
				$a = true;
				foreach($v as $kk=>$vv){
					if($vv != null){
						$a = false;
					}
				}
				if($a == true){
					unset($sheetData[$k]);
				}
			}

			if(empty($sheetData)) $this->error('上传文件不正确，请重新上传');
			foreach ($sheetData as $key => $value) {
				if($times>=20) break;//一次导入最多20条
				if($key==1) continue;//跳过第一条
//				if($value['A']=='') $this->error('牌号不能为空');
//				if($value['B']=='') $this->error('厂家不能为空');
//				if($value['C']=='') $this->error('加工级别不能为空');
//				if($value['D']=='') $this->error('类别不能为空');
//				if($value['E']==0 || $value['E']=='') $this->error('第'.$key.'行，发布数量不能为0或者空');
//				if($value['F']==0 || $value['F']=='') $this->error('发布价格不正确');
//				if($value['G']=='') $this->error('省份不能为空');
//				if($value['J']=='') $this->error('现期货不能为空');
				if($value['A']=='') {
					$error['err'][]='第'.($key)."行,牌号不能为空";
					continue;
				}
				if($value['B']=='') {
					$error['err'][]='第'.($key)."行，厂家不能为空";
					continue;
				}
				if($value['C']=='') {
					$error['err'][]='第'.($key)."行，加工级别不能为空";
					continue;
				}
				if($value['D']=='') {
					$error['err'][]='第'.($key)."行，类别不能为空";
					continue;
				}
				if($value['E']==''||$value['E']==0) {
					$error['err'][]='第'.($key)."行，发布数量不能为空或为0";
					continue;
				}
				if($value['F']==''||$value['F']==0) {
					$error['err'][]='第'.($key)."行，发布价格不能为空或为0";
					continue;
				}
				if($value['G']=='') {
					$error['err'][]='第'.($key)."行，省份不能为空";
					continue;
				}
				if($value['H']=='') {
					$error['err'][]='第'.($key)."行，仓库地址不能为空";
					continue;
				}
				if($value['I']=='') {
					$error['err'][]='第'.($key)."行，是否实价不能为空";
					continue;
				}
				if($value['J']=='') {
					$error['err'][]='第'.($key)."行，现期货不能为空";
					continue;
				}

				//厂家 status 1为正常 2为锁定

				if(!$_factory=$factoryModel->where("f_name='{$value['B']}'")->select('fid')->getOne()){
					$f_data=array(
						'f_name'=>$value['B'],
						'input_time'=>CORE_TIME,
						'status'=>2,
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
					'origin'=>$value['provinces'].'|'.$value['provinces'],//后台显示交货地用
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
			if(!empty($error['err'])){
				$str = '';
				foreach($error['err'] as $k=>$v){
					$str.=$v."\n";
				}
				$this->error("数据部分导入成功,异常数据为:\n".$str."\n【请单独上传异常数据】");
			}else{
				$this->success('导入成功');
			}
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
		$where="un.sale_user_id=$this->user_id and pur.type=$type";
		$where_1=" sb.c_id={$_SESSION['uinfo']['c_id']} AND pur.last_buy_sale=sb.id AND pur.type=1";
		//收索条件
		if($status=sget('status','s','')){
			$where.=" and un.status={$status} ";
			$where_1.=" and un.status={$status} ";
		}
		$page=sget('page','i',1);
		$size=10;
		$list=M('product:salebuy')->getPurPage($where,$page,$size);
		$lists=M('product:salebuy')->get_purs($where_1,$page,$size);
		$list['count']=$list['count']+$lists['count'];
		$list['data']=array_merge($lists['data'],$list['data']);
		$list=array_unique($list);
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->assign('count',ceil($list['count']/$size));
		$this->display('supply_table');
	}

	/**
	 *ajax  异步取消报价管理
	 *
	 */
	public function ajax_submit(){
		$this->is_ajax=true;
		if(sget('id','i','')){
			$id=saddslashes(sget('id'));
			$this->db->model('sale_buy')->startTrans();
			if(!$this->db->model('sale_buy')->where('id='.$id)->update('status=9 and update_time='.CORE_TIME))
				$this->error('取消失败,001');
			if(!$this->db->model('union_order')->where('p_sale_id='.$id)->udate('order_status=3 and update_time='.CORE_TIME)) $this->error('取消失败,002');
			if($this->db->model('sale_buy')->commit()){
				$this->success('取消成功');
			}else{
				$this->rollback();
				$this->error('取消失败');
			}
		}
	}

	/**
	 * 根据牌号异步获取厂家名称
	 */
	public function getfaclist(){
		$this->is_ajax=true;
		$model=sget('model','s');
		$getfaclist=M('product:product')->getFacByModel($model);
		$this->json_output($getfaclist);
	}
	/**
	 * 根据牌号和厂家id获取唯一的商品信息
	 */
	public function getPinfo(){
		$this->is_ajax = true;
		$model = sget('model','s');
		$fid = sget('fid','i',0);
		$data=M('product:product')->getPinfo($model,$fid);
		$data['product_type_1']=L('product_type')[$data['product_type']];
		$data['process_type_1']=L('process_level')[$data['process_type']];
		$this->json_output($data);
	}

	/**
	 * 获取地区列表
	 * @$pid 省份 id
	 * @access public
	 * @return html
	 */
	public function getRegion($pid=0){
		//地区列表
		$regList = M('system:region')->get_allRegions();
		//print_r($regList);
		$list = array();
		$pid = sget('pid','i');
		foreach($regList as $k=>$v){
			if($v['pid']==$pid){
				$list[]=array('id'=> $v['id'],'name' => $v['name']);
			}
		}
		$this->json_output($list);
	}
}