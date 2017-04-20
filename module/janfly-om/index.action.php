<?php
class indexAction extends homeBaseAction{


	public function init()
	{

	    ini_set("max_execution_time", '0');
	    //准备测试的版本的东西
	    //这个是为了可以用API命令接口完成监听方和被监听方的监听过程的测试
	    
	    if((isset($_POST['listener'])) || (!empty($_POST['listener']))){
	        $postdata = $_POST['listener'];
	    }else{
	        $postdata = file_get_contents("php://input");
	    }
	    echo $postdata;
	    
	    // //监听者
	    // $listener=isset($_POST['listener'])?$_POST['listener']:'201';
	    // //被监听者
	    // $byListener=isset($_POST['byListener'])?$_POST['listener']:'202';
	    
	    
	    // 		// $host="10.129.250.151";//设备地址
	    // 		$host="192.168.0.116";//设备地址
	    // 		$port=6060;//设备web远程端口
	    // 		//创建一个socket
	    // 		$socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP)or die("cannot create socket\n");
	    // 		$conn=socket_connect($socket,$host,$port) or die("cannot connect server\n");
	    // 		if($conn){echo "client connect ok!<br/>";}
	    
	    // 		//响应格式
	    /* 		$apixml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<Control attribute=\"Connect\">\n<ext id=\"{$listener}}\"/>\n<ext id=\"{$byListener}\"/>\n</Control>\n";*/
	    // 		$xmllen = strlen($apixml);
	    // 		$httpstr = sprintf("POST /xml HTTP/1.1\r\nContent-Type:text/html\r\nHost:%s:%d\r\nContent-Length:%d\r\n\r\n%s",$host,$port,$xmllen,$apixml);
	    // 		socket_write($socket,$httpstr,strlen($httpstr)) or die("cannot write data\n");
	    // 		//echo "send was:";
	    // 		$rephttpstr = nl2br(htmlspecialchars($httpstr));
	    // 		//echo $rephttpstr;
	    // 		$resultstr = "";
	    // 		while($sRead=socket_read($socket,4096)){
	    // 			$resultstr .= $sRead;
	    // 		}
	    // 		$xml = simplexml_load_string($resultstr);
	    // 		$tmp=array();
	    // 		echo '<pre>';
	    
	    	
	    // 	/**
	    //  * Convert a SimpleXML object into an array (last resort).
	    //  * @param object $xml
	    //  * @param bool   $root    Should we append the root node into the array
	    //  * @return array|string
	    //  */
	    // function xmlToArr($xml, $root = true)
	    // {
	    
	    // 	if(!$xml->children())
	    // 	{
	    // 		return (string)$xml;
	    // 	}
	    // 	$array = array();
	    // 	foreach($xml->children() as $element => $node)
	    // 	{
	    // 		$totalElement = count($xml->{$element});
	    // 		if(!isset($array[$element]))
	    // 		{
	    // 			$array[$element] = "";
	    // 		}
	    // 		// Has attributes
	    // 		if($attributes = $node->attributes())
	    // 		{
	    // 			$data = array('attributes' => array(), 'value' => (count($node) > 0) ? xmlToArr($node, false) : (string)$node);
	    // 			foreach($attributes as $attr => $value)
	    // 			{
	    // 				$data['attributes'][$attr] = (string)$value;
	    // 			}
	    // 			if($totalElement > 1)
	    // 			{
	    // 				$array[$element][] = $data;
	    // 			}
	    // 			else
	    // 			{
	    // 				$array[$element] = $data;
	    // 			}
	    // 			// Just a value
	    // 		}
	    // 		else
	    // 		{
	    // 			if($totalElement > 1)
	    // 			{
	    // 				$array[$element][] = xmlToArr($node, false);
	    // 			}
	    // 			else
	    // 			{
	    // 				$array[$element] = xmlToArr($node, false);
	    // 			}
	    // 		}
	    // 	}
	    // 	if($root)
	    // 	{
	    // 		return array($xml->getName() => $array);
	    // 	}
	    // 	else
	    // 	{
	    // 		return $array;
	    // 	}
	    
	    // }
	    
	    // 	$result=xmlToArr($xml);    //调用函数
	    // 	print_r($result);
	    
	    
	    // 	// foreach($result['DeviceInfo']['devices'] as $key=>$value){
	    // 	// 		if($key=='ext'){
	    // 	// 			foreach($value as $row1){
	    // 	// 				$tmp['ext'][]=$row1['attributes'];
	    // 	// 			}
	    // 	// 		}
	    // 	// 		if($key=='line'){
	    // 	// 			foreach($value as $row){
	    // 	// 				$tmp['line'][]=$row['attributes'];
	    // 	// 			}
	    // 	// 		}
	    // 	// 		if($key=='group'){
	    // 	// 			$tmp['group'][]=$value;
	    // 	// 		}
	    // 	// }
	    // 	echo '这个是显示编码好的东西','<hr />';
	    // 	// }
	    // 	// 	echo '<pre>';
	    // 	// 	var_dump($xml->devices->ext);
	    // 	// 	print_r($xml);
	    // 	// 	echo '<hr />';
	    // 	// 	var_dump($tmp);
	    // 	//
	    // 	print_r($tmp);
	    // 	if(false){
	    // 	//if($resultstr){
	    // 		echo "<br/>response was:";
	    // 		$repstr = nl2br(htmlspecialchars($resultstr));
	    // 		echo $repstr;
	    // 	}
	    // 	socket_close($socket);
	    
// 		$this->display('index.html');
	}
}


 ?>