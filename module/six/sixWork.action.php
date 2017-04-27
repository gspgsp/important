<?php 
/**
 * 6点工作制
 */
class sixWorkAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('daily_summary');
		$this->doact = sget('do','s');
		$this->assign('team',L('team')); //战队名称
	}
	/**
	 * @access public
	 * @return html
	 */
	public function init(){
		$doact=sget('do','s');
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('doact',$doact);
		$this->assign('page_title','6点工作制列表');
		$this->display('work.list.html');
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
		$where.= "1";
		$admin_name=sget('admin_name','s');
		if($admin_name)  $where.=" and `admin_name` = '$admin_name' ";
		$team_id=sget('team_id','i');
		if($team_id)  $where.=" and `depart_id` = '$team_id' ";
		// 筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		$orderby = "$sortField $sortOrder";
		//筛选过滤自己的信息
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
			$where .= " and `customer_manager` in ($sons) ";
		}
		$list=$this->db->where($where)->page($page+1,$size)->order($orderby)->getPage();
		foreach($list['data'] as $k=>$val){
			$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
			$depart =  M('rbac:adm')->getPartByID($val['customer_manager']);
			$list['data'][$k]['depart_name']=$depart['name'];
		}
		$msg="";
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}
	/**
	*日志信息
	* @access public
	*/
	public function info(){
		//从report_user表中拿交易员的指标
		$res = $this->db->model('report_user')
					  ->where('admin_id = '.$_SESSION['adminid'].' and report_date = '.strtotime(date('Y-m',CORE_TIME)))
					  ->select('admin_id,sum(sale_num+buy_num) as person_goal,income,team_goal')
					  ->getRow();
		// 获取当月完成的吨数
		$month_total_num= M('product:order')->getMonthNumByCustomerManager($_SESSION['adminid']);
		//获取今日的采购和销售吨数
		$today_sale_buy_num= M('product:order')->getTodayNumByCustomerManager($_SESSION['adminid']);
		//获取部门
		$depart= M('rbac:adm')->getPartByID($_SESSION['adminid']);
		//获取当日新开发客户
		$new_clients_num = M('user:customer')->getTodayNewClientsByCustomerManager($_SESSION['adminid']);
		//根据订单类型+业务员id查询今日销售或采购吨数
		$today_sales = M('product:order')->getTodaySaleAndBuyNum('1',$_SESSION['adminid']);
		$today_buys = M('product:order')->getTodaySaleAndBuyNum('2',$_SESSION['adminid']);
		//获取当日电话数
		$today_start_time = strtotime(date('Y-m-d',time()));
		$call = $this->db->model('api')
					->getRow('SELECT api.*,admin.name,admin.admin_id
							FROM
							  (SELECT COUNT(id) AS num,phone FROM `p2p_api` WHERE ctime > '.$today_start_time.' AND TIME > 0 AND callstatus="ou" GROUP BY phone)
							AS api 
							LEFT JOIN `p2p_admin` AS admin ON admin.seat_phone = api.`phone`
							WHERE admin.name IS NOT NULL and admin.admin_id='.$_SESSION['adminid']
						);
					showtrace();
		//获取当月毛利
		$profit = M('product:saleLog')->getMonthProfitByCustomerManager($_SESSION['adminid']);
		$info['profit'] = !empty($profit['profit'])?$profit['profit']:0;
		$info['today_phone_num'] = !empty($call['num'])?$call['num']:0;
		$info['person_goal']=substr($res['person_goal'],0,strlen($res['person_goal'])-2);
		$info['admin_id']=$_SESSION['adminid'];
		$info['team_goal']=$res['team_goal'];
		$info['done']=!empty($month_total_num['num'])?$month_total_num['num']:0;
		$info['depart_name']=$depart['name'];
		$info['depart_id']=$depart['id'];
		$info['income']=$res['income'];
		$info['today_purchases']=$today_sale_buy_num['buy_num']['num'];;
		$info['today_sales']=$today_sale_buy_num['sale_num']['num'];
		$info['today_clients']=$new_clients_num['num'];
		$info['admin_name']=$_SESSION['username'];
		$info['action']='add';
		$this->assign('info',$info);
		$this->display('work.edit.html');
	}
	/**
	 * 查看6点工作制详情
	 * @Author   yezhongbao
	 * @DateTime 2016-09-09T16:36:14+0800
	 * @return   [type]                   [description]
	 */
	public function detail(){
		$id = sget('id','i');
		$doaction = sget('doaction','s');
		if(empty($id)) $this->error('错误的请求');
		$info = $this->db->where('id = '.$id)->getRow();
		$depart= M('rbac:adm')->getPartByID($info['customer_manager']);
		$info['admin_id'] = $info['customer_manager'];
		$info['depart_name'] = $depart['name'];
		$info['action'] = $doaction;
		$this->assign('info',$info);
		$this->assign('action','view');
		$this->display('work.edit.html');
	}
	/**
	 * 新增6点工作制
	 * @access public 
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的请求');
		$data['input_time'] = CORE_TIME;
		$res = $this->db->add($data);
		if($res){
			$this->ajaxReturn('0','添加成功');
		}else{
			$this->ajaxReturn('1','添加失败');
		}
	}
	/**
	 * 修改6点工作制
	 * @Author   yezhongbao
	 * @DateTime 2016-09-09T15:45:41+0800
	 * @return   [type]                   [description]
	 */
	public function updateSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的请求');
		$data['input_time'] = CORE_TIME;
		$res = $this->db->where('id = '.$data['id'])->update($data);
		if($res){
			$this->ajaxReturn('0','修改成功');
		}else{
			$this->ajaxReturn('1','修改失败');
		}
	}
}