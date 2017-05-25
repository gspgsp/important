<?php
/**
*  用户操作模型
*/
class channelModel extends model
{
	
	public function __construct() {
		parent::__construct(C('db_default'), 'chanel');
	}
	/**
	 * 获取所有的通道
	 * @return [type] [description]
	 */
	public function getChannels(){
		return $this->select('*')->order('chanel_id asc')->getAll();
	}
	/**
	 * 编辑通道信息
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function editInfo($id){
		$data = $this->select('chanel_id,name,remark,status')->where('chanel_id = '.$id)->getRow();
		if(!empty($data)){
			$data['err'] = 0;
		}else{
			$data['err'] = 1;
		}
		return $data;
	}
	/**
	 * 增加通道
	 * @author gsp <[<email address>]>
	 * @param [type] $arr [description]
	 */
	public function addData($arr){
		$data = array(
			'name'=>$arr['name'],
			'remark'=>$arr['remark'],
			'input_time'=>CORE_TIME,
			'update_time'=>CORE_TIME,
			'status'=>(int)$arr['status'],
			);
		return $this->add($data);;
	}
	/**
	 * 更新数据
	 * @param  [type] $arr [description]
	 * @return [type]      [description]
	 */
	public function refleshData($arr){
		$data = array(
			'name'=>$arr['name'],
			'remark'=>$arr['remark'],
			'update_time'=>CORE_TIME,
			'status'=>(int)$arr['status'],
			);
		 $res = $this->where('chanel_id = '.$arr['chanel_id'])->update($data);
		 return $res;
	}
	/**
	 * 删除操作
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function del($id){
		return $this->where('chanel_id = '.$id)->delete();
	}
}