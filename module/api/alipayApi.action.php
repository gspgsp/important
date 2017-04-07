<?php

class alipayApiAction extends null2Action{

    protected $aop,$cache;

    public function __init(){
        $this->cache = E ('RedisCluster', APP_LIB.'class');
        require_file(APP_LIB.'extend/alipay-sdk-PHP/AopSdk.php');
        $this->aop = new AopClient();
        $this->aop->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';
        $this->aop->appId = '2016080300158443';
        $this->aop->rsaPrivateKey='MIIEpgIBAAKCAQEArsurTmGTZqkRgQjbegA0aCVJiGNeJx5CklpGBF6ccdq+6HShEPx7bcAbV3nOekJT3AgGfNJ4953O8Ok8rJhNFIdvQYy9OhkssVHQKr+P4W9wl7Q0G0IG59Rsr39utiYEraDYjG+U4anqrrMdmeh8cNCITjymAd/y6ovVjf6xDvpWrEECZVeAsXIqcLRuMsFMwdND9Pwte8I0bxICiS3uvZRmuBXKGVqeT0jVU3eLzNUMxPVO9XoAJCnhPQZZzMQlM8XpE2eXcUIEdxL7doW9wECjSfWv7aNMTnEwpDyf/JoLT7ajJq6+6lc4PElrogrIs/y/zXkLk8KOIxkeG9aY3wIDAQABAoIBAQCRln4WiNs29LbpnLEBis4buILooKs5NdEJCTusRAlWI3ZDM3E8Lq+3l/yt/XxnBHvIlr6glMXAqKZGrl2k/C2nXa7jEBBEJde90YDrOibjA+jp0mRcF8Ccs6fa/O7/s+bNn1z+i6mb0+Tuoa2UFbogVPBTCdzTTu6LQPEclfhvmd6pA62352AOtAK5j92SySxbMet6nV8N5EZP/+FO2Am32DJ7aGpQbRoNYdF7jrAEifhQfjeehVbLG6OIXD+pSYUoOrioVWRkNW1NF6EAKv1RCshUfQZ0KB+KxBMnAmcoTQmVlxuGuhpMeW/kMv4P+hEtavMPOm08j4uWv++Oy04BAoGBANqHIDIwyYBAsFRKHlxgNPatrpRJ939UNpWtA9eKDyQnrS3NacrvtxLyYZ/7RtCafy/Y8pTpaVBjXPQ2VxV01/Zbp6/DHxT2GE69iSiGpHIzmvdF4JpeNtxb0+fUA2pRfmiAQOfOr21+7ntwYDDhnfHmHm8iVRFDZtRhtQh5zC6BAoGBAMzEzBB3sH20tnYuALYfnh1i1nAIn6c1I8RoJmrvPleNtKxyv44Ah8c7MXHRfN5C9M6poMiQPUV9DIbOY4Q2Q0Cqs8A1GcRsFzJXSB5oMYWlpygo3PhwrJruQS+C4W8FnJkSQkKb7URGofFJeQ53MBmjDK08YzRBzB8tO0Wig9dfAoGBALyJvtZuzzrvFPL0K7OpcaiueqQIGRfrMVj7uAfbXmrkLH8K7c6f+YTISEA+DH/n+/ntJIYjx7AKumUdCQ9DCxzLQSbcotFz7c7pqg+j8vdw3K+gw0KMLKr8MxyeCABPpU9F8DnPUf2XeOxZLTSfQ6Uz1Ggv59MIIwzz67wPUYGBAoGBAI9oPCpESLyg9TBrI2BpYEjgUaIAyB9IXhZNgqpdh2G2ApTLgFApGu5zDDvUJQlcBys9LTeJnP+vhjhbDuMnRY5ifqTcC4G+2bgN3Jo/Cn+49gpwI+Fyt8+BkPF/TfZ9DaE+Yl1X6qFofj4H4No6qtspj9U7d5a/hf9HpD0uhfstAoGBANQt1zqcCMSevTFk0fOFSUGlLewZ+5DNmVrf66V44CbfrhVwGx/wtYglkuF8fBEP0tgxCrboZbX7z2LIyBCoIuvoMOMfjIzu8HD+825HBwpYYPgX4TR2nFdfH3wLeJ6X4NCNfpVhHHIlczO5kKu9FdzSSbB5tBGj3TnV30KldvaT';
        $this->aop->alipayrsaPublicKey ='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAslcnAaY80obGJtaM89ukAqi38QLxpMdVwhrtIT43BRZ1bggkNGT/L1IXm/ffA1rAxOPta2pauoKl3bNdK9ClvDISJ8Emvd/xP3Ggh09m7k1Xr9yExB0BgIqHvISJ/kdNPSpr2OxDBzHJ6ulzjE8cQ/W7465N9biG/lIjcwYXcdG70UTBfv+L74PQPWrryLS9M7Eu0eemML3pc1w5jwhbzNsUftmZiZTe5gUMG/a/7lXth3cGH4oeBlndfCnFDsiJYiv9bKCjgd0AwAAs/uL/6Q4eJS4jY5Ab66mrJ0rh+rEbdxC0MU3wh9kI3BaoQ5+5UccHrMC4151XEI6vDd0ciQIDAQAB';
        $this->aop->apiVersion = '1.0';
        $this->aop->postCharset='UTF-8';
        $this->aop->format='json';
        $this->aop->signType='RSA2';
    }

