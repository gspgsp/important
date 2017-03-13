<?php 
	class newsModel extends model {
		//初始化模型
		public function __construct(){
			parent::__construct(C('db_default'),'news_content');
		}

		//通过分类id来获取各自分类的文章
		public function getIndex($type=''){
			$cache= E('RedisCluster',APP_LIB.'class');
			$name='news_'.$type;
			if (empty($type)) {$name='news_public';}
			$data=$cache->get($name);
			if (empty($data)) {
				$where='status=1';			
				if($type=='vip'){
					$num=93;
					$where.=' and type="vip"' ;
				}else{
					$num=67;				
					if(!empty($type)){
						$where.=' and type in("'.$type.'","public")' ;
					}				
				}
				$cate_type=L('cate_type');
				if (empty($type)) {
					$type='public';
				}
				foreach ($cate_type as $k => $v) {
					if ($type==$v) {
						$c_type=$k;
					}
				}
				$cates=$this->model('news_cate')->select('cate_id,spell,hot,limits')->where("find_in_set('".$c_type."',type)")->getAll();
				$time=strtotime(date('Y-m-d'));		
				foreach ($cates as $v) {
					$data[$v['spell']]=$this->model('news_content')->select('id,title,input_time,type')->where($where.' and cate_id='.$v['cate_id'])->order('input_time desc,sort_order desc')->limit($v['limits'])->getAll();
					foreach ($data[$v['spell']] as $k=>$v2) {
						if($v2['input_time']-$time>=0){
							$data[$v['spell']][$k]['today']=true;
						}
					}
					if($v['hot']==1){
						$data[$v['spell']]['hot']=$this->model('news_content')->select('id,title,content,input_time,type')->where($where.' and cate_id='.$v['cate_id'].' and hot=1')->order('input_time desc,sort_order desc')->getRow();
						$data[$v['spell']]['hot']['content']=mb_substr(strip_tags($data[$v['spell']]['hot']['content']),0,$num,'utf-8').'...';
					}
				}
				$cache->set($name,$data,86400);	
			}			
			return $data;
		}

		//通过类型和分类来获取列表页文章
		public function getList($type,$cate_id,$page=1,$page_size=0){
			if($cate_id==22){
				$where='cate_id in (22,23,24,25,26,27,28,29,30,32)';
			}else{
				$where='cate_id ='.$cate_id;
			}

			if($type!='public'){
				$where.=' and type in ("'.$type.'","public")';	
			}
			$this->model('news_content')->where($where)->select('id,title,content,cate_id,author,input_time,type')->order('input_time desc,sort_order desc');
			if($page&&$page_size){
				return $this->page($page,$page_size)->getPage();
			}elseif ($page_size) {
				$this->limit($page_size);
			}
			return $this->getAll();
		}
		/**
		 * 通过类型和分类来获取列表页文章
		 * @Author   zhanpeng
		 * @DateTime 2016-11-4 09:50:04
		 *
		 * @return   [type]                   [description]
		 */
		//通过类型和分类来获取列表页文章
		public function getqAppList($type,$cate_id,$page=1,$page_size=0){
			if($cate_id==22){
				$where='cate_id in (22,23,24,25,26,27,28,29,30,32)';
			}elseif($cate_id == 12){
				$where='cate_id in (13,14,15)';
			}else{
				$where='cate_id ='.$cate_id;
			}
			if($type!='public'){
				$where.=' and type in ("'.$type.'","public")';
			}
			$this->model('news_content')->where($where)->select('id,title,content,description,cate_id,author,input_time,type')->order('input_time desc,sort_order desc');
			if($page&&$page_size){
				return $this->page($page,$page_size)->getPage();
			}elseif ($page_size) {
				$this->limit($page_size);
			}
			return $this->getAll();
		}

		//通过id获取文章详情
		public function getNews($id){
			$cache= E('RedisCluster',APP_LIB.'class');
			$name='news_'.$id;
			$data=$cache->get($name);
			if (empty($data)) {
				$data=$this->model('news_content')->where('id='.$id)->getRow();
				//取出右键导航分类名称
				$data['cate_name']=$this->model('news_cate')->select('cate_name')->where('cate_id='.$data['cate_id'])->getOne();
				//取出上一篇和下一篇
				$data['lastOne']=$this->model('news_content')->where('cate_id='.$data['cate_id'].' and id <'.$id)->select('id,title')->order('id desc')->limit(1)->getRow();
				$data['nextOne']=$this->model('news_content')->where('cate_id='.$data['cate_id'].' and id >'.$id)->select('id,title')->order('id asc')->limit(1)->getRow();
				$cache->set($name,$data,86400);
			}
			
			//取出内链文章
			$result=$this->model('news_content')->query("select `id` from p2p_news_content where cate_id=".$data['cate_id']." and id != ".$id." order by rand() limit 10");
			while($re = mysql_fetch_row($result))
			{
			     $id_arr[]=$re[0];
			}
			$id_str=implode(',',$id_arr);
			$data['neilian']=$this->model('news_content')->where('id in ('.$id_str.')')->select('id,title')->getAll();			
			return $data;
		}

		//更新文章访问量
		public function updatePv($id){
			$pv_arr=$this->model('news_content')->where('id='.$id)->select('pv,true_pv')->getRow();
			$this->model('news_content')->where('id='.$id)->update(array('pv'=>($pv_arr['pv']+1),'true_pv'=>($pv_arr['true_pv']+1)));
			return $pv_arr['pv']+1;
		}
		/**
		 * 塑料圈app更新阅读量
		 * @Author   zhanpeng
		 * @DateTime 2016-11-4 11:32:19
		 * @Param
		 * @return
		 */
		public function updateqAppPv($id){
			//pv默认加200阅读量
			$this->model('news_content')->query('update p2p_news_content set pv=pv+1,true_pv=true_pv+1 where id='.$id);//刷一次算一次页面

		}

		//根据访问量进行排行
		public function charts($type,$cate_id,$keywords=''){
			if($keywords){
				$data=$this->model('news_content')->where('title like "%'.$keywords.'%" or content like "%'.$keywords.'%"')->select('id,title,content,cate_id,author,input_time,type,pv')->order('sort_order desc,pv  desc')->limit(10)->getAll();
			}else{
				if($cate_id==22 && $type!='pvc' && $type!='public'){
					$where='cate_id in (22,23,24,25,26,27,28,32)';
				}elseif($cate_id==22 && $type=='pvc'){
					$where='cate_id in (22,23,25,26,27,29,30,32)';
				}elseif($cate_id==22 && $type=='public'){
					$where='cate_id in (22,23,24,25,26,27,28,29,30,32)';
				}else{
					$where='cate_id ='.$cate_id;
				}
				
				if($type!='public'){
					$where.=' and type in ("'.$type.'","public")';	
				}
				//取出同一月份的10条数据用于排行
				$data=$this->model('news_content')->where($where." and DATE_FORMAT(FROM_UNIXTIME(`input_time`,'%Y-%m-%d'),'%Y%m')=DATE_FORMAT(CURDATE(),'%Y%m')")->select('id,title,cate_id,type')->order('sort_order desc,pv  desc')->limit(10)->getAll();
				if (count($data)<5) {
					$data=$this->model('news_content')->where($where." and DATE_FORMAT(FROM_UNIXTIME(`input_time`,'%Y-%m-%d'),'%Y')=DATE_FORMAT(CURDATE(),'%Y')")->select('id,title,cate_id,type')->order('sort_order desc,pv  desc')->limit(10)->getAll();
				}
			}
			//文章排行编号从1开始并进行补零
			foreach ($data as $key => $value) {
				$chartsData[sprintf("%02d",$key+1)]=$value;
				$chartsData[sprintf("%02d",$key+1)]['cate_name']=$this->model('news_cate')->select('cate_name')->where('cate_id="'.$value['cate_id'].'"')->getOne();
				//判断是否有前缀
					if(array_search($value['cate_id'], array(1,3,4,13,14,15))===false){
						$chartsData[sprintf("%02d",$key+1)]['prefix']=1;
					}
			}
			return $chartsData;
		}

		//根据关键词获取文章
		public function search($ids){
			$ids = implode(',', $ids);
			return $this->model('news_content')->where("id in (".$ids.")")->order('input_time desc')->getAll();
		}

		//官网首页5F调用数据
		public function getHomeNews(){
			$arr=array('早盘抢先看'=>'1,2','期刊报告'=>'13,14,15','期货资讯'=>21,'企业动态'=>9,'装置动态'=>11);
			$i=1;
			foreach ($arr as $k => $v) {
				$tmp[$k]=$this->model('news_content')->select('id,title,cate_id,type,input_time')->where('cate_id in ('.$v.')')->order('input_time desc')->limit(10)->getAll();
				foreach ($tmp[$k] as $k2 => $v2) {
					$tmp[$k][$k2]['spell']=$this->model('news_cate')->select('spell')->where('cate_id='.$v2['cate_id'])->getOne();
				}
				$tmp[$k]=array_chunk($tmp[$k], 5);
				$tmp[$k]['num']=$i;
				$i++;

			}
			return $tmp;
		}
	}
 ?>