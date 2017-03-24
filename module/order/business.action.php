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
	 //showtrace();
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
	public function graph_a(){
	    $p_id=sget('p_id','i');
	    $model=sget('model','s');
	    //$cache= E('RedisCluster',APP_LIB.'class');
	    //$graph_cache = $cache->get('GRAPH_B:'.$p_id);
	    if(!empty($graph_cache)&&!is_null($graph_cache)){
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
	    $where =" where 1 AND pro.model ='$model'AND o.order_type = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2";
	    $list = M('public:common')->model('sale_log')->order('input_time')->getAll('SELECT log.`number`,log.`unit_price`,log.`input_time`,log.`update_time`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
			'.$where." order by input_time");
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
	    $num=0;$y=0;$a_price=0;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($list[$j]['time']==$time_u[$i]){
	                $num+=$list[$j]['number'];
	                $a_price+=$list[$j]['unit_price'];
	                ++$y;
	            }
	        }
	        $d[$i]=(int)sprintf("%.0f",$a_price/$y);
	        $a[$i]=$num;
	        $num=0;
	        $a_price=0;
	        $y=0;	        
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
	    //$cache->set('GRAPH_B:'.$p_id,json_encode($cache_to),60*60);
	    $cc=json_encode($c);
	    $aa =json_encode($a);
	    $bb=json_encode($time_u);
	    $this->assign('aa',$aa);
	    $this->assign('bb',$bb);
	    $this->assign('cc',$cc);
	    $this->assign('p_id',$p_id);
	    $this->assign('model',$model);
	    if(sget('date_year','s')=='all'){
	        $this->json_output(array('tip'=>'每日平均价格','aa'=>$a,'bb'=>$time_u,'cc'=>$c,'dd'=>$d,'model'=>$model));
	        exit();
	    }
	    $this->display('business.graph.html');
	}
	/**
	 * 固定年每日销售走势图
	 * @param $p_id
	 * @return json
	 * @Author: yumeilin
	 */	
	public function graph_y(){
	    $date_year=sget('date_year','s');
	    //$date_year='2016';
	    $p_id=sget('p_id','i');
	    $model=sget('model','s');	    
	    //$cache= E('RedisCluster',APP_LIB.'class');
	    //$graph_cache = $cache->get('GRAPH_B:'.$p_id);
	    if(!empty($graph_cache)&&!is_null($graph_cache)){
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
	    $where =" where 1 AND pro.model ='$model'AND o.order_type = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2";
	    $list = M('public:common')->model('sale_log')->order('input_time')->getAll('SELECT log.`number`,log.`unit_price`,log.`input_time`,log.`update_time`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`		
			'.$where." order by input_time");
	    foreach($list as $k=>$v){
	        $list[$k]['time']=date("Y-m-d",$v['input_time']);
	    }
	   //p($list);
	    $year_list=array();
	    foreach($list as $k=>$v){
	        if(date("Y",$v['input_time'])==$date_year){
	           array_push($year_list,$list[$k]);
	        }	        
	    }
	    //p($year_list);
	    //获取销量数据
	    $price=array();
	    $time=array();
	    $time_u=array();
	    $le_l=count($year_list);
	    for($i=0;$i<$le_l;$i++){
	        $time[$i]=$year_list[$i]['time'];
	    }
	    $time_a=array_unique($time);
	    foreach($time_a as $k=>$v){
	        array_push($time_u,$time_a[$k]);
	    }
	    $le_t=count($time_u);
	    $num=0;$y=0;$a_price=0;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($year_list[$j]['time']==$time_u[$i]){
	                $num+=$year_list[$j]['number'];
	                $a_price+=$year_list[$j]['unit_price'];
	                ++$y;
	            }
	        }
	        //p($a_price);
	       // p($y);
	        $a[$i]=$num;
	        //$ad=number_format($a_price/$y,false);	   
	        $d[$i]=(int)sprintf("%.0f",$a_price/$y);
	        //p($ad);
	        //p($d[$i]);
	        $num=0;
	        $a_price=0;
	        $y=0;
	    }
	    //获取价格数据
	    $highest=0;
	    $lowest=10000000;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($year_list[$j]['time']==$time_u[$i]){
	                array_push($price,$year_list[$j]['unit_price']);
	                if($year_list[$j]['unit_price']>=$highest){
	                    $highest=$year_list[$j]['unit_price'];
	                }
	                if($year_list[$j]['unit_price']<=$lowest){
	                    $lowest=$year_list[$j]['unit_price'];
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
	    //$cache->set('GRAPH_B:'.$p_id,json_encode($cache_to),60*60);
	    //p($d);
	    $year_item=array();
	    for($i=1997;$i<=2037;$i++){
	        array_push($year_item,$i);
	    }
	    $this->assign('year_item',$year_item);
	    $dd=json_encode($d);	    
	    //p($dd);
	    $cc=json_encode($c);
	    $aa =json_encode($a);
	    //p($aa);
	    $bb=json_encode($time_u);
	    $this->assign('tip','每日平均价格');
	    $this->assign('dd',$dd);
	    $this->assign('aa',$aa);
	    $this->assign('bb',$bb);
	    $this->assign('cc',$cc);
	    $this->assign('model',$model);	    
	    $this->json_output(array('tip'=>'每日平均价格','aa'=>$a,'bb'=>$time_u,'cc'=>$c,'dd'=>$d,'model'=>$model));
 	}	
	/**
	 * 固定年每月销售走势图
	 * @param $p_id
	 * @return json
	 * @Author: yumeilin
	 */
	public function graph_m(){
	    $date_year=sget('date_year','s');
	    //$date_year='2016';
	    $p_id=sget('p_id','i');
	    $model=sget('model','s');	    
	    //$cache= E('RedisCluster',APP_LIB.'class');
	    //$graph_cache = $cache->get('GRAPH_B:'.$p_id);
	    if(!empty($graph_cache)&&!is_null($graph_cache)){
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
	    $where =" where 1 AND pro.model ='$model'AND o.order_type = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2";
	    $list = M('public:common')->model('sale_log')->order('input_time')->getAll('SELECT log.`number`,log.`unit_price`,log.`input_time`,log.`update_time`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`		
			'.$where." order by input_time");
	    foreach($list as $k=>$v){
	        $list[$k]['time']=date("Y-m",$v['input_time']);
	    }
	   //p($list);
	    $year_list=array();
	    foreach($list as $k=>$v){
	        if(date("Y",$v['input_time'])==$date_year){
	           array_push($year_list,$list[$k]);
	        }	        
	    }
	   // p($year_list);
	    //获取销量数据
	    $price=array();
	    $time=array();
	    $time_u=array();
	    $le_l=count($year_list);
	    for($i=0;$i<$le_l;$i++){
	        $time[$i]=$year_list[$i]['time'];
	    }
	    $time_a=array_unique($time);
	    foreach($time_a as $k=>$v){
	        array_push($time_u,$time_a[$k]);
	    }
	    $le_t=count($time_u);
	    $num=0;$y=0;$a_price=0;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($year_list[$j]['time']==$time_u[$i]){
	                $num+=$year_list[$j]['number'];
	                $a_price+=$year_list[$j]['unit_price'];
	                ++$y;
	            }
	        }
	        //p($a_price);
	       // p($y);
	        $a[$i]=$num;
	        //$ad=number_format($a_price/$y,false);	   
	        $d[$i]=(int)sprintf("%.0f",$a_price/$y);
	        //p($ad);
	        //p($d[$i]);
	        $num=0;
	        $a_price=0;
	        $y=0;
	    }
	    //获取价格数据
	    $highest=0;
	    $lowest=1000000000000000000;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($year_list[$j]['time']==$time_u[$i]){
	                array_push($price,$year_list[$j]['unit_price']);
	                if($year_list[$j]['unit_price']>=$highest){
	                    $highest=$year_list[$j]['unit_price'];
	                }
	                if($year_list[$j]['unit_price']<=$lowest){
	                    $lowest=$year_list[$j]['unit_price'];
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
	    //$cache->set('GRAPH_B:'.$p_id,json_encode($cache_to),60*60);
	    //p($d);
	    $dd=json_encode($d);	    
	    //p($dd);
	    $cc=json_encode($c);
	    $aa =json_encode($a);
	    //p($aa);
	    $bb=json_encode($time_u);
	    $this->assign('tip','每月平均价格');
	    $this->assign('dd',$dd);
	    $this->assign('aa',$aa);
	    $this->assign('bb',$bb);
	    $this->assign('cc',$cc);
	    $this->assign('model',$model);
	    $this->json_output(array('tip'=>'每月平均价格','aa'=>$a,'bb'=>$time_u,'cc'=>$c,'dd'=>$d,'model'=>$model));
	}
	/**
	 * 固定年每周销售走势图
	 * @param $p_id
	 * @return json
	 * @Author: yumeilin
	 */
	public function graph_w(){
	    $date_year=spost('date_year','s');
	    //$date_year="2016";
	    $p_id=spost('p_id','i');
	    $model=spost('model','s');

	    //$cache= E('RedisCluster',APP_LIB.'class');
	    //$graph_cache = $cache->get('GRAPH_B:'.$p_id);
	  /*   if(!empty($graph_cache)&&!is_null($graph_cache)){
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
	    } */
	    $where =" where 1 AND pro.model ='{$model}'AND o.order_type = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2";
	    $list = M('public:common')->model('sale_log')->order('input_time')->getAll('SELECT log.`number`,log.`unit_price`,log.`input_time`,log.`update_time`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
			'.$where." order by input_time");
	    foreach($list as $k=>$v){
	        $list[$k]['time']=date("W",$v['input_time']);
	    }

	    $year_list=array();
	    foreach($list as $k=>$v){
	        if(date("Y",$v['input_time'])==$date_year){
	            array_push($year_list,$list[$k]);
	        }
	    }
	    //p($year_list);
	    //获取销量数据
	    $price=array();
	    $time=array();
	    $time_u=array();
	    $le_l=count($year_list);
	    for($i=0;$i<$le_l;$i++){
	        $time[$i]=$year_list[$i]['time'];
	    }
	    $time_a=array_unique($time);
	    foreach($time_a as $k=>$v){
	        array_push($time_u,$time_a[$k]);
	    }
	    $le_t=count($time_u);
	    $num=0;$y=0;$a_price=0;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($year_list[$j]['time']==$time_u[$i]){
	                $num+=$year_list[$j]['number'];
	                $a_price+=$year_list[$j]['unit_price'];
	                ++$y;
	            }
	        }
	        //p($a_price);
	        // p($y);
	        $a[$i]=$num;
	        //$ad=number_format($a_price/$y,false);
	        $d[$i]=(int)sprintf("%.0f",$a_price/$y);
	        //p($ad);
	        //p($d[$i]);
	        $num=0;
	        $a_price=0;
	        $y=0;
	    }
	    //获取价格数据
	    $highest=0;
	    $lowest=1000000000000000000;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($year_list[$j]['time']==$time_u[$i]){
	                array_push($price,$year_list[$j]['unit_price']);
	                if($year_list[$j]['unit_price']>=$highest){
	                    $highest=$year_list[$j]['unit_price'];
	                }
	                if($year_list[$j]['unit_price']<=$lowest){
	                    $lowest=$year_list[$j]['unit_price'];
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
	    //$cache->set('GRAPH_B:'.$p_id,json_encode($cache_to),60*60);
	    //p($d);
	    $year_item=array();
	    for($i=1997;$i<=2037;$i++){
	        array_push($year_item,$i);
	    }
	    $this->assign('year_item',$year_item);
	    $dd=json_encode($d);
	    //p($dd);
	    $cc=json_encode($c);
	    $aa =json_encode($a);
	    //p($aa);
	    $bb=json_encode($time_u);
	    $this->assign('tip','每周平均价格');
	    $this->assign('dd',$dd);
	    $this->assign('aa',$aa);
	    $this->assign('bb',$bb);
	    $this->assign('cc',$cc);
	    $this->assign('model',$model);
	    $this->json_output(array('tip'=>'每周平均价格','aa'=>$a,'bb'=>$time_u,'cc'=>$c,'dd'=>$d,'model'=>$model));
	}
	/**
	 * 固定年每15天销售走势图
	 * @param $p_id
	 * @return json
	 * @Author: yumeilin
	 */
	public function graph_h(){
	    $date_year=sget('date_year','s');
	    //$date_year='2016';
	    $p_id=sget('p_id','i');
	    $model=sget('model','s');
	    //$cache= E('RedisCluster',APP_LIB.'class');
	    //$graph_cache = $cache->get('GRAPH_B:'.$p_id);
	    if(!empty($graph_cache)&&!is_null($graph_cache)){
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
	    $where =" where 1 AND pro.model ='$model'AND o.order_type = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2";
	    $list = M('public:common')->model('sale_log')->order('input_time')->getAll('SELECT log.`number`,log.`unit_price`,log.`input_time`,log.`update_time`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
			'.$where." order by input_time");
	    foreach($list as $k=>$v){
	        $list[$k]['time']=date("Y-m",$v['input_time']);
	    }
	    //p($list);
	    $year_list=array();
	    foreach($list as $k=>$v){
	        if(date("Y",$v['input_time'])==$date_year){
	            array_push($year_list,$list[$k]);
	        }
	    }
	    //p($year_list);
	    //获取销量数据
	    $price=array();
	    $time=array();
	    $time_u=array();
	    $le_l=count($year_list);
	    for($i=0;$i<$le_l;$i++){
	        $time[$i]=$year_list[$i]['time'];
	    }
	    $time_a=array_unique($time);
	    foreach($time_a as $k=>$v){
	        array_push($time_u,$time_a[$k]);
	    }
	    $le_t=count($time_u);
	    $num1=0;$num2=0;$y1=0;$y2=0;$a_price1=0;$a_price2=0;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($year_list[$j]['time']==$time_u[$i]){
	                if(date("d",$year_list[$j]['time'])<='15'){
	                    $num1+=$year_list[$j]['number'];
	                    $a_price1+=$year_list[$j]['unit_price'];
	                    ++$y1;
	                }
	                $num2+=$year_list[$j]['number'];
	                $a_price2+=$year_list[$j]['unit_price'];
	                ++$y2;
	            }
	        }
	        //p($a_price);
	        // p($y);
	        $a[2*$i]=$num1;
	        $a[2*$i+1]=$num2;
	        //$ad=number_format($a_price/$y,false);
	        $d[2*$i]=(int)sprintf("%.0f",$a_price1/$y1);
	        $d[2*$i+1]=(int)sprintf("%.0f",$a_price2/$y2);
	        //p($ad);
	        //p($d[$i]);
	        $num=0;
	        $a_price=0;
	        $y=0;
	    }
	    //获取价格数据
	    $highest=0;
	    $lowest=1000000000000000000;
	    for($i=0;$i<$le_t;$i++){
	        for($j=0;$j<$le_l;$j++){
	            if($year_list[$j]['time']==$time_u[$i]){
	                array_push($price,$year_list[$j]['unit_price']);
	                if($year_list[$j]['unit_price']>=$highest){
	                    $highest=$year_list[$j]['unit_price'];
	                }
	                if($year_list[$j]['unit_price']<=$lowest){
	                    $lowest=$year_list[$j]['unit_price'];
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
	    //$cache->set('GRAPH_B:'.$p_id,json_encode($cache_to),60*60);
	    //p($d);
	    $year_item=array();
	    for($i=1997;$i<=2037;$i++){
	        array_push($year_item,$i);
	    }
	    //p($year_item);
	    $this->assign('year_item',$year_item);
	    //默认当前年份
	    $this->assign('now_year',date('Y',time()));
	    //p(date("Y",time()));
	    //p($year_item);
	    $b=array();
	   for($i=0;$i<2*$le_t;$i++){
	       array_push($b,$i);
	   }
	   //p($b);
	    $dd=json_encode($d);
	    //p($dd);
	    $cc=json_encode($c);
	    $aa =json_encode($a);
	    //p($aa);
	    $bb=json_encode($b);
	    $this->assign('tip','每15天平均价格');
	    $this->assign('dd',$dd);
	    $this->assign('aa',$aa);
	    $this->assign('bb',$bb);
	    $this->assign('cc',$cc);
	    $this->assign('model',$model);
		$this->json_output(array('tip'=>'每15天平均价格','aa'=>$a,'bb'=>$b,'cc'=>$c,'dd'=>$d,'model'=>$model));
	}


	public function chart_year()
	{
		$model = sget('model','s');
		$cache= E('RedisCluster',APP_LIB.'class');
		//$cache_info = $cache->get('CHART_YEAR:'.$model);

		if(!empty($cache_info)&&!is_null($cache_info))
		{
			return $cache_info;
		}
		$res = $this->db->model('product')->where(" model = '{$model}'")->getAll();
		foreach($res as $val)
		{
			$product_ids[]=$val['id'];
		}
		unset($val);
		//$res = $this->db->model('sale_log')->where(' p_id in ('.join(',',$product_ids).')')->order('unit_price','desc')->getAll();

		$earlist = $this->db->model('sale_log')->where(' p_id in ('.join(',',$product_ids).')')->order('input_time')->getRow();
		$last = $this->db->model('sale_log')->where(' p_id in ('.join(',',$product_ids).')')->order('input_time desc')->getRow();

		$years = range(date("Y",$earlist['input_time']),date("Y",$last['input_time']));
		$arr = array(array('value'=>'all','key'=>'全部'));
		foreach($years as $year)
		{
			$arr[] = array('value'=>$year,'key'=>$year);
		}
		$cache->set('CHART_YEAR:'.$model,json_encode($arr),7*24*60*60);
		$this->json_output($arr);
	}
}