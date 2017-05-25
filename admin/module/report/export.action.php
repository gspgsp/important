<?php
/** 
 * 导出
 */
class exportAction extends adminBaseAction {
	public function __init(){
		set_time_limit(0);
		$this->debug = false;
	}

	/**
	 * 导出数据
	 * @access public 
	 * @return html
	 */
	public function init(){
		$this->display();
	}

	/**
	 * 体验金报表
	 * @access public 
	 * @return html
	 */
	public function expDown(){
		$where=" 1 ";
		$sTime = sget("sTime",'s','last_login'); //搜索时间类型
		$where.=getTimeFilter('reg_time'); //时间筛选
		
		$db=$this->db;
		$users=$db->getAll("select u.mobile,manager_id,reg_time,first_invest,u.user_id,a.id as lid from ss_manager_user u left join ss_invest_exp_apply a on a.user_id=u.user_id where $where");
		
		$str='<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
		$str.='<tr align="center"><td>手机号</td><td>客服ID</td><td>投资时间</td><td>注册时间</td><td>是否领取</td></tr>';
		
		foreach($users as $v){
			$str .= "<tr><td style='vnd.ms-excel.numberformat:@'>".$v['mobile']."</td>
					<td>".$v['manager_id']."</td><td>".($v['first_invest']>0 ? date("Y-m-d",$v['first_invest']) : '')."</td><td>".date("Y-m-d",$v['reg_time'])."</td><td>".($v['lid']>0 ? '是' : '否')."</td></tr>";
		}
		$str .= '</table>';
		
		$filename = 'expReport.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;exit;
	}
	
	/**
	 * 用户投资报表
	 * @access public 
	 * @return html
	 */
	public function investDown(){
		$where=" 1 ";
		$sTime = sget("sTime",'s','last_login'); //搜索时间类型
		$where.=getTimeFilter('i.reg_time'); //时间筛选
		
		$db=$this->db;
		$users=$db->getAll("select u.user_id,u.mobile,u.visit_count,from_unixtime(i.reg_time) reg_time,from_unixtime(u.last_login) as last_login,i.real_name,a1.surplus as money,sum(a2.n_in) as ninvest,sum(a2.all_in) as sinvest
                                from ss_user u
                                join ss_user_info i on u.user_id=i.user_id and i.user_tag=0
                                join ss_usaccount a1 on u.user_id=a1.user_id and a1.ac_type=1
                                join ss_usaccount a2 on u.user_id=a2.user_id and a2.ac_type in (2,3)
								where $where
                                group by u.user_id
                        ");

		$str='<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
		$str.='<tr align="center"><td>用户ID</td><td>手机号</td><td>登录次数</td><td>最近登录</td><td>注册时间</td>
			   <td>姓名</td><td>余额</td><td>投资笔数</td><td>投资金额</td><td>红包</td></tr>';

		foreach($users as $k=>$v){
			$coin=$db->model('user_coins')->select('sum(amount)')->where('user_id='.$v['user_id'].' and status=1')->getOne();
			$str .= "<tr><td style='vnd.ms-excel.numberformat:@'>".$v['user_id']."</td>
					<td>".$v['mobile']."</td><td>".$v['visit_count']."</td><td>".$v['last_login']."</td><td>".$v['reg_time']."</td>
					<td>".$v['real_name']."</td><td>".$v['money']."</td><td>".$v['ninvest']."</td><td>".$v['sinvest']."</td><td>".$coin."</td>
				</tr>";
		}
		$filename = 'investReport.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;exit;
	}
	
	
}
?>
