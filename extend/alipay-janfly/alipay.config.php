<?php
/* *
 * 配置文件
 * 版本：3.5
 * 日期：2016-06-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
 * 解决方法：
 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设置
 * 2、更换浏览器或电脑，重新登录查询。
 */
 
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
//$alipay_config['partner']		= '2088021387864034';


$alipay_config['partner']		= '2088102169782901';

//收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
$alipay_config['seller_id']	= $alipay_config['partner'];

//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
#$alipay_config['private_key']	= 'MIICXgIBAAKBgQDEDJuL2NEtuccEqxoXdLnzy6OaNXRRLxBmpq9Z+NjncuYha9bjflKwfif1lUp8hHouqGK/NPVX57ECbmB/QDE0oBsngGGSZns92gYTcAhj5b6iaOgZ+byq5VrwWgqI0XxVe4GIG4bxO1a6XVbaswp5vIqDieb89c76+eCGMFA4EQIDAQABAoGBAMJ1MvACznEJfaNex9GOQUfVrGAwN0CzaxQ2r2WR1Cu2pxdtbMdXrFNNvvrz6t8ZVEvlT5USxWw87zFXJDX0CRjIDMuR9ThBV19P7x13zANG54X8NKjL+5gHo9cqeM2S/+6mQiCl2N9fcpQTaFwW8l0KWd06S7aPRRPQSumiKStVAkEA+G0ZWEb1AqH3nO/Wv/tft4didLD0dM0FlpgjaSMqWkrbV83vC7aIcMKby9LWgVOSUf5mSNpgDiciDebRbK4DnwJBAMoGuHKSSWcUWR3wM2PafEtxEf8pNJmTEEJeOYTOLiw4dfngEGNZKDdQtjoaRFl67wmkNKVo11nnLzaXwKk8pk8CQGgIoCWEyZQJqf0xvzf0mBfufT+q3ySOuzleiu27iT/4uzRhDCtWjHrUNJQ9vhC4o7zskX8O3Ezw/GTf9XHSybcCQQCE0wZRzXwVGXu+Az7GV8+AikaazCyZC+eUuv52IliZGkX2kyozJ1sZgydL7Bkj+39Nh3hViCiOI0EXU46HtihhAkEAswWqCvtb517+bmykHY01qCgegVyX4BjB1z3C8oWPujhVgJL4M+HkTQ2+o+KOs9Ob2ol5xyF0sHXnnElDW/Eybw==';
#$alipay_config['private_key']	= 'MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAMQMm4vY0S25xwSrGhd0ufPLo5o1dFEvEGamr1n42Ody5iFr1uN+UrB+J/WVSnyEei6oYr809VfnsQJuYH9AMTSgGyeAYZJmez3aBhNwCGPlvqJo6Bn5vKrlWvBaCojRfFV7gYgbhvE7VrpdVtqzCnm8ioOJ5vz1zvr54IYwUDgRAgMBAAECgYEAwnUy8ALOcQl9o17H0Y5BR9WsYDA3QLNrFDavZZHUK7anF21sx1esU02++vPq3xlUS+VPlRLFbDzvMVckNfQJGMgMy5H1OEFXX0/vHXfMA0bnhfw0qMv7mAej1yp4zZL/7qZCIKXY319ylBNoXBbyXQpZ3TpLto9FE9BK6aIpK1UCQQD4bRlYRvUCofec79a/+1+3h2J0sPR0zQWWmCNpIypaSttXze8LtohwwpvL0taBU5JR/mZI2mAOJyIN5tFsrgOfAkEAyga4cpJJZxRZHfAzY9p8S3ER/yk0mZMQQl45hM4uLDh1+eAQY1koN1C2OhpEWXrvCaQ0pWjXWecvNpfAqTymTwJAaAigJYTJlAmp/TG/N/SYF+59P6rfJI67OV6K7buJP/i7NGEMK1aMetQ0lD2+ELijvOyRfw7cTPD8ZN/1cdLJtwJBAITTBlHNfBUZe74DPsZXz4CKRprMLJkL55S6/nYiWJkaRfaTKjMnWxmDJ0vsGSP7f02HeFWIKI4jQRdTjoe2KGECQQCzBaoK+1vnXv5ubKQdjTWoKB6BXJfgGMHXPcLyhY+6OFWAkvgz4eRNDb6j4o6z05vaiXnHIXSwdeecSUNb8TJv';
#


