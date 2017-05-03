<?php


class plasticzoneActivityAction extends adminBaseAction{
    protected $goodsName,$goodsCate,$pointsType,$shareType;
    public function __init(){
        $this->debug = false;
        $this->goodsName = M('public:common')->model('points_goods')->select("id,name")->getAll();
        $this->goodsName = arrayKeyValues($this->goodsName,'id','name');

        $this->pointsType = L('points_type');
        $this->shareType = L('share_type');

        $this->goodsName[999] = '-';
        $this->shareType[999] = '-';
        $this->pointsType[999] = '-';

        $this->assign('goods_name',$this->goodsName);//商品名称
        $this->assign('goods_cate',L('goods_category')); //商品分类
        $this->assign('points_type',$this->pointsType);//积分来源
        $this->assign('share_type', $this->shareType);//分享来源
        $this->assign('startTime', date('Y-m-d',time())); //开始时间
        $this->assign('endTime',date('Y-m-d',time())); //结束时间

        $this->db=M('public:common')->model('points_bill');
        $this->doact = sget('do','s');

    }


    /**
     * 塑豆消耗列表
     * @access public
     * @return html
     */
    public function moneyExchange(){
        $action=sget('action','s');
        if($action=='grid'){ //获取列表
            $this->_grid();exit;
            // }elseif($action=='remove'){ //删除列表数据
            // 	$this->_remove();exit;
            // }elseif($action=='save'){ //获取列表
            // 	$this->_save();exit;
        }
        $this->assign('page_title','塑豆增加或减少');
        $this->display('money_exchange.html');
    }


    /**
     * Ajax获取列表内容
     * @access private
     * @return html
     */
    private function _grid(){
        $page = sget("pageIndex",'i',0); //页码
        $size = sget("pageSize",'i',20); //每页数
        $sortField = sget("sortField",'s','addtime'); //排序字段
        $sortOrder = sget("sortOrder",'s','desc'); //排序
        //搜索条件
        $where=" 1 ";
        $status=sget('status','i');//状态
        $c_id=sget('cate_id','i');//商品分类id
        $key_type = sget('key_type','s');//关键字分类
        $keyword=sget('keyword','s');//关键词
        $customer_type = sget('customer_type','s');//客户分类
        $sTime = sget("sTime",'s','addtime'); //搜索时间类型
        $type = sget('type','i');  //积分来源
        $share_type = sget('share_type','i');//分享来源
        $gid = sget('gid','i'); //商品名称id

        if(!empty($type)) $where .= " and b.type = $type";
        if(!empty($share_type)) $where.=" and b.share_type = $share_type";
        if(!empty($gid)) $where.=" and b.gid=$gid";



        $where.=getTimeFilter($sTime); //时间筛选
        switch($key_type){
            case 'id':
                $keyword=(int)$keyword;
                $where.=" and b.uid=$keyword";
                break;
            case 'name':
                $where.=" and c.name like '%$keyword%'";
                break;
            case 'mobile':
                $where.=" and c.mobile like '%$keyword%'";
                break;
        }
        switch($customer_type){
            case 'all':
                break;
            case 'pc':
                $where.=" and b.is_mobile =0";
                break;
            case 'qapp':
                $where.=" and b.is_mobile =1";
                break;
        }
        $sql ="select sum(case when  b.points < 0 then b.points else 0 end) supply_num,
               sum(case when b.points > 0 then b.points else 0 end) achieve_num,
               count(DISTINCT b.uid) distinct_id
                from p2p_points_bill b left join p2p_customer_contact c on c.user_id = b.uid
                where $where
        ";
        $_tmpSome = $this->db->from('points_bill b')->getRow($sql);
        //$where.=" and addtime >".CORE_TIME;

        $list=$this->db->from("points_bill b")
            ->leftjoin('customer_contact c','c.user_id=b.uid')
            ->select('b.*,c.name as user_name,c.mobile')
            ->where($where)
            ->page($page+1,$size)
            ->order("$sortField $sortOrder")
            ->getPage();

        foreach($list['data'] as $k=>$v){
            $list['data'][$k]['addtime']=$v['addtime']>1000 ? date("Y-m-d H:i:s",$v['addtime']) : '-';
            $list['data'][$k]['gid'] = empty($v['gid'])?999:$v['gid'];
            $list['data'][$k]['share_type'] = empty($v['share_type'])?999:$v['share_type'];
            $list['data'][$k]['type'] = empty($v['type'])?999:$v['type'];
        }
        $msg="消耗塑豆：{$_tmpSome['supply_num']}&nbsp;&nbsp;&nbsp;获取塑豆：{$_tmpSome['achieve_num']}&nbsp;&nbsp;&nbsp;用户使用人数：{$_tmpSome['distinct_id']}&nbsp;&nbsp;&nbsp;用户使用次数：{$list['count']}&nbsp;";
        $result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
        $this->json_output($result);
    }

//    /**
//     * Ajax删除节点s
//     * @access private
//     */
//    public function remove(){
//        $this->is_ajax=true; //指定为Ajax输出
//        $ids=sget('ids','s');
//        if(empty($ids)){
//            $this->error('操作有误');
//        }
//        $result=$this->db->where("id in ($ids)")->delete();
//        if($result){
//            $this->success('操作成功');
//        }else{
//            $this->error('数据处理失败');
//        }
//    }

