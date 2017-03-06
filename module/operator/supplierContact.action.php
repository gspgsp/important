<?php
/**
 * Created by PhpStorm.
 * User:  yjy
 * Date: 2017/2/24
 * Time: 14:37
 */
class supplierContactAction extends adminBaseAction {

    public function __init(){
        $this->debug = false;
        $this->assign('user_chanel',L('user_chanel'));
        $this->db=M('public:common')->model('logistics_contact');
        $this->assign('supplier_contact_type',L('supplier_contact_type'));  // 联系人用户状态
        $this->assign('is_default',L('is_default'));
        $this->assign('sex',L('sex'));
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
        $this->display('supplier_contact.list.html');
    }
    /**
     * Ajax获取列表内容
     * @access private
     * @return html
     */
    private function _grid(){
        $page = sget("pageIndex",'i',0); //页码
        $size = sget("pageSize",'i',20); //每页数
        $sortField = sget("sortField",'s','id'); //排序字段
        $sortOrder = sget("sortOrder",'s','asc'); //排序
        //搜索条件
        $where="1";
        $supplier_id=sget('supplier_id','i',0);     // 供应商id
        if($supplier_id !=0){
            $where.=" and `supplier_id` = $supplier_id ";
        }
//        //筛选状态
//        $status=sget('status','i',0);
//        if($status !=0)  $where.=" and `status` =".$status;
//        //筛选时间
//        $sTime = sget("sTime",'s','input_time'); //搜索时间类型
//        $where.=getTimeFilter($sTime); //时间筛选
//        //关键词搜索
//        $key_type=sget('key_type','s','name');
//        $keyword=sget('keyword','s');
//        if(!empty($keyword) && $key_type=='name' ){
//            $where.=" and `$key_type`  like '%$keyword%' ";
//        }elseif(!empty($keyword) && $key_type=='c_id'){
//            $keyword=M('user:customer')->getLikeCidByCname($keyword);
//            $where.=" and `$key_type`  in ('$keyword') ";
//        }elseif(!empty($keyword)){
//            $where.=" and `$key_type`  = '$keyword' ";
//        }
//        if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
//            $sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
//            $pools = M('user:customer')->getCidByPoolCus($_SESSION['adminid']); //共享客户
//            $where .= " and `customer_manager` in ($sons) ";
//            if(!empty($pools)){
//                $cids = explode(',', $pools);
//                $where .= " or `c_id` in ($pools)";
//            }
//            if(!empty($cidshare)){
//                $where .= " or `c_id` in ($cidshare)";
//            }
//        }
        $list=$this->db->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();
        foreach($list['data'] as $k=>$v){
            $list['data'][$k]['id'] =$v['id'];                             // 联系人id
            $list['data'][$k]['contact_name'] =$v['contact_name'];         // 供应商联系人
            $list['data'][$k]['sex']=L('sex')[$v['sex']];                  // 性别
            $list['data'][$k]['status']=L('supplier_contact_type')[$v['status']];       // 联系人状态
            $list['data'][$k]['is_default']=L('is_default')[$v['is_default']];
            $list['data'][$k]['create_time']=$v['create_time']>1000 ? date("Y-m-d H:i:s",$v['create_time']) : '-';     // 创建时间
            $list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';   // 更新时间
            $list['data'][$k]['create_admin'] = M('rbac:adm')->getNameByUser($v['create_name']);  // 创建人
            $list['data'][$k]['update_admin'] = M('rbac:adm')->getNameByUser($v['update_name']);  // 跟新人
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
//        $this->is_ajax=true;
        $contact_id=sget('supplier_contact','i');
        $var=$this->db->select('id,supplier_id,supplier_name,contact_name,sex,status,contact_tel,mobile_tel,qq,comm_fax,is_default,remark')->where('id='.$contact_id)->getRow();
//        if($user_id>0){
//            $info=$this->db->wherePk($user_id)->getRow();
//            $extra = $this->db->model('contact_info')->where("user_id = $user_id")->getRow();
//            $c_info = M('user:customer')->getInfoByUid("$user_id"); // 根据公司id查询公司名字
//            if($c_info['origin']){
//                $areaArr = explode('|', $c_info['origin']);
//                $c_info['company_province'] = $areaArr[1];
//                $c_info['company_city']=$areaArr[0];
//            }
//        }
//        //联系人详情
//        $this->assign('regionList', arrayKeyValues(M('system:region')->get_reg(),'id','name'));//第一级省市
//        $this->assign('type',L('company_type'));//工厂类型
//        $this->assign('level',L('company_level'));//客户类别
//        $this->assign('chanel',L('company_chanel'));//客户渠道
//        $this->assign('credit_level',L('credit_level'));//信用等级
//        $this->assign('c_info',$c_info);
//        $this->assign('res',$extra);
//        $this->assign('info',$meg);
//        $this->assign('status',L('contact_status'));
//        $this->assign('cstatus',L('status'));
//        $this->assign('sex',L('sex'));
//        $this->assign('page_title','联系人列表');
        $this->assign('info',$var);
        $this->display('contact.viewInfo.html');

    }
}