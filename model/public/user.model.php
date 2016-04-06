<?php
/**
 * 管理员模型 
 */
class userModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'user');
	}
	
	public function getUsers($sTime,$status=0,$utype=0,$key_type="",$keyword="",$sortField="",$sortOrde="",$page=0,$size=20){
		$user_status=L('user_status');
		$user_type=L('user_type');
		
		$where=" 1 ";
		if($sTime){
			$where.=getTimeFilter($sTime); //时间筛选
		}
		if($status>0){
			 $where.=" and status='$status' ";	
		}
		if($utype>0) $where.=" and utype='$utype' ";
		
		if(!empty($keyword) && !empty($key_type)){
			$where.=" and $key_type='$keyword' ";
		}
		
		if(!empty($sortField) && !empty($sortOrder)){
			$list=$this->select('u.*,i.real_name,utype,reg_time')
				->from('user u')->join('user_info i','u.user_id=i.user_id')
				->where($where)
				->page($page,$size)
				->order("$sortField $sortOrder")
				->getPage();
		}else{
			$list=$this->select('u.*,i.real_name,utype,reg_time')
				->from('user u')->join('user_info i','u.user_id=i.user_id')
				->where($where)
				->page($page,$size)
				//->order("$sortField $sortOrder")
				->getPage();
		}
		
		
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['status']=$user_status[$v['status']];
			//企业用户取出公司名称
			if($list['data'][$k]['utype']==2){
				$list_c = $this->select('name')
					->from('user_info_extagent')
					->where("user_id ='".$list['data'][$k]['user_id']."'")
					->getRow();
				$list['data'][$k]['name'] = $list_c['name'];
			}else if($list['data'][$k]['utype']==3){
				$list_c = $this->select('company_name')
					->from('user_info_extcompany')
					->where("user_id ='".$list['data'][$k]['user_id']."'")
					->getRow();
				$list['data'][$k]['name'] = $list_c['company_name'];
			}else{
				$list['data'][$k]['name'] = $v['real_name'];
			}
			$list['data'][$k]['utype']=$user_type[$v['utype']];
			$list['data'][$k]['last_login']=$v['last_login']>1000 ? date("y-m-d H:i",$v['last_login']) : '-';
			$list['data'][$k]['reg_time']=$v['reg_time']>1000 ? date("y-m-d H:i",$v['reg_time']) : '-';
		}
		return $list;
	}
	
	/**
	 * 保存用户附件
	 * @param int $user_id   用户id
	 * @param array  $questions 安全问题关联数组
	 * @return bool
	 */
	public function addUserAtt($user_id,$data){
		return (bool)$this->model('user_att')->add($data,true);
	}
}
?>