    /**
     * 编辑已存在的数据
     * @access public
     * @return html
     */
    public function save(){
        $this->is_ajax=true; //指定为Ajax输出
        $data = sdata(); //获取UI传递的参数
        $sql=array();
        if(empty($data)){
            $this->error('操作数据为空');
        }
        if(($this->doact)=='add'){
            $points = M ('system:setting')->get ('points')['points']; //这个是加了缓存的
            $data['cash'] = (int)$data['cash'];
            $_tmpPoints =(int)$data['cash']*$points['ratio'];
            M ("qapp:pointsBill")->setMoblie(2);
            $result = M ("qapp:pointsBill")->addPoints ($_tmpPoints, $data['user_id'], 16);
            if($result){
                $this->success('操作成功');
            }else{
                $this->error('数据处理失败');
            }
        }
    }

    /**
     * 塑料圈banner设置
     * @access public
     * @return html
     */
    public function qapp_banner(){
        $db=$this->db->model('setting');

        $action=sget('action','s');
        if($_POST){
            /*			$qapp_newest_url=stripslashes($_POST['qapp_newest_url']);
                        $qapp_newest_tip=stripslashes($_POST['qapp_newest_tip']);
                        $qapp_newest_version=stripslashes($_POST['qapp_newest_version']);*/

            $_data = array(
                'qapp_banner'=>json_encode(array(
                    'start_time'=>strtotime($_POST['qapp_banner_start_time']),
                    'end_time'=>strtotime($_POST['qapp_banner_end_time']),
                    'url'=>$_POST['qapp_banner_url'],
                    'jump_url'=>$_POST['qapp_banner_jump_url'],
                )),
                'qapp_cover'=>json_encode(array(
                    'start_time'=>strtotime($_POST['qapp_cover_start_time']),
                    'end_time'=>strtotime($_POST['qapp_cover_end_time']),
                    'url'=>$_POST['qapp_cover_url'],
                    'jump_url'=>$_POST['qapp_cover_jump_url'],
                ))
            );
            $db->execute("replace into ".$db->ftable." (code,value) values ('qapp_banner','".$_data['qapp_banner']."')");
            $db->execute("replace into ".$db->ftable." (code,value) values ('qapp_cover','".$_data['qapp_cover']."')");

            $this->clearMCache('setting');
            $this->success('更新成功');
        }

        $setting=M('system:setting')->getSetting();
        if(!empty($setting)){
            $qapp_banner=$setting['qapp_banner'];
            $qapp_banner['start_time']=date("Y-m-d H:i:s",$qapp_banner['start_time']);
            $qapp_banner['end_time']=date("Y-m-d H:i:s",$qapp_banner['end_time']);

            $qapp_cover=$setting['qapp_cover'];
            $qapp_cover['start_time']=date("Y-m-d H:i:s",$qapp_cover['start_time']);
            $qapp_cover['end_time']=date("Y-m-d H:i:s",$qapp_cover['end_time']);
        }else{
            $qapp_banner=array();
            $qapp_cover=array();
            $qapp_newest_version='';
        }
        $this->assign('qapp_banner',$qapp_banner);
        $this->assign('qapp_cover',$qapp_cover);
        $this->assign('page_title','塑料圈banner设置');
        $this->display('qapp_banner.html');
    }

