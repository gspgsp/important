<?php
/**
 *这个模块主要处理的是点击做单子选择客户的问题
 */
class customerchoseAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->assign('status',L('status'));// 联系人用户状态
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户级别
		$this->assign('identification',L('identification'));//客户级别
		$this->assign('credit',L('is_credit'));//授信状态
		$this->db=M('public:common')->model('customer');
		$this->doact = sget('do','s');
		$this->pt = sget('pt','i',2);
		$this->public = sget('isPublic','i',0);
		$this->moreChoice = sget('moreChoice','i',0);
		$this->cooperation = sget('cooperation','i',0);
		$this->supplier = sget('supplier','i',0);
	}

	/**
	 * 会员列表
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('privated',sget('privated','i',1));//私有客户
		//私有客户
		$this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('page_title','会员列表');
		$this->display('customerchose.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	public function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$pt = sget("pt",'i',2);
		$sortField = sget("sortField",'s','c_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$where = ' 1  and `status` != 10 and `customer_manager` > 0 ';
		// pt主要标示黄名单客户的一些信息（8代表黄明单）
		if($pt ==2) $where .= ' and `status` != 9 and  `status` != 8 ';
		if($pt ==1) $where .= ' and `status` != 9 and chanel != 6 ';
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		$status = sget("status",'s',''); //状态
		if($status!='') $where.=" and status='$status' ";
		$type = sget("type",'s',''); //状态
		if($type!='') $where.=" and type='$type' ";//type 客户类型
		$invoice = sget("invoice",'i',''); //开票资料状态
		if($invoice != 0) $where .=" and invoice=$invoice ";
		$level = sget("level",'s',''); //level 客户级别
		if($level!='') $where.=" and level='$level' ";
		$identification = sget("identification",'s',''); //认证
		if($identification!='') $where.=" and identification='$identification' ";
		// 关键词
		$key_type=sget('key_type','s','c_id');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type=='c_name'){
				$where.=" and $key_type like '%$keyword%' ";
				$cidshare = M('user:customer')->getcidByCname($keyword);
			}elseif($key_type=='customer_manager'){
				$adms = join(',',M('rbac:adm')->getIdByName($keyword));
				$where.=" and $key_type in ($adms) ";
			}elseif($key_type=='need_product'){
				$where.=" and `need_product_adm` like '%$keyword%' ";
			}else{
				$where.=" and $key_type='$keyword' ";
			}
		}
		//筛选自己的客户
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
			$pools = M('user:customer')->getCidByPoolCus($_SESSION['adminid']); //共享客户
			$where .= " and `customer_manager` in ($sons) ";
			// 如果是搜索需要显示搜索客户的姓名
			if(!empty($keyword) && $cidshare){
				//我用这个用户的id去共享表查询下看有没有这个id
				if(M('user:customer')->judgeShare($cidshare)) $where .= " or `c_id` in ($cidshare)";
			}else{
				// 默认列表显示全部的共享客户
				if(!empty($pools)){
					$cids = explode(',', $pools);
					$where .= " or `c_id` in ($pools)";
				}
			}
		}
		$list=$this->db ->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list['data'][$k]['chanel']=L('company_chanel')[$v['chanel']];//客户渠道
			$list['data'][$k]['level']=L('company_level')[$v['level']];
			$list['data'][$k]['depart']=C('depart')[$v['depart']];
			$list['data'][$k]['type']=L('company_type')[$v['type']];
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("y-m-d H:i",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("y-m-d H:i",$v['update_time']) : '-';
			$list['data'][$k]['chk'] = $this->_accessChk();
			//获取联系人的姓名和手机号
			$contact = $this->db->model('customer_contact')->select('name,mobile,tel')->where('user_id='.$v['contact_id'])->getRow();
			//超管+李总+业务员+物流自己可以看电话，其余都是打星
			$see = $this->_getSee($v['customer_manager']);
			$list['data'][$k]['mobile'] = $this->_hidestr($contact['mobile'],$see);
			$list['data'][$k]['tel'] = $this->_hidestr($contact['tel'],$see);
			//对客户名称打星(战队领导才打星号)
			$list['data'][$k]['c_name']  = _leader($v['c_name'], $v['customer_manager'],!M('user:customer')->judgeShare($v['c_id']));
			//获取最新一次跟踪消息
			$message = $this->db->model('customer_follow')->select('remark')->where('c_id='.$v['c_id'])->order('input_time desc')->getOne();
			$list['data'][$k]['remark'] = $message;
			$list['data'][$k]['bli'] = $this->db->model('customer_billing')->select('id')->where("`c_id`={$v['c_id']}")->getOne();
			$list['data'][$k]['invoice'] =  $v['invoice']==2 ? '是' : '否';
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}
	/**
	 * [hidestr description]隐藏电话号码信息
	 * @Author   cuiyinming               QQ:1203116460
	 * @DateTime 2017-03-15T17:07:02+0800
	 * @param    string                   $str          [description]
	 * @param    integer                  $see          [description]
	 * @param    integer                  $len          [description]
	 * @return   [type]                                 [description]
	 */
	private function _hidestr($str='',$see=0,$len=4){
		$hide ='';
		if($see==1) return $str;
		for ($i=0; $i < $len; $i++) {
			$hide .= '*';
		}
		$str = empty($str) ? '' : substr($str,0,strlen($str)-$len).$hide;
		return $str;
	}
	/**
	 * 获取可查看信息的权限
	 * @Author   cuiyinming               QQ:1203116460
	 * @DateTime 2017-03-15T17:08:52+0800
	 * @return   [type] //超管+李总+业务员+物流自己可以看电话，其余都是打星
	 */
	private function _getSee($customer=0){
		$uid = $_SESSION['adminid'];
		//超管与李总
		if($uid == 1 || $uid == 726) return $see = 1;
		//判断交易员是否是自己的
		if($customer == $uid) return $see = 1;
		//判断当前用户是否是物流人员
		if($this->db->model('adm_role_user')->where("role_id in (24,25) and user_id = $uid")->getAll()) return $see = 1;
	}

}