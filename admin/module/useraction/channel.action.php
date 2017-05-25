<?php
/**
*  用户行为操作
*/
class channelAction extends adminBaseAction
{
	/**
	 * 初始化方法
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('chanel');
	}
	/**
	 * 入口方法
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function init(){
		$action=sget('action');
		if($action=='grid'){
			$list = M('useraction:channel')->getChannels();
			foreach($list as &$v){
				$v['input_time'] = $v['input_time'] > 1000 ? date('Y-m-d H:i:m',$v['input_time']):'-';
				$v['update_time'] = $v['update_time'] > 1000 ? date('Y-m-d H:i:m',$v['update_time']):'-';
				$v['status'] = $v['status'] == 1?'可用':'禁用';
			}
			$result=array('data'=>$list,'msg'=>'');
			$this->json_output($result);

		}
		$this->display('index');
	}
	/**
	 * 编辑用户访问通道
	 * @return [type] [description]
	 */
	public function editInfo(){
		$id = sget('id','i',0);
		$data = M('useraction:channel')->editInfo($id);
		$this->json_output($data);
	}
	/**
	 * ajax保存数据
	 * @return [type] [description]
	 */
	public function ajaxSave(){
		$data = sdata();
		if((int)$data['chanel_id'] <1){
			//新增数据
			if(M('useraction:channel')->addData($data)){
				$this->json_output(array('err'=>0));
			}else{
				$this->json_output(array('err'=>1));
			}

		}else{
			//更新数据
			if(M('useraction:channel')->refleshData($data)){
				$this->json_output(array('err'=>0));
			}else{
				$this->json_output(array('err'=>1));
			}
		}
	}
	/**
	 * 删除操作
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function del(){
		$id = sget('id','i',0);
		if(M('useraction:channel')->del($id)){
				$this->json_output(array('err'=>0));
			}else{
				$this->json_output(array('err'=>1));
			}
	}

}