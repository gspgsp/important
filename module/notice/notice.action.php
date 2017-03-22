<?php

    /**
     * 内部公告
     */
    class noticeAction extends adminBaseAction  {
        public function __init()
        {   
            $this->debug = false;
            $this->db = M('public:common')->model('in_notice');
            $this->doact = sget('do', 's');
        }

        /**
         * @access public
         * @return html
         */
        public function init()
        {
            $doact = sget('do', 's');
            $action = sget('action', 's');
            if ($action == 'grid') { //获取列表
                $this->_grid();
                exit;
            }
            $this->assign('doact', $doact);
            $this->display('notice.list.html');
        }

        /**
         * Ajax获取列表内容
         * @return json
         */
        public function _grid()
        {
            $page = sget("pageIndex", 'i', 0); //页码
            $size = sget("pageSize", 'i', 20); //每页数
            $sortField = sget("sortField", 's', 'input_time'); //排序字段
            $sortOrder = sget("sortOrder", 's', 'desc'); //排序
            $where.= "1";
            // 筛选时间
            $where.=" and `status` >= 1 ";
            $sTime = sget("sTime",'s','input_time'); //搜索时间类型
            $where.=getTimeFilter($sTime); //时间筛选 
            $orderby = "$sortField $sortOrder , status desc ";
            $list = $this->db->where($where)->page($page + 1, $size)->order($orderby)->getPage();
            foreach ($list['data'] as $k => $val) {
                $list['data'][$k]['input_time'] = !empty($val['input_time']) ? date("Y-m-d H:i:s", $val['input_time']) : '-';
                $list['data'][$k]['update_time'] = !empty($val['update_time']) ? date("Y-m-d H:i:s", $val['update_time']) : '-';
            }
            $msg = "";
            $result = array('total' => $list['count'], 'data' => $list['data'], 'msg' => $msg);
            $this->json_output($result);
        }

        /**
         * 新增公告页面
         * @return html
         */
        public function add()
        {
            $this->assign('model', 1);
            $this->display('notice.add.html');
        }

        /**
         * 新增公告动作
         * @return json
         */
        public function save()
        {
            $title = spost('title', 's');
            $time = time();
            $path = spost('path', 's');
            $path_arr = explode('|', $path);
            $img = array();
            foreach ($path_arr as $info) {
                if (!empty($info)) {
                    $_tmp = explode('-', $info);
                    $img[$_tmp[0]] = $_tmp[1];
                }
            }
            $data = array('title' => $title, 'input_time' => $time,'update_time' => $time, 'input_admin'=>$_SESSION['name'],'update_admin'=>$_SESSION['name'],'path' => json_encode(array_values($img)), 'status' => 1);

            if (!$this->db->add($data)) {
                $this->error("添加失败");
            } else {

                $this->success("添加成功");
            }
        }
    /**
	 * 查看公告
	 * @access public 
	 * @return html
	 */
	public function info(){
	    $id=sget('id','i'); 
	    $list=$this->db->where('id='.$id)->select('path')->getOne();
        $list_path=json_decode($list,true);
        $images='';$a=1;
        if(count($list_path)%2==0){
        for($i=0;$i<count($list_path)/2;$i++){
            $images.='<tr>
						<td width="200px;"><img src="__UPLOAD__/'.$list_path[2*$i].'"  style="width:50%; height:50%; margin:0 20%; float:center;" ></td>
						<td width="200px;"><img src="__UPLOAD__/'.$list_path[2*$i+1].'"  style="width:50%; height:50%; margin:0 20%; float:center;" ></td>
					</tr>';        
        }
        }else{
            for($i=0;$i<(count($list_path)-1)/2;$i++){
                $images.='<tr>
						<td width="200px;"><img src="__UPLOAD__/'.$list_path[2*$i].'" style="width:50%; height:50%; margin:0 20%; float:center;" ></td>
						<td width="200px;"><img src="__UPLOAD__/'.$list_path[2*$i+1].'" style="width:50%; height:50%; margin:0 20%; float:center;" ></td>
					</tr>';
        }
        $images.='<tr>
						<td width="200px;"><img src="__UPLOAD__/'.$list_path[count($list_path)-1].'" style="width:50%; height:50%; margin:0 20%; float:center;" ></td>
					</tr>';
        }
        $this->assign('images',$images);
	    $this->display('notice.info.html');
	}
	/**
	 * 删除公告
	 * @access public	 
	 * @return html
	 */
	public function remove(){
	    $this->is_ajax=true; //指定为Ajax输出
	    $ids=sget('ids','s');
	    if(empty($ids)){
	        $this->error('操作有误');
	    }
	    $_data = array(
	        'status'=>0,
	        'update_admin'=>$_SESSION['name'],
	        'update_time'=>time()
	    );
	    $result=$this->db->where("id in (".$ids.")")->update($_data);
	    if($result){
	        $this->success('操作成功');
	    }else{
	        $this->error('删除操作失败');
	    }
	}
    }