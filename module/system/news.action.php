<?php 
	class newsAction extends adminBaseAction {
		public function __init(){
			$this->db=M('public:common');
			$this->action=ROUTE_A;
			$this->cate=$this->db->model('news_cate')->select('cate_id,cate_name,pid,spell')->getAll();
			$this->cateTree=M('news:newsCate')->getTree($this->cate);
		}
		
		//列表页
		public function lst(){
			$action=sget('action','s');
			//准备where搜索条件
			$where=' 1 ';
				//判断分类查询
				if($cate_id=sget('cate_id','i')){
					$ids=M('news:newsCate')->getChildrensId($this->cate,$cate_id,true);
					$ids[]=$cate_id;
					$ids=implode(',', $ids);
					//添加条件
					$where.=' and cate_id in ('.$ids.')';
				}
				//判断状态是否可用
				if($status=sget('status','i')){
					if($status>0){
						$where.=' and status ='.($status-1);
					}
				}
				//根据标题进行模糊判断
				$key_type=sget('key_type','s','title');
				$keyWords=sget('keyword','s');
				if($key_type && $keyWords){
					if($key_type=='title'){
						$where.=' and title like "%'.$keyWords.'%"';
					}elseif ($key_type=='id') {
						$where.=' and id = '.$keyWords;
					}
				}
			if($action=='grid'){
				//准备分页参数
				$pageIndex=sget('pageIndex','i',0);
				$pageSize=sget('pageSize','i',20);
				$sortField=sget('sortField','s','input_time');
				$sortOrder=sget('sortOrder','s','desc');
				//查询数据
				$data=$this->db->model('news_content')
						  ->where($where)
						  ->select('id,title,cate_id,status,hot,sort_order,true_pv,update_time,input_time,admin_name')
						  ->page($pageIndex+1,$pageSize)
						  ->order("$sortField $sortOrder")
						  ->getPage();
				foreach ($data['data'] as $key => $value) {
					$data['data'][$key]['update_time']=date('Y-m-d H:i',$value['update_time']);
				}
				$list=array('total'=>$data['count'],'data'=>$data['data']);
				$this->json_output($list);
			}
			
			$this->display('news_list');
		}

		//添加新闻
		public function info(){
			$this->newsType=array(array('valueField'=>'public','textField'=>'公共文章'),array('valueField'=>'pe','textField'=>'PE文章'),array('valueField'=>'pp','textField'=>'PP文章'),array('valueField'=>'pvc','textField'=>'PVC文章'),array('valueField'=>'vip','textField'=>'VIP文章'));
			$this->is_ajax=true;
			$edit_id=sget('edit_id','i');
			if($edit_id>0){
				//p($edit_id);exit;
				$info=$this->db->model('news_content')->wherePk($edit_id)->getRow();
				$this->info=sstripslashes($info);
				//p($this->info);exit;
				$cate_ids=M('news:newsCate')->getParents($this->info);
				$this->cate_ids=array_reverse($cate_ids);
			}
			$action=sget('action','s');
			if($action=='info'){
				$info=sget('info','a');
				//p($info);exit;
				$info['admin_name']=$_SESSION['name'];
				//获取分类id，最大值就是对应的分类id
				$info['cate_id']=max($info['cate_id']);
				if(empty($info['keywords'])){
					//去除描述里的标签和空格
					$keywords=strip_tags($info['content']);
					//正则去除图片
					$keywords=preg_replace('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', '', htmlspecialchars_decode($keywords));
					//引入分词类，获取关键词
					   $pscws = new PSCWS4();
					        $pscws->set_dict(APP_LIB.'class/keyword/lib/dict.utf8.xdb');
					        $pscws->set_rule(APP_LIB.'class/keyword/lib/rules.utf8.ini');
					        $pscws->set_ignore(true);
					        $pscws->send_text($keywords);
					        $words = $pscws->get_tops(10);
					        $tags = array();
					        foreach ($words as $val) {
					            $tags[] = $val['word'];
					        }
					        $tags=implode(',', $tags);
					        $pscws->close();     
					    $info['keywords'] = $tags;
					    if(empty($info['description'])){
					    	$tmp=str_replace(array(" ","　","\t","\n","\r"),'',strip_tags($keywords));
						$info['description'] = mb_substr($tmp, 0,100,'utf-8');
					    }
					    
				}else{
					if(empty($info['description'])){
						$tmp=str_replace(array(" ","　","\t","\n","\r"),'',strip_tags($info['content']));
						$info['description'] = mb_substr($tmp, 0,100,'utf-8');
					}
				}
				$info['content']=str_replace('font-family:', '', $info['content']);
				
				$data=saddslashes($info);
				//获取id，用来判断是更新数据还是添加数据
				$id=sget('id','i');
				$repeat=sget('repeat','i');
				if($id>0){
					$data['update_time']=CORE_TIME;
					$result=$this->db->model('news_content')->wherePk($id)->update($data);
				}else{	
					if($repeat<1){
						$data['content']=str_ireplace(array('我的塑料网','PE','PP','PVC'), array('<a target="_blank" href="http://www.myplas.com">我的塑料网</a>（www.myplas.com）','<a target="_blank" href="/pe.html">PE</a>','<a target="_blank" href="/pp.html">PP</a>','<a target="_blank" href="/pvc.html">PVC</a>'), $data['content']);
					}
					
					$data['pv']=rand(150,250);
					$data['input_time']=CORE_TIME;
					$data['update_time']=CORE_TIME;
					$result=$this->db->model('news_content')->add($data);
				}
				if($result){
					$this->success('操作成功');
				}else{
					$this->error('操作失败');
				}
			}
			
			$this->cate=$this->db->model('news_cate')->select('cate_id,cate_name,pid')->where('pid=0')->getAll();
			$this->display('news_info');
		}
		
		//根据父级分类得到子分类
		public function getCate(){
			$pid=sget('pid','i');
			$cate=$this->db->model('news_cate')->select('cate_id,cate_name,pid')->where('pid='.$pid)->getAll();
			echo json_encode($cate);
		}

		//删除新闻
		public function del(){
			$this->is_ajax=true;
			$ids=spost('ids','s');
			if($ids==''){
				$this->error('操作有误');
			}
			$result=$this->db->model('news_content')->where('id in ('.$ids.')')->delete();
			if($result){
				$this->success('操作成功！');
			}else{
				$this->error('数据处理失败！');
			}
		}
		
		//保存行内修改数据
		public function save(){
			$this->is_ajax=true;
			$data=sdata();
			if(empty($data)){
				$this->error('操作有误');
			}
			$sql=array();
			foreach ($data as $v) {
				$update_data=array(
					'title'=>$v['title'],
					'cate_id'=>$v['cate_id'],
					'hot'=>$v['hot'],
					'status'=>$v['status'],
					'sort_order'=>$v['sort_order'],
					'update_time'=>CORE_TIME,
					'admin_name'=>$_SESSION['name'],
				);
				$sql[]=$this->db->model('news_content')->wherePk($v['id'])->updateSql($update_data);
			}
			if(empty($sql)){
				$this->error('操作有误！');
			}
			$result=$this->db->commitTrans($sql);
			if($result){
				$this->success('操作成功！');
			}else{
				$this->error('数据处理失败！');
			}
		}
	}
 ?>