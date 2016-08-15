<?php
/**
*token
*/
class testAction
{
	function __construct()
	{
		define("TOKEN", "wodesuliao");
		$this->AppID = 'wxbe66e37905d73815';
		$this->AppSecret = '7eb6cc579a7d39a0e123273913daedb0';
		// $this->AppID = 'wx00df62a914e25294';
		// $this->AppSecret = '9be0026abfd442209334c1ef28bc46e6';
		$this->mch_id = '1324710901';
		$this->partnerkey = 'x39kmrlyOBOYvfR3vBlJpnAvkNsmQygJ';
		if(!isset($_GET["echostr"])){
		    $this->responseMsg();
		}else{
 			$echoStr = $_GET["echostr"];
			if($this->valid()){
		 		echo $echoStr;
		 		exit;
		 	}
		}
	}
	protected function valid() {
		if (!defined("TOKEN")) {
			define("TOKEN", "wodesuliao");
		}
      $signature = $_GET["signature"];
      $timestamp = $_GET["timestamp"];
      $nonce = $_GET["nonce"];
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
   }
   

   
   public function responseMsg()
   {
       //get post data, May be due to the different environments
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
   
       //extract post data
       if (!empty($postStr)){
            
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            
           $fromUsername = $postObj->FromUserName;
           $toUsername = $postObj->ToUserName;
           $MsgType = $postObj->MsgType;
   
           $time = time();
            
           $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";
   
           switch($MsgType)
           {
               case "text":
                   $resultStr = $this->handleText($postObj);
                   break;
               case "link":
                   $resultStr = $this->handleLink($postObj);
                   break;
               case "event":
                   $resultStr = $this->handleEvent($postObj);
                   break;
               case "image":
                   $resultStr = $this->handleImage($postObj);
                   break;
               case "voice":
                   $resultStr = $this->handleVoice($postObj);
                   break;
               case "video":
                   $resultStr = $this->handleVideo($postObj);
                   break;
               case "location":
                   $resultStr = $this->responseLocation($postObj);
                   break;
               default:
                   break;
           }
       }else {
           echo "";
           exit;
       }
   }
   
   
   ////判断点击事件
   public function  handleEvent($postObj)
   {
       $FromUserName = $postObj->FromUserName;
       $ToUserName = $postObj->ToUserName;
       $MsgType = $postObj->MsgType;
       $Event = $postObj->Event;
       $CreateTime = $postObj->CreateTime;
   
//        require("db.php");
       //$dbh = mysql_connect("localhost:3306","root","root");
       //mysql_select_db("***");
       //mysql_query("set names UTF8");
        
       switch ($postObj->Event)
       {
           case "subscribe":
//                $sqlsubscribe = "select * from account where OpenId = '".trim($FromUserName)."'";
//                $connsubscribe = mysql_query($sqlsubscribe,$dbh);
//                $resultsubscribe = mysql_fetch_assoc($connsubscribe);
                
//                $FakeID = file_get_contents("http://bauschlomb.ruisheng.info/success.php");
                
//                if($resultsubscribe){
//                    $sqlinTu = "update account set Enable = 0,CreateTime = $CreateTime,FakeId ='".trim($FakeID)."'   where OpenID ='".trim($FromUserName)."'";
//                    $connTu = mysql_query($sqlinTu,$dbh);
//                }else{
//                    ////如果数据库没有这个用户的记录
//                    $insertsubscribe = "insert into account(OpenID,Enable,State,FakeId,CreateTime) values('".trim($FromUserName)."',0,10,'".trim($FakeID)."',$CreateTime)";
//                    $insertsubscribe = mysql_query($insertsubscribe,$dbh);
//                }
//                $sqlsub = "insert into message(FromUserName,ToUserName,MsgType,Event,CreateTime) values ('".trim($FromUserName)."','".trim($ToUserName)."','event','subscribe',$CreateTime)";
//                $resultsub  = mysql_query($sqlsub,$dbh);
                
               $resultStr = $this->responseSubscribe($postObj);
               break;
   
           case "unsubscribe":
               $contentStr = "取消关注微信号：!查看更多内容!!!.";///////这里是最先开始加关注的时候,发出的信息
//                $sqlunmsg = "insert into message(FromUserName,ToUserName,MsgType,Event,CreateTime) values ('".trim($FromUserName)."','".trim($ToUserName)."','".trim($MsgType)."','unsubscribe',$CreateTime)";
//                $resultunmsg  = mysql_query($sqlunmsg,$dbh);
//                $sqlinTu = "update account set Enable = 1  where OpenId ='".trim($FromUserName)."'";
//                $connTu = mysql_query($sqlinTu,$dbh);
//                ///StaticMethod::delUserInfoBySub($FromUserName,$CreateTime);
               $resultStr = $this->responseEvent($postObj,$contentStr);
               break;
           case "CLICK":
               switch ($postObj->EventKey)
               {
                   case "V1001_Product011":
                       $contentStr = '';
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Product012":
                       $contentStr = '增减。';
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Product013":
                       $contentStr = 'bb';
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Product014":
                       $contentStr ='b一次2~3滴。';
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Personal021":
                       $contentStr = "";
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Personal022":
                       $contentStr = "";
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Personal023":
                       $contentStr = "";
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Personal024":
                       $contentStr = "请回复【认证】，即可开启认证流程";
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Personal025":
                       $contentStr = "请回复【学分查询】，即可对自己当前学分情况进行了解";
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Activity031":
                       $contentStr = "";
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
                   case "V1001_Activity032":
                       ///$contentStr = "敬请期待";
                       ///$resultStr = $this->responseEvent($postObj, $contentStr);
                       $this->responseMatchGuess($postObj);
                       break;
                   case "V1001_Activity033":
                       //$contentStr = "活动信息";
                       //$resultStr = $this->responseEvent($postObj, $contentStr);
                       $resultStr = $this->responseGrid($postObj);
                       break;
                   case "V1001_Activity034":
                       //$contentStr = "活动信息";
                       //$resultStr = $this->responseEvent($postObj, $contentStr);
                       $resultStr = $this->responseMood($postObj);
                       break;
                   case "V1001_Activity035":
                       $contentStr = "第二期考试活动已结束";
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       ///$this->responseExam($postObj);
                       break;
                   default:
                       $contentStr = "欢迎关注微课堂\n\n";
                       $resultStr = $this->responseEvent($postObj, $contentStr);
                       break;
               }
       }
   }
    
