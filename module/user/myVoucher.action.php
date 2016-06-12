<?php
/**
*财务中心-我的抵用券
*/
class myVoucherAction extends homeBaseAction
{
	public function __init(){
		$this->db = M('public:common')->model('coupon');
	}
	//显示我的抵用券
	public function showMyVoucher(){
		$this->is_ajax = true;
		if($this->user_id<0) $this->error('账户错误');
		$page=sget('page','i',1);
		$page_size=2;
		if(!$list = $this->db->where('user_id='.$this->user_id)->page($page,$page_size)->getPage()) $this->error('没有相关的抵用券');
		$this->_getTickets($list,$page,$page_size);
	}
	//查询
	public function queryMyVoucher(){
		$this->is_ajax = true;
		$id = sget('id','i');//获取查询的条件
		$page=sget('page','i',1);
		$page_size=1;
		if($id == 5){
			$page_size=2;
			if(!$list = $this->db->where('user_id='.$this->user_id)->page($page,$page_size)->getPage()) $this->error('没有相关的抵用券');
		}else{
			if(!$list = $this->db->where("user_id=$this->user_id and state=$id")->page($page,$page_size)->getPage()) $this->error('没有相关的抵用券');
		}
		$this->_getTickets($list,$page,$page_size);
	}
	//分页显示
	private function _getTickets($list,$page,$page_size){
		foreach ($list['data'] as $key => $value) {
			$list['data'][$key]['state'] = $state = $value['state'] == 4 ? 4 : M('user:myVoucher')->checkVoucherState($value['id'],$value['start_time'],$value['end_time']);
			$list['data'][$key]['start_time'] = $value['start_time']>1000 ? date("Y-m-d",$value['start_time']):'-';
			$list['data'][$key]['end_time'] = $value['end_time']>1000 ? date("Y-m-d",$value['end_time']):'-';
			if($state == 3){
				$die_time = M('user:myVoucher')->getDieTime($value['id']);
				$list['data'][$key]['die_time'] = M('user:myVoucher')->getDieTime($value['id'])>1000 ? date("Y-m-d",M('user:myVoucher')->getDieTime($value['id'])):'-';
			}
		}
		$pages = pages($list['count'], $page, $page_size);
		$this->assign('data',$list['data']);
		$this->assign('pages',$pages);
		$this->display('myVoucher');
	}
	//使用抵用券
	public function useMyVoucher(){
		// $this->is_ajax = true;
		// $id = sget('id','i');
		// // 还要判断能不能使用
		// $coupon = M('financecenter:myVoucher');
		// $user = M('user:customerContact')->getListByUserid($this->user_id);
		// $order_num = $coupon->genOrderNum();
		// $coupon->startTrans();
		// try {
  //               if(!$coupon->where('id='.$id)->update(array('state'=>4,'use_time'=>CORE_TIME,'order_num'=>$order_num))) throw new Exception('更新数据失败');
  //               if(!$this->db->model('log_coupon')->add(array('user_id'=>$this->user_id,'name'=>$user['name'],'coupon_id'=>$id,'input_time'=>CORE_TIME))) throw new Exception('写入日志失败');
  //           } catch (Exception $e) {
  //               $coupon->rollback();
  //               $this->error($e->getMessage());
  //           }
  //           //事物提交
  //           $coupon->commit();
  //           $this->success('抵用券使用成功');
	}
	//查看使用记录
	public function checkUseLog(){
		$this->is_ajax = true;
		$id = sget('id','i');
		$log = $this->db->model('log_coupon')->where('id='.$id)->getRow();
		$log['input_time'] = date("Y-m-d H:i:s",$log['input_time']);
		if(!empty($log)) $this->json_output($log);
	}
}