#$alipay_config['private_key']	= 'MIIEowIBAAKCAQEA8G1zCY2vx6OiKU40dEnz4Hx1LYhcZK4PLqsmPPQgxnEtg64E4S2zke8RUpXfx21POWOalWwJ5Kug4e4ljiYcv3gl2qO3F9FKJtjR+3J1Sni1uAG+5QF00pQImURODZNj7Moz4fqYNAlXJPKt7QdrCsaH0XULQz4/kkJvFfdNw/7IJaJczuCHMZlJZCzRzZ8TJVSscoNfh0JMQhToJ+ln1Um1S6SlB++vBEQiWs/cfJ2Hdc20KN5gVlh8nAPKzSm8FZCAr5tlJIdLhbJpMvOwTfj1HosK7ip7+WYk27aZ/i7Dj1tD638gpeGKSRAlfddrpBtLyGgK82HnNGxe/Sr1zQIDAQABAoIBAAn+Qp3PCdNedcsJuRxsRnq6MZSm6lA0++SQsJIwA3y8D4w9m25Qm/og2bN5D0gWvwg/n5k+WFrs108MuvrhkqhSsiAYVe/jfxWGRZQR+dYSllsDl4+lM9nc1U6pjCT8T1b0QJvP8quXB8w9WgVUHZrhDZwjJUiyOao2oK9lDBJtW/pL8b9GV0lnixSm1TWuf6kcEebhLqOkCZXqLGO1dtMeOAYdIJzxNn6ntQ4ZoKI59t5tjvEV0InPOX0rleJl73Tg/pv4UouTAS0hGgdUSsji4PiRYFWRVilIonI5ma1nNtadB1hnbYN+KwolJ3tXWdej0NfHMbZlzTrztA9CwHECgYEA/5QSH5GgVwssTbJpEZeSTPQTewDHiQCT91Mw1tBCQ8DB3NYg3TlLJfwHeSkenfMQoey9VvyAqJS8ZFOYP+q3yC5EG/CdfaLYdHJr0nLYSOloAJlNhFLxzFWmXGTMmBw+Jckm36CPlFzwoUe3uJkA9zvpBe74KYuAjlef8Lcj8qcCgYEA8NL6/ukJcHS6AC/kqBqio6UQCnKSWCPwYMdxMj8LRdurQzA82ghcybPTDiVpL+PnA5Jr9Ywnbt0S82flbcChJOJefFaWSmH10WyHs6fUiFzkO/8xaWJNAtjd4cIX4OU9R+Sqny1agRvcX2PRUWfETKeSFiVecryB5iMB52qaZmsCgYBmgFA0cQdJYaXHE4KoF44JZupZLprYC29MhgTUYnnxCmG12/saPlwxQ78LjL5GwrEs//F/7Yz8D4rvAcfR+qTyl/ug6QOA28rMCQfNQ1oTA+k+ND2ukEWBmeg6rgKQDuOt69q3XYG4Ev4+8LByTIrinRh71LeakeRhMitnM0wriQKBgQClgkqwQJaVLBPdJw0+HzghwcKZe45qdyCwUsHQJ5XmyAcXISLhMhgbI7Rsp3d4S2gsg0ghNNaxEc0Bbik22cmmINGRIK0fp92atb050qF1qmnNdiCObdnBmCw+CPgCP2pza4t75F6sFurIwEBOc0Ns70muYLZdooKgGPUpB5HfywKBgCc18QfQDyf3mELn+/uFEbP8PljYiDo+ocO/MYZab4ybljaYUhuF4vKzUhFpfOn0cMDX1fMB6oQ/uI2WDIRyJN643+vwpmBToSpxgs0mjzzKcQPiDjk3K2ICJYANsUIrHRtaBDZPCrM5JWiOeFEWZgg6JEE0jDl/FZE+VM3kE+jZ';

