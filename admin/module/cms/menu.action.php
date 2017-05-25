<?php
/*
 * 
*/

class menuAction extends adminBaseAction {
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
	 * 菜单设置	
	 * @access public 
	 * @return html
	 */
	public function setmenu(){
		//抓取menu列表
		$menu_list = M("system:wxMenu")->getMenuList();
		
		$menu = array();
		foreach($menu_list['data'] as $k=>$v){
			$v['menu_string']  = json_decode($v['menu_string'],true);
			if($v['mark']==1){
				if($v['level']==1){
					$menu1['main'] = $v;
				}else if($v['level']==2){
					$menu1[$v['id']] = $v;
				}
			}else if($v['mark']==2){
				if($v['level']==1){
					$menu2['main'] = $v;
				}else if($v['level']==2){
					$menu2[$v['id']] = $v;
				}
			}else if($v['mark']==3){
				if($v['level']==1){
					$menu3['main'] = $v;
				}else if($v['level']==2){
					$menu3[$v['id']] = $v;
				}
			}
		}
		
		$this->menu1 = $menu1;
		$this->menu2 = $menu2;
		$this->menu3 = $menu3;
		
		$this->assign('appid',$this->appid);			
		$this->assign('page_title','菜单设置');
		$this->display('cms/menu/menu.set.html');
	}
	
	/**
	 * 编辑事件	
	 * @access public 
	 * @return html
	 */
	public function addEvent(){
		$mark = sget('mark','i');
		$level = sget('level','i');
		$name = sget('name','s');
		$id = sget('id','i');
		
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
		$this->assign('name',$name);
		$this->assign('level',$level);
		$this->assign('mark',$mark);
		$this->assign('id',$id);
		$this->assign('page_title','事件设置');
		$this->display('cms/menu/addevent.html');
	}
	
	/**
	 * 新增/重命名菜单	
	 * @access public 
	 */
	public function addmname(){
		$id   = sget('id','s');
		$name = sget('name','s');
		$mark = sget('mark','s');
		$level = sget('level','s');
		$type = sget('type','s');
		$keyname = sget('keyname','s');
		
		if($id){
			$item = M("system:wxMenu")->getMenuDetail($id);
			$menu_str = json_decode($item['menu_string'],true);
			$this->mString = $menu_str;
		}
		
		$this->assign('keyname',$keyname?$keyname:$item['keyname']);
		$this->assign('type',$type?$type:$item['type']);
		$this->assign('name',$name?$name:$item['name']);
		
		$this->assign('mark',$mark);
		$this->assign('level',$level);
		$this->assign('id',$id);
		
		$this->assign('page_title','管理菜单模板');
		$this->display('cms/menu/addmname.html');
	}
	
	/**
	 * 菜单管理	
	 * @access public 
	 * @return html
	 */
	public function mmenu(){
		$this->assign('page_title','管理菜单模板');
		$this->display('cms/menu/manger.menu.html');
	}
	
	/**
	 * 访问次数统计	
	 * @access public 
	 * @return html
	 */
	public function access(){
		$this->assign('page_title','访问统计');
		$this->display('cms/menu/menu.access.html');
	}
	
	/**
	 * 菜单预览	
	 * @access public 
	 * @return html
	 */
	public function viewmenu(){
		//抓取menu列表
		$menu_list = M("system:wxMenu")->getMenuList();
		
		$menu = array();
		foreach($menu_list['data'] as $k=>$v){
			$v['menu_string']  = json_decode($v['menu_string'],true);
			if($v['mark']==1){
				if($v['level']==1){
					$menu1['main'] = $v;
				}else if($v['level']==2){
					$menu1[$v['id']] = $v;
				}
			}else if($v['mark']==2){
				if($v['level']==1){
					$menu2['main'] = $v;
				}else if($v['level']==2){
					$menu2[$v['id']] = $v;
				}
			}else if($v['mark']==3){
				if($v['level']==1){
					$menu3['main'] = $v;
				}else if($v['level']==2){
					$menu3[$v['id']] = $v;
				}
			}
		}
		
		$menu = array('m1'=>$menu1,'m2'=>$menu2,'m3'=>$menu3);
		$this->menu = $menu;
		
		$this->assign('appid',$this->appid);			
		$this->assign('page_title','菜单预览');
		$this->display('cms/menu/menu.view.html');
	}
	/**
	 * 菜单添加/编辑	
	 * @access public 
	 * @return html
	 */
	public function addmenu(){
		$id = sget("id",'s');
		
		$temples = M("system:wxMenu")->getMenuDetail($id);
		$info = $temples;
		// 回复文本字段
		$info['menu_string'] = json_decode($temples['menu_string'],true);
		
		$this->info = $info;
		$this->assign('id',$id);
		$this->display('cms/menu/addmenu.html');
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
				$v['desc'] 		      = $tmpArr['desc'];
				$v['template_string'] = $tmpArr;
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