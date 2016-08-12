<?php
/**
*红包模型
*/
class hbModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'weixin_name');
	}
	public function updateTimes($openid,$userinfo){
		//记录openid
		if($data=$this->model('weixin_name')->where("openid='{$openid}'")->getRow()){
				$today = strtotime(date('Y-m-d',time()));
				//每天更新抽奖机会
				if($data['updatetime']<$today){
					$this->model('weixin_name')->where("id=$data['id']")->update(array('updatetime'=>time(),'times'=>$data['base_num']));
				}
				//先判断有没有绑定用户(就是已经注册过的用户)
				if($data['uid']>0 && $data['app_time']<$today){
					//新注册app，加一次机会
					$where1 = "user_id=$data['uid'] && reg_time > $today && reg_chanel==2";
					if($this->model('contact_info')->where($where1)->getRow()){
						$this->model('weixin_name')->where("id=$data['id']")->update(array('app_time'=>time(),'times'=>$data['times']+1));
					}
					//登录app，加一次机会
					$where2 = "log.user_id=$data['uid'] && log.input_time > $today && log.chanel==2 && cinfo.reg_time < $today";
					$res = $this->model('log_login')->select('log.user_id')->from('log_login log')
		            ->join('contact_info cinfo','log.user_id=cinfo.user_id')
		            ->where($where2)
		            ->getRow();
		            if($res){
		            	$this->model('weixin_name')->where("id=$data['id']")->update(array('app_time'=>time(),'times'=>$data['times']+1));
		            }
				}

		}else{
			//未保存openid 保存openid
			$_data=array(
				'openid'=>$userinfo['openid'],
				'name'=>$userinfo['nickname'],
				'times'=>1,
				'img'=>$userinfo['headimgurl'],
				'base_num'=>1,
				'addtime'=>time(),
				'updatetime'=>time(),
				);
			$this->model('weixin_name')->add($_data);
		}
	}
}