    public function alipaySome(){
        $request = new AlipayTradeWapPayRequest ();
        //$request->setReturnUrl("http://www.nsuliao.com/api/alipayApi/alipayReturnUrl");
        $request->setNotifyUrl("http://test.myplas.com/api/alipayApi/alipayReturnUrl");
        $sno='so'.time().'sssss';
        $request->setBizContent("{" .
            "    \"body\":\"对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加传给body。\"," .
            "    \"subject\":\"大乐透\"," .
            "    \"out_trade_no\":\"$sno\"," .
            "    \"timeout_express\":\"90m\"," .
            "    \"total_amount\":0.01," .
            "    \"product_code\":\"QUICK_WAP_PAY\"" .
            "  }");
        $result = $this->aop->pageExecute ( $request);
        echo $result;
    }


    public function alipayReturnUrl(){
        header('Refresh:3,Url=http://test.myplas.com');
        $this->display('plasticzone/alipay_return.html');
    }

    public function alipayNotifyUrl(){
        $this->cache->set('ssssalipay',serialize($_POST));
    }

    public function alipayQuery(){
        $request = new AlipayTradeQueryRequest ();
        $request->setBizContent("{" .
            //"    \"out_trade_no\":\"20150320010101001\"," .
            "    \"trade_no\":\"2017040521001004040200108842\"" .
            "  }");
        $result = $this->aop->execute ( $request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }
    }

