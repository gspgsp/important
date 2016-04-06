<?php
/** 
 * App设备类
 * Andy@2014-06-03
 */
class deviceModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'),'app_device');
	}
	
	/**
	 * 检查设备记录
	 * @access public 
	 * @param string device 设备号
	 * return array
	 */
	public function getDevice($device=''){
		return $this->where("device='".$device."'")->getRow();
	}
	
	/**
	 * 新增设备
	 * @access public 
	 * @param string device 设备号
	 * @param int dtype 设备类型：7-andriod,8-ios
	 * @param int user_id 用户ID
	 * return bool
	 */
	public function addDevice($device='',$dtype=7,$user_id=0){
		$_data=array(
			'device'=>$device,
			'dtype'=>(int)$dtype,
			'user_id'=>(int)$user_id,
			'input_time'=>CORE_TIME,
			'act_time'=>CORE_TIME,
			'last_time'=>CORE_TIME,		 
		);
		return $this->add($_data,true);
	}

	/**
	 * 更新设备用户ID
	 * @access public 
	 * @param string $device 设备号
	 * @param int $dtype 设备类型：7-andriod,8-ios
	 * @param int $user_id 用户ID
	 * return bool
	 */
	public function updateDeviceUser($device='',$dtype=7,$user_id=0){
		if(!in_array($dtype,array(7,8))) return false;
		
		$result=$this->getDevice($device);
		if(empty($result)){
			return $this->addDevice($device,$dtype,$user_id);	
		}
		
		$_data=array();
		//用户更新了
		if($user_id>10000 && $user_id!=$result['user_id']){
			//最后一次的用户ID
			$_data['user_id']=$user_id;
			if(strlen($result['user_ids'])>4){
				if(!in_array($user_id,explode(',',$result['user_ids']))){					
					$_data['user_ids']=$result['user_ids'].','.$user_id;
				}
			}else{
				$_data['user_ids']=$user_id;
			}				
		}
		return $this->updateData($result['id'],$_data);
	}

	/**
	 * 更新设备数据
	 * @access public 
	 * @param int $id 设备ID
	 * @param array $data 更新数据
	 * return bool
	 */
	public function updateData($id=0,$data=array()){
		$data['last_time']=CORE_TIME; //最后更新时间
		return $this->where("id=".$id)->update($data);
	}
}
?>