<?php
/**
 * 报表
 */
class reportModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'sys_report');
	}

	/**
	 * 生成报表数据
	 * @param int $ctype 报表类型：1-周报，2-季度报表，3-年度报表
	 * @return bool
	 */
	public function createReport($ctype=0,$day='',$beg_time,$day_time,$year,$mark,$logfile){
		$where=' input_time>='.($beg_time).' and input_time<'.$day_time.' ';
		
		$data = array();
		$data['ctype'] =$ctype;
		//统计注册人数
		$where2=str_replace('input_time','reg_time',$where);
    	$data['register'] = (int)$this->select('count(1)')->from('user_info')->where($where2)->getOne();
		
		//统计不同产品的投资金额（项目）
    	$rowData = $this->select('i.ndays,sum(iu.principal) s')->from('item_uapply iu')->leftjoin('item i','iu.item_id=i.id')->where($where." AND iu.status=1")->group("i.ndays")->getAll();
		$data['invest_7day_sum'] = $data['invest_30day_sum'] = $data['invest_90day_sum'] = $data['invest_180day_sum'] = 0;
		foreach($rowData as $k=>$v){
			if($v['ndays']=='7'){
    			$data['invest_7day_sum'] = (float)$v['s'];
			}elseif($v['ndays']=='30'){
    			$data['invest_30day_sum'] = (float)$v['s'];
			}elseif($v['ndays']=='90'){
    			$data['invest_90day_sum'] = (float)$v['s'];
			}elseif($v['ndays']=='180'){
    			$data['invest_180day_sum'] = (float)$v['s'];
			}
		}
		
		//统计投资
    	$row = $this->select('count(DISTINCT user_id) u,count(1) c,sum(principal) s')->from('item_uapply')->where($where." AND status=1")->getRow();
    	$data['invest_user'] = (int)$row['u'];//投资人数
    	$data['invest_count'] = (float)$row['c'];//投资总笔数
    	$data['invest_sum'] = (float)$row['s'];//投资总金额
		
		//统计首次投资人次
		//$rowFirst = $this->select("count(1) c")->from('manager_user ui')->where($where2." and ui.invest_num=1")->getRow();
		//$data['first_investuser'] = (int)$rowFirst['c'];//投资人数
		
		//统计首次投资
		$rows = $this->select('count(DISTINCT iu.user_id) u,sum(iu.principal) s')->from('item_uapply iu')->leftjoin('manager_user ui','iu.user_id=ui.user_id')->where(' iu.input_time>='.($beg_time).' and iu.input_time<'.$day_time.' and ui.reg_time>='.($beg_time).' and ui.reg_time<'.$day_time.' AND iu.status=1 and ui.invest_num=1')->getRow();
    	$data['first_investuser'] = (int)$rows['u'];//投资人次
    	$data['first_investsum'] = (float)$rows['s'];//投资总金额
		
		//所有用户首次投资
		$rows_all = $this->select('count(DISTINCT iu.user_id) u,sum(iu.principal) s')->from('item_uapply iu')->leftjoin('manager_user ui','iu.user_id=ui.user_id')->where(' iu.status=1 and ui.invest_num=1 and iu.input_time<'.$day_time)->getRow();
    	$data['firstall_investuser'] = (int)$rows_all['u'];//首次投资人次
    	$data['firstall_investsum'] = (float)$rows_all['s'];//首次投资金额

		//注册用户总数
		$data['all_register'] = (int)$this->select('count(1)')->from('user_info')->where('reg_time<'.$day_time)->getOne();
		//投资用户总数
		$data['all_investuser'] = (int)$this->select('count(1)')->from('manager_user')->where('is_invest=1 and reg_time<'.$day_time)->getOne();
		//单次投资用户
		$data['single_investuser'] = (int)$this->select('count(1)')->from('manager_user')->where('invest_num=1 and reg_time<'.$day_time)->getOne();
		//重复投资用户
		$data['many_investuser'] = (int)$this->select('count(1)')->from('manager_user')->where('invest_num>1 and reg_time<'.$day_time)->getOne();
		
		$data['year']=$year;
		$data['mark']=$mark;
		$this->model('sys_report')->add($data,true);
		
		wlog($logfile,"Day Batch Start @ ".date("m-d H:i:s")."\r\n");

	}

}