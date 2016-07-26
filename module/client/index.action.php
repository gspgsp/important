<?php

class indexAction extends homeBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
	}

	
	public function init()
	{	
		$where=1;
		$order="of.input_time desc";
		$cid=explode('_',sget('cid','s',''));
		//公司筛选
		if($cid[0]){
			$this->assign('ccid',implode('_',$cid));
			$where.=" and of.cid in (".implode(',',$cid).")";
		}
		// 品牌筛选
		if($br=sget('br','s','')){
			$this->assign('br',$br);
			$where.=" and of.factory=$br";
		}
		//类型筛选
		if($type=sget('type','s','')){
			$this->assign('type',$type);
			$where.=" and of.type='$type'";
		}
		//牌号筛选
		if($model=sget('model','s','')){
			$this->assign('model',$model);
			$where.=" and of.model like '%$model%'";
		}
		//价格范围筛选
		$from=sget('from','s','');
		$to=sget('to','s','');
		$this->assign('from',$from);
		$this->assign('to',$to);
		if($from&&!$to){
			$where.=" and of.price >= $from";
		}elseif(!$from&&$to){
			$where.=" and of.price <= $to";
		}elseif($from&&$to){
			$where.=" and of.price between $from and $to";
		}
		//排序
		if($sort=sget('sort','i',0)){
			$this->assign('sort',$sort);
			$order="of.price desc";
		}

		//大客户
		$client=$this->db->model('big_client')->getAll();
		foreach ($client as $key => $value) {
			if(in_array($value['id'], $cid)){
				$client[$key]['ss']=preg_match("/_".$value['id']."_/",implode('_',$cid))? preg_replace("/" . $value['id'] . "_/",'',implode('_',$cid)):preg_replace("/_?" . $value['id'] . "_?/", '', implode('_', $cid));
			}else{
				$client[$key]['ss']=empty($cid[0]) ? $value['id'] : implode('_', $cid).'_'.$value['id'];
			}
		}
		$this->assign('cid',$cid);
		$this->assign('client',$client);

		//品牌
		$this->brand=$this->db->model('big_offers')->group('factory')->select('factory')->getAll();
		//报价列表
		$page=sget('page','i',1);
		$pageSize=20;
		$list=M('product:bigOffers')->getOfferList($where,$order,$page,$pageSize);
//		//p($list['data']);
//		foreach($list['data'] as $v ){
//
////			$price=M('product:bigOffers')->getPrice($v);
////			P($price);
//			$price=$this->db->model('big_offers')
//				->where('type='.$v['type'] and 'model='.$v['model'] and 'factory='.$v['factory'] and 'address='.$v['address'])
//				->order('input_time desc')
//				->select('id,cid,price')
//				->limit('2')
//				->getAll();
//			p($price);
////			showTrace();
//		}

		$this->pages=pages($list['count'],$page,$pageSize);
		$this->assign('list',$list);
		$this->display('index');
	}
}