<?php
/*
 * Memcache Session类
 */
class appSession{
	public static $SSID = '';//加校验位的SSID
	private static $handler=null;
	private static $lifetime=null;
	private static $deviceid = '';
	private static $channel = '';

	private static function init($ssid=''){
		self::genHandle();
		self::$lifetime=1200;
		@ini_set('session.use_cookies',   0);
		@ini_set('session.name', 'ssid');
		if($ssid){
			self::$SSID = $ssid;
			//取前32位做CRC32位校检
            $tmp_sid = substr($ssid, 0, 32);
            if (self::genKey($tmp_sid) == substr($ssid, 32)){
                $ssid = $tmp_sid;
            }else{
                $ssid = '';
            }
		}

		if(empty($ssid)){
			$ssid = md5(uniqid(mt_rand(), true));
			self::$SSID = $ssid.self::genKey($ssid);
		}

		//校验ssid合法性
		session_id($ssid);
	}
	
	public static function start($ssid='', $deviceid='', $chanel=''){
		self::$deviceid = $deviceid ?: $_REQUEST['deviceid'];
		self::$channel = $chanel ?: $_SERVER['HTTP_USER_AGENT'];
		self::init($ssid);
		session_set_save_handler(
				array(__CLASS__, 'open'),
				array(__CLASS__, 'close'),
				array(__CLASS__, 'read'),
				array(__CLASS__, 'write'),
				array(__CLASS__, 'destroy'),
				array(__CLASS__, 'gc')
			);
		session_start();
	}
	
	public static function open($path='', $name=''){
		return true;
	}
	
	public static function close(){
		return true;
	}
	
	public static function read($ssid=''){
		$handel=self::genHandle();
		$out=$handel->get($ssid);
		if($out===false || $out == null) return '';	
		return $out;
	}
	
	public static function write($ssid='', $data=''){
		if(empty($data)) return false;
		return self::$handler->set($ssid, $data, MEMCACHE_COMPRESSED, self::$lifetime);
	}
	
	public static function destroy($ssid=''){
		self::genHandle();
		return self::$handler->delete($ssid);
	}
	
	public static function gc($lifetime){
		return true;
	}
	
	public static function getHandle(){
		self::genHandle();
		return self::$handler;
	}
	
	private static function genHandle(){
		if(empty(self::$handler)){
			$config=C('session_memcache');
			
			$memcache  = new Memcache;
			foreach(explode(',',$config) as $v) {
				$arr=explode(':',$v);
				$port=isset($arr[1]) ? intval($arr[1]) : 11211;
				$memcache->addServer($arr[0], $port);
			}
			self::$handler=$memcache;
		}
		return self::$handler;
	}

	public static function resetSession($ssid=''){
		self::destroy($ssid);
		return true;
	}

	public static function clear(){
		$handel=self::genHandle();
		$handel->flush();
	}

	private function genKey($sid) {
        return sprintf('%08x', crc32(self::$deviceid.self::$channel.$sid));
    }
}
?>
