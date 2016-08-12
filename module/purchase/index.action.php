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

		if($type=sget('type','i',0)){
			$this->assign('type',$type);
			$where.=" and pro.product_type=$type";
		}
		if($ct=sget('ct','i',0)){
			$this->assign('ct',$ct);
			$where.=" and pur.provinces=$ct";
		}
		if($key_model=sget('key_model','s','')){
			$this->assign('key_model',$key_model);
			$where.=" and pro.model like '%{$key_model}%'";
		}
		if($key_fa=sget('key_fa','s','')){
			$this->assign('key_fa',$key_fa);
			$where.=" and fa.f_name like '%{$key_fa}%'";
		}
		if($cargo_type=sget('cargo_type','i',0)){
			$this->assign('cargotype',$cargo_type);
			$where.=" and pur.cargo_type=$cargo_type";
		}
		if($status=sget('status','i',0)){
			$this->assign('status',$status);
			$where.=" and pur.status=$status";
		}


		//产品类型
		$product_type=L('product_type');
		$this->assign('product_type',$product_type);
		//报价周期
		$this->period=L('period');
		//地区
		$provinces=$this->db->model('lib_region')->where("pid=1")->order('sort desc')->getAll();
		$this->assign('provinces',$provinces);
		$belong_area=L('belong_area');

		foreach ($provinces as $key => $value) {
			$area[$value['area']]['name']=$belong_area[$value['area']];
			$area[$value['area']]['arr'][]=$value;
		}
		ksort($area);
		$this->assign('area',$area);

		$page=sget('page','i',1);
		$pageSize=20;//分页数
		$list=M('product:purchase')->getPurPage($where,$page,$pageSize);
		$this->pages = pages($list['count'], $p, $pageSize);
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
		}
		//最近订单
		$info=M('product:unionOrderDetail')->getInfo();
		$grabList=array();
			foreach ($info as $key => $value) {
				$grabList[$key]['name'] = str_pad(substr($value['name'], 0, 1), strlen($value['name']), '*');
			}
		$this->seo = array('title'=>'采购单',);
		$this->assign('info',$grabList);
		$this->assign('list',$list);
		$this->display('index');
	}

	//采购单 右侧免费委托发布
	public function contentPurchase(){
		if($_POST){
			$this->is_ajax=true;
			if($this->user_id<=0) $this->error('请先登录');
			$data=saddslashes($_POST);
			$str="所需牌号：%s，交货地：%s，数量：%s，价格：%s。";
			$str=sprintf($str,$data['model'],$data['place'],$data['num'],$data['price']);
			$_data=array(
				'user_id'=>$this->user_id,//用户id
				'content'=>$str,//临时信息字符串
				'input_time'=>CORE_TIME,
				'status'=>1,//待审核
				'type'=>1,//采购
			);
			$this->db->model('purchase')->add($_data);
			$this->success('提交成功');
		}
	}



}