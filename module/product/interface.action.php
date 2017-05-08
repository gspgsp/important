<?php
/**
 * 文本采购
 */
class interfaceAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->doact = sget('doact','s');
		$this->db=M('public:common')->model('purchase');
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
		}elseif($action=='remove'){ //获取列表
			$this->_remove();exit;
		}
		$this->assign('id',sget('id','i',''));
		$this->assign('doact',$this->doact);
		$this->assign('slt','slt');
		$this->assign('ctype','2');
		$this->assign('page_title','文本求购列表');
		$this->display('interface.list.html');
	}

	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$slt = sget("do",'s','');
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1  and `sync` = 6 and `is_union` = 0";   //1 采购
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.= getTimeFilter('pur.'.$sTime); //时间筛选

		//供求
		$type = sget("type",'i','');
		if($type!='') $where.=" and pur.type='$type' ";

		//是否资源库,is_erp=6表示是资源库数据
		$is_erp = sget("is_erp",'i','');
		if($is_erp ==1) $where.=" and pur.is_erp=6 ";

		//关键词搜索
		$key_type=sget('key_type','s','pur.model');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type=='c_id'){
				$cids = M('user:customer')->getcidByCname($keyword);
				$where.=" and pur.$key_type in ($cids) ";
			}elseif($key_type=='customer_manager'){
				$adms = join(',',M('rbac:adm')->getIdByName($keyword));
				$where.=" and $key_type in ($adms) ";
			}else{
				$where.=" and pur.`$key_type` like '%$keyword%' ";
			}
		}
		$list=$this->db->from('purchase pur')
			->leftjoin('product pro','pur.p_id=pro.id')
			->leftjoin('factory fac','pro.f_id=fac.fid')
			->leftjoin('customer_contact cc','pur.user_id=cc.user_id')
			->select("pur.*,pro.model,fac.f_name,cc.mobile")
			->where($where)
			->page($page+1,$size)
			->order("$sortField $sortOrder")
			->getPage();

		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['username'] = M('rbac:adm')->getUserByCol($v['customer_manager'],'name');//后台管理员名字
			$list['data'][$k]['c_name'] = M('user:customer')->getColByName($v['c_id']);//公司名字

			$list['data'][$k]['name'] = M('user:customerContact')->getNameByUserId($v['user_id']);//公司联系人
			$list['data'][$k]['customer_manager'] = M('user:customerContact')->getCusNameByUserId($v['user_id']);//业务员
			$list['data'][$k]['type'] = $list['data'][$k]['type']==1?'采购':'报价';
			$list['data'][$k]['is_erp'] = $list['data'][$k]['is_erp']==6?'是':'-';
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * Ajax删除节点s
	 * @access private
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	/**
	 * Ajax将公司联系人拉黑
	 * 此方法前台已注销，可删除
	 * @access private
	 */
	public function defriend(){
		$this->is_ajax=true; //指定为Ajax输出
		$uids=sget('uids','s');
		if(empty($uids)){
			$this->error('操作有误');
		}
		$u_ids = join(array_unique(explode(',',$uids)),',');
		$result=$this->db->model('customer_contact')->where("user_id in ($u_ids)")->update( array("status"=>2,"update_time"=>CORE_TIME) );

		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	/**
	 * 导出excel
	 * @access public
	 * @return html
	 */
	public function download(){
		$slt = sget("do",'s','');
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1  and `sync` = 6 and `is_union` = 0";   //1 采购
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.= getTimeFilter('pur.'.$sTime); //时间筛选

		//供求
		$type = sget("type",'i','');
		if($type!='') $where.=" and pur.type='$type' ";

		$list=$this->db->from('purchase pur')
			->leftjoin('product pro','pur.p_id=pro.id')
			->leftjoin('factory fac','pro.f_id=fac.fid')
			->leftjoin('customer_contact cc','pur.user_id=cc.user_id')
			->leftjoin('customer_follow c_fo','pur.c_id=c_fo.c_id')
			->select("pur.*,pro.model,fac.f_name,cc.mobile,c_fo.remark as f_info")
			->where($where)
			->page($page+1,$size)
			->order("$sortField $sortOrder")
			->getAll();
		foreach($list as &$v){

			$v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$v['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$v['username'] = M('rbac:adm')->getUserByCol($v['customer_manager'],'name');//后台管理员名字
			$v['c_name'] = M('user:customer')->getColByName($v['c_id']);//公司名字

			$v['name'] = M('user:customerContact')->getNameByUserId($v['user_id']);//公司联系人
			$v['customer_manager'] = M('user:customerContact')->getCusNameByUserId($v['user_id']);//业务员
			$v['type'] = $v['type']==1?'采购':'报价';
		}
		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
		$str .= '<tr><td>客户名称</td><td>联系人</td><td>客户电话</td><td>厂家</td><td>牌号</td>
					<td>塑料内容</td><td>跟进记录</td><td>供求</td><td>创建时间</td><td>更新时间</td><td>交易员</td>
				</tr>';
		foreach($list as $k=>$val){
			$str .= "<tr><td style='vnd.ms-excel.numberformat:@'>".$val['c_name']."</td><td>".$val['name']."</td><td>".$val['mobile']."</td><td>".$val['f_name']."</td><td>".$val['model']."</td><td>".$val['content']."</td><td>".$val['f_info']."</td><td>".$val['type']."</td><td>".$val['input_time']."</td><td>".$val['update_time']."</td><td>".$val['customer_manager']."</td></tr>";
		}
		$str .= '</table>';
		$filename = 'plasticzone.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;
		exit;
	}

	/**
	 * 保存行内编辑仓库数据
	 * @access public
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'remark'       =>$v['remark'],
					'update_time'  =>CORE_TIME,
				);
				$sql[]=$this->db->wherePk($_id)->updateSql(saddslashes($update));
			}
		}
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('purchase');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	/**
	 * 保存不规范数据
	*/
	public function shows(){
		$id = sget('id','i',0);
		$info = $this->db->where("id = $id")->getRow();
		$this->cname = M('user:customer')->getColByName($info['c_id']);//公司名字
		$this->user_name = M('user:customerContact')->getNameByUserId($info['user_id']);
		$this->mobile = M('user:customerContact')->getNameByUserId($info['user_id'],'mobile');
		$this->type = $info['type'] == 1 ? '采购':'报价';
		$this->assign('info',$info);
		$this->display('interface.info.html');
	}
	/**
	 * 规范化后的数据保存入库
	 */
	public function saveshow(){
		$data = sdata();
		$id = $data['id'];
		$row = $this->db->wherePk($id)->getRow();
		$tm = ((intval(count($data))-1)/3)+1;
		$arr = array();
		if($tm>1){
			for ($i=1; $i < $tm; $i++) {
				$arr[$i]['pid'] = $data['pid'.$i];
				$arr[$i]['price'] =$data['price'.$i];
				$arr[$i]['store'] =$data['store'.$i];
			}
		}
		//删除原始行
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				if($k == 1){
					$this->db->wherePk($id)->delete();
				}else{
					 unset($row['id']);
				}
				$row['store_house'] = $v['store'];
				$row['unit_price'] = $v['price'];
				$row['p_id'] = $v['pid'];
				$row['input_time'] = CORE_TIME;
				$this->db->add($row);
			}
		}
		$this->success('规范化处理成功');
	}
	/**
	 * 发送短信
	 * @access public
	 * @return html
	 */
	public function send(){
		$ids = sget('ids','s') OR $this->error('传参错误');
		$uinfo = $this->db->model('purchase')->select("`user_id`,`c_id`")->where("id in ($ids)")->getAll();
		$send_info = '';
		$users_id = array();
		if(!empty($uinfo)){
			foreach ($uinfo as $k=>$v) {
				if($v['c_id'] > 0){
					$send_info .= M('user:customer')->getColByName($v['c_id']);
				}
				if($v['user_id'] > 0){
					$send_info .= '< '.M('user:customerContact')->getNameByUserId($v['user_id']).' >';
				}
				if(count($uinfo) != ($k+1)){
					$send_info .= '   |  ';
				}
				$users_id[]= $v['user_id'];
			}
		$users_id = join(',',$users_id);
		}
		if($_POST){
			$users = array();
			$user_ids = spost('user_ids','s','');
			$sms = spost('sms','s','');
			$send_time = spost('send_time','s','');
			$users = $this->db->model('customer_contact')->select('user_id,mobile')->where("`user_id` IN ($user_ids)")->getAll();
			M('system:sysSMS')->sendBatch($users,$sms,10,strtotime($send_time)?:CORE_TIME);
			$this->db->model('purchase')->where("id in ($ids)")->update(array('msg_count'=>'+=1'));
			$this->success();
		}
		$this->assign('send_info',$send_info);
		$this->assign('ids',$users_id);
		$this->display('sms.send.html');
	}
	/**
	 * setsea修改版本的回收客户
	 */
	public function setsea(){
		$this->id = sget('id','i') OR $this->error('传参错误');
		if($_POST){
			$id = $_POST['cid'];
			$reason = $_POST['reason'];
			if(empty($reason)) $this->error('释放公海原因不能为空的哦');
			//新增客户流转记录日志----S
			$remarks = "对客户操作：还原为公海客户,释放原因：".$reason;// 审核用户
			M('user:customerLog')->addLog($ids,'check','私海客户','还原为公海客户',1,$remarks);
			//新增客户流转记录日志----E
			$result=$this->db->model('customer')->where("c_id = $id")->update(array('customer_manager'=>0,'depart'=>0,'status'=>1,));
			showtrace();
			$this->success('操作成功');
		}
		$this->display('customer.sea.html');
	}
	/**
	 * 给客户发送短信
	 * @access public
	 * @return html
	 */
	public function customer_send(){
		$ids = sget('ids','s') OR $this->error('传参错误');
		$uinfo = $this->db->model('customer')->select("`c_name`,`c_id`,`contact_id`")->where("c_id in ($ids)")->getAll();
		$send_info = '';
		$fail_info = '';
		$users_id = array();
		if(!empty($uinfo)){
			foreach ($uinfo as $k=>$v) {
				if($v['c_id'] > 0){
					$send_info .= M('user:customer')->getColByName($v['c_id']);
				}
				if($v['contact_id'] > 0){
					$send_info .= '< '.M('user:customerContact')->getNameByUserId($v['contact_id']).' >';
				}else{
					$fail_info .= M('user:customer')->getColByName($v['c_id']). '&nbsp&nbsp';
				}
				if(count($uinfo) != ($k+1)){
					$send_info .= '   |   ';
				}
				$users_id[]= $v['contact_id'];
			}
		$users_id = join(',',$users_id);
		}
		if($_POST){
			$users = array();
			$user_ids = spost('user_ids','s','');
			$sms = spost('sms','s','');
			$send_time = spost('send_time','s','');
			$users = $this->db->model('customer_contact')->select('user_id,mobile')->where("`user_id` IN ($user_ids)")->getAll();
			M('system:sysSMS')->sendBatch($users,$sms,10,strtotime($send_time)?:CORE_TIME);
			// $this->db->model('purchase')->where("id in ($ids)")->update(array('msg_count'=>'+=1'));
			$this->success();
		}
		$this->assign('fail_info',$fail_info);
		$this->assign('send_info',$send_info);
		$this->assign('ids',$users_id);
		$this->display('sms.send.html');
	}

}