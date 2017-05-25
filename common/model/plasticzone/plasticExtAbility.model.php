<?php
/**
* 塑料圈扩展的产品-gsp
*/
class plasticExtAbilityModel extends model
{	
	private $today = '';
	public function __construct() {
        parent::__construct(C('db_default'), 'news_cate');
        $this->today = strtotime(date('Y-m-d',time()));
    }
    public function getNewsCate($extendType,$item=1,$page=1,$size=10,$cateId){
    	$data = array();
    	switch ($extendType) {
    		case 1:
    			$data['cate'] = array(2=>'塑料上游',1=>'早盘预测',9=>'企业动态',4=>'中晨塑说',5=>'外盘快递',21=>'期货资讯',11=>'装置动态',13=>'期刊报告',22=>'独家解读');
    			if($item == 1){
    				$data['items'] = $this->getItemsByCateId($item,0,$page,$size,1);//固定类别 限制到5条
    				return array('err'=>0,'data'=>$data,'msg'=>'获取头条成功');
    			}
    			if ($cateId > 0){
    				$data['items'] = $this->getItemsByCateId($cateId,0,$page,$size,0);//某一个类别 非限制
    				if(empty($data['items']['data']) && $page == 1) return array('err'=>2,'msg'=>'没有相关的数据');
    				$result = $this->checkLastPage($data['items']['count'],$size,$page);
    				if(!empty($result)) return $result;
    				return array('err'=>0,'data'=>$data['items']['data'],'msg'=>'获取头条成功');
    			}
    			return array('err'=>2,'msg'=>'获取头条失败');
    		case 2:
    			# code...//预留
    			break;
			case 3:
    			# code...//预留
    			break;
			case 4:
    			# code...//预留
    			break;
    	}
    }
    //通过cateId获取内容
    public function getItemsByCateId($cateId,$detail,$page=1,$size=10,$is_limit=0){//detail 是否显示详情内容
    	if($detail == 0){
    		$where = "cate_id = $cateId";
    		$select = 'id,cate_id,title,description,keywords,input_time,type';
    		if($is_limit == 1){
    			$data = $this->model('news_content')->select($select)->where($where)->order("input_time desc")->limit('0,5')->getAll();
    			foreach ($data as &$value) {
    				if($value['input_time'] > $this->today){
		        		$value['input_time'] = date('H:i',$value['input_time']);
		        	}else{
		        		$value['input_time'] = date('m-d',$value['input_time']);
		        	}
		        	$value['description'] = mb_substr($value['keywords'], 0,50,'utf-8').'...';
		        	$value['type'] = strtoupper($value['type']);
    			}
    			return $data;
    		}
    		return $this->getNewsContent($select,$where,$page,$size,$cateId);
    	}elseif ($detail == 1) {//详情显示
    		$where = "id = $cateId";
    		$select = 'id,cate_id,title,content,input_time,type,pv,author';
    		$data = $this->model('news_content')->select($select)->where($where)->getRow();
    		if($data['input_time'] > $this->today){
        		$data['input_time'] = date('H:i',$data['input_time']);
        	}else{
        		$data['input_time'] = date('m-d',$data['input_time']);
        	}
        	$data['type'] = strtoupper($data['type']);
        	$data['content'] = sstripslashes($data['content']);
        	if(!empty($data)) return array('err'=>0,'data'=>$data);
        	return array('err'=>2,'msg'=>'没有相关数据');
    	}
    }
    public function getNewsContent($select,$where,$page,$size,$cateId=0){
    	$data = $this->model('news_content')->select($select)
    		->page($page,$size)
	    	->where($where)
	    	->order("input_time desc")
	        ->getPage();
        foreach ($data['data'] as &$value) {
        	if($value['input_time'] > $this->today){
        		$value['input_time'] = date('H:i',$value['input_time']);
        	}else{
        		$value['input_time'] = date('m-d',$value['input_time']);
        	}
            if(!empty($value['description'])){
                $cateIds = array(1,9,5,22,11);
                if(in_array($cateId, $cateIds)){
                    $value['description'] = '';
                }else{
                    $value['description'] = mb_substr($value['description'], 0,50,'utf-8').'...';
                }

            }
        	$value['type'] = strtoupper($value['type']);
        }
        return $data;
    }
    //判断是否到最后一页
	public function checkLastPage($count,$size,$page){
		if($count>0){
			if($count%$size==0 && ceil($count/$size)<$page){
				return array('err'=>3,'msg'=>'没有更多数据');
			}elseif ($count%$size!=0 && ceil($count/$size)<$page) {
				return array('err'=>3,'msg'=>'没有更多数据');
			}
		}
	}
}
