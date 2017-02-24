<?php 
/**
* 报表：
*/
class reportViewModel extends model
{
	
	function __construct()
	{
		parent::__construct(C('db_default'), 'order');
	}
	public function getDataByTime($order_type,$time_type,$start_time,$end_time,$team_or_person,$order_by_time_field,$team_id)
	{
		$where=" 1 ";
		switch ($time_type) {
			case 'this_week':
				$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))) );
				$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))) );
				break;
			case 'last_week':
				$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"))) );
				$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"))) );
				break;
			case 'this_month':
				$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))) );
				$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y"))) );
				break;
			case 'last_month':
				$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y"))) );
				$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m") ,0,date("Y"))) );
				break;
			case 'user_defined_time':
				$start= $start_time;
				$end  = $end_time;
				break;
			default:
				$start = strtotime("-1 month");
				$eend = strtotime("now");
				break;
		}

		if($team_or_person == 'team'){
			//2个sql 一个处理总吨数和总金额 一个处理总收款
			$where.=" and `ord`.".$order_by_time_field." >=".$start." and `ord`.".$order_by_time_field." <=".$end." and role.pid=22 and `ord`.order_type=".$order_type." and `ord`.order_status = 2 and `ord`.transport_status = 2 ";
			$num_price_data = $this->from('order as ord')
					->leftjoin('adm_role_user as adm','ord.customer_manager=adm.user_id')
					->leftjoin('adm_role as role','adm.role_id=role.id')
					->where($where)
					->select('sum(ord.total_num) as num,sum(ord.total_price) as price,ord.input_time,ord.customer_manager,adm.role_id,role.name,0 as collectiond')
					->order('ord.'.$order_by_time_field.' asc')
					->group('adm.role_id')
					->getAll();
					// return $this->getLastSql();

			$where2 = "`ord`.".$order_by_time_field." >=".$start." and `ord`.".$order_by_time_field." <=".$end." and  `ord`.order_type=".$order_type." and `ord`.order_status = 2 and `ord`.transport_status = 2 and `coll`.collection_status = 2 and `role`.pid = 22";
			$collectiond = $this->db->getAll('SELECT coll.customer_manager,SUM(coll.collected_price) AS collectiond ,adm.role_id,role.name,0 as num,0 as price
				FROM `p2p_order` `ord`
				LEFT JOIN `p2p_collection` `coll` ON ord.o_id=coll.o_id
				LEFT JOIN `p2p_adm_role_user` `adm` ON coll.customer_manager=adm.user_id
				LEFT JOIN `p2p_adm_role` `role` ON adm.role_id=role.id
				WHERE '.$where2.'
				GROUP BY `role`.`name`');
			// return $this->getLastSql();
			//提前将2个数组的结构保持一致  sql中 select 0 as collectiond  这样吨数和价格数组中就有collectiond 这个字段 
			//如果 role_id相同 则把利润数据赋值到 吨数和价格 数组中
			foreach ($num_price_data as $key => $value) {
				foreach ($collectiond as $k => $v) {
					if($value['role_id'] == $v['role_id']){
						$num_price_data[$key]['collectiond'] = $v['collectiond'];
					}
				}
			}
			//如果是采购订单直接返回没有算利润的数据，订单为采购时才算利润 
			if($order_type == 2){
				return $return = array('time'=>array($start,$end),'data'=>$num_price_data);
			}
			//利润算法
			//计算利润 分2种方式：1先销后采：（sale_log表销售的吨数*单价）-（purchase_log表采购的吨数*单价）-（p2p_out_log表中的ship)
			//2先采后销：sale_log中采购金额-销售金额 在连表out_log-ship
			//先销后采 sale_pur_data  
		$sale_pur_data = $this->db->getAll('SELECT pro.`o_id`,pro.`customer_manager`,SUM(pro.`profit`-IFNULL(ou.`ship`,0))AS profit FROM(
				SELECT sale.`o_id`,sale.`customer_manager`,SUM((sale.`s_price` - pur.`p_price`) )AS profit
				FROM (
				SELECT o.`o_id`, o.`join_id`, o.`customer_manager`,SUM(s.`number` * s.`unit_price`) AS s_price
				FROM p2p_order AS o
				JOIN p2p_sale_log AS s ON o.`o_id` = s.`o_id`
				WHERE  1 AND o.`sales_type` = 2 AND o.`order_type` = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2 AND o.`is_join_check` = 1 AND o.`collection_status` = 3 AND o.`input_time` > '.$start.' AND o.`input_time` < '.$end.'
				GROUP BY o.`o_id`
				) AS sale
				JOIN (
				SELECT o.`o_id`, o.`join_id`, o.`customer_manager`,SUM(p.`number` * p.`unit_price`) AS p_price
				FROM p2p_order AS o
				JOIN p2p_purchase_log AS p ON o.`join_id` = p.`o_id`
				WHERE  1 AND o.sales_type = 2 AND o.order_type = 1 AND o.order_status = 2 AND o.transport_status = 2 AND o.is_join_check = 1 AND o.collection_status = 3 AND o.input_time > '.$start.' AND o.input_time < '.$end.'
				GROUP BY o.o_id
				) AS pur
				ON (sale.`o_id` = pur.`o_id`)
				GROUP BY sale.`o_id`
				) AS pro
			LEFT JOIN (SELECT o_id,SUM(ship) AS ship FROM `p2p_out_log` GROUP BY o_id) AS ou ON ou.`o_id` = pro.`o_id`
			GROUP BY pro.`customer_manager`'
						);
				 		 // return $this->db->getLastSql();
		// p($sale_pur_data);
		// 先采后销 pur_sale_data
		$where = ' 1 ';
		$where .= 'and o.sales_type = 1 and o.order_type = 1 and o.order_status = 2 and o.transport_status = 2 and o.collection_status = 3 and o.input_time > '.$start.' and o.input_time < '.$end;
		$pur_sale_data = $this->from('order as o')
							 ->getAll('SELECT o.`o_id`,o.`customer_manager`, SUM((s.`unit_price` - s.`purchase_price`)*s.`number` - IFNULL(ou.`ship`,0)) AS profit
								FROM `p2p_order` AS `o`
								LEFT JOIN `p2p_sale_log` AS `s` ON o.`o_id`=s.`o_id`
								LEFT JOIN (SELECT o_id,SUM(ship) AS ship FROM `p2p_out_log` GROUP BY o_id) AS ou ON ou.`o_id` = o.`o_id`
								WHERE  1 AND o.`sales_type` = 1 AND o.`order_type` = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2 AND o.`collection_status` = 3 AND o.`input_time` > '.$start.' AND o.`input_time` < '.$end.'
								GROUP BY o.`customer_manager`');
				 		 // return $this->db->getLastSql();
		// p($pur_sale_data);die();
		//合并2种方式的利润
		$temp =array();
		foreach ($pur_sale_data as $key => $value) {
			foreach ($sale_pur_data as $k => $v) {
				if($value['customer_manager'] == $v['customer_manager']){
					$value['profit'] += $v['profit'];
					$temp[] = $value;
					unset($pur_sale_data[$key]);
					unset($sale_pur_data[$k]);
				}
			}
		}
		$new = array_merge($pur_sale_data,$sale_pur_data,$temp);
		// p($new);die;
		//得到role_id
		foreach ($new as $key => $value) {
			$role_id = $this->from('adm_role_user as adm')
					->select('adm.role_id')
					->where('adm.user_id='.$value['customer_manager'])
					->getOne();
			$new[$key]['role_id'] = $role_id;
		}
		// p($new);die;
		// p($num_price_data);die;
		//将roid_id 相同的数据 合并
		foreach ($num_price_data as $key => $value) {
			foreach ($new as $k => $v) {
				if($value['role_id'] == $v['role_id']){
					$num_price_data[$key]['profit'] += $v['profit'];
				}
			}
		}
			return $return = array('time'=>array($start,$end),'data'=>$num_price_data);
		}else{
			$team_user_arr = $this->from('adm_role_user as adm')
					->where('adm.role_id='.$team_id)
					->select('adm.user_id')
					->getAll();
			$str = ',';
			foreach ($team_user_arr as $key => $value) {
				$string.=$str.$value['user_id'];
			}
			$in_string = trim($string,',');
			//2个sql 一个处理总吨数和总金额 一个处理总收款
			$where.="and `ord`.order_type=".$order_type." and `ord`.".$order_by_time_field." >=".$start." and `ord`.".$order_by_time_field." <=".$end.' and `ord`.order_status = 2 and `ord`.transport_status = 2 and ord.customer_manager in (' .$in_string.' )';
			$num_price_data = $this->from('order as ord')
					->leftjoin('admin as a','ord.customer_manager=a.admin_id')
					->where($where)
					->select('sum(ord.total_num) as num,sum(ord.total_price) as price,ord.input_time,ord.customer_manager,a.name,0 as collectiond')
					->order('ord.'.$order_by_time_field.' asc')
					->group('ord.customer_manager')
					->getAll();
					// return $this->getLastSql();		

			$where2="`ord`.order_type=".$order_type." and `ord`.".$order_by_time_field." >=".$start." and `ord`.".$order_by_time_field." <=".$end.' and `ord`.order_status = 2 and `ord`.transport_status = 2 and `coll`.collection_status = 2 and ord.customer_manager in (' .$in_string.' )';
			$collectiond = $this->from('order as ord')
					->leftjoin('collection as coll','ord.o_id=coll.o_id')
					->where($where2)
					->select('sum(coll.collected_price) as collectiond,ord.customer_manager,0 as num,0 as price')
					->order('ord.'.$order_by_time_field.' asc')
					->group('ord.customer_manager')
					->getAll();
					// return $this->getLastSql(); 	
			
			foreach ($num_price_data as $key => $value) {
				foreach ($collectiond as $k => $v) {
					if($value['customer_manager'] == $v['customer_manager']){
						$num_price_data[$key]['collectiond'] = $v['collectiond'];
					}
				}
			}
			$return = array('time'=>array($start,$end),'data'=>$num_price_data);
			return $return;
		}
	}
}
?>