$alipay_config['private_key']	= 'MIIEpgIBAAKCAQEArsurTmGTZqkRgQjbegA0aCVJiGNeJx5CklpGBF6ccdq+6HShEPx7bcAbV3nOekJT3AgGfNJ4953O8Ok8rJhNFIdvQYy9OhkssVHQKr+P4W9wl7Q0G0IG59Rsr39utiYEraDYjG+U4anqrrMdmeh8cNCITjymAd/y6ovVjf6xDvpWrEECZVeAsXIqcLRuMsFMwdND9Pwte8I0bxICiS3uvZRmuBXKGVqeT0jVU3eLzNUMxPVO9XoAJCnhPQZZzMQlM8XpE2eXcUIEdxL7doW9wECjSfWv7aNMTnEwpDyf/JoLT7ajJq6+6lc4PElrogrIs/y/zXkLk8KOIxkeG9aY3wIDAQABAoIBAQCRln4WiNs29LbpnLEBis4buILooKs5NdEJCTusRAlWI3ZDM3E8Lq+3l/yt/XxnBHvIlr6glMXAqKZGrl2k/C2nXa7jEBBEJde90YDrOibjA+jp0mRcF8Ccs6fa/O7/s+bNn1z+i6mb0+Tuoa2UFbogVPBTCdzTTu6LQPEclfhvmd6pA62352AOtAK5j92SySxbMet6nV8N5EZP/+FO2Am32DJ7aGpQbRoNYdF7jrAEifhQfjeehVbLG6OIXD+pSYUoOrioVWRkNW1NF6EAKv1RCshUfQZ0KB+KxBMnAmcoTQmVlxuGuhpMeW/kMv4P+hEtavMPOm08j4uWv++Oy04BAoGBANqHIDIwyYBAsFRKHlxgNPatrpRJ939UNpWtA9eKDyQnrS3NacrvtxLyYZ/7RtCafy/Y8pTpaVBjXPQ2VxV01/Zbp6/DHxT2GE69iSiGpHIzmvdF4JpeNtxb0+fUA2pRfmiAQOfOr21+7ntwYDDhnfHmHm8iVRFDZtRhtQh5zC6BAoGBAMzEzBB3sH20tnYuALYfnh1i1nAIn6c1I8RoJmrvPleNtKxyv44Ah8c7MXHRfN5C9M6poMiQPUV9DIbOY4Q2Q0Cqs8A1GcRsFzJXSB5oMYWlpygo3PhwrJruQS+C4W8FnJkSQkKb7URGofFJeQ53MBmjDK08YzRBzB8tO0Wig9dfAoGBALyJvtZuzzrvFPL0K7OpcaiueqQIGRfrMVj7uAfbXmrkLH8K7c6f+YTISEA+DH/n+/ntJIYjx7AKumUdCQ9DCxzLQSbcotFz7c7pqg+j8vdw3K+gw0KMLKr8MxyeCABPpU9F8DnPUf2XeOxZLTSfQ6Uz1Ggv59MIIwzz67wPUYGBAoGBAI9oPCpESLyg9TBrI2BpYEjgUaIAyB9IXhZNgqpdh2G2ApTLgFApGu5zDDvUJQlcBys9LTeJnP+vhjhbDuMnRY5ifqTcC4G+2bgN3Jo/Cn+49gpwI+Fyt8+BkPF/TfZ9DaE+Yl1X6qFofj4H4No6qtspj9U7d5a/hf9HpD0uhfstAoGBANQt1zqcCMSevTFk0fOFSUGlLewZ+5DNmVrf66V44CbfrhVwGx/wtYglkuF8fBEP0tgxCrboZbX7z2LIyBCoIuvoMOMfjIzu8HD+825HBwpYYPgX4TR2nFdfH3wLeJ6X4NCNfpVhHHIlczO5kKu9FdzSSbB5tBGj3TnV30KldvaT';


