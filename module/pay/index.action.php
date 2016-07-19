<?php

class indexAction extends homeBaseAction
{

    public function __init()
    {}

    public function init()
    {
        $ss = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
//         $ss->_base64Sign('1231321312312312');
//         $ss->_base64Verify('{"name":"123456"}',$ss->_getSign('{"name":"123456"}'));
//            echo $ss->memberbindquery();
//            echo $ss->AccountQuery();
//         echo $ss->memberbind();
//            echo  $ss->OrderQuery();
//            $ss->CloseDirectPayment();
           echo $ss->WithholdPayment();
//            echo $ss->CloseWithholdPayment();
//            echo $ss->Margin();
//         header("Location:".$ss->memberbind());
//         $this->display('index.html');
    }
}

?>