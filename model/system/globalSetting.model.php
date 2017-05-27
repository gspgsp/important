<?php
/**
 * 系统设置
 */
class globalSettingModel extends model{
    public function __construct() {
        parent::__construct(C('db_default'), 'global_setting');
    }

    /*
     * 获取系统设置
     * @access public
     * @return array
     */
    public function getSetting(){
        $_key='global_setting';
        $cache = E ('RedisClusterServer', APP_LIB.'class');
        $data=$cache->get($_key);
        if(empty($data)){
            $arr=$this->getAll();
            foreach($arr as $k=>$v){
                $value=$v['value'];
                if($value && $value{0} == '{'){
                    $value=json_decode($value,true);
                }
                $data[$v['code']]=$value;
            }
            $data = json_encode($data);
            $cache->set($_key,$data,300); //加入缓存
        }
        return json_decode($data,true);
    }

    /**
     * 获取单个系统设置
     * @param  string $code 设置key
     * @return mixed
     */
    public function get($code){
        $_key='global_setting_'.$code;
        $cache = E ('RedisClusterServer', APP_LIB.'class');
        $data=$cache->get($_key);
        if(empty($data)){
            $data = $this->getfieldbycode($code,'value');
            $data = json_decode($data, TRUE) ?: $data;
            $cache->set($_key,json_encode($data),300); //加入缓存
        }
        return json_decode($data,true);
    }

    public function set($code,$value)
    {

        $this->execute("replace into ".$this->ftable." (code,value) values ('".$code."','".$value."')");
        $cache = E ('RedisClusterServer', APP_LIB.'class');

        $_key='global_setting';
        $cache->remove($_key);
        $_key='global_setting_'.$code;
        $cache->remove($_key);

        return true;
    }

    public function del_cache($key)
    {
        if(empty($key))
        {
            return false;
        }
        $cache = E ('RedisClusterServer', APP_LIB.'class');
        if(!empty($data))
        {
            $cache->remove($key);
        }

        return true;
    }
}
?>