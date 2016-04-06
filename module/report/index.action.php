<?php
/** 
 * 日报表
 */
class indexAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('stat_day');
	}

	/**
	 * 日报统计
	 * @access public 
	 * @return html
	 */
	public function init(){

		$action=sget('action','s');
		$this->user_id=sget('user_id','i',0);
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','day_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			//搜索条件
			$where="1 ";
			
			$starTime=sget('startTime','s'); //开始时间
			$endTime=sget('endTime','s');  //结束时间
			if(!empty($starTime) && strlen($starTime)>=10){
				$where.=' and day_id>='.substr(str_replace(array('-',' '),'',$starTime),2);	
			}
			if(!empty($endTime) && strlen($endTime)>=10){
				$where.=' and day_id<='.substr(str_replace(array('-',' '),'',$endTime),2);	
			}

			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
						
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','日报表');
		$this->display('index.list.html');
	}

	/**
	 * 渠道天报表
	 * @access public 
	 * @return html
	 */
	public function chanel(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','day_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			
			//搜索条件
			$where="1 ";
			
			$starTime=sget('startTime','s'); //开始时间
			$endTime=sget('endTime','s');  //结束时间
			if(!empty($starTime) && strlen($starTime)>=10){
				$where.=' and day_id>='.substr(str_replace(array('-',' '),'',$starTime),2);	
			}
			if(!empty($endTime) && strlen($endTime)>=10){
				$where.=' and day_id<='.substr(str_replace(array('-',' '),'',$endTime),2);	
			}
		
			//关键词
			$key_type=sget('key_type','s','username'); 
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='username'){
					$arr=$this->db->select('c.chanel_id')->from('chanel c')->leftjoin('chanel_user u','u.uid=c.uid')->where("u.username='$keyword'")->getCol();
					$_ids=empty($arr) ? '-1' : join(',',$arr);
					$where.=" and chanel_id in ($_ids) ";	
				}elseif($key_type=='chanelname'){
					$arr=$this->db->select('c.chanel_id')->from('chanel c')->where("c.name='$keyword'")->getCol();
					$_ids=empty($arr) ? '-1' : join(',',$arr);
					$where.=" and chanel_id in ($_ids) ";	
				}
			}

			$logs=$this->db->model('stat_chanel')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			
			$chanels=M('system:chanel')->getChanels();
			$chanels=array_flip($chanels);
			foreach($logs['data'] as $k=>$v){
				$logs['data'][$k]['day_id']=substr($v['day_id'],0,2).'-'.substr($v['day_id'],2,2).'-'.substr($v['day_id'],4,2);
				$logs['data'][$k]['chanel_id']=$chanels[$v['chanel_id']];
				$logs['data'][$k]['n_reg_ratio']=$v['n_pv']>0 ? round($v['n_reg']*100/$v['n_pv'],1) : '-';
			}
			
			$msg="";
			if($logs['count']>0){
				$sum=$this->db->model('stat_chanel')->select("sum(n_reg) as n_reg,sum(n_pv) as n_pv,sum(n_uv) as n_uv,sum(n_reg_r) as n_reg_r,sum(n_pv_r) as n_pv_r,sum(n_uv_r) as n_uv_r")
							->where($where)->getRow();
				$msg="[筛选结果]pv：$sum[n_pv]/$sum[n_pv_r]； uv：$sum[n_uv]/$sum[n_uv_r]； 注册：$sum[n_reg]/$sum[n_reg_r]";
			}
			$result=array('total'=>$logs['count'],'data'=>$logs['data'],'msg'=>$msg);
			$this->json_output($result);
		}

		$this->assign('page_title','渠道日报表');
		$this->display('day.chanel.html');
	}

	/**
	 * 日报导出报表
	 * @access public 
	 * @return html
	 */
	public function download(){
		//搜索条件
		$where="1 ";
		
		$starTime=sget('startTime','s'); //开始时间
		$endTime=sget('endTime','s');  //结束时间
		if(!empty($starTime) && strlen($starTime)>=10){
			$where.=' and day_id>='.substr(str_replace(array('-',' '),'',$starTime),2);	
		}
		if(!empty($endTime) && strlen($endTime)>=10){
			$where.=' and day_id<='.substr(str_replace(array('-',' '),'',$endTime),2);	
		}

		$list=$this->db->where($where)->getAll();

		$str='<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
		$str.='<tr align="center"><td rowspan="2">日期</td><td rowspan="2">注册数</td>
			<td colspan="3">充值</td><td colspan="3">提现</td><td colspan="3">投资</td><td colspan="3">借款</td><td colspan="6">还款</td></tr>
			<tr align="center"><td>笔数</td><td>金额</td><td>手续费</td><td>笔数</td><td>总额</td>
			<td>手续费</td><td>会员数</td><td>笔数</td><td>金额</td>
			<td>成交数</td><td>成交额</td><td>手续费</td><td>笔数</td>
			<td>总额</td><td>利率</td><td>担保费</td><td>出让</td><td>收入</td></tr>';
		
		foreach($list as $k=>$v){
			$str .= "<tr><td style='vnd.ms-excel.numberformat:@'>".$v['day_id']."</td>
			<td>".$v['register']."</td><td>".$v['recharge_count']."</td><td>".$v['recharge_sum']."</td><td>".$v['recharge_fee']."</td>
			<td>".$v['draw_count']."</td><td>".$v['draw_sum']."</td><td>".$v['draw_fee']."</td>
			<td>".$v['invest_user']."</td><td>".$v['invest_count']."</td><td>".$v['invest_sum']."</td>
			<td>".$v['deal_count']."</td><td>".$v['deal_sum']."</td><td>".$v['deal_fee']."</td>			
			<td>".$v['refund_count']."</td><td>".$v['refund_sum']."</td><td>".$v['refund_profit']."</td><td>".$v['refund_assure']."</td><td>".$v['refund_convey']."</td><td>".$v['refund_fee']."</td>
			</tr>";
		}
		$str .= '</table>';

		$filename = 'dayReport.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;
		exit;
	}
	
	/**
	 * 设置渠道数据
	 * @access public 
	 * @return html
	 */
	public function chanelSave(){
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		foreach($data as $v){
			$update=array(
				'n_reg_r'=>(int)$v['n_reg_r'],			  
				'n_pv_r'=>(int)$v['n_pv_r'],			  
				'n_uv_r'=>(int)$v['n_uv_r'],			  
				'is_confirm'=>(int)$v['is_confirm'],			  
			);
			$sql[]=$this->db->model('stat_chanel')->where("day_id=".str_replace('-','',$v['day_id']).' and chanel_id='.$v['chanel_id'])->updateSql($update);
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>
