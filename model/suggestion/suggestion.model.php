<?php
/** 
 * 推荐使用的队列的消息推送
 */
class suggestionModel extends model{

    /** @var Redis 数据库的链接 */
    private $redis;

    /** @var array 配置文件 */
    private $config;

    private $suggestion;

    /**
     * 构造器，创建连接
     * suggestionModel constructor.
     */
	public function __construct() {
        // 连接本地的 Redis 服务
        $this->redis = new Redis();
        $this->config = C('queue_redis');
        $this->redis->connect($this->config['host'], $this->config['port']);
        $this->suggestion = M('public:common')->model('pms_product_suggestion');
	}

    /**
     * 析构器，断开连接
     */
	public function __destruct()
    {
        // 断开服务器
        $this->redis->close();
    }


    /**
     *  “供”新增队列 Redis
     */
    public function suggestion_product($user_id,$product_id){
        //存储数据到列表中
        $this->redis->lPush($this->config['product'], $user_id."|".$product_id);
    }

    /**
     *  新增队列 Redis
     */
    public function suggestion_purchase($user_id,$purchase_id,$todo='A'){
        //存储数据到列表中
        $this->redis->lPush($this->config['purchase'], $user_id.":".$purchase_id.":".$todo);
    }

    /**
     * 自己关注牌号变化 Redis
     */
    public function suggestion_model($user_id,$model,$type="*",$focus_id="*",$todo="A"){
        //存储数据到列表中
        $this->redis->lPush($this->config['model'], $user_id.":".$model.":".$type.":".$focus_id.":".$todo);
       //echo  $this->redis->rPop($this->config['model']);exit;
    }

    /**
     *好友关注队列变化 Redis
     */
    public function suggestion_fans($user_id,$fans_id,$todo='A'){
        //存储数据到列表中
        $this->redis->lPush($this->config['user_fans'], $user_id.":".$fans_id.":".$todo);
    }



    /**
     * 获取商品变化的ID
     * @return mixed
     */
    public function getProduct(){
        // 获取存储的数据并输出
        $arList = $this->redis->rPop($this->config['product']);
        return $arList;
    }


    /**
     * 获取好友关注变化的ID
     * @return mixed
     */
    public function getFans(){
        // 获取存储的数据并输出
        $arList = $this->redis->rPop($this->config['user_fans']);
        return $arList;
    }

    /**
     * 获取推荐的商品信息
     * @return array
     */
    public function getSuggestionList($user_id, $page, $size){
        // 获取推荐信息
        $where = array();
        $arList = $this->$this->suggestion->where($where)->limit($page, $size)->select();
        return $arList;
    }
}
?>