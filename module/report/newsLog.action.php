<?php 
	/**
	* 塑料头条行情内参访问日志
	*/
	class newsLogAction extends adminBaseAction{
		/**
		 * 初始化变量
		 */
		public function __init(){
			$this->db=M('public:common')->model('log_news');
		}

		/**
		 * 访问日志列表
		 */
		public function init(){
			$action=sget('action','s');
			if ($action=='grid') {
				$page = sget("pageIndex",'i',0); //页码
				$size = sget("pageSize",'i',20); //每页数
				$sortField = sget("sortField",'s','a.id'); //排序字段
				$sortOrder = sget("sortOrder",'s','desc'); //排序
				//搜索条件
				$where="1 ";
				//交易时间筛选
				$where.=getTimeFilter('a.input_time');
				//关键词
				$key_type=sget('key_type','s','name');
				$keyword=sget('keyword','s');
				if(!empty($keyword)){
					if($key_type=='name'){
						$where.=" and c.name='$keyword' ";	
					}else{
						$where.=" and c.mobile='$keyword' ";	
					}
				}
				$data=$this->db->from('log_news a')
						  ->leftjoin('customer_contact c','a.user_id=c.user_id')
						  ->leftjoin('news_content n','a.news_id=n.id')
						  ->select('a.id,a.input_time,c.name,c.mobile,n.title,n.cate_id')
						  ->where($where)
						  ->page($page+1,$size)
						  ->order("$sortField $sortOrder")
						  ->getPage();
				foreach ($data['data'] as $k => $v) {
					$data['data'][$k]['cate_name']=$this->db->model('news_cate')->wherePk($v['cate_id'])->select('cate_name')->getOne();
					$data['data'][$k]['input_time']=date("Y-m-d H:i:s",$v['input_time']);
				}
				$list=array('total'=>$data['count'],'data'=>$data['data']);
				$this->json_output($list);
			}
			$this->assign('page_title','头条内参检测');
			$this->display('logNews.html');
		}
		
	}
 ?>