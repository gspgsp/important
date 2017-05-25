<?php
class wxCmsModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'wxcms_reply');
	}
	
	public function setReply($_data=array(),$id=""){
		//检查当前输入关键字是否存在
		if($this->getReplyDetail('',$_data['input_string'])){
			$result = $this->getReplyDetail('',$_data['input_string']);
			$input = $_data['input_string'];
			$_data['id'] = $result['id'];
			
			$this->where("input_string='$input'")->update($_data);
			return array('id'=>$result['id'],'msg'=>'该关键字已存在');
		}

		// 检查是否传入模板id，传入模板id
		if(!empty($id)&&$this->getPk($id)){
			$temp_id = (int)$id;
			$result = $this->where("id='$temp_id'")
						->update($_data);
			if($result){
				return $temp_id;
			}
		}else{
			// 未传入模板id,添加一个模板
			$result = $this->add($_data);
			$id = $this->getLastID();
			if($result){
				return $id;
			}
		}
		return false;
	}
	
	public function getReplyList($input_type="",$page=0,$size=20){
		$page = (int)$page;
		$size  = (int)$size;
		if(empty($input_type)){
			$result = $this->order('create_time DESC')
						->page($page+1,$size)
						->getPage();
		}else{
			// 分类查找
			$arr = explode(',',$input_type);
			$where_str = "input_type in (";
			foreach($arr as $k=>&$v){
				$id = (int)$v;
				$where_str = $where_str."'$id',";
			}
			$where_str = preg_replace("/,$/",")",$where_str);
			$result = $this->where($where_str)
						->order('create_time DESC')
						->getAll();
			if($result){
				$result = array('data'=>$result,'count'=>count($result));
			}
		}
		return $result;
	}
		
	public function getWelcome($type=2){
		$type  = (int)$type; 
		$time = time();
		$result = $this->where("use_type='$type'AND (expire_time=0 OR expire_time>'$time')")
					->order('create_time DESC')
					->getRow();
		if($result){
			return $result;
		}
	}
	
	// 单选欢迎语的回复模板
	public function getUseTemp(){
		$result = $this->where('use_type=1')->order('create_time DESC')->getRow();
		return $result;
	}
	
	public function getReplyDetail($id="",$input=""){
		if(!empty($id)){
			$ids = explode(',',$id);
			$where_str = "id in (";
			foreach($ids as $k=>&$v){
				$id = (int)$v;
				$where_str = $where_str."'$id',";
			}
			$where_str = preg_replace("/,$/",")",$where_str);
			
			$result = $this->select('id,title,create_time,expire_time,use_type,input_type,input_string,reply_type,reply_rand,reply_string')
					->where($where_str)
					->getPage();
			return $result;
		}else if(empty($id)&&!empty($input)){
			$result = $this->where("input_string='$input'")->getRow();
			return $result;
		}
	}
	
	// 设置回复模板
	public function setTemple($_data=array(),$id=""){
		
		// 检查是否传入模板id，传入模板id
		if(!empty($id)&&$this->getTempleDetail($id)){
			$temp_id = (int)$id;
			$result = $this->model('wxcms_template')
						   ->where("id='$temp_id'")
						   ->update($_data);
			if($result){
				return $temp_id;
			}
		}else{
			// 未传入模板id,添加一个模板
			$result = $this->model('wxcms_template')->add($_data);
			$id = $this->getLastID();
			if($result){
				return $id;
			}
		}
		
		return false;
	}
	// 批量添加模板模板
	public function transTemple($_data=array()){
		foreach($_data as $k=>&$v){
			// 未传入模板id,添加一个模板
			$sql[] = $this->model('wxcms_template')->addSql($v);
		}
		if(!empty($sql) && $this->commitTrans($sql)){
			// $result = $arr;
		}
		// $id = $this->getLastID();
		if($result){
			return $id;
		}
		
		return false;
	}
	// 获取回复模板
	public function getTempleList($template_type=2,$page=0,$size=20){
		$template_type = (int)$template_type;
		$result = $this->model('wxcms_template')
						->select('id,title,create_time,expire_time,template_type,template_string')
						->where("template_type='$template_type'")
						->order('create_time DESC')
						->page($page+1,$size)
						->getPage();
		return $result;
	}
	// 获取某个模板的值
	public function getTempleDetail($temp_id=""){
		$arr = explode(',',$temp_id);
		$where_str = "id in (";
		foreach($arr as $k=>&$v){
			$id = (int)$v;
			$where_str = $where_str."'$id',";
		}
		$where_str = preg_replace("/,$/",")",$where_str);
		
		$result = $this->model('wxcms_template')
				->select('id,title,create_time,expire_time,template_type,template_string')
				->where($where_str)
				->getPage();
		return $result;
	}
	
	//删除模板
	public function deleteReply($id=''){
		$sql=array();
		// 传入多个id值，批处理删除
		if(count(explode(",",$id))>1){
			$arr = explode(",",$id);
			foreach($arr as $k=>&$v){
				$reply_id = (int)$v;
				$sql[] = $this->where("id='$reply_id'")
							   ->deleteSql();
			}
			if(!empty($sql) && $this->commitTrans($sql)){
				$result = $arr;
			}
		}else{
			$reply_id = (int)$id;
			$result  = $this->where("id='$reply_id'")
						   ->delete();
		}
		return $result;
	}
	
	//删除模板
	public function deleteTemple($id=''){
		$sql=array();
		// 传入多个id值，批处理删除
		if(count(explode(",",$id))>1){
			$arr = explode(",",$id);
			foreach($arr as $k=>&$v){
				$temp_id = (int)$v;
				$sql[] = $this->model('wxcms_template')
							   ->where("id='$temp_id'")
							   ->deleteSql();
			}
			if(!empty($sql) && $this->commitTrans($sql)){
				$result = $arr;
			}
		}else{
			$temp_id = (int)$id;
			$result  = $this->model('wxcms_template')
						   ->where("id='$temp_id'")
						   ->delete();
		}
		return $result;
	}
	
	public function saveMenu($_data=array(),$id=''){
		
	}
	
}	
?>