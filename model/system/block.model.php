<?php
/**
 * 栏目管理 
 */
class blockModel extends model{
	private $cache=NULL; //缓存
	public function __construct() {
		parent::__construct(C('db_default'), 'block');
		$this->cache= E('RedisCluster',APP_LIB.'class');
	}
	
	/*
	 * 获取所有广告位
	 * @access public
     	 * @return array
	 */
	public function getPosition(){
		$_key='blockPos';
		$data=$this->cache->get($_key);
		if(empty($data)){
			$arr=$this->model('block_position')->select('id,name,content')->getAll();
			foreach($arr as $k=>$v){
				$data[$v['id']]=array('name'=>$v['name'],'content'=>json_decode($v['content'],true));
			}
			$this->cache->set($_key,$data); //加入缓存
		}
		return $data;
	}
	
	/*
	 * 获取广告区内容
	 * @access public
	 * @param int $position 栏位ID
	 * @param int $num 数量
     * @return array
	 */
	public function getBlock($position=0,$num=1){
		if(empty($position)) return array();
		$_key='blockPos_'.$position;
		$data=$this->cache->get($_key);
		if(empty($data)){
			$where='position='.$position.' and status=1 and input_time<='.CORE_TIME.' and (end_time=0 OR end_time>='.CORE_TIME.')';
			$arr=$this->model('block')->select('content,(end_time-'.CORE_TIME.') as time')
					->where($where)->order('sort_order asc')->limit($num)->getAll();
			$expire=0;
			foreach($arr as $k=>$v){
				$cont=json_decode($v['content'],true);
				if(count($cont)==1 && isset($cont['self']) && count($cont['self'])==1){
					$data[$k]=$cont['self'][0];
				}else{
					$data[$k]=$cont;
				}
				$expire=$expire>0 ? min($expire,$v['time']) : $v['time'];
			}
			if(!empty($data)){
				$this->cache->set($_key,$data,$expire); //加入缓存
			}
		}
		return $data;
	}

    /**
     * 公共底部友情链接
     * @param int $postions
     */
    public function getFriendshipLink($positions=3){
        if(empty($positions)) return array();
        $_key='blockPos_'.$positions;
        $data=$this->cache->get($_key);
        if(empty($data)){
            $where='position='.$positions.' and status=1 and input_time<='.CORE_TIME.' and (end_time=0 OR end_time>='.CORE_TIME.')';
            $arr=$this->model('block')->select('content')->where($where)->order('sort_order asc')->getAll();
            $expire=0;
            foreach($arr as $k=>$v){
                $cont=json_decode($v['content'],true);
                if(count($cont)==1 && isset($cont['self']) && count($cont['self'])==1){
                    $data[$k]=$cont['self'][0];
                }else{
                    $data[$k]=$cont;
                }
                $expire=$expire>0 ? min($expire,$v['time']) : $v['time'];
            }
           if(!empty($data)){
                $this->cache->set($_key,$data,$expire);//存缓存
           }
        }
        return $data;

    }
}
?>