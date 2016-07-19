<?php
//东方付通会员相关操作
class memberAction extends homeBaseAction
{

    public function __init()
    {
    }
    public function init()
    {
        $ss = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
        header("Location:".$ss->memberbind());//会员绑定
//      $this->display('member.html');
    }

}

?>