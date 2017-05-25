<?php
/**
 * 订单作废处理
 */
class orderInvalidAction extends adminBaseAction {
    public function __init(){
        $this->debug = false;
        $this->db=M('public:common')->model('order');
    }
    public function init(){
        $action=sget('action','s');
        if($action=='grid'){ //获取列表
            $this->_grid();exit;
        }
        $this->display('orderInvalid.list.html');
    }
    /**
     * Ajax获取列表内容
     * @access private
     * @return html
     */
    public function _grid(){
        $page = sget("pageIndex",'i',0); //页码
        $size = sget("pageSize",'i',20); //每页数
        $sortField = sget("sortField",'s','o_id'); //排序字段
        $sortOrder = sget("sortOrder",'s','desc'); //排序
        $where = ' 1 ';
        // 关键词
        $order_sn=sget('order_sn','s');
        // p($order_sn);die;
        if(!empty($order_sn)){
            $where.=" and order_sn ='".$order_sn."'";
            $list=$this->db
            ->where($where)
            ->page($page+1,$size)
            ->order("$sortField $sortOrder")
            ->getPage();
            $result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
        }else{
            $result='无对应订单号不能查询';
        }
        $this->json_output($result);
    }
    public function invalid()
    {
        $this->is_ajax=true; //指定为Ajax输出
        $data = sdata(); //获取UI传递的参数
        if(empty($data)) $this->error('错误的操作');
        $order_res = M('product:order')->getOinfoById($data['o_id']);//订单结果集
        //判断作废前的一系列操作是否完结，款，票，出入库
        //查票
        $bill_where = ' o_id = '.$data['o_id'].' AND invoice_status = 1 ';
        if($order_res['invoice_status'] != 1) $this->error('该订单的票据尚未处理完毕，订单作废失败！'); 
        if($this->db->model('billing')->where($bill_where)->getRow()) $this->error('该订单尚有票据申请未处理完毕，订单作废失败！');
        //查款
        if($order_res['collection_status'] != 1) $this->error('该订单的款项尚未处理完毕，订单作废失败！'); 
        $coll_where = '( o_id = '.$data['o_id'].' AND collection_status = 1)OR (o_id = '.$data['o_id']*(-1).' AND collection_status = 1)';
        if($this->db->model('collection')->where($coll_where)->getRow()) $this->error('该订单尚有收/付款申请未处理完毕，订单作废失败！');
        //出库
        if($order_res['order_type'] == 1 && $order_res['out_storage_status'] != 1) $this->error('该订单的出库状态尚未处理完毕，订单作废失败！'); 
        //入库
        if($order_res['order_type'] == 2 && $order_res['in_storage_status'] != 1) $this->error('该订单的入库状态尚未处理完毕，订单作废失败！'); 
        $this->db->startTrans(); //开启事务
        //作废处理规则，销售单作废：现销现采，un_pur = 0，不同战队---回销扣采
        //作废处理规则，销售单作废：现销现采，un_pur = 1,有采购，采购双审，不同战队---回销扣采
        //作废处理规则，销售单作废：销库存，不同战队---回销扣采
        //作废处理规则，采购单作废：销售采购，销售双审，不同战队---回销扣采
        //作废处理规则，采购单作废：备货采购，销售双审，不同战队---回销扣采
        if($order_res['order_type'] == 1 ){
            $buy_oid = M('product:order')->getPurOidOrSaleOid($data['o_id']);//采购o_id
            if($buy_oid){
                $pur_res = M('product:order')->getOinfoById($buy_oid);//采购订单结果集
                $team_ids = M('product:order')->getCustomerManagerTeamStatusByOid($data['o_id'],$buy_oid);
                if($team_ids['sale_team_id'] != $team_ids['buy_team_id']){
                    if($order_res['un_pur'] == 0 || ($order_res['un_pur'] == 1 && $pur_res['order_status'] == 2 && $pur_res['transport_status'] == 2 && $order_res['sales_type'] == 2 ) || $order_res['sales_type'] == 1){
                        $buy_team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($pur_res['customer_manager']);
                        if(!$buy_team_capital) $this->error('采购订单业务员所在战队的配资指标未设，处理失败');
                        $sale_team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($order_res['customer_manager']);
                        if(!$sale_team_capital) $this->error('关联销售订单业务员所在战队的配资指标未设，处理失败');
                        $sale_cost = M('product:order')->getSaleCost($order_res['o_id']);//销售单采购金额（成本价格）
                        $sale_capital = M('user:teamCapital')->comeMoney($sale_team_capital,$sale_cost);//销售战队+资金占用
                        $buy_capital = M('user:teamCapital')->goMoney($buy_team_capital,$sale_cost);//采购战队-资金占用
                        if(!$sale_capital || !$buy_capital) $this->error('战队配资处理失败');
                        //新增战队配资变动日志----S
                        $sale_team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($order_res['customer_manager']);
                        $buy_team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($pur_res['customer_manager']);
                        $sale_remarks = "销售订单作废，增加销售战队额度";
                        $buy_remarks = "销售订单作废，削减采购战队额度";
                        M('user:teamCapital')->addLog($order_res['o_id'],$sale_team_capital['team_id'],'sale_invalid',$sale_team_capital['available_money'],$sale_team_capital_now['available_money'],1,$sale_cost,$sale_remarks);
                        M('user:teamCapital')->addLog($pur_res['o_id'],$buy_team_capital['team_id'],'sale_invalid',$buy_team_capital['available_money'],$buy_team_capital_now['available_money'],1,$sale_cost,$buy_remarks);
                            //新增战队配资变动日志----E
                    }
                }
            }
        }else{
            if($order_res['purchase_type'] == 1){
                $sale_oid = M('product:order')->getPurOidOrSaleOid($data['o_id']);//销售o_id
                if($sale_oid){
                    $sale_res = M('product:order')->getOinfoById($sale_oid);//销售订单结果集
                    $team_ids = M('product:order')->getCustomerManagerTeamStatusByOid($sale_oid,$data['o_id']);
                    if($sale_res['order_status'] == 2 && $sale_res['transport_status'] == 2 && ($team_ids['sale_team_id'] != $team_ids['buy_team_id']) ){
                        $buy_team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($order_res['customer_manager']);
                        if(!$buy_team_capital) $this->error('采购订单业务员所在战队的配资指标未设，处理失败');
                        $sale_team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($sale_res['customer_manager']);
                        if(!$sale_team_capital) $this->error('关联销售订单业务员所在战队的配资指标未设，处理失败');
                        $sale_capital = M('user:teamCapital')->comeMoney($sale_team_capital,$order_res['total_price']);//销售战队+资金占用
                        $buy_capital = M('user:teamCapital')->goMoney($buy_team_capital,$order_res['total_price']);//采购战队-资金占用
                        if(!$sale_capital || !$buy_capital) $this->error('战队配资处理失败');
                        //新增战队配资变动日志----S
                        $sale_team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($sale_res['customer_manager']);
                        $buy_team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($order_res['customer_manager']);
                        $sale_remarks = "销售采购订单作废，增加销售战队额度";
                        $buy_remarks = "销售采购订单作废，削减采购战队额度";
                        M('user:teamCapital')->addLog($sale_res['o_id'],$sale_team_capital['team_id'],'buy_invalid',$sale_team_capital['available_money'],$sale_team_capital_now['available_money'],1,$order_res['total_price'],$sale_remarks);
                        M('user:teamCapital')->addLog($order_res['o_id'],$buy_team_capital['team_id'],'buy_invalid',$buy_team_capital['available_money'],$buy_team_capital_now['available_money'],1,$order_res['total_price'],$buy_remarks);
                        //新增战队配资变动日志----E
                    }
                }
            }else{
                $sales_result = M('product:order')->getSaleResByPurOid($data['o_id']);//销售订单结果集(二维数组，可能是一采购对应多个销售)
                if($sales_result){
                    foreach ($sales_result as $key => $value) {
                        if($value['order_status'] == 2 && $value['transport_status'] == 2){
                            $sale_res = M('product:order')->getOinfoById($value['o_id']);//销售订单结果集
                            $team_ids = M('product:order')->getCustomerManagerTeamStatusByOid($value['o_id'],$data['o_id']);
                            if($team_ids['sale_team_id'] != $team_ids['buy_team_id']){
                                $buy_team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($order_res['customer_manager']);
                                if(!$buy_team_capital) $this->error('采购订单业务员所在战队的配资指标未设，处理失败');
                                $sale_team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($sale_res['customer_manager']);
                                if(!$sale_team_capital) $this->error('关联销售订单业务员所在战队的配资指标未设，处理失败');
                                $sale_cost = M('product:order')->getSaleCost($value['o_id']);//销售单采购金额（成本价格）
                                $sale_capital = M('user:teamCapital')->comeMoney($sale_team_capital,$sale_cost);//销售战队+资金占用
                                $buy_capital = M('user:teamCapital')->goMoney($buy_team_capital,$sale_cost);//采购战队-资金占用
                                if(!$sale_capital || !$buy_capital) $this->error('战队配资处理失败');
                                //新增战队配资变动日志----S
                                $sale_team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($sale_res['customer_manager']);
                                $buy_team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($order_res['customer_manager']);
                                $sale_remarks = "备货采购订单作废，增加销售战队额度";
                                $buy_remarks = "备货采购订单作废，削减采购战队额度";
                                M('user:teamCapital')->addLog($sale_res['o_id'],$sale_team_capital['team_id'],'buy_invalid',$sale_team_capital['available_money'],$sale_team_capital_now['available_money'],1,$sale_cost,$sale_remarks);
                                M('user:teamCapital')->addLog($order_res['o_id'],$buy_team_capital['team_id'],'buy_invalid',$buy_team_capital['available_money'],$buy_team_capital_now['available_money'],1,$sale_cost,$buy_remarks);
                            //新增战队配资变动日志----E
                            }
                        }
                    }
                } 
            }
        }
        $this->db->model('order')->where('o_id = '.$data['o_id'])->update(array('transport_status'=>3,'update_time'=>time()));
        if($this->db->commit()){
            $this->success('操作成功');
        }else{
            $this->db->rollback();
            $this->error('操作失败');
        }
    }
}