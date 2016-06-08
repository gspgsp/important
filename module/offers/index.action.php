	<?php
class indexAction extends homeBaseAction{

	protected $db;
	protected $cartKey;
	public function __init(){
		E('Cart',APP_LIB.'extend');
		$this->cartKey="cartList";
		$this->db=M('public:common');
	}

	public function init()
	{
		//购物车
		// $this->cartList=isset($_SESSION[$this->cartKey]) ? $_SESSION[$this->cartKey] : array();
		$this->cartList=Cart::getGoods();
		// p($this->cartList);

		$cityWhere='pid=1';
		$factoryWhere=1;
		$where="pur.type=2 and pur.shelve_type=1 and pur.status in (2,3,4)";

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
		if($union=sget('union','i',0)){
			$this->assign('union',$union);
			if($union==1){
				$where.=" and pur.is_union=1";
			}else{
				$where.=" and pur.is_union=0";
			}
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

		$page=sget('page','i',1);
		$pageSize=10;

		$list=M('product:purchase')->getPurPage($where,$page,$pageSize);

		$this->pages = pages($list['count'], $p, $pageSize);
		$list=$list['data'];
		foreach ($list as $key => $value) {
			if($value['is_union']==0){
				$uids[]=$value['user_id'];
			}
		}
		$uids=array_unique($uids);
		//获取用户信息
		$contactList=M("user:customerContact")->getContactByuserid($uids);
		foreach ($contactList as $key => $value) {
			$customerTemp[$value['user_id']]=$value;
		}

		foreach ($list as $key => $value) {
			if($value['is_union']==0){
				$list[$key]['customer']=$customerTemp[$value['user_id']];
			}else{
				$list[$key]['customer']=$this->db->from('purchase pur')
					->join('admin ad','pur.customer_manager=ad.admin_id')
					->select('ad.name,ad.mobile')
					->getRow()+array('c_name'=>'商城自营');
			}
		}
		$this->assign('list',$list);
		$this->display('index');
	}

	//添加购物车
	public function addCart()
	{
		if($_POST)
		{
			$this->is_ajax=true;
			$id=sget('id','i',0);
			$goods=Cart::getGoods();
			if(count($goods)>=5) $this->error('最多只能选购5个商品');
			foreach ($goods as $key => $value) {
				if($value['id']==$id) $this->error('该商品已选购');
			}
			if(!$data=M('product:purchase')->getPurchaseInfo($id)) $this->error('信息错误');
			// 获取产品类型
			$data['product_type']=witchType($data['product_type']);
			//获取发货地区信息
			$data['city']=$this->db->model('lib_region')->where("id={$data['provinces']}")->select('name')->getOne();
			$sid=$this->addCol($data);
			$data['sid']=$sid;
			$this->success($data);
						
		}
	}

	//移除购物车
	public function delCart()
	{
		if($_POST)
		{
			$this->is_ajax=true;
			$sid=sget('id','s','');
			Cart::del($sid);
			$this->success('删除成功');
		}
	}

	protected function addCol($data){
		$arr=array(
			'id'=>$data['id'],
			'name'=>$data['model'],
			'num'=>1,
			'price'=>$data['unit_price'],
			'options'=>array(
				'p_id'=>$data['p_id'],
				'product_type'=>$data['product_type'],
				'model'=>$data['model'],
				'f_name'=>$data['f_name'],
				'unit_price'=>$data['unit_price'],
				'city'=>$data['city'],
			),
		);
		return Cart::add($arr);
	}

}