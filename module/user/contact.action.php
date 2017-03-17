<?php
/**
 * 联系人信息管理
 */
class contactAction extends adminBaseAction {
    public function __init(){
        $this->debug = false;
        $this->assign('user_chanel',L('user_chanel'));
        $this->db=M('public:common')->model('customer_contact');
        $this->assign('status',L('contact_status'));// 联系人用户状态
        $this->doact = sget('do','s');
    }
    /**
     * 联系人列表
     * @access public
     * @return html
     */
    public function init(){
        $action=sget('action','s');
        if($action=='grid'){ //获取列表
            $this->_grid();exit;
        }elseif($action=='remove'){ //获取列表
            $this->_remove();exit;
        }
        $this->assign('page_title','资源库列表');
        $this->display('contact.list.html');
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
        $filte = sget('filte','i',0);
        //搜索条件
        $where=" status != 9 ";
        $c_id=sget('c_id','i',0);
        if($c_id !=0){
            $where.=" and `c_id` = $c_id ";
        }
        //筛选状态
        $status=sget('status','i',0);
        if($status !=0)  $where.=" and `status` =".$status;
        //筛选时间
        $sTime = sget("sTime",'s','input_time'); //搜索时间类型
        $where.=getTimeFilter($sTime); //时间筛选
        //关键词搜索
        $key_type=sget('key_type','s','name');
        $keyword=sget('keyword','s');
        if(!empty($keyword) && $key_type=='name' ){
            $where.=" and `$key_type`  like '%$keyword%' ";
        }elseif(!empty($keyword) && $key_type=='c_id'){
            $keyword=M('user:customer')->getLikeCidByCname($keyword);
            $where.=" and `$key_type`  in ('$keyword') ";
        }elseif(!empty($keyword)){
            $where.=" and `$key_type`  = '$keyword' ";
        }
        if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
            $sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
            $pools = M('user:customer')->getCidByPoolCus($_SESSION['adminid']); //共享客户
            $where .= " and `customer_manager` in ($sons) ";
            if(!empty($pools)){
                if($filte != 1){
                    $cids = explode(',', $pools);
                    $where .= " or `c_id` in ($pools)";
                }
            }
            if(!empty($cidshare)){
                $where .= " or `c_id` in ($cidshare)";
            }
        }
        $list=$this->db->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();
        foreach($list['data'] as $k=>$v){
            $list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
            $list['data'][$k]['depart']=C('depart')[$v['depart']];
            $list['data'][$k]['sex']=L('sex')[$v['sex']];
            $list['data'][$k]['is_default']=L('is_default')[$v['is_default']];
            $list['data'][$k]['name'] = in_array($v['c_id'],$cids) ? '******' : $v['name'];
            $list['data'][$k]['mobile'] = in_array($v['c_id'],$cids) ? '******' : $v['mobile'];
            $list['data'][$k]['c_id']= M('user:customer')->getColByName($v['c_id']);
            $list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
            $list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
            $list['data'][$k]['input_admin'] = M('rbac:adm')->getNameByUser($v['input_admin']);
        }

