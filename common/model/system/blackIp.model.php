<?php
/**
 * IP黑名单 
 */
class blackIpModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'black_ip');
	}
	
	/*
	 * 检查ip黑名单
	 * @access public
	 * @param string ip 用户IP
     * @return bool
	 */
	public function checkIp($ip=''){
		$black=$this->blackIp();
		if(empty($black)){
			return false;	
		}
		return preg_match("/^(".$black.")$/", $ip);
	}
	
	/*
	 * 获取ip黑名单
	 * @access private
     * @return string
	 */
	private function blackIp(){
		$_key='blackIp';
		$cache=cache::startMemcache();
		$data=$cache->get($_key);
		if(empty($data)){
			$arr=$this->model('black_ip')->where('expiration>'.CORE_TIME)->getAll();
			$vip=array();
			foreach($arr as $k=>$v){
				for($i==1;$i<=4;$i++){
					if($v['ip'.$i]==-1){
						$v['ip'.$i]='\d+';
					}
				}	
				$vip[]="$v[ip1]\.$v[ip2]\.$v[ip3]\.$v[ip4]";
			}
			$data=implode('|',$vip);
			$cache->set($_key,$data,86400); //加入缓存
		}
		return $data;
	}
}
?>