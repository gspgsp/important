<?php
/** 
 * app每个渠道日报
 */
class appStatChannelAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('app_stat_channel');
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
			//关键词
			$key_type=sget('key_type','s','chanel_id'); 
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='name'){
					$arr=$this->db->select('c.chanel_id')->from('chanel c')->where("c.name='$keyword'")->getCol();
					$_ids=empty($arr) ? '-1' : join(',',$arr);
					$where.=" and chanel_id in ($_ids) ";	
				}elseif($key_type=='user'){
					$arr=$this->db->select('c.chanel_id')->from('chanel c')->join('chanel_user u','c.uid=u.uid')->where("u.username='$keyword'")->getCol();
					$_ids=empty($arr) ? '-1' : join(',',$arr);
					$where.=" and chanel_id in ($_ids) ";	
				}else{
					$where.=" and $key_type='$keyword' ";
				}
			}

			$chanels=M('system:chanel')->getChanels();
			$chanels=array_flip($chanels);

			$list=$this->db->model('app_stat_channel')->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['day_id']=substr($v['day_id'],0,2).'-'.substr($v['day_id'],2,2).'-'.substr($v['day_id'],4,2);
				$list['data'][$k]['chanel_id']=$chanels[$v['chanel_id']];
			}
			
			$msg='';		
			if($list['count']>0){
				$sum=$this->db->model('app_stat_channel')
							->select("sum(actived_count) as actived_count,sum(register_count) as register_count")
							->where($where)->getRow();
				//投资统计（时间内注册并投资的）
				$regTimeWhere="1 ".getTimeFilter('u.reg_time');
				$investTimeWhere=getTimeFilter('ua.input_time');
				if($_ids){
					$investTimeWhere .= " and u.chanel_id in ($_ids) ";
				}
				
				$count=$this->db->select('count(distinct u.user_id) as icount,sum(principal) as imoney')->from('manager_user u')
				->join('item_uapply ua','ua.user_id=u.user_id')
				->where($regTimeWhere.$investTimeWhere)
				->getRow();
				$msg="[筛选结果]激活:".$sum['actived_count']."，注册:".$sum['register_count'].'，投资人数:'.(int)$count['icount'].'，投资总额:'.(float)$count['imoney'];
			}
						
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
			$this->json_output($result);
		}
		
		$this->assign('page_title','app每个渠道日报');
		$this->display('appStatChannel.list.html');
	}
}
?>
