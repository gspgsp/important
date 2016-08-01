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
			$where.=" and `ord`.input_time >=".$start.' and `ord`.input_time <='.$end.' and role.pid=22 and order_type='.$order_type;
			$data = $this->from('order as ord')
					->leftjoin('adm_role_user as adm','ord.customer_manager=adm.user_id')
					->leftjoin('adm_role as role','adm.role_id=role.id')
					->where($where)
					->select('sum(ord.total_num) as num,sum(ord.total_price) as price,ord.input_time,ord.customer_manager,adm.role_id,role.name')
					->order('ord.'.$order_by_time_field.' asc')
					->group('adm.role_id')
					->getAll();
					// return $this->getLastSql();
			$return = array('time'=>array($start,$end),'data'=>$data);
			return $return;
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
			$where.="and order_type=".$order_type." and `ord`.input_time >=".$start.' and `ord`.input_time <='.$end.' and ord.customer_manager in (' .$in_string.' )';
			$data = $this->from('order as ord')
					->leftjoin('admin as a','ord.customer_manager=a.admin_id')
					->where($where)
					->select('sum(ord.total_num) as num,sum(ord.total_price) as price,ord.input_time,ord.customer_manager,a.name')
					->order('ord.'.$order_by_time_field.' asc')
					->group('ord.customer_manager')
					->getAll();
					// return $this->getLastSql();	
			$return = array('time'=>array($start,$end),'data'=>$data);
			return $return;
		}
	}
}
?>