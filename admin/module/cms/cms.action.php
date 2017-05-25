<?php
/*
 * 
*/

class cmsAction extends adminBaseAction {
	private $wap_url='';
	private $img_url='';
	private $appid=''; //
	private $secret='';
	private $sub_appid='';
	private $sub_secret ='';
	public function __init(){
		$this->wap_url=C('wap_url');
		$this->img_url=C('TEMP_REPLACE.__IMG__');
		$setting = M('system:setting')->get('weixin');
		$this->appid=$setting['appid'];
		$this->secret=$setting['secret'];
		$this->sub_appid  = $setting['serve_appid'];
		$this->sub_secret = $setting['serve_secret'];
	}
	
	/**
	 * 设置自动回复	
	 * @access public 
	 * @return html
	 */
	public function setreply(){
		$page = sget('page','i',1);
		$size = sget('size','i',20);
		
		// 获取文本列表数据
		$tlist = $this->getTxtList($page-1,$size);
		// 获取图文列表数据	
		$nlist = $this->getNewsList($page-1,$size);
		//print_r($tlist);
		if($tlist['total']>$t_size){
			$tpages=pages($tlist['total'], $page, $size);
			$this->assign('tpages',$tpages);
		}
		
		if($nlist['total']>$n_size){
			$npages=pages($nlist['total'], $page, $size);
			$this->assign('npages',$npages);
		}
		$this->tlist = $tlist['data'];
		$this->lists = $nlist['data'];
		
		$this->assign('page_title','自动回复设置');
		$this->display('cms/reply.set.html');
	}
	
	/**
	 * 自动回复管理	
	 * @access public 
	 * @return html
	 */
	public function manger(){
		$type = sget('type','s');
		
		//获取回复列表
		$result = M("system:wxCms")->getReplyList('',$page,$size);
		foreach($result['data'] as $k=>&$v){
			if(is_array(json_decode($v['input_string'],true))){
				$inputArr = json_decode($v['input_string'],true);
				$input_strs = "包含：".($inputArr['contain']==1?"是":"否")." | ";
				// 判断输入类型
				if($inputArr['txt']){
					$input_strs = $input_strs."关键字：“".$inputArr['txt']."”";
				}else{
					// 正则表达式的构造
					$input_reg  = $inputArr['regexp'];
					// 数字
					if($input_reg['num']){
						$input_strs = $input_strs."数字：".($input_reg['num']=="rnd"?"随机":$input_reg['num'])."位，";
					}
					// 字母
					if($input_reg['wrd']){
						$input_strs = $input_strs."字母：".($input_reg['wrd']=="rnd"?"随机":$input_reg['wrd'])."位，";
					}
					// 文本
					if($input_reg['wrd']){
						$input_strs = $input_strs."文本：“".$input_reg['txt']."”";
					}
				}
				$v['input_string'] = $input_strs;
			}
			$reply_str = array();
			// 回复的json字符串解析
			if(is_array(json_decode($v['reply_string'],true))){
				$v['reply_string'] = json_decode($v['reply_string'],true);
			}else{
				$replyArr = "";
				$replyArr = $v['reply_string'];
				
				$temples = M("system:wxCms")->getTempleDetail($replyArr);
				$count = $temples['count'];
				
				foreach($temples['data'] as $key=>$tmp){
					$reply_str[] = json_decode($tmp['template_string'],true);
					// 模板类型1：文本；2：图文
					$tmp_type = $v['reply_type'];
				}
				
				// 将模板内容解析出来
				$tmp_str = $this->formateTemp($reply_str,$v['reply_type']);
				if($tmp_str){
					$v['reply_string'] = $tmp_str;
				}
			}
			
			$v['create_time']  = date("Y-m-d",$v['create_time']);
			$v['expire_time']  = date("Y-m-d",$v['expire_time']);
			if($v['input_type']==1){
				$v['input_type'] = "文本";
			}else if($v['input_type']==2){
				$v['input_type'] = "正则表达式";
			}else if($v['input_type']==3){
				$v['input_type'] = "图片";
				$v['input_string'] = "image";
			}
			$v['reply_type']   = ($v['reply_type']==1?"文本":"图文");
			$rlist[] = $v;
		}
		
		// 获取文本列表数据
		$tlist = $this->getTxtList();
		// 获取图文列表数据	
		$nlist = $this->getNewsList();
			
		$this->tlist  = $tlist;
		$this->nlist  = $nlist;
		$this->rlist  = $rlist;
		$this->assign('page_title','管理自动回复');
		$this->display('cms/reply.manger.html');
	}
	
	/**
	 * 图文模板	
	 * @access public 
	 * @return html
	 */
	public function mnews(){
		$this->assign('page_title','管理图文模板');
		$this->display('cms/manger.news.html');
	}
	
	/**
	 * 文本模板	
	 * @access public 
	 * @return html
	 */
	public function mtext(){
		$this->assign('page_title','管理文本模板');
		$this->display('cms/manger.text.html');
	}
	
