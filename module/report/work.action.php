<?php
/**
 * 每日任务消息提醒
 *
 */
class workAction extends adminBaseAction {
	// private $title='后台用户信息列表'; //
	public function __init(){
		$this->debug = false;
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('cus_status',L('cus_status'));//客户状态
		$this->assign('belong_area',L('new_area'));//所属区域
		// $this->db=M('public:common')->model('work');
	}
	/**
	 * 站内信息列表
	 * @access public
	 * @return html
	 */
	public function init(){
		// $action=sget('action','s');
		// $type=sget('type','i');
		// $this->user_id= $_SESSION['adminid'];
		// if($action=='grid'){
		// 	//分页
		// 	$page = sget("pageIndex",'i',0); //页码
		// 	$size = sget("pageSize",'i',20); //每页数
		// 	$sortField = sget("sortField",'s','input_time'); //排序字段
		// 	$sortOrder = sget("sortOrder",'s','desc'); //排序
		// 	$where = '  1 ';
		// 	//关键词
		// 	$key_type=sget('key_type','s','order_sn');
		// 	$keyword=sget('keyword','s');
		// 	if(!empty($keyword) && $key_type=='order_sn'){
		// 		$where.=" and order_sn='$keyword' ";
		// 	}elseif(!empty($keyword) && $key_type=='input_admin'){
		// 		$admin_id = M('rbac:adm')->getAdmin_Id($keyword);
		// 		$where.=" and `customer_manager` = '$admin_id'";
		// 	}
		// 	//处理分类下拉框
		// 	$alert_type=sget('alert_type','s');//分类下拉框
		// 	if($alert_type !='')  $where.=" and `type` = ".$alert_type;
		// 	//处理分类按钮
		// 	if(!empty($type)){
		// 		$where .= " and type = ".$type." and input_time > ".strtotime(date("Y-m-d",CORE_TIME));
		// 	}
		// 	//筛选时间
		// 	$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		// 	$where.=getTimeFilter($sTime); //时间筛选
		// 	//筛选自己的数据
		// 	if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
		// 		$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
		// 		$where .= " and `customer_manager` in ($sons)";
		// 	}
		// 	$list=$this->db->where($where)
		// 				->page($page+1,$size)
		// 				->order("$sortField $sortOrder")
		// 				->getPage();
		// 	foreach($list['data'] as $k=>$val){
		// 		//短信内容
		// 		$list['data'][$k]['content']=$val['content'];
		// 		//请求时间
		// 		$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
		// 		$list['data'][$k]['admin_name']=$this->db->model('admin')->select('name')->where("admin_id = {$val['customer_manager']}")->getOne();
		// 		//是否已读
		// 		switch ($val['is_read'])
		// 		{
		// 			case 1:
		// 			  $is_read = "已读";
		// 			  break;
		// 			default:
		// 			  $is_read = "未读";
		// 		}
		// 		$list['data'][$k]['is_read']=$is_read;

		// 	}
		// 	$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		// 	$this->json_output($result);
		// }
		// $this->assign('page_title','提醒信息列表');
		//获取客户数，合作客户，私海客户，公海客户
		$customer_arr = array();
		$customer_arr['hz_cus'] = $this->db->model('customer')->select('count(c_id)')->where('is_sale = 1 and status not in (8,9,10) and customer_manager = '.$_SESSION['adminid'])->getOne();
		$customer_arr['self_cus'] = $this->db->model('customer')->select('count(c_id)')->where('is_sale = 0 and is_pur = 0 and status not in (8,9,10) and customer_manager = '.$_SESSION['adminid'])->getOne();
		$customer_arr['public_cus'] = $this->db->model('customer')->select('count(c_id)')->where('status not in (8,9,10) and customer_manager = 0 and status = 2 ')->getOne();
		// p($customer_arr);die;
		//从report_user表中拿交易员的指标,目标吨数，目标利润
		$res = $this->db->model('report_user')
					  ->where('admin_id = '.$_SESSION['adminid'].' and report_date = '.strtotime(date('Y-m',CORE_TIME)))
					  ->select('admin_id,sum(sale_num+buy_num) as num,profit')
					  ->getRow();
		// 获取当月完成的吨数
		$month_num_done= M('product:order')->getMonthNumByCustomerManager($_SESSION['adminid']);
		// showtrace();
		//获取当月毛利
		$month_profit_done = M('product:saleLog')->getMonthProfitByCustomerManager($_SESSION['adminid']);
		//获取今日的采购和销售吨数
		$today_sale_buy_num= M('product:order')->getTodayNumByCustomerManager($_SESSION['adminid']);
		$today_num_done = $today_sale_buy_num['buy_num']['num'] + $today_sale_buy_num['sale_num']['num'];
		//获取今日完成的利润
		$today_profit_done = M('product:saleLog')->getTodayProfitByCustomerManager($_SESSION['adminid']);
		//获取电话指标
		$phonelist=$this->db->model('phone_name')
                           ->getAll("SELECT p.* FROM p2p_phone_name AS p
							LEFT JOIN p2p_admin AS admin ON admin.`admin_id` = p.admin_id
							LEFT JOIN p2p_adm_role AS role ON role.`id` = p.role_id
							WHERE role.`pid` = 22 AND admin.`status` = 1 AND p.seat_phone <> '' AND p.admin_id = ".$_SESSION['adminid']);
        // p($phonelist);die;
        foreach($phonelist as $row){
         	//呼出匹配数量和匹配时间
            $sql6="SELECT count(distinct a.id) as sum,sum(a.time) as out_match_time,(select count(distinct c_id) from (select cus.c_id FROM `p2p_api` `a`
                    JOIN `p2p_phone_name` `c` ON a.phone=c.seat_phone
                    JOIN `p2p_customer_contact` `con` ON con.mobile=a.remark
                    JOIN `p2p_customer` `cus` ON cus.c_id=con.c_id
                    WHERE phone='{$row['seat_phone']}' and callstatus='ou' and a.ctime>{$startTime} and a.ctime<{$endTime} and cus.c_name is not null group by a.id) adfs) out_eff_match_num
                    FROM `p2p_api` `a`
                    JOIN `p2p_phone_name` `c` ON a.phone=c.seat_phone
                    JOIN `p2p_customer_contact` `con` ON con.mobile=a.remark
                    JOIN `p2p_customer` `cus` ON cus.c_id=con.c_id
                    WHERE phone='{$row['seat_phone']}' and callstatus='ou' and a.ctime>{$startTime} and a.ctime<{$endTime} and cus.c_name is not null";
            $rs6=$this->db->model('api')->getAll($sql6);
            //匹配时间
            $sql7="SELECT a.id,a.time,a.phone
                    FROM `p2p_api` `a`
                    JOIN `p2p_phone_name` `c` ON a.phone=c.seat_phone
                    JOIN `p2p_customer_contact` `con` ON con.mobile=a.remark
                    JOIN `p2p_customer` `cus` ON cus.c_id=con.c_id
                    WHERE phone='{$row['seat_phone']}' and callstatus='ou' and a.ctime>{$startTime} and a.ctime<{$endTime} and cus.c_name is not null";

            $rs7=$this->db->model('api')->getAll($sql7);
            $rs6[0]['out_match_time']= 0;
            $ids2=array();
            $newArr2=array();
            foreach($rs7 as $row7){
                if(!isset($ids2[$row7['id']])){
                    $ids2[$row7['id']]=$row7['id'];
                    $newArr2[]=$row7;
                }
            }
            foreach($newArr2 as $ese){
                $rs6[0]['out_match_time'] += $ese['time'];
            }
            $sql2="select count(id) as out_num,sum(time) as out_time from p2p_api where phone='{$row['seat_phone']}' and callstatus='ou' and ctime>{$startTime} and ctime<{$endTime}";
			$rs2=$this->db->model('api')->getAll($sql2);
			$_tmpTime_match_ratio=$rs6[0]['out_match_time']/$rs2[0]['out_time'];
			$rs2[0]['out_time'] = $this->returnSomeTime($rs2[0]['out_time']);
            $tmp[]=array(
                            // 'customer_manager'=>$row['admin_id'],
                            // 'seat_phone'=>$row['seat_phone'],
                            'out_eff_match_num' => $rs6[0]['out_eff_match_num'],//去重个数
                            // 'out_match_time'=>$rs6[0]['out_match_time'],    //匹配时长
                            'out_time'=>$rs2[0]['out_time'],//呼出总时长
                            'time_match_ratio'=>(sprintf("%.4f",$_tmpTime_match_ratio)*100).'%',//匹配时长率
                    );
         }
		$goal_info = array('month_num_goal'=>$res['num'],//当月吨数目标
						   'month_profit_goal'=>$res['profit'],//当月利润目标
						   'month_num_done'=>$month_num_done['num'],//当月吨数完成情况
						   'month_profit_done'=>$month_profit_done['profit'],//当月利润完成情况
						   'today_num_done'=>$today_num_done,//今日吨数完成情况
						   'today_profit_done'=>$today_profit_done['profit'],//今日利润完成情况
						   'out_eff_match_num'=>$tmp[0]['out_eff_match_num'],
						   'out_time'=>$tmp[0]['out_time'],
						   'time_match_ratio'=>$tmp[0]['time_match_ratio'],
						   'name'=>$_SESSION['username'],
						   );
		foreach ($goal_info as $key => &$value) {
			if(empty($value)){
				$value = 0;
			}
		}
		$this->assign('goal_info',$goal_info);
		$this->assign('customer_info',$customer_arr);
		$this->display('work.list.html');
	}
	public function returnSomeTime($b=0){
        $a['outime'] = $b;
        $dayc=floor($a['outime']/86400);
        $hourc=floor($a['outime']/60/60%24);
        $minc=floor($a['outime']/60%60);
        $sc=floor($a['outime']%60);
        $str1='';
        $str1.=empty($dayc)?'':$dayc.'天';
        $str1.=empty($hourc)?'':$hourc.'时';
        $str1.=empty($minc)?'':$minc.'分钟';
        $str1.=empty($sc)?'':$sc.'秒';
        if(empty($str1)){
            $str1="-";
        }
        return $str1;
    }
	/**
	 * 删除提醒信息
	 * @access private
	 */
	public function remove(){
		// $this->is_ajax=true; //指定为Ajax输出
		// $ids=sget('ids','s');
		// if(empty($ids)){
		// 	$this->error('操作有误');
		// }
		// $result=$this->db->model('alert_log')->where("id in ($ids)")->delete();
		// if($result){
		// 	$this->success('操作成功');
		// }else{
		// 	$this->error('数据处理失败');
		// }
	}
	/**
	 * 标记提醒信息
	 * @access private
	 */
	public function read(){
		// $this->is_ajax=true; //指定为Ajax输出
		// $ids=sget('ids','s');
		// if(empty($ids)){
		// 	$this->error('操作有误');
		// }
		// $result=$this->db->model('alert_log')->where("id in ($ids)")->update(array('is_read'=>1,));
		// if($result){
		// 	$this->success('操作成功');
		// }else{
		// 	$this->error('数据处理失败');
		// }
	}
}