//支付宝的公钥，查看地址：https://b.alipay.com/order/pidAndKey.htm 
#$alipay_config['alipay_public_key']= 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB';
#$alipay_config['alipay_public_key']='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2LpbJC/SUDFanNZ89CXm+MEbYKoGjOppIU0LuBPaRZs4ZYPhgAkbwGKeNi2Q7sWwShhDpoj+qLbEGz/IVVer1GdVPCLCKmvVc5yaRZE3Y6jc5DBujSysY6bilkw6ny4cCxPypNVkppmudGE2fVsFRnDU/lMp16EYXiUzgYrWGDuoO68M47oh1wJCqjSnJERahNjy0O6omeHQf0AOHBLFHZT+oV00JqXbQUh7vOgtq7u3R0mfQeRo2pjy6/kx0y3GwGMJn/A+Q/83KzEZGHtfnOUWVgBsz06OlNoFPtS09/DJ7n4weHmi1HQcQ2Ycf3v5g0Q56C+CX9F67ooRfPcMDwIDAQAB';
$alipay_config['alipay_public_key']='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArsurTmGTZqkRgQjbegA0aCVJiGNeJx5CklpGBF6ccdq+6HShEPx7bcAbV3nOekJT3AgGfNJ4953O8Ok8rJhNFIdvQYy9OhkssVHQKr+P4W9wl7Q0G0IG59Rsr39utiYEraDYjG+U4anqrrMdmeh8cNCITjymAd/y6ovVjf6xDvpWrEECZVeAsXIqcLRuMsFMwdND9Pwte8I0bxICiS3uvZRmuBXKGVqeT0jVU3eLzNUMxPVO9XoAJCnhPQZZzMQlM8XpE2eXcUIEdxL7doW9wECjSfWv7aNMTnEwpDyf/JoLT7ajJq6+6lc4PElrogrIs/y/zXkLk8KOIxkeG9aY3wIDAQAB';
// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$alipay_config['notify_url'] = "http://test.myplas.com/api/qapi1_1/alipayNotify";

// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
#$alipay_config['return_url'] = "http://www.eachke.com/orders/return/alipay";

//签名方式
$alipay_config['sign_type']    = strtoupper('RSA');

//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset']= strtolower('utf-8');

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
// 更改了这个文件到include文件下，方便以后用curl https   leon
//$alipay_config['cacert']    = \yii::getAlias('@root_path') . "/includes/ssl/cacert.pem";

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';

// 支付类型 ，无需修改
$alipay_config['payment_type'] = "1";
		
// 产品类型，无需修改
$alipay_config['service'] = "create_direct_pay_by_user";

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


//↓↓↓↓↓↓↓↓↓↓ 请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
	
// 防钓鱼时间戳  若要使用请调用类文件submit中的query_timestamp函数
$alipay_config['anti_phishing_key'] = "";
	
// 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
$alipay_config['exter_invoke_ip'] = "";
		
//↑↑↑↑↑↑↑↑↑↑请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
return $alipay_config;
?>
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArsurTmGTZqkRgQjbegA0aCVJiGNeJx5CklpGBF6ccdq+6HShEPx7bcAbV3nOekJT3AgGfNJ4953O8Ok8rJhNFIdvQYy9OhkssVHQKr+P4W9wl7Q0G0IG59Rsr39utiYEraDYjG+U4anqrrMdmeh8cNCITjymAd/y6ovVjf6xDvpWrEECZVeAsXIqcLRuMsFMwdND9Pwte8I0bxICiS3uvZRmuBXKGVqeT0jVU3eLzNUMxPVO9XoAJCnhPQZZzMQlM8XpE2eXcUIEdxL7doW9wECjSfWv7aNMTnEwpDyf/JoLT7ajJq6+6lc4PElrogrIs/y/zXkLk8KOIxkeG9aY3wIDAQAB