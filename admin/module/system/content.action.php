<?php
/** 
 * 内容管理
 */
class contentAction extends adminBaseAction {
	private $title='内容管理'; //
	private $hasCate=0; //是否有分类
	private $hasPrice=0; //是否有价格
	private $extField=''; //额外字段
	public function __init(){
		$this->debug = false;
		$this->doact = sget('do','s');
		$this->db=M('public:common');
		$this->assign('action',ROUTE_A); //当前方法

		$cache=cache::startMemcache();
		$cache->delete('article_index');//清除首页资讯缓存

	}
	
	/**
	 * 资讯管理
	 * @access public 
	 * @return html
	 */
	public function _null($spell){
		$this->title='资讯管理';
		$this->hasCate=1; //分类
		$this->db=M('public:common')->model('info');
		if($spell && $spell != 'info') $this->cate_id = M('system:cate')->getfieldbyspell($spell,'cate_id');
		$this->_list();
	}

	/**
	 * 内容列表
	 * @access private 
	 * @return html
	 */
	private function _list(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}elseif($action=='info'){ //获取详情
			$this->_info();exit;
		}elseif($action=='submit'){ //提交内容
			$this->_submit();exit;
		}elseif($action=='remove'){ //删除
			$this->_remove();exit;
		}elseif($action=='save'){ //编辑行内数据
			$action=sget('action','s');
			$this->_save();exit;
		}elseif($action=='chkUnique'){ //检查重复
			$this->_chkUnique();exit;
		}
		
		$this->assign('page_title',$this->title);
		if($this->hasCate>0){
			$this->assign('cate',M('system:cate')->getTree($this->hasCate));
		}
		$this->display('content_list.html');
	}
	
	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
	
		//搜索条件
		$where=" 1 ";
		$_field=''; //额外字段
		if($this->hasCate>0){
			$_field.=',cate_id';
			$cate_id=sget('cate_id',0);
			if($cate_id>0){
				$cate_ids = M('system:cate')->select('cate_id')->where('pid='.$cate_id)->getCol();
				$cate_ids[] = $cate_id;
				$where.=' and cate_id IN ('.implode(',',$cate_ids).')';	
			}
		}
		$_field.=$this->hasPrice>0 ? ',price' : '';
		$_field.=$this->extField;
		if($this->doact=='search'){
			$_field.=',img,link_url as url';
		}
		
		//状态
		$status=sget('status',0);
		if($status>0){
			$where.=' and status='.($status-1);	
		}

		//关键词
		$key_type=sget('key_type','s','title');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type=='title'){
				$where.=" and $key_type like '%$keyword%' ";
			}else{
				$where.=" and $key_type='$keyword' ";
			}
		}

		$list=$this->db->select('id,title,sn,update_time,status,sort_order,hot'.$_field)
					->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}

	/**
	 * Ajax获取详情
	 * @access private 
	 * @return html
	 */
	private function _info(){
		$this->is_ajax=true;
		$this->assign('mini_list',0);
		
		if($this->hasCate>0){ //需要分类树
			$this->cate=M('system:cate')->getCates($this->hasCate,sget('cate_id','i',0));
		}
		
		$id=sget('id','i');
		if($id>0){
			$info=$this->db->wherePk($id)->getRow();
			if(empty($info)){
				$this->error('错误的数据请求');	
			}
			$info['input_time']=$info['input_time']>1000 ? date("Y-m-d H:i:s",$info['input_time']) : '-';
			$info['update_time']=$info['update_time']>1000 ? date("Y-m-d H:i:s",$info['update_time']) : '-';
		}else{
			$info=array('code_type'=>1,'info'=>0, 'cate_id'=>sget('cate_id','i'));
		}
		$this->assign('info',sstripslashes($info)); 
		
		$this->assign('page_title',$this->title);
		$this->display('content_info.html');
	}

	/**
	 * Ajax提交详情数据
	 * @access private 
	 * @return html
	 */
	private function _submit() {
		$this->is_ajax=true;
		$id=sget('id','i'); //产品ID
		$_info=sget('info','a');
		$_info['sn']=preg_replace('/[^\w|.|-]/','',$_info['sn']); //过滤非法字符:a-Z0-9.-_
		if(strlen($_info['title'])<3){
			$this->error('请输入标题');	
		}
		
		//是否存在
		$where="  1 ";
		if($this->hasPrice>0){
			$_info['price']=(float)$_info['price'];	
			$_info['stock']=(float)$_info['stock'];	
		}
		
		$_info['admin_name']=$_SESSION['name'];
		if(empty($_info['keywords'])){
			//去除描述里的标签和空格
			$keywords=strip_tags($_info['content']);
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
			    $_info['keywords'] = $tags;
			if(empty($_info['description'])){
				$_info['description'] = mb_substr(strip_tags($keywords), 0,200);
			}
		}else{
			if(empty($_info['description'])){
				$_info['description'] = mb_substr(strip_tags($_info['content']), 0,200);
			}			
		}
		$_data=saddslashes($_info);
		//更新或新增商品数据
		if($id>0){
			$_data['update_time']=CORE_TIME;
			$this->db->wherePk($id)->update($_data);	
		}else{
			$_data['input_time']=$_data['update_time']=CORE_TIME;
			$this->db->add($_data);
		}
		$this->success('操作成功');
	}
	
	/**
	 * Ajax删除节点s
	 * @access private 
	 */
	private function _remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 编辑已存在的数据
	 * @access public 
	 * @return html
	 */
	private function _save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$sql=array();
		if(empty($data)){
			$this->error('操作数据为空');
		}
		foreach($data as $k=>$v){
			$_data=array(
				'title'=>$v['title'],		 
				'status'=>(int)$v['status'],
				'hot'=>(int)$v['hot'],			 
				'sort_order'=>(int)$v['sort_order'],
				'update_time'=>CORE_TIME,
				'admin_name'=>$_SESSION['name'],
			);
			$sql[]=$this->db->wherePk($v['id'])->updateSql($_data);
		}
		
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 检查重复
	 * @access private 
	 * @return html
	 */
	private function _chkUnique() {
		$this->is_ajax=true;
		$id=sget('id','i'); //产品ID
		$type=sget('type','s','sn');
		$value=sget('value','s');
				
		//是否存在
		$where=" $type='$value'";
		if($id>0){
			$where.=" and id!='$id'";
		}
		
		$exist=$this->db->select('id')->where($where)->getOne();
		if($exist>0){
			$this->error('err');	
		}
		$this->success('OK');
	}
}
?>