    public function alipayRefund(){
        $request = new AlipayTradeRefundRequest ();
        $request->setBizContent("{" .
            //           "    \"out_trade_no\":\"20150320010101001\"," .
            "    \"trade_no\":\"2017040521001004040200108845\"," .
            "    \"refund_amount\":0.01," .
//            "    \"refund_reason\":\"正常退款\"," .
//            "    \"out_request_no\":\"HZ01RF001\"," .
//            "    \"operator_id\":\"OP001\"," .
//            "    \"store_id\":\"NJ_S_001\"," .
//            "    \"terminal_id\":\"NJ_T_001\"" .
            "  }");
        $result = $this->aop->execute ( $request);
        p($result);exit;
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }
    }

    public function alipayPrecreate(){
        $request = new AlipayTradePrecreateRequest ();
        $request->setBizContent("{" .
            "    \"out_trade_no\":\"20150320010101001\"," .
            "    \"seller_id\":\"2088102146225135\"," .
            "    \"total_amount\":88.88," .
            "    \"discountable_amount\":8.88," .
            "    \"undiscountable_amount\":80," .
            "    \"buyer_logon_id\":\"15901825620\"," .
            "    \"subject\":\"Iphone6 16G\"," .
            "      \"goods_detail\":[{" .
            "                \"goods_id\":\"apple-01\"," .
            "        \"alipay_goods_id\":\"20010001\"," .
            "        \"goods_name\":\"ipad\"," .
            "        \"quantity\":1," .
            "        \"price\":2000," .
            "        \"goods_category\":\"34543238\"," .
            "        \"body\":\"特价手机\"," .
            "        \"show_url\":\"http://www.alipay.com/xxx.jpg\"" .
            "        }]," .
            "    \"body\":\"Iphone6 16G\"," .
            "    \"operator_id\":\"yx_001\"," .
            "    \"store_id\":\"NJ_001\"," .
            "    \"disable_pay_channels\":\"pcredit,moneyFund,debitCardExpress\"," .
            "    \"enable_pay_channels\":\"pcredit,moneyFund,debitCardExpress\"," .
            "    \"terminal_id\":\"NJ_T_001\"," .
            "    \"extend_params\":{" .
            "      \"sys_service_provider_id\":\"2088511833207846\"," .
            "      \"hb_fq_num\":\"3\"," .
            "      \"hb_fq_seller_percent\":\"100\"" .
            "    }," .
            "    \"timeout_express\":\"90m\"," .
            "    \"royalty_info\":{" .
            "      \"royalty_type\":\"ROYALTY\"," .
            "        \"royalty_detail_infos\":[{" .
            "                    \"serial_no\":1," .
            "          \"trans_in_type\":\"userId\"," .
            "          \"batch_no\":\"123\"," .
            "          \"out_relation_id\":\"20131124001\"," .
            "          \"trans_out_type\":\"userId\"," .
            "          \"trans_out\":\"2088101126765726\"," .
            "          \"trans_in\":\"2088101126708402\"," .
            "          \"amount\":0.1," .
            "          \"desc\":\"分账测试1\"," .
            "          \"amount_percentage\":\"100\"" .
            "          }]" .
            "    }," .
            "    \"sub_merchant\":{" .
            "      \"merchant_id\":\"19023454\"" .
            "    }," .
            "    \"alipay_store_id\":\"2016052600077000000015640104\"" .
            "  }");
        $result = $this->aop->execute ( $request);
        p($result);exit;
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }
    }


    public function alipayBillQuery(){
        require_file(APP_LIB.'extend/alipay-sdk-PHP/AopSdk.php');
        $this->aop = new AopClient();
        $this->aop->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';
        $this->aop->appId = '2016080300158443';
        $this->aop->rsaPrivateKey='MIIEpgIBAAKCAQEArsurTmGTZqkRgQjbegA0aCVJiGNeJx5CklpGBF6ccdq+6HShEPx7bcAbV3nOekJT3AgGfNJ4953O8Ok8rJhNFIdvQYy9OhkssVHQKr+P4W9wl7Q0G0IG59Rsr39utiYEraDYjG+U4anqrrMdmeh8cNCITjymAd/y6ovVjf6xDvpWrEECZVeAsXIqcLRuMsFMwdND9Pwte8I0bxICiS3uvZRmuBXKGVqeT0jVU3eLzNUMxPVO9XoAJCnhPQZZzMQlM8XpE2eXcUIEdxL7doW9wECjSfWv7aNMTnEwpDyf/JoLT7ajJq6+6lc4PElrogrIs/y/zXkLk8KOIxkeG9aY3wIDAQABAoIBAQCRln4WiNs29LbpnLEBis4buILooKs5NdEJCTusRAlWI3ZDM3E8Lq+3l/yt/XxnBHvIlr6glMXAqKZGrl2k/C2nXa7jEBBEJde90YDrOibjA+jp0mRcF8Ccs6fa/O7/s+bNn1z+i6mb0+Tuoa2UFbogVPBTCdzTTu6LQPEclfhvmd6pA62352AOtAK5j92SySxbMet6nV8N5EZP/+FO2Am32DJ7aGpQbRoNYdF7jrAEifhQfjeehVbLG6OIXD+pSYUoOrioVWRkNW1NF6EAKv1RCshUfQZ0KB+KxBMnAmcoTQmVlxuGuhpMeW/kMv4P+hEtavMPOm08j4uWv++Oy04BAoGBANqHIDIwyYBAsFRKHlxgNPatrpRJ939UNpWtA9eKDyQnrS3NacrvtxLyYZ/7RtCafy/Y8pTpaVBjXPQ2VxV01/Zbp6/DHxT2GE69iSiGpHIzmvdF4JpeNtxb0+fUA2pRfmiAQOfOr21+7ntwYDDhnfHmHm8iVRFDZtRhtQh5zC6BAoGBAMzEzBB3sH20tnYuALYfnh1i1nAIn6c1I8RoJmrvPleNtKxyv44Ah8c7MXHRfN5C9M6poMiQPUV9DIbOY4Q2Q0Cqs8A1GcRsFzJXSB5oMYWlpygo3PhwrJruQS+C4W8FnJkSQkKb7URGofFJeQ53MBmjDK08YzRBzB8tO0Wig9dfAoGBALyJvtZuzzrvFPL0K7OpcaiueqQIGRfrMVj7uAfbXmrkLH8K7c6f+YTISEA+DH/n+/ntJIYjx7AKumUdCQ9DCxzLQSbcotFz7c7pqg+j8vdw3K+gw0KMLKr8MxyeCABPpU9F8DnPUf2XeOxZLTSfQ6Uz1Ggv59MIIwzz67wPUYGBAoGBAI9oPCpESLyg9TBrI2BpYEjgUaIAyB9IXhZNgqpdh2G2ApTLgFApGu5zDDvUJQlcBys9LTeJnP+vhjhbDuMnRY5ifqTcC4G+2bgN3Jo/Cn+49gpwI+Fyt8+BkPF/TfZ9DaE+Yl1X6qFofj4H4No6qtspj9U7d5a/hf9HpD0uhfstAoGBANQt1zqcCMSevTFk0fOFSUGlLewZ+5DNmVrf66V44CbfrhVwGx/wtYglkuF8fBEP0tgxCrboZbX7z2LIyBCoIuvoMOMfjIzu8HD+825HBwpYYPgX4TR2nFdfH3wLeJ6X4NCNfpVhHHIlczO5kKu9FdzSSbB5tBGj3TnV30KldvaT';
        $this->aop->alipayrsaPublicKey ='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAslcnAaY80obGJtaM89ukAqi38QLxpMdVwhrtIT43BRZ1bggkNGT/L1IXm/ffA1rAxOPta2pauoKl3bNdK9ClvDISJ8Emvd/xP3Ggh09m7k1Xr9yExB0BgIqHvISJ/kdNPSpr2OxDBzHJ6ulzjE8cQ/W7465N9biG/lIjcwYXcdG70UTBfv+L74PQPWrryLS9M7Eu0eemML3pc1w5jwhbzNsUftmZiZTe5gUMG/a/7lXth3cGH4oeBlndfCnFDsiJYiv9bKCjgd0AwAAs/uL/6Q4eJS4jY5Ab66mrJ0rh+rEbdxC0MU3wh9kI3BaoQ5+5UccHrMC4151XEI6vDd0ciQIDAQAB';
        $this->aop->apiVersion = '1.0';
        $this->aop->postCharset='UTF-8';
        $this->aop->format='json';
        $this->aop->signType='RSA2';
        $request = new AlipayDataDataserviceBillDownloadurlQueryRequest ();
        $request->setBizContent("{" .
            "    \"bill_type\":\"trade\"," .
            "    \"bill_date\":\"2017-04\"" .
            "  }");
        $result = $this->aop->execute ( $request);
        p($result);exit;
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }
    }

    public function janfly4(){
        $_tmp = $this->cache->get('ssssalipay');
        p($_tmp);
    }


}