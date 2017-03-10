<?php
class indexAction extends homeBaseAction{
	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}
	public function init(){
		//如果用户是从移动端，则转到移动注册页面
		if(M('public:common')->is_mobile_request()){
			$this->forward('http://m.myplas.com');
		}
		//轮播图
		$this->banners=array_reverse(M('system:block')->getBlock(1,10));
		//产品应用
		$this->process_level=L('process_level');
		//产品分类
		$this->assign('product_type',array_slice(L('product_type'),0,6));
		//省份地区
		$this->area=M('system:region')->getProvinceCache();
		//牌号新采购
//		$this->newPur=M('product:purchase')->getPurPage("type=1 and shelve_type=1 and pur.status in (2,3,4)",1,5)['data'];
		$newPurs=M('product:order')->getPurPage("o.order_type=2 AND o.order_status=2 AND o.transport_status=2");
		foreach($newPurs as $key=>$value){
			$newPurs[$key]['delivery_location']=str_pad(mb_substr($value['delivery_location'],0,2,'utf-8'),4,'...');
			$newPurs[$key]['pickup_location']=str_pad(mb_substr($value['pickup_location'],0,2,'utf-8'),4,'...');
		}
		$this->assign('newPurs',$newPurs);
		//供求信息
		$this->purBuy=M('product:purchase')->getPurPage("pur.shelve_type=1 and pur.status in (2,3,4) and pur.sync in(1,2,7)")['data'];

   //实时成交订单和最新订单
   $this->deals=M('product:order')->getTrad();

		//即时抢货
   $grabList=$this->db->model('resourcelib')->select('content,user_qq,user_nick,qq_image,input_time,realname')->where('type=0')        ->limit(3)->order("input_time desc")->getAll();
        if($this->user_id<=0){
        foreach ($grabList as $key => $value) {
            $grabList[$key]['user_qqs'] = str_pad(substr($value['user_qq'], 0, 4), strlen($value['user_qq']), '*');
        }
    }
		$this->assign('grabList',$grabList);
		
   $readjust=M('operator:dynamic')->getList();
    //调价动态(行情中心)
   $readjust=$this->setReadjust($readjust);

		$this->assign('readjust', $readjust);
		
		//行情信息(原油指数右边曲线)
		$this->quotation = M('operator:market')->get_quotation_index();
		//原油指数
		$this->oil1=M('operator:oilPrice')->get_index('0');   //WIT

		$this->oil2=M('operator:oilPrice')->get_index('1');   //布油


		//2F 大客户报价
		$this->bigClient=$this->db->model('big_client')->limit(12)->getAll();
		$this->bigOffers=M('product:bigOffers')->getOfferList("1","of.input_time desc",1,5)['data'];

		//3F中晨物流 12小时缓存 S 方法
		$this->shipList = M('operator:ship_price')->get_index_ship(3);

		//新闻资讯
		$this->articleList = M('news:news')->getHomeNews();
		// $this->seo = array('title'=>'首页',);
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


	/**
	 * 注册协议
	 * @Author: yuanjiaye
	 */
	public function agreement(){

		$this->display('agreement.html');
	}
}


