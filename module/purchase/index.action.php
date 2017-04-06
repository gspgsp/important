<?php

class indexAction extends homeBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
	}
	//采购单列表
	public function init()
	{
		$where="pur.type=1 and pur.shelve_type=1 and pur.status in (2,3,4)";

		//筛选条件
		if($keywords=sget('keywords','s','')){
			$where.=" and (pro.model like '%{$keywords}%' or fa.f_name like '%{$keywords}%')";
		}

		//品种11
		if($type=sget('type','i',0)){
			$this->assign('type',$type);
			$where.=" and pro.product_type=$type";
		}
//		//地区
//		if($ct=sget('ct','i',0)){
//			$this->assign('ct',$ct);
//			$where.=" and pur.provinces=$ct";
//		}
		//牌号
		if($key_model=sget('key_model','s','')){
			$this->assign('key_model',$key_model);
			$where.=" and pro.model like '%{$key_model}%'";
		}
		//厂家
		if($key_fa=sget('key_fa','s','')){
			$this->assign('key_fa',$key_fa);
			$where.=" and fa.f_name like '%{$key_fa}%'";
		}
		//类型  现/期货
//		if($cargo_type=sget('cargo_type','i',0)){
//			$this->assign('cargotype',$cargo_type);
//			$where.=" and pur.cargo_type=$cargo_type";
//		}
		//状态
//		if($status=sget('status','i',0)){
//			$this->assign('status',$status);
//			$where.=" and pur.status=$status";
//		}


		//产品类型
		$product_type=L('product_type');
		$this->assign('product_type',$product_type);
		//报价周期
		$this->period=L('period');
		//地区
//		$provinces=$this->db->model('lib_region')->where("pid=1")->order('sort desc')->getAll();
//		$this->assign('provinces',$provinces);
//		$belong_area=L('belong_area');
//
//		foreach ($provinces as $key => $value) {
//			$area[$value['area']]['name']=$belong_area[$value['area']];
//			$area[$value['area']]['arr'][]=$value;
//		}
//		ksort($area);
//		$this->assign('area',$area);

		$page=sget('page','i',1);
		$pageSize=10;//分页数
		$list=M('product:purchase')->getPurPage($where,$page,$pageSize);
		$this->pages = pages($list['count'], $page, $pageSize);

		foreach ($list['data'] as $key => $value) {
			$uids[]=$value['user_id'];
		}
		$list=$list['data'];
		$uids=array_unique($uids);
		$contactList=M("user:customerContact")->getContactByuserid($uids);
		foreach ($contactList as $key => $value) {
			$contactList[$key]['admobile'] = str_pad(substr($value['admobile'], 0, 7), strlen($value['admobile']), '*');
		}
		foreach ($contactList as $key => $value) {
			$customerTemp[$value['user_id']]=$value;
		}
		foreach ($list as $key => $value) {
			$list[$key]['customer']=$customerTemp[$value['user_id']];
			$list[$key]['product_type']=$product_type[$value['product_type']];
			$list[$key]['number']=floatval($value['number']);
			$list[$key]['unit_price']=floatval($value['unit_price']);
			$list[$key]['cityname']=(!empty($value['region_name']))?$value['region_name']:$value
				['store_house'];
		}
		//最近订单
//		$info=M('product:unionOrderDetail')->getInfo();
//		$grabList=array();
//			foreach ($info as $key => $value) {
//				$grabList[$key]['name'] = str_pad(substr($value['name'], 0, 1), strlen($value['name']), '*');
//			}
		//****************************************
//		$where='o.order_type=2 AND o.order_status=2 AND o.transport_status=2';
//		//筛选条件
//		if($keywords=sget('keywords','s','')){
//			$where.=" and (pro.model like '%{$keywords}%' or fac.f_name like '%{$keywords}%')";
//		}
//		//品种11
//		if($type=sget('type','i',0)){
//			$this->assign('type',$type);
//			$where.=" and pro.product_type=$type";
//		}
//		//牌号
//		if($key_model=sget('key_model','s','')){
//			$this->assign('key_model',$key_model);
//			$where.=" and pro.model like '%{$key_model}%'";
//		}
//		//厂家
//		if($key_fa=sget('key_fa','s','')){
//			$this->assign('key_fa',$key_fa);
//			$where.=" and fac.f_name like '%{$key_fa}%'";
//		}
//		$page=sget('page','i',1);
//		$pageSize=15;//分页数
//		$orders=M('product:order')->getPurs($where,$page,$pageSize);
//		$this->pages=pages($orders['count'],$page,$pageSize);
		//****************************************
		$this->seo = array(
			'title'=>'采购单',
			'keywords'=>'塑料采购，塑料贸易，塑料交易，塑料原料采购',
			'description'=>'我的塑料网采购单栏目为塑料工厂、塑料贸易商带价采购塑料原料提供真实有效的塑料采购询盘，资深塑料交易员全程为您服务',
			'status'=>3
			);
		$this->assign('list',$list);
		$this->display('index');
	}

	//采购单 右侧免费委托发布
	public function contentPurchase(){
		if($_POST){
			$this->is_ajax=true;
			if($this->user_id<=0) json_output(array('err'=>1,'msg'=>'请先登录'));
			$data=saddslashes($_POST);
			$contact_info = M('user:customerContact')->getListByUserid($this->user_id);
			if($data['tips']==1){
				$str=$data['content'];
			}else{
				$str="所需牌号：%s，交货地：%s，数量：%s，价格：%s。";
				$str=sprintf($str,$data['model'],$data['place'],$data['num'],$data['price']);
			}
			$_data=array(
				'user_id'=>$this->user_id,//用户id
				'content'=>$str,//临时信息字符串
				'c_id'=>$contact_info['c_id'],
				'input_time'=>CORE_TIME,
				'status'=>1,//待审核
				'type'=>1,//采购
			);
			$this->db->model('purchase')->add($_data);
			$this->success('成功发布');
		}
	}

	/**
	 * 根据报价id 发布求购
	 *
	 */
	public function wantBuy(){
		$purId=sget('purid','i');
		$data=M('product:purchase')->getPurchaseById($purId);
		if($_SESSION['uinfo']['type']==2 &&$data['type']==1 ) $this->error('买家不能进行此操作');
		if($_SESSION['uinfo']['type']==1 &&$data['type']==2 ) $this->error('卖家不能进行此操作');
		$var=$this->db->model('purchase as pur')
			->join('customer as cus','cus.c_id=pur.c_id')
			->join('customer_contact as con','cus.c_id=con.c_id')
			->leftjoin('product as pro','pur.p_id=pro.id')
			->leftjoin('factory as fac','fac.fid=pro.f_id')
			->leftjoin('lib_region as r','r.id=pur.store_house')
			->select('pur.id,pur.p_id,pro.model,pur.user_id,pur.c_id,pur.number,pur.cargo_type,pur.store_house,pro.product_type,pro.process_type,pur.unit_price,pur.type,fac.f_name,r.name,cus.c_name,con.name as con_name')->where('pur.id='.$purId)->getRow();
		$var['city_name']=(!empty($var['name']))?$var['name']:$var['store_house'];
		$this->area=M('system:region')->get_regions(1);//地区
		$this->transport_type=L('transport_type');
		$this->assign('info',$var);
		$this->display('wantBuy.html');
	}
}