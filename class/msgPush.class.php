<?php
/**
 *websocket推送消息
 **/
class msgPush{

	public function __construct(){
	}

	/**
	 * 推送消息
	 */
	public function send($type,$content,$to){
	    //type:微信 weixin,app,pc,所有平台 publish
		// 指明给谁推送，为空表示向所有在线用户推送
		$to_uid = '';
		// 推送的url地址，上线时改成自己的服务器地址
		$push_api_url = "http://www.myplas.com:2121/";
// 		$post_data = array(
// 		    'type' => 'publish',
// 		    'content' => '上海中晨,全力以赴!',
// 		    'to' => $to_uid,
// 		);
		$post_data = array(
		    'type' => $type,
		    'content' => $content,
		    'to' => $to,
		);
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
		$return = curl_exec($ch);
		curl_close ($ch);
		var_export($return);
	}
}
