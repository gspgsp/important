<?php
/**
 * 业绩实时报表
 */
class realAchieveAction extends adminBaseAction {
	protected $db;
	public function __init(){
		$this->assign('depart',C('depart'));//所属部门
		$this->assign('team',L('team')); //战队名称
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->display('realAchieve.list.html');
	}
	public function download(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','average'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$orderby = " order by $sortField $sortOrder";
		//筛选显示类别
		$where=" 1 ";
		//自定义时间搜索
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$input_time= getTimeFilter($sTime); //时间筛选
		if(empty($input_time)){
			$input_time = ' and 1';
		}
		//战队筛选
		$team_id=sget('team_id','i');
		if($team_id)  $where.=" and part.id = '$team_id' ";
		//关键词
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			$ids = M('rbac:adm')->getIdByName("$keyword");
			$str=implode(',',$ids);
			$where.=" and `customer_manager` in ($str)";
		}
		$list=$this->db->getAll('SELECT SUM(aa.sale_num) as sale_num,SUM(aa.pur_num) as pur_num,(SUM(aa.sale_num) + SUM(aa.pur_num))/2 AS average,customer_manager,role.`role_id` FROM(	
					SELECT order_type,SUM(total_num) AS sale_num,0 AS pur_num,customer_manager,input_time
					FROM p2p_order
					WHERE order_status = 2 AND transport_status = 2 AND order_type = 1 '.$input_time.'
					GROUP BY `customer_manager`
					UNION 
					SELECT order_type,0 AS sale_num,SUM(total_num) AS pur_num,customer_manager,input_time 
					FROM p2p_order
					WHERE order_status = 2 AND transport_status = 2 AND order_type = 2 '.$input_time.'
					GROUP BY  `customer_manager`
				) AS aa
				LEFT JOIN p2p_adm_role_user AS role ON aa.customer_manager = role.user_id
				LEFT JOIN p2p_adm_role AS part ON part.id = role.role_id
				where part.pid = 22 and '.$where.'
				GROUP BY customer_manager'
				.$orderby);
		foreach($list as $k=>&$v){
				$v['name'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
				$depart =  M('rbac:adm')->getPartByID($v['customer_manager']);
				$v['depart_name']=$depart['name'];
				$all[] = $v['average'];
		}
		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
			$str .= '<tr><td>业务员</td><td>战队</td><td>销售吨数</td><td>采购吨数</td><td>个人平均</td>
					</tr>';
			foreach($list as $k=>&$v){
				$str .= "<tr><td>".$v['name']."</td><td>".$v['depart_name']."</td>
						<td style='vnd.ms-excel.numberformat:@'>".$v['sale_num']."</td>
						<td style='vnd.ms-excel.numberformat:@'>".$v['pur_num']."</td>
						<td style='vnd.ms-excel.numberformat:@'>".$v['average']."</td>
				</tr>";
			}
			$str.="<tr><td></td><td></td><td></td><td></td><td align='left' color='red'>".array_sum($all)."</td></tr>";
		$str .= '</table>';
		$filename = 'person_data.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;
		exit;
	}
	/**
	 *
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','average'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$orderby = " order by $sortField $sortOrder";
		//筛选显示类别
		$where=" 1 ";
		//自定义时间搜索
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$input_time= getTimeFilter($sTime); //时间筛选
		if(empty($input_time)){
			$input_time = ' and 1';
		}
		//战队筛选
		$team_id=sget('team_id','i');
		if($team_id)  $where.=" and part.id = '$team_id' ";
		//关键词
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			$ids = M('rbac:adm')->getIdByName("$keyword");
			$str=implode(',',$ids);
			$where.=" and `customer_manager` in ($str)";
		}
		$list=$this->db->getAll('SELECT SUM(aa.sale_num) as sale_num,SUM(aa.pur_num) as pur_num,(SUM(aa.sale_num) + SUM(aa.pur_num))/2 AS average,customer_manager,role.`role_id` FROM(	
					SELECT order_type,SUM(total_num) AS sale_num,0 AS pur_num,customer_manager,input_time
					FROM p2p_order
					WHERE order_status = 2 AND transport_status = 2 AND order_type = 1 '.$input_time.'
					GROUP BY `customer_manager`
					UNION 
					SELECT order_type,0 AS sale_num,SUM(total_num) AS pur_num,customer_manager,input_time 
					FROM p2p_order
					WHERE order_status = 2 AND transport_status = 2 AND order_type = 2 '.$input_time.'
					GROUP BY  `customer_manager`
				) AS aa
				LEFT JOIN p2p_adm_role_user AS role ON aa.customer_manager = role.user_id
				LEFT JOIN p2p_adm_role AS part ON part.id = role.role_id
				where part.pid = 22 and '.$where.'
				GROUP BY customer_manager'
				.$orderby.' limit '.($page)*$size.','.$size);
					// showtrace();
					// p($list);die;
		foreach($list as $k=>&$v){
				$v['name'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
				$depart =  M('rbac:adm')->getPartByID($v['customer_manager']);
				$v['depart_name']=$depart['name'];
		}
		$list_count=$this->db->getCol('SELECT (SUM(aa.sale_num) + SUM(aa.pur_num))/2 AS average FROM(	
					SELECT order_type,SUM(total_num) AS sale_num,0 AS pur_num,customer_manager,input_time
					FROM p2p_order
					WHERE order_status = 2 AND transport_status = 2 AND order_type = 1 '.$input_time.'
					GROUP BY `order_type`, `customer_manager`
					UNION 
					SELECT order_type,0 AS sale_num,SUM(total_num) AS pur_num,customer_manager,input_time 
					FROM p2p_order
					WHERE order_status = 2 AND transport_status = 2 AND order_type = 2 '.$input_time.'
					GROUP BY  `customer_manager`
				) AS aa
				LEFT JOIN p2p_adm_role_user AS role ON aa.customer_manager = role.user_id
				LEFT JOIN p2p_adm_role AS part ON part.id = role.role_id
				where part.pid = 22 and '.$where.' GROUP BY customer_manager');
		
		$msg="";
		if(count($list_count)>0){
			$msg="[筛选结果] 个人平均吨数:【".array_sum($list_count)."】";
		}
		$result=array('total'=>count($list_count),'data'=>$list,'msg'=>$msg);
		$this->json_output($result);
	}
}