   ///针对Event事件的文本回复（正确）
   public function responseEvent($postObj,$contentStr)
   {
       $time = time();
       $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        
       $resultStr = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName, $time,$contentStr);
       echo $resultStr;
       exit();
   }
   ////图片
   public function responseSubscribe($postObj)
   {
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
       ///图文混排
       if (!empty($postStr)){
           $fromUsername = $postObj->FromUserName;
           $toUsername = $postObj->ToUserName;
           $CreateTime =$postObj->CreateTime;
           $time = time();
           $msgType ="news";
            
           $title ="欢迎您 ！";
           $description = "请回复【认证】马上进行会员认证，成功绑定就能获得学分，先到先得！早到多得！点击“阅读全文”有惊喜哦。";
   
           $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <ArticleCount>1</ArticleCount>
                            <Articles>
                                <item>
                                <Title><![CDATA[".$title."]]></Title>
                                <Description><![CDATA[".$description."]]></Description>
                                <PicUrl><![CDATA[http://***.info/images/mmexport2.jpg]]></PicUrl>
                                <Url><![CDATA[http://****.info/yyy.htm]]></Url>
                                </item>
                            </Articles>
                           <FuncFlag>1</FuncFlag>
                  </xml> ";
            
           $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $CreateTime, $msgType,$title,$description);
           echo $resultStr;
           exit;
       }
   }
   ///判断文本
   public function handleText($postObj)
   {
       $fromUsername = $postObj->FromUserName;
       $toUserName = $postObj->ToUserName;
       $keyword = $postObj->Content;
       $CreateTime = $postObj->CreateTime;
        
       require("db.php");
       //$dbh = mysql_connect("localhost:3306","root","root");
       //mysql_select_db("****");
       //mysql_query("set names UTF8");
        
       $sqlmsg = "insert into message(FromUserName,ToUserName,MsgType,Content,CreateTime) values ('".trim($fromUsername)."','".trim($toUserName)."','text','".trim($keyword)."',$CreateTime)";
       $result  = mysql_query($sqlmsg,$dbh);
        
       $time = time();
   
       $sqlMapping ="select count(*) as count from mapping where OpenId = '".trim($fromUsername)."'";
       $mappingRs  = mysql_query($sqlMapping,$dbh);
       $mappingRow = mysql_fetch_assoc($mappingRs);
       $mappingCount = $mappingRow["count"];
   
       if(intval($mappingCount)!=0){
   
           ////说明这个人已经访问过了
           //根据OpenId查询Mapping表的Telephone
           ///查询关键子 start
            
           $sqlkeywords = "select KeyWords from customsg";
           $keywordRs  = mysql_query($sqlkeywords,$dbh);
           $keys = array();
           while($keyArray  = mysql_fetch_assoc($keywordRs)){
               $keys[] = $keyArray["KeyWords"];
           }
           $catekeywords = "select CategoryName from category";
           $catekeywordRs  = mysql_query($catekeywords,$dbh);
           $catekeys = array();
           while($catekeyArray  = mysql_fetch_assoc($catekeywordRs)){
               $catekeys[] = $catekeyArray["CategoryName"] ;
           }
           ////关键字end
           /////查询category.customsg表里关键字 ----start--
            
           if(in_array($keyword,$keys))
           {
               $wordresult = mysql_query("select * from customsg where KeyWords = '".$keyword."'",$dbh);
               $wordrow = mysql_fetch_assoc($wordresult);
                
               ////$count = intval($wordrow["Count"]);
               ////if(empty($count)){ $count = 1 ;}else{  $count = intval($count) +1;}
               ////$updatesql ="update manager set  Count= $count and LoginTime = $time where username='".$username."'  and password ='".$password."'";
                
               if(!empty($wordrow)){
                   mysql_query("update category set count =count+1 where CategoryName = '".$keyword."'",$dbh);
               }
               $time =time();
               /*if($wordrow["MsgType"]=="music")
                {
                $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%d</CreateTime>
                <MsgType><![CDATA[music]]></MsgType>
                <Music>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <MusicUrl><![CDATA[%s]]></MusicUrl>
                <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                </Music>
                </xml>";
                $title = $wordrow["Title"];
                $description = $wordrow["Description"];
                $musicUrl = 'http://www.xyzk.net/images/bgm/Audio/tianya.mp3';
                $HQMusicUrl = 'http://www.xyzk.net/images/bgm/Audio/tianya.mp3';
                 
                $resultStr = sprintf($textTpl,$fromUsername,$toUserName,$time,$title,$description,$musicUrl,$HQMusicUrl);
                echo  $resultStr;
   
                }else*/if($wordrow["MsgType"]=='news'){
                 
                $Title =$wordrow["Title"];
                $Description = $wordrow["Description"];
                $PicUrl = "http://**********.info/".$wordrow["PicUrl"];
                $Url = $wordrow["Url"]."?OpenID=$fromUsername";
   
                $textTpl2 = "<xml>
                                            <ToUserName><![CDATA[%s]]></ToUserName>
                                            <FromUserName><![CDATA[%s]]></FromUserName>
                                            <CreateTime>%s</CreateTime>
                                            <MsgType><![CDATA[news]]></MsgType>
                                            <Content><![CDATA[%s]]></Content>
                                            <ArticleCount>1</ArticleCount>
                                            <Articles>
                                                <item>
                                                <Title><![CDATA[".$Title."]]></Title>
                                                <Description><![CDATA[".$Description."]]></Description>
                                                <PicUrl><![CDATA[".$PicUrl."]]></PicUrl>
                                                <Url><![CDATA[".$Url."]]></Url>
                                                </item>
                                             </Articles>
                                            <FuncFlag>0</FuncFlag>
                                        </xml>";
                $resultStr = sprintf($textTpl2,$postObj->FromUserName,$postObj->ToUserName,$time,$Title,$Description);
                echo $resultStr;
               }else{
                   $textTpl3 = "<xml>
                                                <ToUserName><![CDATA[%s]]></ToUserName>
                                                <FromUserName><![CDATA[%s]]></FromUserName>
                                                <CreateTime>%s</CreateTime>
                                                <MsgType><![CDATA[text]]></MsgType>
                                                <Content><![CDATA[%s]]></Content>
                                                <FuncFlag>0</FuncFlag>
                                                </xml>";
                    
                   $contentStr = $wordrow["Content"];
                   $resultStr = sprintf($textTpl3, $postObj->FromUserName, $postObj->ToUserName, $time,$contentStr);
                   echo $resultStr;
                   ///$this->responseEvent($postObj,$contentStr);
               }
               /*
                }elseif(in_array($keyword,$catekeys)){
                //////只在Category表中
                mysql_query("update category set count =count+1 where KeyWords = '".$keyword."'",$dbh);
                $contentStr = "属于关键子列表Category,目前没有自定义的显示形式";
                $this->responseEvent($postObj,$contentStr);
   
                */}else{
   
                $sqlMappingTel ="select Telephone from mapping where OpenId = '".$fromUsername."'";
                $mappingTelRs  = mysql_query($sqlMappingTel,$dbh);
                $mappingTelRow = mysql_fetch_assoc($mappingTelRs);
                 
                $Telephone = $mappingTelRow["Telephone"];
                 
                $sqluser = "select * from account where Telephone='".$Telephone."'";
                $connuser = mysql_query($sqluser,$dbh);
                $userrow = mysql_fetch_assoc($connuser);
                 
                if($keyword=='学分查询')
                {
                    $id = $userrow["id"];
                    $RealName = $userrow["RealName"];
                    $Certification = $userrow["Certification"];
                    $State = $userrow["State"];
   
                    if($State=='1' || $State==1){
                        $contentStr = $RealName.",您是内部人员。欢迎来到”微课堂“，回复【帮助】可获得更多辅助信息。";
                    }else{
                        $contentStr = $RealName.",您是第 $id 位认证会员，得到 $Certification 学分。欢迎来到”微课堂“，回复【帮助】可获得更多辅助信息。";
                    }
                    $this->responseEvent($postObj,$contentStr);
                     
                }elseif($keyword=="考试2323232323"){
                    ////$contentStr = "欢迎来到”微课堂“，回复【心情】可获得更多辅助信息。";
                    $this->responseExam($postObj);
                     
                }elseif($keyword=="心情"){
                    ////$contentStr = "欢迎来到”微课堂“，回复【心情】可获得更多辅助信息。";
                    $this->responseMood($postObj);
                     
                }elseif($keyword=="百万格子"){
                    ////$contentStr = "欢迎来到”微课堂“，回复【心情】可获得更多辅助信息。";
                    $this->responseGrid($postObj);
                     
                }elseif($keyword=="照片陈列竞赛"){
                    ////$contentStr = "欢迎来到”“，回复【心情】可获得更多辅助信息。";
                    $this->responsePhotoWall($postObj);
   
                }elseif($keyword=="世界杯"){
                    ////$contentStr = "欢迎来到”课堂“，回复【世界杯】可获得更多辅助信息。";
                    $this->responseMatchGuess($postObj);
                     
                }else{
                    if($keyword =="认证"){
                        $id = $userrow["id"];
                        $RealName = $userrow["RealName"];
                        $Certification = $userrow["Certification"];
                        $State = $userrow["State"];
                        if($State=='1'){
                            $contentStr = $RealName.",您已通过认证。回复【帮助】可获得更多辅助信息。";
                        }else{
                            $contentStr = $RealName.",您已通过认证。您是第 $id 位认证会员，已获得 $Certification 学分。回复【学分查询】即可查询当前学分情况。";
                        }
                        $this->responseEvent($postObj,$contentStr);
                    }else{
                        $this->responseGraphic($postObj);
                    }
                }
               }
       }else{
           ////说明这个人没有认证，
           ///保存用户访问记录，然后删除
           $sqlIfExsitSign="select * from signin where OpenId = '".trim($fromUsername)."'";
           $signIsExsitRs  = mysql_query($sqlIfExsitSign,$dbh);
           $signIsExsitRow = mysql_fetch_assoc($signIsExsitRs);
            
           if($keyword =="认证")
           {
               $contentStr ="请输入手机号码";
               $this->responseEvent($postObj,$contentStr);
   
           }elseif(preg_match("/^1[34578][0-9]{9}$/",trim($keyword))){
               ////匹配手机号码
               $sqlinT = "select * from account where Telephone = '".$keyword."'";
               $connT = mysql_query($sqlinT,$dbh);
               $resultT = mysql_fetch_assoc($connT);
               if($resultT){
                   ///account 表中有号码
                   $signInsert = "insert into signin(OpenId,Telephone,CreateDate)  values ('".trim($fromUsername)."','".$keyword."',$CreateTime)";
                   $connSign = mysql_query($signInsert,$dbh);
                    
                   $contentStr ="请输入姓名";
                   $this->responseEvent($postObj,$contentStr);
               }else{
                   ////$contentStr ="您的电话号不存在，请重新认证";
                   //$contentStr ="非常抱歉认证失败，可能由于您的电话号码或者姓名输入有误，请重新回复 【认证】 进行会员认证，谢谢。" ;
                   $contentStr ="请输入姓名";
                   $this->responseEvent($postObj,$contentStr);
               }
           }elseif($signIsExsitRow){
   
               ////向数据表 ACCout 中查询 用户信息
                
               $Telephone = $signIsExsitRow["Telephone"];
               $RealName =  trim($keyword);
               $time = time();
               $sqlAccountTRStr = "select * from account where Telephone = '".$Telephone."'  and RealName ='".$RealName."' ";
               $accountTRRs = mysql_query($sqlAccountTRStr,$dbh);
               $accountTRRow = mysql_fetch_assoc($accountTRRs);
                
               $State = $accountTRRow["State"];
                
               if($State=='1'|| $State==1){
                   //$sqlAccountTRStrUpdate = "update account set Certification =10 where Telephone = '".$Telephone."'  and RealName ='".$RealName."' ";
                   //$accountTRRsUpdate = mysql_query($sqlAccountTRStrUpdate,$dbh);
               }elseif($State=='2'|| $State==2 || $State=='3'|| $State==3 || $State==4 || $State=='4'){
                    
                   $sqlAccountTRStrUpdate = "update account set Certification =50 where Telephone = '".$Telephone."'  and RealName ='".$RealName."' ";
                   $accountTRRsUpdate = mysql_query($sqlAccountTRStrUpdate,$dbh);
                    
                   $sqlScoreLog = "insert into scorelog(RealName,Telephone,Score,Type,Description,Source,CreateTime)values('".$RealName."','".$Telephone."','50','Certification','认证分数','新用户进行认证',$time);";
                   $ScoreLogResult= mysql_query($sqlScoreLog,$dbh);
   
               }else{
                    
               }
               if(!empty($accountTRRow)){
                    
                   $distictSql ="select count(*) as c from mapping where OpenID ='".trim($fromUsername)."'";
                   $distictRs =mysql_query($distictSql,$dbh);
                   $distictCount =0 ;
                   while($distictRow = mysql_fetch_array($distictRs)){
                       $distictCount = $distictRow["c"];
                   }
                   if($distictCount>0){
                       $contentStr = "您的微信号已经绑定，如果不是您的微信账号，请联系我们";
                   }else{
                       ///删除签到表
                       $deleteSign ="delete from signin where OpenId ='".trim($fromUsername)."'";
                       $delSignRs =mysql_query($deleteSign,$dbh);
                        
                       ////插入一条mapping记录方便下次记录用户信息
                       $insertMapping = "insert into mapping(OpenId,Telephone,CreateTime) values('".trim($fromUsername)."','".$Telephone."',$time)";
                       $insertMapRs = mysql_query($insertMapping,$dbh);
                       if($State=='1'|| $State==1){
                           $contentStr = $RealName.",您已通过认证，回复【帮助】可获得更多辅助信息。";
                            
                       }else{
                           $uid = $accountTRRow["id"];
                           $Certification = $accountTRRow["Certification"];
                           $contentStr = $RealName.",您已通过认证。您是第 $uid 位认证会员，已获得  $Certification 学分。";
                       }
                   }
                   $this->responseEvent($postObj,$contentStr);
               }else{
                   ///删除签到表
                   $deleteSign ="delete from signin where OpenId ='".trim($fromUsername)."'";
                   $delSignRs =mysql_query($deleteSign,$dbh);
                    
                   $contentStr = "非常抱歉认证失败,可能由于您的电话号码或者姓名输入有误，请重新回复 【认证】 进行会员认证，谢谢。";
                   $this->responseEvent($postObj,$contentStr);
               }
           }else{
               $contentStr ="非常抱歉认证失败,可能由于您的电话号码或者姓名输入有误，请重新回复 【认证】 进行会员认证，谢谢。";
               $this->responseEvent($postObj,$contentStr);
           }
       }
   }
    
   ////图片---考试
   public function responseExam($postObj)
   {
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
       ///图文混排
       if (!empty($postStr)){
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
           $fromUsername = $postObj->FromUserName;
           $toUsername = $postObj->ToUserName;
           $keyword = trim($postObj->Content);
           $time = time();
   
           $msgType ="news";
           $title ="学期第二期";
           $description = '累积学分拿好"礼"答对1题50分 答错不计分）！';
   
   
           $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <ArticleCount>1</ArticleCount>
                            <Articles>
                                <item>
                                <Title><![CDATA[".$title."]]></Title>
                                <Description><![CDATA[".$description."]]></Description>
                                   <PicUrl><![CDATA[http://***.info/images/bauschlomb_exam2.jpg]]></PicUrl>
                                   <Url><![CDATA[http://***.info/interHandler.php?OpenID=$fromUsername&param=exam]]></Url>
                                   </item>
                                   </Articles>
                                   <FuncFlag>1</FuncFlag>
                                   </xml> ";
           if(!empty($keyword))
           {
               $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$title,$description);
               echo $resultStr;
           }else{
               echo 'nokeyword';
           }
           exit;
       }
   }
    
    
   ////图片------心情点赞
   public function responseMood($postObj)
   {
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
       ///图文混排
       if (!empty($postStr)){
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
           $fromUsername = $postObj->FromUserName;
           $toUsername = $postObj->ToUserName;
           $keyword = trim($postObj->Content);
           $time = time();
   
           $msgType ="news";
           $title ='【心心情一起为完美点"睛"';
            
           $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <ArticleCount>1</ArticleCount>
                            <Articles>
                                <item>
                                <Title><![CDATA[".$title."]]></Title>
                                   <Description><![CDATA[用一双润眸传达你每天的多彩心情，每物！]]></Description>
                                   <PicUrl><![CDATA[http://***.info/images/mood_kv_20140508.jpg]]></PicUrl>
                                   <Url><![CDATA[http://****.info/interHandler.php?OpenID=$fromUsername&param=mood]]></Url>
                                   </item>
                                   </Articles>
                                   <FuncFlag>1</FuncFlag>
                                   </xml> ";
           //if(!empty($keyword))
           //{
           $description = "情，获学分赢精美提示物！";
           $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$title,$description);
           echo $resultStr;
           //}else{
           //echo 'nokeyword';
           //}
           //exit;
       }
   }
    
    
   ////图片------百万格子
   public function responseGrid($postObj)
   {
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
       ///图文混排
       if (!empty($postStr)){
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
           $fromUsername = $postObj->FromUserName;
           $toUsername = $postObj->ToUserName;
           $keyword = trim($postObj->Content);
           $time = time();
   
           $msgType ="news";
           $title ='百万格子';
            
           $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <ArticleCount>1</ArticleCount>
                            <Articles>
                                <item>
                                <Title><![CDATA[".$title."]]></Title>
                                   <Description><![CDATA[参与活动获学分赢精美提示物！]]></Description>
                                   <PicUrl><![CDATA[http://***.info/images/million.jpg]]></PicUrl>
                                   <Url><![CDATA[http://****.info/interHandler.php?OpenID=$fromUsername&param=grid]]></Url>
                                   </item>
                                   </Articles>
                                   <FuncFlag>1</FuncFlag>
                                   </xml> ";
           //if(!empty($keyword))
           //{
           $description = "美提示物！";
           $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$title,$description);
           echo $resultStr;
           //}else{
           ///echo 'nokeyword';
           //}
           ///exit;
       }
   }
    
   ////图片------照片陈列竞赛
   public function responsePhotoWall($postObj)
   {
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
       ///图文混排
       if (!empty($postStr)){
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
           $fromUsername = $postObj->FromUserName;
           $toUsername = $postObj->ToUserName;
           $keyword = trim($postObj->Content);
           $time = time();
   
           $msgType ="news";
           $title ='照片陈列竞赛';
            
           $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <ArticleCount>1</ArticleCount>
                            <Articles>
                                <item>
                                <Title><![CDATA[".$title."]]></Title>
                                   <Description><![CDATA[参与活动获学分赢精美提示物！]]></Description>
                                   <PicUrl><![CDATA[http://***.info/images/photowall.jpg]]></PicUrl>
                                   <Url><![CDATA[http://&&&&&&&.info/interHandler.php?OpenID=$fromUsername&param=photo]]></Url>
                                   </item>
                                   </Articles>
                                   <FuncFlag>1</FuncFlag>
                                   </xml> ";
           if(!empty($keyword))
           {
               $description = "照片陈列竞赛";
               $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$title,$description);
               echo $resultStr;
           }else{
               echo 'nokeyword';
           }
           exit;
       }
   }
    
   ////世界杯大竞赛
   public function responseMatchGuess($postObj)
   {
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
       ///图文混排
       if (!empty($postStr)){
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
           $fromUsername = $postObj->FromUserName;
           $toUsername = $postObj->ToUserName;
           $keyword = trim($postObj->Content);
           $time = time();
   
           $msgType ="news";
           $title ='世界杯大竞猜';
            
           $textTpl8 = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <ArticleCount>1</ArticleCount>
                            <Articles>
                                <item>
                                <Title><![CDATA[".$title."]]></Title>
                                   <Description><![CDATA[世界杯大竞猜，赢学分！兑好礼！]]></Description>
                                   <PicUrl><![CDATA[http://****.info/img/match.jpg]]></PicUrl>
                                   <Url><![CDATA[http://********.info/interHandler.php?OpenID=$fromUsername&param=match]]></Url>
                                   </item>
                                   </Articles>
                                   <FuncFlag>1</FuncFlag>
                                   </xml> ";
           //if(!empty($keyword))
           //{
           $description = "世界杯大兑好礼！";
           $resultStr = sprintf($textTpl8, $fromUsername, $toUsername, $time, $msgType,$title,$description);
           echo $resultStr;
           ///}else{
           //echo 'nokeyword';
           ///}
           ///exit;
       }
   }
    
   ///判断Link,接收目的文件 目前只有图文混排
   public function handleLink($postObj)
   {
       $fromUsername = $postObj->FromUserName;
       $toUsername = $postObj->ToUserName;
       $msgType = trim($postObj->MsgType);
       $CreateTime = $postObj->CreateTime;
       $Title = $postObj->Title;
       $Description = $postObj->Description;
       $Url = $postObj->Url;
   
       require("db.php");
       //$dbh = mysql_connect("localhost:3306","root","root");
       //mysql_select_db("&&&&&");
       //mysql_query("set names UTF8");
        
       $sql = "insert into message(FromUserName,ToUserName,MsgType,Title,Description,Url,CreateTime) values ('".$fromUsername."','".$toUsername."','".$msgType."','".$Title."','".$Description."','".$Url."',$CreateTime)";
       $result  = mysql_query($sql,$dbh);
        
       $time = time();
        
       $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                        <item>
                        <Title><![CDATA[".$Title."]]></Title>
                        <Description><![CDATA[".$Description."]]></Description>
                        <PicUrl><![CDATA[http://********o/images/boshilun.jpg]]></PicUrl>
                        <Url><![CDATA[".$Url."]]></Url>
                        </item>
                        <item>
                     </Articles>
                    <FuncFlag>1</FuncFlag>
                    </xml>";
        
       $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time,$Title,$Description);
       echo $resultStr;
       exit();
   }
   ///判断图片
   public function handleImage($postObj)
   {
       $fromUsername = $postObj->FromUserName;
       $toUsername = $postObj->ToUserName;
       $msgType = trim($postObj->MsgType);
       $PicUrl = $postObj->PicUrl;
       $CreateTime = $postObj->CreateTime;
        
       $MediaId = $postObj->MediaId;
        
       require("db.php");
       //$dbh = mysql_connect("localhost:3306","root","root");
       //mysql_select_db("*********");
       //mysql_query("set names UTF8");
        
       $sql = "insert into message(FromUserName,ToUserName,MsgType,PicUrl,MediaId,CreateTime) values ('".$fromUsername."','".$toUsername."','".$msgType."','".$PicUrl."','".$MediaId."',$CreateTime)";
       $result  = mysql_query($sql,$dbh);
        
       $time = time();
   
       $sqlOpenId = "select * from account where OpenId = '".$fromUsername."'";
       $userResult = mysql_query($sqlOpenId,$dbh);
       $userrow =  mysql_fetch_assoc($userResult) ;
        
       $RealName = $userrow["RealName"];
       $Telephone = $userrow["Telephone"];
       $contentStr = $RealName .":上传了图片";
        
       $sqlimg ="insert into photowall(OpenId,RealName,Telephone,MediaId,MsgType,PicUrl,CreateTime) values ('".$fromUsername."','".$RealName."','".$Telephone."','".$MediaId."','image','".$PicUrl."',$CreateTime)";
       $resultimg  = mysql_query($sqlimg,$dbh);
        
       $this->responseEvent($postObj,$contentStr);
       /*
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        <FuncFlag>0</FuncFlag>
        </xml>";
        $resultStr = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName, $time,$contentStr);
        echo $resultStr;
        exit();
       */
   }
    
    
   ///判断语音
   public function handleVoice($postObj)
   {
       $fromUsername = $postObj->FromUserName;
       $toUsername = $postObj->ToUserName;
       $MsgType = $postObj->MsgType;
        
       $MediaId = $postObj->MediaId;
       $Format = $postObj->Format;
       $Recognition = $postObj->Recognition;
        
       $CreateTime = $postObj->CreateTime;
        
       require("db.php");
       //$dbh = mysql_connect("localhost:3306","root","root");
       //mysql_select_db("********");
       //mysql_query("set names UTF8");
        
       $sql = "insert into message(FromUserName,ToUserName,MsgType,MediaId,Format,Recognition,CreateTime) values ('".$fromUsername."','".$toUsername."','".$MsgType."','".$MediaId."','".$Format."','".$Recognition."',$CreateTime)";
       $result  = mysql_query($sql,$dbh);
       $time = time();
       $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[voice]]></MsgType>
                <Voice>
                <MediaId><![CDATA[".$MediaId."]]></MediaId>
                <Format><![CDATA[".$Format."]]></Format>
                </Voice>
                </xml>";
       $resultStr = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName, $time,$MediaId,$Format);
       echo $resultStr;
       exit();
   }
   
   
   
   ///判断视频
   public function handleVideo($postObj)
   {
       $fromUsername = $postObj->FromUserName;
       $toUsername = $postObj->ToUserName;
       $msgType = $postObj->MsgType;
       $MediaId = $postObj->MediaId;
       $ThumbMediaId = $postObj->ThumbMediaId;
       $CreateTime = $postObj->CreateTime;
   
       require("db.php");
       //$dbh = mysql_connect("localhost:3306","root","root");
       //mysql_select_db("*********");
       //mysql_query("set names UTF8");
        
       $sql = "insert into message(FromUserName,ToUserName,MsgType,MediaId,ThumbMediaId,CreateTime) values ('".$fromUsername."','".$toUsername."','".$msgType."','".$MediaId."','".$ThumbMediaId."',$CreateTime)";
       $result  = mysql_query($sql,$dbh);
       $time = time();
       $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[video]]></MsgType>
                    <Video>
                    <MediaId><![CDATA[".$MediaId."]]></MediaId>
                    <ThumbMediaId><![CDATA[".$ThumbMediaId."]]></ThumbMediaId>
                    </Video>
                    </xml>";
        
       $resultStr = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName, $time, $msgType,$MediaId,$ThumbMediaId);
       echo $resultStr;
       exit();
   }
    
    
    
   ////图片
   public function responseGraphic($postObj)
   {
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
       ///图文混排
       if (!empty($postStr)){
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
           $fromUsername = $postObj->FromUserName;
           $toUsername = $postObj->ToUserName;
           $keyword = trim($postObj->Content);
           $time = time();
   
           $msgType ="news";
            
           $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <ArticleCount>1</ArticleCount>
                            <Articles>
                                <item>
                                <Title><![CDATA[什么可以帮助您?]]></Title>
                                <Description><![CDATA[询目前学分情况，过往信息查询，可点击右上角图标，查询历史消息。]]></Description>
                                <PicUrl><![CDATA[http://***g.info/images/mmexport2.jpg]]></PicUrl>
                                <Url><![CDATA[http://********.info/help.html]]></Url>
                                </item>
                            </Articles>
                            <FuncFlag>1</FuncFlag>
                  </xml> ";
           if(!empty($keyword))
           {
               $title ="以帮助您?";
               $description = "息查询，可点击右上角图标，查询历史消息。";
   
               $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$title,$description);
               echo $resultStr;
           }else{
               echo 'nokeyword';
           }
           exit;
       }
   }
    
    
   /////返回音乐文件
   public function responseMusic()
   {
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        
       if (!empty($postStr)){
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
           $fromUsername = $postObj->FromUserName;
           $toUsername = $postObj->ToUserName;
           $keyword = trim($postObj->Content);
            
           $time = time();
            
           $textTpl = "<xml>
                     <ToUserName><![CDATA[%s]]></ToUserName>
                     <FromUserName><![CDATA[%s]]></FromUserName>
                     <CreateTime>%d</CreateTime>
                     <MsgType><![CDATA[music]]></MsgType>
                     <Music>
                     <Title><![CDATA[%s]]></Title>
                     <Description><![CDATA[%s]]></Description>
                     <MusicUrl><![CDATA[%s]]></MusicUrl>
                     <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                     </Music>
                </xml>";
            
           $title = '爱乐团-天涯';
           $msgType ='music';
           $description = '由金牌音乐制作人王超领军，坚持词曲编全部原创的路线。加女主音胡霖高亢的声音加所有爱音乐的朋友等于爱乐团。现由胡霖和王超两位成员组成。（原主唱为徐立)2005年发行第一张大碟《天涯》，艳惊整个华语乐坛';
           $musicUrl = 'http://www.xyzk.net/images/bgm/Audio/tianya.mp3';
           $HQMusicUrl = 'http://www.xyzk.net/images/bgm/Audio/tianya.mp3';
            
           $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $title, $description, $musicUrl, $HQMusicUrl);
           echo $resultStr;
   
           exit;
       }
   }
   public function responseLocation($postObj)
   {
       $fromUsername = $postObj->FromUserName;
       $toUsername = $postObj->ToUserName;
        
       $Location_X = $postObj->Location_X;
       $Location_Y = $postObj->Location_Y;
       $Scale = $postObj->Scale;
       $Label = $postObj->Label;
        
       $CreateTime = $postObj->CreateTime;
        
       $time = time();
        
       require("db.php");
       //$dbh = mysql_connect("localhost:3306","root","root");
       //mysql_select_db("******");
       //mysql_query("set names UTF8");
        
       $sql = "insert into message(FromUserName,ToUserName,MsgType,Location_X,Location_Y,Scale,Label,CreateTime) values ('".trim($fromUsername)."','".$toUsername."','location','".$Location_X."','".$Location_Y."','".$Scale."','".$Label."',$CreateTime)";
       $result  = mysql_query($sql,$dbh);
        
        
       $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        
       $contentStr =$sql;
       $resultStr = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName,$time,$contentStr);
       echo $resultStr;
   }
   
 
}