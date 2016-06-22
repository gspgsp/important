<?php
/**
*大客户报价
*/
class clientsAction extends adminBaseAction
{
	protected $offerModel;
	public function __init(){
		$this->db=M('public:common')->model('big_client');
		// $this->offerModel=M('public:common')->model('big_offers');
	}

	public function init(){
		$action=sget('action');
		if($action=='grid'){
			$where=1;
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','fid'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$list=$this->db->where($where)
				->page($page+1,$size)
				->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['lasttime']=$v['lasttime']>1000 ? date("Y-m-d H:i:s",$v['lasttime']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);

		}

		$this->display('clients.list.html');
	}

	/**
	 * 删除大客户
	 * @access public
	 */
	public function del(){
		$this->is_ajax=true;
		$id=sget('id','i',0);
		if(!$data=$this->db->wherePk($id)->getRow()) $this->error('信息不存在');
		$this->db->wherePk($id)->delete();
		$this->success('删除成功');
	}

	/**
	 * 保存新增大客户
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)){
			$this->error('错误的请求');
		}
		$id=$data['id'];
		unset($data['id']);
		if(!$id){
			$result = $this->db->add($data);
		}else{
			$data['lasttime']=CORE_TIME;
			$result = $this->db->wherePk($id)->update($data);
		}
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}

	/**
	 * 编辑大客户信息
	 * @access public
	 */
	public function editInfo(){
		$this->is_ajax=true; //指定为Ajax输出
		$id=sget('id','i',0);
		if(!$data=$this->db->wherePk($id)->getRow()) $this->error('信息不存在');
		$result=array('err'=>0,'data'=>$data,'msg'=>'');
		$this->json_output($result);
	}

	
	public function offerlist(){
		$action=sget('action');
		if($action=='grid'){
			$where=1;
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','fid'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$list=$this->db->from('big_offers o')
				->leftjoin('big_client c','o.cid=c.id')
				->where($where)
				->select('o.*,c.gsname')
				->page($page+1,$size)
				->order("input_time desc")
				->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			}

			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);

		}

		$this->display('clients.offers.list.html');
	}

	public function doExcel(){
		$this->is_ajax=true;
		$data=sdata();
		if(empty($data)){
			$this->error('错误的请求');
		}
		$savePath=C('upload_local.path');//文件保存路径
		$path=$savePath.$data['url'];//文件路径
		if(!is_file($path)) $this->error('文件不存在');
		E('PHPExcel',APP_LIB.'extend');//引入phpexcel类

		$objPHPExcel = PHPExcel_IOFactory::load($path);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		if(empty($sheetData)) $this->error('上传文件不正确，请重新上传');
		p($sheetData);
	}
}