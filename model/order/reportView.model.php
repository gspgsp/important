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
					->select('sum(ord.total_num) as num,sum(ord.total_price) as price,ord.input_time,ord.customer_manager,adm.role_id,role.name,0 as profit')
					->order('ord.'.$order_by_time_field.' asc')
					->group('adm.role_id')
					->getAll();
					// return $this->getLastSql();

			$where2 = "`ord`.".$order_by_time_field." >=".$start." and `ord`.".$order_by_time_field." <=".$end." and  `ord`.order_type=".$order_type." and `ord`.order_status = 2 and `ord`.transport_status = 2 and `coll`.collection_status = 2 and `role`.pid = 22";
			// $profit = $this->from('order as ord')
			// 			   ->select('sum(coll.collected_price) as price ,adm.role_id,role.name')
			// 			   ->leftjoin('collection as coll','ord.o_id=coll.o_id')
			// 			   ->leftjoin('adm_role_user as adm','coll.customer_manager=adm.user_id')
			// 			   ->leftjoin('adm_role as role','adm.role_id=role.id')
			// 	 		   ->where($where2)
			// 	 		   ->group('coll.customer_manager')
			// 	 		   ->getAll();
			$profit = $this->db->getAll('SELECT coll.customer_manager,SUM(coll.collected_price) AS profit ,adm.role_id,role.name,0 as num,0 as price
				FROM `p2p_order` `ord`
				LEFT JOIN `p2p_collection` `coll` ON ord.o_id=coll.o_id
				LEFT JOIN `p2p_adm_role_user` `adm` ON coll.customer_manager=adm.user_id
				LEFT JOIN `p2p_adm_role` `role` ON adm.role_id=role.id
				WHERE '.$where2.'
				GROUP BY `role`.`name`');
			// return $this->getLastSql();
			//提前将2个数组的结构保持一致  sql中 select 0 as profit  这样吨数和价格数组中就有profit 这个字段 
			//如果 role_id相同 则把利润数据赋值到 吨数和价格 数组中
			foreach ($num_price_data as $key => $value) {
				foreach ($profit as $k => $v) {
					if($value['role_id'] == $v['role_id']){
						$num_price_data[$key]['profit'] = $v['profit'];
					}else{
						array_push($temp, $v);
					}
				}
			}
			foreach ($temp as $key => $value) {
				array_push($num_price_data, $value);
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
					->select('sum(ord.total_num) as num,sum(ord.total_price) as price,ord.input_time,ord.customer_manager,a.name,0 as profit')
					->order('ord.'.$order_by_time_field.' asc')
					->group('ord.customer_manager')
					->getAll();
					// return $this->getLastSql();		

			$where2="`ord`.order_type=".$order_type." and `ord`.".$order_by_time_field." >=".$start." and `ord`.".$order_by_time_field." <=".$end.' and `ord`.order_status = 2 and `ord`.transport_status = 2 and `coll`.collection_status = 2 and ord.customer_manager in (' .$in_string.' )';
			$profit = $this->from('order as ord')
					->leftjoin('collection as coll','ord.o_id=coll.o_id')
					->where($where2)
					->select('sum(coll.collected_price) as profit,ord.customer_manager,0 as num,0 as price')
					->order('ord.'.$order_by_time_field.' asc')
					->group('ord.customer_manager')
					->getAll();
					// return $this->getLastSql(); 	
			
			foreach ($num_price_data as $key => $value) {
				foreach ($profit as $k => $v) {
					if($value['customer_manager'] == $v['customer_manager']){
						$num_price_data[$key]['profit'] = $v['profit'];
					}else{
						array_push($temp, $value);
					}
				}
			}
			foreach ($temp as $key => $value) {
				array_push($num_price_data, $value);
			}
			$return = array('time'=>array($start,$end),'data'=>$num_price_data);
			return $return;
		}
	}
}
?>