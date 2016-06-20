<?php
class indexAction extends homeBaseAction{
	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}
	public function init()
	{

		
		//轮播图
		$this->banners=M('system:block')->getBlock(1,10);

		//产品应用
		$this->process_level=L('process_level');
		//产品分类
		$this->product_type=L('product_type');
		//省份地区
		$this->area=M('system:region')->getProvinceCache();
		//牌号新采购
		$this->newPur=M('product:purchase')->getPurPage("type=1 and shelve_type=1 and pur.status in (2,3,4)",1,5)['data'];

		//供求信息
		$this->purBuy=M('product:purchase')->getPurPage("pur.shelve_type=1 and pur.status in (2,3,4)")['data'];
		//即时抢货
		$grabList=$this->db->model('resourcelib')->select('content,user_qq,user_nick,qq_image,input_time,realname')->limit(3)->order("input_time desc")->getAll();
		if($this->user_id<=0){
			foreach ($grabList as $key => $value) {
				$grabList[$key]['user_qq'] = str_pad(substr($value['user_qq'], 0, 4), strlen($value['user_qq']), '*');
			}
		}
		$this->assign('grabList',$grabList);
		$arr=array(
			array(
				'name'=>'埃克森 LLDPE 102W',
				'price'=>'111',
				'type'=>2,
				'num'=>'50',
				'time'=>time()

			),
			array(
				'name'=>'埃克森 LLDPE 102W',
				'price'=>'222',
				'type'=>2,
				'num'=>'50',
				'time'=>time()

			),
			array(
				'name'=>'埃克森 LLDPE 102W',
				'price'=>'333',
				'type'=>1,
				'num'=>'50',
				'time'=>time()

			),
			array(
				'name'=>'埃克森 LLDPE 102W',
				'price'=>'333',
				'type'=>1,
				'num'=>'50',
				'time'=>time()

			),
			array(
				'name'=>'埃克森 LLDPE 102W',
				'price'=>'333',
				'type'=>1,
				'num'=>'50',
				'time'=>time()

			),
			array(
				'name'=>'埃克森 LLDPE 102W',
				'price'=>'333',
				'type'=>1,
				'num'=>'50',
				'time'=>time()
			),
			
		);
		//调价动态
		$cache=cache:: startCacheFile();
		$cache->set('readjust',$arr);
		$readjust=$cache->get('readjust');
		$readjust=$this->setReadjust($readjust);
		$this->assign('readjust', $readjust);
		
		//行情信息
		$this->quotation = M('info:market')->get_quotation_index();
		//原油指数
		$this->oil1=M('info:oilPrice')->get_index('0');
		$this->oil2=M('info:oilPrice')->get_index('1');

		//2F 大客户报价
		$this->bigClient=$this->db->model('big_client')->limit(12)->getAll();
		$this->bigOffers=M('product:bigOffers')->getOfferList("1","of.input_time desc",1,5)['data'];

		//3F中晨物流 12小时缓存 S 方法
		$this->shipList = M('operator:ship_price')->get_index_ship(3);

		//新闻资讯
		$this->articleList = M('system:info')->get_index_article();
		$this->display('index.html');
	}


	protected function setReadjust($readjust){
		foreach ($readjust as $key => $value) {
			if($value['type'] == 1){
				$readjust[$key]['rate'] = number_format($value['num']/($value['price'] + $value['num'])*100,2);
			}elseif($value['type'] == 2){
				$readjust[$key]['rate'] = number_format($value['num']/($value['price'] - $value['num'])*100,2);
			}else{
				$readjust[$key]['rate'] = 0;
			}
			list($readjust[$key]['cj'],$readjust[$key]['pz'],$readjust[$key]['ph']) = explode(' ', $value['name']);
		}
		return $readjust;
	}
}


