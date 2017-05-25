<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/11/24
 * Time: 17:02
 * 大户报价列表导入
 */
class clientAction extends adminBaseAction
{

	protected $offerModel;
	protected $db;
	public function __init(){
//		$this->db=M('public:common')->model('big_client');
		$this->db=M('public:common')->model('big_offers');
		 $this->offerModel=M('public:common')->model('big_offers');
	}

	public function init(){
		$action=sget('action');
		if($action=='grid'){
			$where=1;
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','fid'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$list=$this->db->model('big_offers as `bo`')
				->leftjoin('big_client as bc','bo.cid=bc.id')
				->select('bo.*,bc.gsname')
				->order('bo.input_time desc')
				->where($where)
				->page($page+1,$size)
				->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']= date("Y-m-d H:i:s",$v['input_time']);
//				$list['data'][$k]['input_time']=$v['']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		$this->display('client.list.html');
	}

	/**
	 * 删除大客户报价
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
		$data['type']=strtoupper($data['type']);
		$data['model']=strtoupper($data['model']);
		if(empty($data)){
			$this->error('错误的请求');
		}
		$id=$data['id'];
		unset($data['id']);
		$data['input_time']=CORE_TIME;
		if(!$id){
			$result = $this->db->add($data);
		}else{
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

	/**
	 * 大客户报价导入
	 * @Author: yuanjiaye
     */
	public function inputExcel(){
		$this->is_ajax = true;
		E('PHPExcel',APP_LIB.'extend');
		if(empty($_FILES['check_file']) || $_FILES['check_file']['error']) $this->error('文件上传失败！');
		try {
			$objPHPExcel = PHPExcel_IOFactory::load($_FILES['check_file']['tmp_name']);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			if(empty($sheetData)) $this->error('上传文件不正确，请重新上传');
			if(count(array_shift($sheetData)) !==9) throw new Exception('Excel表数据格式不匹配');

			$error=array();
			foreach($sheetData as $key=> $row){
				//如果为空或者不是数字则 不检查导入该行
				if(empty($row['A']) || empty($row['B']) || empty($row['C']) || empty($row['D']) || !is_numeric($row['E']) || !is_numeric($row['F']) || !is_numeric($row['G'])|| empty($row['H'])|| empty($row['I']) ){
					$error['number']+=1;
					$error['err'][]='数据不规范';
					continue;
				}
				//获取大客户公司id
				$c_id = $this->db->model('big_client')->select('id')->where('gsname='.'"'.$row['A'].'"')->getOne();
				if (!$c_id){
					$error['number']+=1;
					$error['err'][]=$row['A'].'公司名有误,大客户联系中无此公司';
					continue;
				}
				//获取产品类型
				$rowB = strtoupper($row['B']);
				$product_type=array_flip(L('product_type'))[$rowB];
				if(!$product_type){
					$error['number']+=1;
					$error['err'][]=$row['B'].'产品类型有误';
					continue;
				}
				//获取交货地的id
				$add_id = $this->db->model('lib_region')->select('id')->where('name='."'".$row['H']."'")->getOne();
				if (!$add_id){
					$error['number']+=1;
					$error['err'][]=$row['H'].'交货地有误';
					continue;
				}

				//写数据到表中p2p_big_offers
				$_infoData = array(
					'cid'		=>    $c_id,
					'type'      =>    $row['B'],
					'model'		=>    strtoupper($row['C']),
					'factory'   =>    $row['D'],
					'num'	    =>    $row['E'],
					'price'     =>    $row['F'],
					'upordown'  =>    $row['G'],
					'ck' 	    =>    $row['I'],
					'address'   =>    $row['H'],
					'input_time' => CORE_TIME
				);
				$this->db->model('big_offers')->add($_infoData);
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
		$this->json_output(array('err'=>$error['number'],'result'=>!$error['err']?'导入成功':$error['err']));
	}
}