        $result=array('total'=>$list['count'],'data'=>$list['data']);
        $this->json_output($result);
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
        $data = explode(',',$ids);
        if(is_array($data)){
            foreach ($data as $k => $v) {
                $res = M('user:customer')->getColByName($v,"c_id","contact_id");
                if($res>0){
                    $this->error('主联系人不能删除');
                }
            }
        }
        $result=$this->db->where("user_id in ($ids)")->update(array('status'=>9));
        if($result){
            $this->success('操作成功');
        }else{
            $this->error('数据处理失败');
        }
    }

    public function info(){
        $this->is_ajax=true;
        $user_id=sget('id','i');
        if($user_id>0){
            $info=$this->db->wherePk($user_id)->getRow();
            if($info['c_id']>0) $c_name = M('user:customer')->getColByName("$info[c_id],c_name"); // 根据公司id查询公司名字
        }
        //联系人详情
        $this->assign('c_name',$c_name);
        $this->assign('info',$info);
        $this->assign('status',L('contact_status'));
        $this->assign('sex',L('sex'));
        $this->assign('page_title','联系人列表');
        $this->display('contact.edit.html');

    }

    /**
     * 查看联系人及相关信息
     *
     */
    public function viewInfo(){
        $this->is_ajax=true;
        $user_id=sget('id','i');
        if($user_id>0){
            $info=$this->db->wherePk($user_id)->getRow();
            $extra = $this->db->model('contact_info')->where("user_id = $user_id")->getRow();
            //关注牌号
            $models=$this->db->model('suggestion_model')->where("user_id=$user_id")->select('name')->getCol();
            $model['model']=(count($models)>1)?implode('、',$models):$models;
            $model['model']=implode('、',$models);
            $c_info = M('user:customer')->getInfoByUid("$user_id"); // 根据公司id查询公司名字
            if($c_info['origin']){
                $areaArr = explode('|', $c_info['origin']);
                $c_info['company_province'] = $areaArr[1];
                $c_info['company_city']=$areaArr[0];
            }
        }
        $meg = array_merge($info,$model);
        $c_info['file_url1'] = FILE_URL.'/upload/'.$c_info['file_url'];
        $c_info['business_licence_pic1'] = FILE_URL.'/upload/'.$c_info['business_licence_pic'];
        $c_info['organization_pic1'] = FILE_URL.'/upload/'.$c_info['organization_pic'];
        $c_info['tax_registration_pic1'] = FILE_URL.'/upload/'.$c_info['tax_registration_pic'];
        $c_info['legal_idcard_pic1'] = FILE_URL.'/upload/'.$c_info['legal_idcard_pic'];
        //联系人详情
        $this->assign('regionList', arrayKeyValues(M('system:region')->get_reg(),'id','name'));//第一级省市
        $this->assign('type',L('company_type'));//工厂类型
        $this->assign('level',L('company_level'));//客户类别
        $this->assign('chanel',L('company_chanel'));//客户渠道
        $this->assign('credit_level',L('credit_level'));//信用等级
        $this->assign('c_info',$c_info);
        $this->assign('res',$extra);
        $this->assign('info',$meg);
        $this->assign('status',L('contact_status'));
        $this->assign('cstatus',L('status'));
        $this->assign('sex',L('sex'));
        $this->assign('page_title','联系人列表');
        $this->display('contact.viewInfo.html');

    }
    /**
     * 查看联系人牌号以及公司信息
     * @author gsp <[<email address>]>
     * @return [type] [description]
     */
    public function viewModel(){
        $this->is_ajax=true;
        $user_id=sget('id','i');
        if($user_id>0){
            $usermodel = M('user:customerContact')->getContactModel($user_id);//会员牌号 公司
        }
        $this->assign('user_id',$user_id);
        $this->assign('usermodel',$usermodel);
        $this->display('contact.modelinfo.html');
    }

    /**
     * 会员登录密码修改
     * @access public
     */
    public function modifyPasswd(){
        $user_id=sget('id','i');
        if($user_id<1){
            $this->error('错误的用户信息');
        }
        $user=$this->db->model('customer_contact')->getPk($user_id);
        if(empty($user)){
            $this->error('错误的用户信息');
        }
        $this->assign('user',$user);
        $this->assign('page_title','会员登录密码修改');
        $this->display('user.modPasswd.html');
    }
    /**
     * 更新用户密码
     * @access public
     * @return html
     */
    public function passWdSubmit() {
        $this->is_ajax=true; //指定为Ajax输出
        $data = sdata(); //获取UI传递的参数
        $user_id=(int)$data['user_id'];
        if(empty($user_id)){
            $this->error('错误的用户信息');
        }
        //用户原信息
        $info=$this->db->model('customer_contact')->getPk($user_id);
        if(empty($info)){
            $this->error('错误的用户信息');
        }

        //需要更新的信息
        $basic=array();
        if(!empty($data['password'])){
            if(strlen($data['password'])<8){
                $this->error('密码应为8-20位');
            }
            //更新密码
            $basic['salt']=randstr(6);
            $basic['password']=M('system:sysUser')->genPassword($data['password'].$basic['salt']);
        }

        //开始更新数据
        if(!empty($basic)){
            $msg = sprintf(L('sms_template.passwd_edit_success'),$data['password']);
            $this->db->model('customer_contact')->wherePk($user_id)->update($basic);

            //管理员重置密码，写日志
            $remarks = "管理员重置用户密码";
            M('user:applyLog')->addLog($user_id,'reset_passwd','',$data['password'],1,$remarks);
            //发送手机短信
            M('system:sysSMS')->send($user_id,$info['mobile'],$msg,2);
        }

        $this->success('操作成功');
    }
    //分配注册客户的交易员
    function allotCustomer(){
        $this->is_ajax=true; //指定为Ajax输出
        $data = sdata(); //获取UI传递的参数
        $c_id = sget('cid','i',0); //未通过审核的产品ID
        if($c_id<1) $this->error('错误的分配信息');
        $_data=array(
            'update_time'=>CORE_TIME,
            'update_admin'=>$_SESSION['name'],
        );
        // 查询下分配的管理员所属的部门
        $result = $this->db->where(" user_id = '$c_id'")->update($_data+array('customer_manager'=>$data['id'],));
        if(!$result) $this->error('操作失败');
        $this->success('操作成功');
    }


}