    public function offTop(){
        $this->assign('page_title','置顶信息修改');
        $this->display('top_modify.html');
    }

    public function getTopList()
    {

        $where="pur_id is not null and outpu_time >".CORE_TIME;
        $goods_id=sget('goods_id','i');//商品id
        if($goods_id>0) $where.=" and goods_id = $goods_id";


        $page = sget("pageIndex",'i',0); //页码
        $size = sget("pageSize",'i',20); //每页数
        $sortField = sget("sortField",'s','outpu_time'); //排序字段
        $sortOrder = sget("sortOrder",'s','desc'); //排序



        $list = M("points:pointsOrder")->select('id,status,create_time,order_id,goods_id,uid,usepoints,outpu_time,num,pur_id')
                ->where($where)
                ->page($page+1,$size)
                ->order("$sortField $sortOrder")
                ->getPage();

        foreach($list['data'] as $k=>$v){
            $list['data'][$k]['create_time']=$v['create_time']>1000 ? date("Y-m-d H:i:s",$v['create_time']) : '-';
            $list['data'][$k]['goods_id'] = empty($v['goods_id'])?999:$v['goods_id'];
            $list['data'][$k]['outpu_time']=$v['create_time']>1000 ? date("Y-m-d H:i:s",$v['outpu_time']) : '-';
        }
        $result=array('total'=>$list['count'],'data'=>$list['data']);
        $this->json_output($result);


    }


    public function topSave(){
        $this->is_ajax=true; //指定为Ajax输出
        $data = sdata(); //获取UI传递的参数
        $sql=array();
        if(empty($data)){
            $this->error('操作数据为空');
        }
        $result=True;
        foreach($data as $row){
            $a= M("points:pointsOrder")->where("id = {$row['id']}")->update(array('status'=>$row['status'],'update_time'=>CORE_TIME));
            $result=$result&&$a;
        }
        if($result){
            $this->success('操作成功');
        }else{
            $this->error('数据处理失败');
        }
    }


    /**
     * 通讯录查看记录列表
     * @access public
     * @return html
     */
    public function getPlasticPersonList(){
        $action=sget('action','s');
        if($action=='grid'){ //获取列表
            $this->_grid2();exit;
            // }elseif($action=='remove'){ //删除列表数据
            // 	$this->_remove();exit;
            // }elseif($action=='save'){ //获取列表
            // 	$this->_save();exit;
        }
        $this->assign('page_title','通讯录查看记录列表');
        $this->display('plasticPerson.listRecord.html');
    }


    /**
     * Ajax获取列表内容
     * @access private
     * @return html
     */
    private function _grid2(){
        $page = sget("pageIndex",'i',0); //页码
        $size = sget("pageSize",'i',20); //每页数
        $sortField = sget("sortField",'s','input_time'); //排序字段
        $sortOrder = sget("sortOrder",'s','desc'); //排序
        //搜索条件
        $where=" 1 ";
        $key_type = sget('key_type','s');//关键字分类
        $keyword=sget('keyword','s');//关键词
        $sTime = sget("sTime",'s','b.input_time'); //搜索时间类型

        $where.=getTimeFilter($sTime); //时间筛选
        switch($key_type){
            case 'id':
                $keyword=(int)$keyword;
                $where.=" and b.user_id=$keyword";
                break;
            case 'name':
                $where.=" and c.name like '%$keyword%'";
                break;
        }

        $list=$this->db->from("info_list b")
            ->leftjoin('customer_contact c','c.user_id=b.user_id')
            ->leftjoin('customer_contact d','d.user_id=b.other_id')
            ->select('b.*,c.name as c_name,d.name as d_name')
            ->where($where)
            ->page($page+1,$size)
            ->order("$sortField $sortOrder")
            ->getPage();
        foreach($list['data'] as $k=>$v){
            $list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
        }
        $result=array('total'=>$list['count'],'data'=>$list['data']);
        $this->json_output($result);
    }






}