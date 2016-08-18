<?php

class rtnpayAction extends homeBaseAction
{

    public function __init()
    {
		$this->db=M('public:common');
    }

    public function init()
    {
//         $this->display('rtnpay.html');
    }
    
    // 自营订单
    // 支付成功回调
    public function selforder_callback()
    {
        $ip= get_ip();
        file_put_contents("./pay.txt", $ip,FILE_APPEND);
        // $token=cookie::get(C('SESSION_TOKEN'));
        //获取参数
        if((isset($_POST['postdata'])) || (!empty($_POST['postdata']))){
            $postdata = $_POST['postdata'];
        }else{
            $postdata = file_get_contents("php://input");
        }
        //         file_put_contents("./pay.txt", $postdata,FILE_APPEND);
        $param = json_decode($postdata);
        try {
            if(isset($param)){
                // 支付消息
                $message = $param->payMessage;
                // 支付订单的支付号码
                $payID = $param->payID;
                // 支付状态
                $payStatus = $param->payStatus;
                // 签名
                $signature = $param->signature;
            
                $obj = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
                $rtn = $obj->_base64Verify($postdata,$signature);
                //验证签名，是否为东方付通发送的指令
                if($rtn == "1"){
                    // 订单支付成功(其他不处理)
                    if ($payStatus == "000000") {
//                             //$payID
//                             $this->db->startTrans();
//                             // 修改订单状态 已支付 (商城逻辑处理)
//                             //状态为3默认全部付款
//                             $update=array(
//                                 'collection_status' => "3",
//                             );
//                             $update2=array(
//                                 'payMessage' => $message,
//                                 'payStatus' => $payStatus,
//                             );
//                             if(!$this->db->model('order')->where("pay_id='{$payID}'")->update(saddslashes($update))) throw new Exception("更新支付状态失败!");
//                             if(!$this->db->model('pay_message')->where("payID='{$payID}'")->update(saddslashes($update2))) throw new Exception("更新支付信息失败!"); 
//                             if($this->db->commit()){
//                                 $this->success('生成成功');
//                             }else{
//                                 $this->db->rollback();
//     //                             $this->error('生成失败:'.$this->db->getDbError());
//     //                             throw new Exception($this->db->getDbError());
//                             }
                                //状态为3默认全部付款
                                $update=array(
                                    'collection_status' => "3",
                                );
                                $update2=array(
                                    'payMessage' => $message,
                                    'payStatus' => $payStatus,
                                );
                                //自营订单支付操作
                                $remarks = "自营订单支付成功回调";
                                M('user:applyLog')->addLog($this->user_id,'selforder_callback','',json_encode($param),1,$remarks);
                                if(!$this->db->model('pay_message')->where("payID='{$payID}'")->update(saddslashes($update2))) throw new Exception("更新支付信息失败!");
                                if(!$this->db->model('order')->where("pay_id='{$payID}'")->update(saddslashes($update))) throw new Exception("更新支付状态失败!");
                        }
                        // 响应支付平台已接收,接收到消息必须返回  // echo "{\"payStatus\":\"000000\"}";
                    }else{
                        //签名验证失败！
//                         $this->error('签名验证失败!');
                        throw new Exception("签名验证失败!");
                    }
            }else{
                throw new Exception("支付失败,回调内容为空!");
//                 $this->error('支付失败,回调内容为空!');
            }
        } catch (Exception $e) {
            //自营订单支付操作
            $remarks = "自营订单支付成功回调";
            M('user:applyLog')->addLog($this->user_id,'selforder_callback','支付失败',$e->getMessage()+'!',1,$remarks);
            $this->error('支付失败:'. $e->getMessage()+'!');
        }
    }
    
    // 联营订单
    // 支付成功回调
    public function unionorder_callback()
    {
        //获取参数
        if(isset($_POST['postdata']) || !empty($_POST['postdata'])){
            $postdata = $_POST['postdata'];
        }else{
            $postdata = file_get_contents("php://input");
        }
        //         file_put_contents("./pay.txt", $postdata,FILE_APPEND);
        $param = json_decode($postdata);
        try {
            if(isset($param)){
                // 支付消息
                $message = $param->payMessage;
                // 支付订单的支付号码
                $payID = $param->payID;
                // 支付状态
                $payStatus = $param->payStatus;
                // 签名
                $signature = $param->signature;
        
                $obj = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
                $rtn = $obj->_base64Verify($postdata,$signature);
                //验证签名，是否为东方付通发送的指令
                if($rtn == "1"){
                    // 订单支付成功(其他不处理)
                    if ($payStatus == "000000") {
//                         //$payID
//                         $this->db->startTrans();
//                         // 修改订单状态 已支付 (商城逻辑处理)
                        //状态为3默认全部付款
                        $update=array(
                            'collection_status' => "3",
                        );
                        $update2=array(
                            'payMessage' => $message,
                            'payStatus' => $payStatus,
                        );
                        //自营订单支付操作
                        $remarks = "联营订单支付成功回调";
                        M('user:applyLog')->addLog($this->user_id,'unionorder_callback','',json_encode($param),1,$remarks);
                        if(!$this->db->model('pay_message')->where("payID='{$payID}'")->update(saddslashes($update2))) throw new Exception("更新支付信息失败!");
                        if(!$this->db->model('union_order')->where("pay_id='{$payID}'")->update(saddslashes($update))) throw new Exception("更新支付状态失败!");
//                         if($this->db->commit()){
//                             $this->success('生成成功');
//                         }else{
//                             $this->db->rollback();
//                             $this->error('生成失败:'.$this->db->getDbError());
//                         }
                    }
                    // 响应支付平台已接收,接收到消息必须返回  // echo "{\"payStatus\":\"000000\"}";
                }else{
                    //签名验证失败！
//                         $this->error('签名验证失败!');
                        throw new Exception("签名验证失败!");
                }
            }else{
                throw new Exception("支付失败,回调内容为空!");
//                 $this->error('支付失败,回调内容为空!');
            }
        } catch (Exception $e) {
            //联营订单支付操作
            $remarks = "联营订单支付成功回调";
            M('user:applyLog')->addLog($this->user_id,'unionorder_callback','支付失败',$e->getMessage()+'!',1,$remarks);
            $this->error('支付失败:'. $e->getMessage()+'!');
        }
    }
}

?>