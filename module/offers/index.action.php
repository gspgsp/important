<?php
class indexAction extends homeBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
	}

	public function init()
	{
		$cityWhere='pid=1';
		$factoryWhere=1;
		$where="type=1 and shelve_type=1 and pur.status in (2,3,4)";

		if($keywords=sget('keywords','s','')){
			$where.=" and (pro.model like '%{$keywords}%' or fa.f_name like '%{$keywords}%')";
		}

		if($type=sget('type','i',0)){
			$this->assign('type',$type);
			//地区筛选
			$area_ids=$this->db->from('purchase pur')
				->join('product pro','pur.p_id=pro.id')
				->select('pur.provinces')
				->group('pur.provinces')
				->where("pro.product_type=$type")
				->getCol();
			$area_ids=array_unique($area_ids);
			$cityWhere="id in (".implode(',',$area_ids).")";
			//厂家晒讯
			$factory_ids=$this->db->from('product')->select('f_id')->where("product_type=$type")->group('f_id')->getCol();
			$factoryWhere="fid in (".implode(',',$factory_ids).")";
			$where.=" and pro.product_type=$type";
			//应用筛选
			$process_ids=$this->db->from('product')->select('process_type')->where("product_type=$type")->group('process_type')->getCol();
		}
		//筛选条件
		if($process=sget('process','i',0)){
			$this->assign('process',$process);
			$where.=" and pro.process_type=$process";
		}

		if($ct=sget('ct','i',0)){
			$this->assign('ct',$ct);
			$where.=" and pur.provinces=$ct";
		}

		if($fa=sget('fa','i',0)){
			$this->assign('fa',$fa);
			$where.=" and pro.f_id=$fa";
		}

		if($key_model=sget('key_model','s','')){
			$this->assign('key_model',$key_model);
			$where.=" and pro.model like '%{$key_model}%'";
		}
		if($key_name=sget('key_name','s','')){
			$this->assign('key_name',$key_name);
			$cus_ids=$this->db->from('customer cus')
				->join('customer_contact con','cus.c_id=con.c_id')
				->where("cus.c_name like '%{$key_name}%'")
				->select('con.user_id')
				->getCol();
			$where.=" and pur.user_id in (".implode(',',$cus_ids).")";
		}
		if($cycle=sget('cycle','i',0)){
			$this->assign('cycle',$cycle);
			$where.=" and pur.period=$cycle";
		}
		if($cargo_type=sget('cargo_type','i',0)){
			$this->assign('cargotype',$cargo_type);
			$where.=" and pur.cargo_type=$cargo_type";
		}
		//筛选条件结束

		//报价周期
		$period=L('period');
		$this->assign('period',$period);
		//产品类型
		$product_type=L('product_type');
		$this->assign('product_type',$product_type);
		//产品应用
		$process_level=L('process_level');
		if($process_ids){
			foreach ($process_ids as $key => $value) {
				if(!$value) continue;
				$processList[$value]=$process_level[$value];
			}
		}else{
			$processList=$process_level;
		}
		$this->assign('processList',$processList);

		//地区
		$provinces=$this->db->model('lib_region')->where($cityWhere)->order('sort desc')->getAll();
		$this->assign('provinces',$provinces);
		$belong_area=L('belong_area');

		foreach ($provinces as $key => $value) {
			$area[$value['area']]['name']=$belong_area[$value['area']];
			$area[$value['area']]['arr'][]=$value;
		}
		ksort($area);
		$this->assign('area',$area);

		//厂家
		$factoryList=$this->db->model('factory')->where($factoryWhere)->limit(28)->getAll();
		$this->assign('factoryList',$factoryList);

		$p=sget('page','i',1);
		$pageSize=10;

		$list=$this->db->from('purchase pur')
			->join('product pro','pur.p_id=pro.id')
			->join('factory fa','pro.f_id=fa.fid')
			->join('lib_region reg','pur.provinces=reg.id')
			->where($where)
			->page($p,$pageSize)
			->select('pur.id,pur.unit_price,pur.c_id,pur.user_id,pur.number,pur.provinces,pur.cargo_type,pur.period,pur.input_time,pur.type,pro.model,pro.f_id,pro.product_type,pro.process_type,fa.f_name,reg.name as cityname')
			->getPage();

		$this->pages = pages($list['count'], $p, $pageSize);
		$list=$list['data'];
		foreach ($list as $key => $value) {
			$uids[]=$value['user_id'];
		}
		$uids=array_unique($uids);
		//获取用户信息
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
		// p($list);
		// showTrace();
		$this->assign('list',$list);
		$this->display('index');
	}
}