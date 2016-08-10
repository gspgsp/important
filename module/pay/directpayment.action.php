<?php
//直接支付
class directpaymentAction extends homeBaseAction
{

    public function __init()
    {}

    public function init()
    {
        $order= sget("id",'s',''); 
        echo $order;
        $this->assign('order',$order);
        //         $this->display('directpayment.html');
        $this->display('pay.html');
    }
}

?>