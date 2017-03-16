<?php 
/**
 * 实时成交数据
 */
class businessAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('sale_log');
		$this->doact = sget('do','s');
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$pid=sget('p_id','i');
		$this->assign('p_id',$pid);
		$oid=sget('o_id','i');
		$this->assign('o_id',$oid);
		$doact=sget('do','s');
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('doact',$doact);
		$this->assign('page_title','订单管理列表');
		$this->display('business.list.html');
	}
	
	/**
	 * Ajax获取列表内容
	 */
	public function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		$where .=' where 1 ';
		//筛选时间
		$sTime = sget("sTime",'s','log.`input_time`'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','name');
		$keyword=sget('keyword','s');
		$model=sget('model','s');
		$fname=sget('f_name','s');
		if(!empty($keyword) && $key_type=='name'  ){
			$where.=" and adm.`name` = '$keyword'";
		}elseif(!empty($keyword) && $key_type=='order_sn'){
			$where.=" and o.`order_sn` = '$keyword'";
		}
		if(!empty($model)){
			$where.=" and pro.`model` = '$model'";
		}
		if(!empty($fname)){
			$where.=" and fac.`f_name` = '$fname'";
		}
		$pid=sget('p_id','i');
		if($pid>0){
			$where.=" and log.`p_id` = '$pid' ";
			$page = 0;
			$size = 10;
			$limit = 10;
		}
		$oid=sget('o_id','i');
		if($oid > 0){
			$where.=" and o.`o_id` < '$oid' ";
		}
		$orderby = " order by $sortField $sortOrder";
		$where .=' and o.order_type = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2';
		//筛选过滤自己的订单信息
		// if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
		// 	if(!in_array($roleid, array('30','26','27'))){
		// 		$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
		// 		$where .= " and (`s_customer_manager` in ($sons) or `p_customer_manager` = {$_SESSION['adminid']})  ";
		// 	}
		// }
	$list = $this->db->getAll('SELECT o.`order_sn`,o.`transport_type`,o.`pickup_location`,o.`is_futures`,pro.`model`,fac.`f_name`,o.`o_id`,log.`p_id`,log.`number`,log.`unit_price`,log.`input_time`,log.`customer_manager`,adm.`name`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
			LEFT JOIN `p2p_admin` AS adm ON adm.`admin_id` = log.`customer_manager`
			'.$where.$orderby.' limit '.($page)*$size.','.$size);
	// showtrace();
	$list_count = $this->db->getAll('SELECT o.`order_sn`,o.`transport_type`,o.`pickup_location`,o.`is_futures`,pro.`model`,fac.`f_name`,o.`o_id`,log.`p_id`,log.`number`,log.`unit_price`,log.`input_time`,log.`customer_manager`,adm.`name`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
			LEFT JOIN `p2p_admin` AS adm ON adm.`admin_id` = log.`customer_manager`'.$where.$limit);
		foreach($list as &$value){
			$value['input_time']=$value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']) : '-';
			$value['transport_type'] = $value['transport_type']==1?'供方送到':'需方自提';
			$value['is_futures'] = $value['is_futures']==1?'否':'是';
		}
		$msg="";
		$result=array('total'=>count($list_count),'data'=>$list,'msg'=>$msg);
		$this->json_output($result);
	}
	/**
	 * 销售走势图
	 * @param $p_id
	 * @return json
	 * @Author: yumeilin
	 */
	public function graph(){
	    $p_id=sget('p_id','i');
	    $model=sget('model','s');
	    $cache= E('RedisCluster',APP_LIB.'class');
	    $graph_cache = $cache->get('GRAPH_B:'.$p_id);
	    if(!empty($graph_cache)){
	        $data=json_decode($graph_cache,true);
	        $c=$data['list'];
	        $a=$data['num'];
	        $b=$data['date'];
	        $cc=json_encode($c);
	        $aa =json_encode($a);
	        $bb=json_encode($b);
	        $this->assign('aa',$aa);
	        $this->assign('bb',$bb);
	        $this->assign('cc',$cc);
	        $this->assign('model',$model);
	        $this->display('business.graph.html');
	        die();
	    }
	    $list=M('public:common')->model('purchase_log')->where('p_id='.$p_id)->select('input_time,update_time,number,unit_price')->order('input_time')->getAll();
	    foreach($list as $k=>$v){
	        $list[$k]['time']=date("Y-m-d",$v['input_time']);
	    }
	    //获取销量数据
	    $price=array();
	    $time=array();
	    $time_u=array();
	    $le_l=count($list);
	    for($i=0;$i<$le_l;$i++){
	        $time[$i]=$list[$i]['time'];
	    }
	    $time_a=array_unique($time);
	    foreach($time_a as $k=>$v){
	        array_push($time_u,$time_a[$k]);
	    }
	    $le_t=count($time_u);
	    $num=0;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($list[$j]['time']==$time_u[$i]){
	                $num+=$list[$j]['number'];
	            }
	        }
	        $a[$i]=$num;
	        $num=0;
	    }
	    //获取价格数据
	    $highest=0;
	    $lowest=10000000;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($list[$j]['time']==$time_u[$i]){
	                array_push($price,$list[$j]['unit_price']);
	                if($list[$j]['unit_price']>=$highest){
	                    $highest=$list[$j]['unit_price'];
	                }
	                if($list[$j]['unit_price']<=$lowest){
	                    $lowest=$list[$j]['unit_price'];
	                }
	            }
	        }
	        $c[$i]['open']=(int)reset($price);
	        $c[$i]['close']=(int)end($price);
	        $c[$i]['lowest']=(int)$lowest;
	        $c[$i]['highest']=(int)$highest;
	        $price=array();
	        $highest=0;
	        $lowest=1000000000;
	    }
	    foreach($c as $k=>$v){
	        $c[$k]=array_values($v);
	    }
	    $cache_to=array(
	        'list'=>$c,
	        'num'=>$a,
	        'date'=>$time_u
	    );
	    $cache->set('GRAPH_B:'.$p_id,json_encode($cache_to),60*60);
	    $cc=json_encode($c);
	    $aa =json_encode($a);
	    $bb=json_encode($time_u);
	    $this->assign('aa',$aa);
	    $this->assign('bb',$bb);
	    $this->assign('cc',$cc);
	    $this->assign('model',$model);
	    $this->display('business.graph.html');
	}
}