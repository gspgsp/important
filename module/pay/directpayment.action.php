<?php
//直接支付
class directpaymentAction extends homeBaseAction
{

    public function __init()
    {}

    public function init()
    {
        $this->display('directpayment.html');
    }
}

?>