	/**
	 * 添加图文回复	
	 * @access public 
	 * @return html
	 */
	public function addnews(){
		$id = sget("id",'s');
		
		$temples = M("system:wxCms")->getTempleDetail($id);
		$info    = $temples['data'][0];
		
		$info    = json_decode($info['template_string'],true);
		$info['mtitle'] = $temples['data'][0]['title'];
		
		$this->info = $info;
		$this->assign('id',$id);
		$this->display('cms/addnews.html');
	}
	/**
	 * 添加文本回复	
	 * @access public 
	 * @return html
	 */
	public function addtxt(){
		$id = sget("id",'s');
		
		$temples = M("system:wxCms")->getTempleDetail($id);
		$arr = $temples['data'][0];
		// 回复文本字段
		$info = json_decode($arr['template_string'],true);
		$info['title'] = $arr['title'];
		$this->info = $info;
		$this->assign('id',$id);
		$this->display('cms/addtext.html');
	}
	
	/**
	 * 编辑回复	
	 * @access public 
	 * @return html
	 */
	public function editReply(){
		$id 	= sget("id",'s');
		$reply  = M("system:wxCms")->getReplyDetail($id);
		$arr  = $reply['data'][0];
		$info = $arr;
		$info['create_time']  = date("Y-m-d",$info['create_time']);
		$info['expire_time']  = date("Y-m-d",$info['expire_time']);
		//转化输入类型
		$info['input_string'] = json_decode($arr['input_string'],true);
		// 输出模板
		$info['reply_string'] = explode(",",$arr['reply_string']);
		// 多个模板
		if(count($info['reply_string'])>1){
			$info['rnd'] = "rnd";
		}
		
		$page = sget('page','i',1);
		$size = sget('size','i',20);
		
		// 获取文本列表数据
		$tlist = $this->getTxtList($page-1,$size);
		// 获取图文列表数据	
		$nlist = $this->getNewsList($page-1,$size);
		//print_r($tlist);
		if($tlist['total']>$t_size){
			$tpages=pages($tlist['total'], $page, $size);
			$this->assign('tpages',$tpages);
		}
		
		if($nlist['total']>$n_size){
			$npages=pages($nlist['total'], $page, $size);
			$this->assign('npages',$npages);
		}
		$this->tlist = $tlist['data'];
		$this->lists = $nlist['data'];
		
		$this->info = $info;	
		$this->display('cms/reply.edit.html');
	}
	// 获取模板信息
	private function getTxtTemp(){
		$data =$this->getTxtList();
		
		$temp =array();
		foreach($data as $k=>$v){
			$item['title'] = $v['title'];
			$item['id'] = $v['id'];
			$item['template_type'] = $v['template_type'];
			$item['template_string'] = json_decode($v['template_string'],true);
			$temp[] = $item;
		}
		return $temp;
	}
	
	// 获取文本回复列表
	private function getTxtList($page=0,$size=20){
		$txt_list = M("system:wxCms")->getTempleList(1,$page,$size);
		foreach($txt_list['data'] as $k=>&$v){
			if(is_array(json_decode($v['template_string'],true))){
				$linkArr = json_decode($v['template_string'],true);
				$link_str  = "";
				$reply_str = $linkArr['reply'];
				
				if(count($linkArr['link'])>0){
					foreach ($linkArr['link'] as $link=>&$val){
						$link_str = $link_str."<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>";
						// 为文本替换链接
						$reply_str = str_replace($val['wrd'],"<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>",$reply_str);
					}
				}
				$v['link'] = $link_str;
				$v['reply'] = $reply_str;
			}
			$v['create_time']     = date("Y-m-d",$v['create_time']);
			$tlist[] = $v;
		}
		return array('total'=>$txt_list['count'],'data'=>$tlist);
	}
	
	private function getNewsList($page=0,$size=20){
		// 获取图文列表
		$news_list = M("system:wxCms")->getTempleList(2,$page,$size);
		foreach($news_list['data'] as $k=>&$v){
			$tmpArr = "";
			if(json_decode($v['template_string'],true)){
				$tmpArr 			  = json_decode($v['template_string'],true);
				$v['desc'] 		  = $tmpArr['desc'];
				$v['template_string']  = $tmpArr;
				$v['views']		  = "<span class='fl_left'><a href='".$tmpArr['link']."' class='cblue'><img src='".$tmpArr['imgurl']."' width='100px' height='50px'/><br/>".$tmpArr['title']."</a>";
			}
			$v['create_time']     =  date("Y-m-d",$v['create_time']);
			$nlist[] = $v;
		}
		return array('total'=>$news_list['count'],'data'=>$nlist);
	}
	
	// 解析模板内容
	private function formateTemp($tmp=array(),$type=''){
		// 模板类型1：文本；2：图文
		if(empty($type)){
			return false;
		}
		
		if($type==1){
			$reply_str = "";
			foreach($tmp as $k=>&$v){
				$link_str  = "";
				$r_str = $v['reply'];
				 
				if(count($v['link'])>0){
					foreach ($v['link'] as $link=>&$val){
						$link_str = $link_str."<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>";
						// 为文本替换链接
						$r_str = str_replace($val['wrd'],"<a href='".$val['url']."' class='cblue'>".$val['wrd']."</a>",$r_str);
					}
				}
				
				
				$v['link']  = $link_str;
				$v['reply'] = $r_str;
			}
				
			$tlist    = $reply_str;		
			return $tlist;
		}else if($type==2){
			foreach($tmp as $k=>&$v){
				$str 	 = "<a href='".$v['link']."'><img src='".$v['imgurl']."' width='100px' height='50px'><br/>".$v['title']."</a>";
				$nlist[] = $str;
			}
			return $nlist;
		}
		return false;
	}
}

?>