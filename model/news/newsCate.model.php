<?php 
	class newsCateModel extends model {
		public function __construct(){
			parent::__construct(C('db_default'),'news_cate');	
		}

		 //通过一个分类ID取出所有子分类
		public function getChildrens($cate_id){
			$data=$this->select('cate_id,cate_name,pid,spell')->getAll();
			return $this->_getChildrens($data,$cate_id);
		}
		private function _getChildrens($data,$cate_id){
			$arr=array();
			foreach ($data as $v) {
				if($v['pid']==$cate_id){
					$arr[$v['spell']]=$v;
					foreach($data as $v2){
						if($v2['pid']==$v['cate_id']){
							$arr[$v['spell']]['childrens'][$v2['spell']]=$v2;
						}
					}
				}	
			}
			return $arr;
		}
		//通过子分类id循环取出所有子分类数据
		public function getChildrensData($childrens){
			$arr=array();
			foreach ($childrens as $v) {
				$arr[$v['spell']]=$this->model('news_content')->select('id,title,content,update_time')->where('cate_id ='.$v['cate_id'])->order('sort_order desc,update_time desc')->limit(9)->getAll();
				foreach ($arr[$v['spell']] as $key=>$value) {
					$arr[$v['spell']][$key]['update_time']=date('Y-m-d',$value['update_time']);
				}
				$arr[$v['spell']]['hot']=$this->model('news_content')->select('id,title,content,update_time')->where('cate_id ='.$v['cate_id'].' and hot = 1')->order('update_time desc')->getRow();
				$arr[$v['spell']]['hot']['content']=strip_tags($arr[$v['spell']]['hot']['content'],span);
			}
			return $arr;
		}
		//通过分类ID获取该分类的所有新闻数据
		public function getListByMid($mid,$page=1,$page_size=0){
			$data=$this->select('cate_id,cate_name,pid,spell')->getAll();
			$childrensid=$this->getChildrensId($data,$mid,true);
			$childrensid[]=$mid;
			$mid=implode(',', $childrensid);
			$this->model('news_content')->where('cate_id in ('.$mid.') and status = 1')->order('sort_order desc,update_time desc');
			if($page&&$page_size){
				return $this->page($page,$page_size)->getPage();
			}elseif ($page_size) {
				$this->limit($page_size);
			}
			return $this->getAll();
		}
		//获取分类id的所有子分类id
		public function getChildrensId($data,$id,$isFlush=false){
			static $arr=array();
			if($isFlush) $arr=array();
			foreach ($data as $v) {
				if($v['pid']==$id){
					$arr[]=$v['cate_id'];
					$this->getChildrensId($data,$v['cate_id']);
				}	
			}
			return $arr;
		}

		//递归写出分类树
		public function getTree($data,$c_id=0,$deep=0){
			static $arr=array();
			foreach($data as $k=>$v){
				if($v['pid']==$c_id){
					$arr[$k]=$v;
					$arr[$k]['deep']=str_repeat('&nbsp;', $deep*6);
					$this->getTree($data,$v['cate_id'],$deep+1);
				}
			}
			return $arr;
		}
		//根据分类id获取父级分类id
		public function getParents($info){
			$arr=array();
			$arr[]=$info['cate_id'];
			$pid=$this->select('pid')->where('cate_id='.$info['cate_id'])->getOne();
			if($pid==0){
				return $arr;
			}
			$parentInfo=$this->select('cate_id,pid')->where('cate_id='.$pid)->getRow();
			if($parentInfo['pid']!=0){
				$arr[]=$parentInfo['cate_id'];
				$parentInfo=$this->select('cate_id,pid')->where('cate_id='.$parentInfo['pid'])->getRow();
				$arr[]=$parentInfo['cate_id'];
			}else{
				$arr[]=$parentInfo['cate_id'];
			}
			return $arr;
		}

	}

 ?>