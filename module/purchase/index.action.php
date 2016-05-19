<?php

class indexAction extends homeBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
	}
	public function init()
	{
		$where="type=1 and pur.status in (2,3,4)";

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

		$p=sget('page','i',1);
		$pageSize=10;
		$list=$this->db->from('purchase pur')
			->join('product pro','pur.p_id=pro.id')
			->join('factory fa','pro.f_id=fa.fid')
			->join('lib_region reg','pur.provinces=reg.id')
			->where($where)
			->order('input_time desc')
			->page($p,$pageSize)
			->select('pur.id,pur.user_id,pur.unit_price,pur.c_id,pur.number,pur.provinces,pur.status,pur.cargo_type,pur.period,pur.input_time,pur.type,pro.model,pro.f_id,pro.product_type,pro.process_type,fa.f_name,reg.name as cityname')
			->getPage();

		$this->pages = pages($list['count'], $p, $pageSize);
		foreach ($list['data'] as $key => $value) {
			$uids[]=$value['user_id'];
		}
		$list=$list['data'];
		$uids=array_unique($uids);
		$contactList=M("user:customerContact")->getContactByuserid($uids);
		foreach ($contactList as $key => $value) {
			$customerTemp[$value['user_id']]=$value;
		}
		foreach ($list as $key => $value) {
			$list[$key]['customer']=$customerTemp[$value['user_id']];
			$list[$key]['product_type']=$product_type[$value['product_type']];
			$list[$key]['number']=floatval($value['number']);
			$list[$key]['unit_price']=floatval($value['unit_price']);
		}

		$this->assign('list',$list);
		$this->display('index');
	}

}