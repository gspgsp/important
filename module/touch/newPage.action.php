<?php
/**
*微信-新界面
*/
class newPageAction extends homeBaseAction
{
    public function __init() {
        $this->debug = false;
        $this->db=M('public:common');
    }
	public function init(){
		$this->is_ajax = true;
        // if($this->user_id<=0) $this->error('账户错误');
        // $this->assign('service',$service);
		$this->display('newpage');
	}
	//买/卖 获取供求的筛选条件
    public function getSupplyCondition(){
        $this->is_ajax = true;
        $ptype = sget('ptype','i',0);//类型下标1
        if(!$typeData=M('myapp:mainPage')->getSupplyConditionData($ptype)) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
        $this->json_output(array('err'=>0,'typeData'=>$typeData));
    }
    //我要买/我要卖
	public function getSupplyCondData(){
        $this->is_ajax = true;
        //筛选条件
        $model = sget('model','s');//牌号 
        $f_name = sget('f_name','s');//厂家名称
        $provinces = sget('provinces','s');//地区
        $product_type = sget('product_type','i');//类型1/2/3/4/5/6/7/8/9
        $cargo_type = sget('cargo_type','i');//货物属性1现货 2期货
        //普通条件
        $type = sget('type','i',0);//1求(采)购 2报价(供应)
        $otype = sget('otype','i',3);//1价格升2价格降3默认(时间)
        $page = sget('page','i',1);
        $size = sget('size','i',10);
        $ttype = sget('ttype','i',0);//来源于touch端,只显示当天的数据
        $data = M('myapp:mainPage')->getSupplyCondDatas($model,$f_name,$product_type,$provinces,$cargo_type,$type,$otype,$page,$size,$ttype);
        if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关的数据'));
        foreach ($data['data'] as &$value) {
        	$value['input_time'] = date("H:i",strtotime($value['input_time']));
        }
        $this->_checkLastPage($data['count'],$size,$page);
        $this->json_output(array('err'=>0,'data'=>$data['data']));
    }
    //获取详情页和mobi/mainPage/getCheckDelegate相同
	//今日指数
	public function todayIndex(){
		$this->is_ajax = true;
		$market = M('touch:market')->get_quotation_index();
		$oilPrice = M('touch:oilprice')->get_oil_price();
        $sys = M('system:setting')->getSetting();
        // 网站服务人数
        $service = explode('|', $sys['service']);
		if(empty($market) || empty($oilPrice)) $this->json_output(array('err'=>2,'msg'=>'没有相关数据!'));
		$this->json_output(array('err'=>0,'today'=>date('Y-m-d',time()),'market'=>$market,'oilPrice'=>$oilPrice,'service'=>$service));
	}
	//实时成交价格
	public function getRealPrice(){
		$this->is_ajax = true;
		$today = strtotime(date('Y-m-d',time()));
		$page = sget('page','i',1);
        $size = sget('size','i',10);
        $data = M('touch:realPrice')->getRealPrice($page,$size,$today);
        if(empty($data['data']) && $page==1) $this->json_output(array('err'=>2,'msg'=>'没有相关数据!'));
        $this->_checkLastPage($data['count'],$size,$page);
        $this->json_output(array('err'=>0,'data'=>$data['data']));
	}
    //资源发布
    public function release(){
        $this->is_ajax=true;
        $content = trim(sget('content', 's', ''));
        $_data=array(
                'content'=>$content,
                'input_time'=>CORE_TIME,
                );
        if($this->db->model('weixin_release')->add($_data)) $this->json_output(array('err'=>0,'msg'=>'发布成功'));
        $this->json_output(array('err'=>2,'msg'=>'发布失败'));
    }
	//判断是否到最后一页
    private function _checkLastPage($count,$size,$page){
        if($count>0){
            if($count%$size==0 && ceil($count/$size)<$page){
                $this->json_output(array('err'=>3,'msg'=>'没有更多数据'));
            }elseif ($count%$size!=0 && ceil($count/$size)<$page) {
                $this->json_output(array('err'=>3,'msg'=>'没有更多数据'));
            }
        }
    }
    //时间戳转换为秒-分钟-小时-天
    private function  _changeTime($time) {
        $int = time() - $time;
        $str = '';
        if ($int <= 2){
            $str = sprintf('刚刚', $int);
        }elseif ($int < 60){
            $str = sprintf('%d秒前', $int);
        }elseif ($int < 3600){
            $str = sprintf('%d分钟前', floor($int/60));
        }elseif ($int < 86400){
            $str = sprintf('%d小时前', floor($int/3600));
        }elseif ($int < 2592000){
            $str = sprintf('%d天前', floor($int/86400));
        }else{
            $str = date('Y-m-d H:i:s', $time);
        }
        return $str;
    }
}