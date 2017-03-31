<?php
/**
 * Created by PhpStorm.
 * User:  yjy
 * Date: 2017/2/24
 * Time: 14:37
 */
class supplierContactAction extends adminBaseAction {

	public function __init(){
		$this->debug = false;
		$this->assign('user_chanel',L('user_chanel'));
		$this->db=M('public:common')->model('logistics_contact');
		$this->assign('supplier_contact_type',L('supplier_contact_type'));  // 联系人用户状态
		$this->assign('is_default',L('is_default'));
		$this->assign('sex',L('sex'));
		$this->doact = sget('do','s');
	}
	/**
	 * 联系人列表
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
		$this->assign('page_title','资源库列表');
		$this->display('supplier_contact.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0);   //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','asc'); //排序
		$size=20;
		//搜索条件
		$where="1";
		$supplier_id=sget('supplier_id','i',0);     // 供应商id
		if($supplier_id !=0){
			$where.=" and `supplier_id` = {$supplier_id} ";
		}
	    $list=$this->db->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['id'] =$v['id'];                             // 联系人id
			$list['data'][$k]['contact_name'] =$v['contact_name'];         // 供应商联系人
			$list['data'][$k]['sex']=L('sex')[$v['sex']];                  // 性别
			$list['data'][$k]['status']=L('supplier_contact_type')[$v['status']];       // 联系人状态
			$list['data'][$k]['is_default']=L('is_default')[$v['is_default']];
			$list['data'][$k]['create_time']=$v['create_time']>1000 ? date("Y-m-d H:i:s",$v['create_time']) : '-';     // 创建时间
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';   // 更新时间
			$list['data'][$k]['create_admin'] = M('rbac:adm')->getNameByUser($v['create_name']);  // 创建人
			$list['data'][$k]['update_admin'] = M('rbac:adm')->getNameByUser($v['update_name']);  // 跟新人
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);

		$this->json_output($result);
	}



	/**
	 * 查看联系人及相关信息
	 *
	 */
	public function viewInfo(){
		$this->is_ajax=true;
		$contact_id=sget('supplier_contact','i');
		$var=$this->db->select('id,supplier_id,supplier_name,contact_name,sex,status,contact_tel,mobile_tel,qq,comm_fax,is_default,remark,comm_email')->where('id='.$contact_id)->getRow();
		$this->assign('info',$var);
		$this->display('contact.viewInfo.html');

	}

	/**
	 * ajax 保存编辑联系人信息
	 *
	 */
	public function ajaxSave(){
		$this->is_ajax=true;
		$data = sdata();
		if(empty($data['contact_name']))  $this->error('联系人姓名不能为空');
		if(empty($data['mobile_tel']))  $this->error('联系人联系手机');
		if(empty($data['comm_email']))  $this->error('联系人邮箱不能为空');
		if(empty($data['status']))  $this->error('联系人状态不能为空');
		$data['update_name']=$_SESSION['name'];
		$data['update_time']=time();
		if($this->db->where('id='.$data['id'])->update($data)){
			$this->success('编辑成功');
		}else{
			$this->error('编辑失败');
		}

	}

}